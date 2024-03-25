<?php

namespace App\Http\Controllers;

use App\Models\RegisterLoads;
use App\Models\Trucks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReportsController extends Controller
{
    public function getReports(Request $request){

        $initialDate = $request->initialDate;
        $finalDate = $request->finalDate;

        try {
            $trucks = Trucks::all();

            if(str_word_count($finalDate) > 0){
                $reports = DB::table('load_registers')
                ->join('users', 'load_registers.id_user', '=', 'users.id_user')
                ->join('loads', 'load_registers.id_load', '=', 'loads.id_load')
                ->join('trucks', 'load_registers.id_truck', '=', 'trucks.id_truck')
                ->join('register_type', 'load_registers.id_register_type', '=', 'register_type.id_register_type')
                ->whereBetween('load_registers.register_date', [$initialDate, $finalDate])
                ->where('load_registers.status', 1)
                ->where('load_registers.id_truck', '<' , 5)
                ->select('load_registers.*', 'users.name', 'loads.load_name', 'trucks.truck_name', 'register_type.register_name')
                ->orderBy('load_registers.filename', 'asc')
                ->get();
            } else{

                $reports = DB::table('load_registers')
                ->join('users', 'load_registers.id_user', '=', 'users.id_user')
                ->join('loads', 'load_registers.id_load', '=', 'loads.id_load')
                ->join('trucks', 'load_registers.id_truck', '=', 'trucks.id_truck')
                ->join('register_type', 'load_registers.id_register_type', '=', 'register_type.id_register_type')
                ->where('load_registers.register_date', $initialDate)
                ->where('load_registers.status', 1)
                ->where('load_registers.id_truck', '<' , 5)
                ->select('load_registers.*', 'users.name', 'loads.load_name', 'trucks.truck_name', 'register_type.register_name')
                ->orderBy('load_registers.filename', 'asc')
                ->get();
            }


            $reportsVideo = $this->getVideoPath($reports);


            return response()->json([
                'status' => true,
                'reportsVideo' => $reportsVideo,
                'trucks' => $trucks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en la consulta',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getVideoPath($dataReport){
        $data = json_decode($dataReport, true);


        foreach($data as $key => $report){
            $videoPath =  $report['register_date'] . '-confirmados/' . $report['filename'];
            $data[$key]['videoPath'] = $videoPath;
        }
        return $data;

    }

    public function editRegisterByID(Request $request){
        $id_load_register = $request->id_load_register;
        $id_load = $request->id_load;
        $id_truck = $request->id_truck;
        $id_register_type = $request->id_register_type;
        try {
            $loadRegister = RegisterLoads::find($id_load_register);
            $loadRegister->id_load = $id_load;
            $loadRegister->id_truck = $id_truck;
            $loadRegister->id_register_type = $id_register_type;
            $loadRegister->update();

            return response()->json([
                'status' => true,
                'message' => 'Registro actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en la consulta',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function deleteRegisterByID(Request $request){
        $id_load_register = $request->id_load_register;

        try {
            $loadRegister = RegisterLoads::find($id_load_register);
            $loadRegister->status = 0;
            $loadRegister->update();

            return response()->json([
                'status' => true,
                'message' => 'Registro eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en la consulta',
                'error' => $e->getMessage()
            ]);
        }
    }
}
