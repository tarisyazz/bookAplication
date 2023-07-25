<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class userController extends Controller
{
    //CRUD BUAT TABEL USER
        // -- SHOW -- //
        public function show_user(Request $request)
        {
            $data = User::select('*');

            $data = $data->orderBy('created_at', 'DESC')->paginate($request->perPage);

            $response = [
                'success' => true,
                'message' => 'Data User',
                'data' => $data 
            ];
            return response($response, 200);        
        }

        // -- CREATE -- //
        Public function create_user(Request $request)
        {
            try
            {
                $data = User::make($request->all(),[
                    'name' => 'required | string',
                    'username' => 'required | string | unique',
                    'email' => 'required | string',
                    'password' => 'required | string',
                    'jenisKelamin' => 'required | string',
                    'alamat' => 'required | string',
                    'tempatLahir' => 'required | string',
                    'tanggalLahir' => 'required | string',
                    'profilePhoto' => 'Required | string'
                ]);

                $data = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => $request->password,

                    'jenisKelamin' => $request->jenisKelamin,
                    'alamat' => $request->alamat,
                    'tempatLahir' => $request->tempatLahir,
                    'tanggalLahir' => $request->tanggalLahir,
                    'profilePhoto' => $request->profile
                ]);

                $response = [
                    'success' => true,
                    'message' => 'User berhasil dibuat',
                    'data' => $data
                ];
                return response($response, 200);
            }

            catch (Exception $ex)
            {
                return response()->json($ex->getMessage())->setStatusCode(500);
            }
        } 

        // -- UPDATE -- //
        public function update_user(Request $request, $id)
        {
            $user = User::find($id);        
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => $request->password,

                'jenisKelamin' => $request->jenisKelamin,
                'alamat' => $request->alamat,
                'tempatLahir' => $request->tempatLahir,
                'tanggalLahir' => $request->tanggalLahir,
                'profilePhoto' => $request->profile
            ]);
                    
            $response = [
                'success' => true,
                'message' => 'Data Berhasil di update',
                'data' => $user
            ];
            return response($response, 200);
        }
    
        // -- DELETE -- //
        public function delete_user($id)
        {
            $user = User::find($id);
            $user->delete();
        
            $response = [
                'success' => true,
                'message' => 'Data Berhasil di hapus'
            ];
            return response($response, 200);
        }
    
        // -- SAW -- //
        public function saw_user(Request $request)
        {
            $data = User::find($request->id);
        
            $response = [
                'success' => true,
                'message' => $data
            ];
            return response($response, 200);
        }
    //
}
