<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\novel;
use App\Models\Kategori;
use App\Models\penulis;
use App\Models\User;
use App\Models\daftarMenu;
use App\Models\pesanan;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;
use PhpParser\Node\Stmt\TryCatch;


class novelController extends Controller
{

    //LATIHAN AWAL  
       //ini buat validasi data //
        public function store(Request $request )
        {

            try {
                $validator = Validator::make($request->all(),[
                    'title' => 'required | string',
                    'description' => 'required | string',
                    'price' => 'required | integer'
                ]);

                if ($validator->fails()){
                    return response()->json($validator->errors())->setStatusCode(422);
                }

                return response()->json([
                    "status" => 200,
                    "message" => "novel $request->title berhasil dibuat"
                ], 200);
            } 

            catch (\Exception $e) {
                return response()->json($e->getMessage())->setStatusCode(500);
            }
        }
        //ini buat show //
        public function index(Request $request)
        {
            $data = novel::select("*");

            if (!empty($request->from)&& !empty($request->to))
            {
                $from = $request->from;
                $to = $request->to;

                $data = $data->whereBetween('price', [$request->from, $to]);
            }
            $data = $data->orderBy('created_at', 'DESC')->paginate($request->perPage);

            return response()->json(["content" => $data, "status" => 200], 200);
        }

        public function latihan(Request $request) 
        {

            $data = novel::select("*");

            if (in_array($request->stat, [0,1]))
            {
                $data = $data->where('status', $request->stat);
            }
                $data = $data->orderBy('created_at', 'DESC')->paginate($request->perPage);

                return response()->json(["content" => $data, "status" => 200], 200);   
                
                 
        }

        //ini buat update //
        public function update(Request $request, $id)
        {
            $novel = novel::find($id);

            return response()->json($novel);
        }
    //

    // -- validasi login -- //

    //CRUD BUAT TABEL NOVEL
        // -- SHOW -- //
        public function show_novel(Request $request)
        {

            $data = novel::Select("*");

            $data = $data->orderBy('created_at', 'DESC')->paginate($request->perPage);

            return response()->json(["content" => $data, "status" => 200], 200);
        }  

        // -- CREATE -- //
        Public function create_novel(Request $request)
        {
            try
            {
                $data = novel::make($request->all(),[
                    'judul' => 'required | string',
                    'sinopsis' => 'required | string',
                    'harga' => 'required | integer',
                    'status' => 'required | boolean'
            ]);

            $data = novel::create([
                'penulis_id' => $request->penulisId,
                'judul' => $request->judul,
                'sinopsis' => $request->sinopsis,
                'harga' => $request->harga,
                'status' => $request->status
            ]);

            return response()->json([
                "status" => 200,
                "message" => "penulis $data berhasil dibuat"
            ], 200);
            }

            catch(Exception $ex)
            {
                return response()->json($ex->getMessage())->setStatusCode(500);
            }
        } 

        // -- UPDATE -- //
        public function update_novel(Request $request, $id)
        {
            $novel = novel::find($id);
            
            $novel->update([
                'penulis_id' => $request->penulisId,
                'judul' => $request->judul,
                'sinopsis' => $request->sinopsis,
                'harga' => $request->harga,
                'status' => $request->status
            ]);
             
            return response()->json([
                'status' => 200,
                "message" => "data berhasil di update"
            ]);
        }

        // -- DELETE -- //
        public function delete_novel($id)
        {
            $penulis = novel::find($id);
            $penulis->delete();

            return response()->json([
                'status' => 200,
                "message" => "data berhasil di delete"
            ]);

        }

        // -- SAW -- //
        public function saw_novel(Request $request)
        {
            $data = novel::find($request->id);

            return response()->json([
                'status' => 200,
                "message" => $data
            ]);
        }

    //
}
