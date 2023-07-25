<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class bukuController extends Controller
{
        //CRUD BUAT TABEL KATEGORI
        // -- SHOW -- //
        public function show_buku(Request $request)
        {

            $data = book::Select("*");

            $data = $data->orderBy('created_at', 'DESC')->paginate($request->perPage);

            $response = [
                'success' => true,
                'message' => 'Data Buku',
                'data' => $data 
            ];
    
            return response($response, 200);
        }

        // -- CREATE -- //
        Public function create_buku(Request $request)
        {
            try
            {
                $validate = Validator::make($request->all(),[
                    'judul' => 'required',
                    'sinopsis' => 'required',
                    'tahunTerbit' => 'required',
                    'harga' => 'required',
                    'cover' => 'required | image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'user_id' => 'required',
                    'kategori_id' => 'required'
                ]);

                if ($validate->fails()) {
                    return response()->json([
                        "status" => 422,
                        "error" => $validate->errors()
                    ]);
                }

        //
                $input = $request->all();

                $path = '/uploads/books';
                $input['cover'] = $path . '/' .time() . '.' . $request->cover->extension();
        

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0775, true, true);
                }
        
                $request->cover->move(public_path('/uploads/books'), $input['cover']);
        
                // $input['user_id'] = $request->user()->id;
        

                Book::create($input);

                $response = [
                    'success' => true,
                    'message' => "Buku $request->judul berhasil dibuat"
                ];
                    return response($response, 201);

                // $data = book::create([
                //     'judul' => $request->judul,
                //     'sinopsis' => $request->sinopsis,
                //     'tahunTerbit' => $request->tahunTerbit,
                //     'harga' => $request->harga,
                //     'cover' => $request->cover,
                //     'user_id' => $request->userId,
                //     'kategori_id' => $request->kategoriId
                // ]);

                // $response = [
                //     'success' => true,
                //     'message' => 'Buku berhasil dibuat',
                //     'data' => $data
                // ];
                //     return response($response, 200);
        //
            }

            catch(Exception $ex)
            {
                return response()->json($ex->getMessage())->setStatusCode(500);
            }
        }

        // -- UPDATE -- // belum diganti
        public function update_buku(Request $request, $id)
        {
            $kategori = book::find($id);
            
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
        public function delete_buku($id)
        {
            $kategori = book::find($id);
            $kategori->delete();

            $response = [
                'success' => true,
                'message' => 'Data Berhasil di hapus'
            ];
            return response($response, 200);
        }

        // -- SAW -- //
        public function saw_buku(Request $request)
        {
            $data = book::find($request->nama);

            $response = [
                'success' => true,
                'message' => $data
            ];
            return response($response, 200);
        }


}
