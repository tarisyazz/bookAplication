<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\kategori;
use Exception;
use Illuminate\Http\Request;

class kategoriController extends Controller
{
    //CRUD BUAT TABEL KATEGORI
        // -- SHOW -- //
        public function show_kategori(Request $request)
        {

            $data = Kategori::Select("*");

            $data = $data->orderBy('created_at', 'DESC')->paginate($request->perPage);

            $response = [
                'success' => true,
                'message' => 'Data Kategori',
                'data' => $data 
            ];
    
            return response($response, 200);
        }

        // -- CREATE -- //
        Public function create_kategori(Request $request)
        {
            try
            {
                $data = Kategori::make($request->all(),[
                    'namaKategori' => 'required | string'
                ]);

                $data = Kategori::create([
                    'namaKategori' => $request->namaKategori
                ]);

                $response = [
                    'success' => true,
                    'message' => 'Kategori berhasil dibuat',
                    'data' => $data
                ];
                    return response($response, 200);
            }

            catch(Exception $ex)
            {
                return response()->json($ex->getMessage())->setStatusCode(500);
            }
        }

        // -- UPDATE -- //
        public function update_kategori(Request $request, $id)
        {
            $kategori = kategori::find($id);
            
            $kategori->update([
                'namaKategori' => $request->namaKategori
            ]);
             
            $response = [
                'success' => true,
                'message' => 'Data Berhasil di update',
                'data' => $kategori
            ];
            return response($response, 200);
        }

        // -- DELETE -- //
        public function delete_kategori($id)
        {
            $kategori = kategori::find($id);
            $kategori->delete();

            $response = [
                'success' => true,
                'message' => 'Data Berhasil di hapus'
            ];
            return response($response, 200);
        }

        // -- SAW -- //
        public function saw_kategori(Request $request)
        {
            $data = Kategori::find($request->nama);

            $response = [
                'success' => true,
                'message' => $data
            ];
            return response($response, 200);
        }
    //
}
