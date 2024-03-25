<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadVideo extends Controller
{
    public function createFolder(Request $request)
    {

        $newFolderName = $request->input('folderName');
        try {
            $base_path = base_path() . '/videos';
            $basePath = str_replace(['\\\\', '\\', '\/', '//', '////', '////'], '/', $base_path);

            if (mkdir($basePath . '/' . $newFolderName, 0777, true)) {

                return response()->json([
                    'status' => true,
                    'message' => 'Directorio creado',
                    'data' => $newFolderName
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'El dirextorio ya existe',
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: El directorio ya existe',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function getAllDirectories()
    {
        try {
            $base_path = base_path() . '/videos';
            $content = scandir($base_path);
            $directories = [];
            foreach ($content as $item) {
                if (is_dir($base_path . '/' . $item) && $item !== '.' && $item !== '..') {
                    $directories[] = $item;
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Directorios encontrados',
                'directoryList' => $directories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: El directorio ya existe',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function saveFilesIntoDirectory(Request $request)
    {
        $folderName = $request->folderName;
        $files = $request->file('files');
        try {
            $base_path = base_path() . '/videos/' . $folderName;
            $basePath = str_replace(['\\\\', '\\', '\/', '//', '////', '////'], '/', $base_path);

            foreach ($files as $file) {
                if ($file->getClientOriginalExtension() != 'webm') {
                    throw new \Exception('El archivo ' . $file->getClientOriginalName() . ' no es de tipo webm.');
                }
                $file->move($basePath, $file->getClientOriginalName());
            }
            return response()->json([
                'status' => true,
                'message' => 'Archivos guardados con exito',
                'files' => $files,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: Al guardar los archivos',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function deleteDirectory(Request $request)
    {
        $folderName = $request->folderName;

        try {
            $base_path = base_path() . '/videos/' . $folderName;
            if ($this->rrmdir($base_path)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Directorio eliminado con éxito',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Error: Al eliminar el directorio',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: Al gal eliminar la carpeta',
                'error' => $e->getMessage(),
            ]);
        }
    }

    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    $this->rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        return rmdir($src);
    }
}
