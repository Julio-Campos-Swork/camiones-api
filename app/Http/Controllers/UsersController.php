<?php

namespace App\Http\Controllers;

use App\Models\LoginRegister;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class UsersController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',

        ];
        try {
            $validator = \Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all(),
                    'message' => 'Datos incompletos'
                ]);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'id_level' => $request->id_level,
                'password' => \Hash::make($request->password),
            ]);


                return response()->json([
                    'status' => true,
                    'message' => 'Usuario creado con éxito',
                    'data' => $user,
                ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Error en el servidor, contacta a soporte',
                'error' => $e->getMessage()
            ]);
        }


    }

    public function login(Request $request)
    {
        try {
            $rules = [
                'identifier' => 'required|string',
                'password' => 'required|string'
            ];

            $validator = \Validator::make($request->input(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all(),
                    'message' => 'Datos incompletos',

                    "request->input()" =>$request->input()
                ]);
            }

            $credentials = $request->only('identifier', 'password');

            if (filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)) {
                if (!\Auth::attempt(['email' => $credentials['identifier'], 'password' => $credentials['password']])) {
                    return response()->json([
                        'status' => false,
                        'errors' => 'Unauthorized',
                        'message' => 'Credenciales inválidas',
                    ]);
                }
            $user = User::where('email', $credentials['identifier'])->first();

            } else {
                if (!\Auth::attempt(['name' => $credentials['identifier'], 'password' => $credentials['password']])) {
                    return response()->json([
                        'status' => false,
                        'errors' => 'Unauthorized',
                        'message' => 'Credenciales inválidas',
                    ]);

                }
                $user = User::where('name', $credentials['identifier'])->first();
            }


            $token = $user->createToken('Token')->plainTextToken;
            //detalles de ingreso una vez que se confirme el correo y el auth
            $ip_value = $request->ip_value;
            $city = $request->city;
            $country = $request->country;
            $newLogin = new LoginRegister();
            $newLogin->id_user = $user->id_user;
            $newLogin->key = $token;
            $newLogin->ip = $ip_value;
            $newLogin->city = $city;
            $newLogin->country = $country;
            $newLogin->status = 1;
            $newLogin->save();


            return response()->json([

                'status' => true,
                'message' => 'Se inició sesión correctamente',
                'userData' => $user,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en el servidor, contacta a soporte',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            //code...
            $user = User::where('email', $request->email)->first();
            $user->tokens()->where('tokenable_id', $user->id_user)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Se cerró sesión correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error en el servidor, contacta a soporte',
                'error' => $e->getMessage()
            ]);
        }
    }
}
