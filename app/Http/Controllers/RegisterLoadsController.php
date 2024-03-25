<?php

namespace App\Http\Controllers;

use App\Models\Loads;
use App\Models\RegisterLoads;
use App\Models\RegisterType;
use App\Models\Trucks;
use Illuminate\Http\Request;

class RegisterLoadsController extends Controller
{


    public function getRegisterData()
    {
        try {

            $trucks = Trucks::all();
            $loads = Loads::all();
            $register_types = RegisterType::all();

            return response()->json([
                'status' => true,
                'trucks' => $trucks,
                'loads' => $loads,
                'register_type' => $register_types,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en el servidor, contacta a soporte',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function saveRegisterDate(Request $request)
    {
        $register_date = $request->register_date;
        $id_load = $request->id_load;
        $id_truck = $request->id_truck;
        $id_register_type = $request->id_register_type;
        $filename = $request->filename;
        $id_user = auth()->user()->id_user;
        try {

            // $register_exists = RegisterLoads::where('filename', $filename)->count();
            // if ($register_exists > 0) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'El video ya tiene un registro, por favor intenta otro video',
            //     ]);
            // }

            $newRegisterDate = new RegisterLoads();
            $newRegisterDate->register_date = $register_date;
            $newRegisterDate->id_load = $id_load;
            $newRegisterDate->id_truck = $id_truck;
            $newRegisterDate->id_register_type = $id_register_type;
            $newRegisterDate->id_user = $id_user;
            $newRegisterDate->filename = $filename;
            $newRegisterDate->save();



            return response()->json([
                'status' => true,
                'message' => 'Registro creado correctamente',
                "newRegister" => $newRegisterDate,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en el servidor, contacta a soporte',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function confirmVideo(Request $request)
    {
        $register_date = $request->register_date;
        $filename = $request->filename;

        try {
            // mover archivos
            $basePath = base_path() . '/videos';
            $basePath = str_replace(['\\\\', '\\', '\/', '//', '////', '////'], '/', $basePath);
            $newFolder = $basePath . '/' . $register_date . '-confirmados';
            if (!file_exists($newFolder)) {
                mkdir($newFolder, 0777, true);
            }
            $oldFilePath = $basePath . '/' . $register_date . '/' . $filename;

            $newFilePath = $newFolder . '/' . $filename;

            if (!rename($oldFilePath, $newFilePath)) {
                throw new \Exception('Error al mover el archivo');
            }
            return response()->json(['status' => true, "message" => "Video completado con éxito"]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al guardar registro',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getVideoByDate(Request $request)
    {


        $dateString = $request->date;
        try {
            $basePath = base_path() . '/videos';
            $basePath = str_replace(['\\\\', '\\', '\/', '//', '////', '////'], '/', $basePath);
            $completePath = $basePath . '/' . $dateString;
            $content = scandir($completePath);
            $content = array_diff($content, ['.', '..']);
            $content = array_values($content);

            $fileList = [];
            foreach ($content as $filename) {
                $fileList[] = ['src' => $filename];
            }
            return response()->json(['status' => true, 'fileList' => $fileList]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'La fecha ' . $dateString . ' no tiene videos',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function discartVideo(Request $request)
    {

        try {
            $filename = $request->filename;
            $dateString = $request->date;
            $basePath = base_path() . '/videos';

            $basePath = str_replace(['\\\\', '\\', '\/', '//', '////', '////'], '/', $basePath);
            $newFolder = $basePath . '/' . $dateString . '-descartados';
            if (!file_exists($newFolder)) {
                mkdir($newFolder, 0777, true);
            }

            $oldFilePath = $basePath . '/' . $dateString . '/' . $filename;

            $newFilePath = $newFolder . '/' . $filename;

            if (rename($oldFilePath, $newFilePath)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Archivo descartado con éxito.'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Error al mover el archivo.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'La fecha ' . $dateString . ' no tiene videos',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function saveNewRegisters(Request $request){
        $registerArray = json_decode($request->input('registers'), true);
        try {

            foreach ($registerArray as $register) {
                $newRegisterDate = new RegisterLoads();
                $newRegisterDate->register_date = $register['register_date'];
                $newRegisterDate->id_load = $register['id_load'];
                $newRegisterDate->id_truck = $register['id_truck'];
                $newRegisterDate->id_register_type = $register['id_register_type'];
                $newRegisterDate->id_user = auth()->user()->id_user;
                $newRegisterDate->filename = $register['filename'];
            $newRegisterDate->save();

            }
            return response()->json([
                'status' => true,
                'message' => 'Registros guardados exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error al guardar los datos, intentalo de nuevo',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
