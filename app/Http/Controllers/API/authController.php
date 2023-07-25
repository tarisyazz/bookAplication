<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\book;
use App\Models\kategori;
use App\Models\novel;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
// register -- approved
    public function registrasi(Request $request)
    {
        $fields = $request->all();

        $validate = Validator::make($request->all(), [
            'nama' => 'required | string',
            'username' => 'required | unique:users',
            'email' => 'required | unique:users',
            'password' => 'required'
        ],
        [
            '*.required' =>'data tidak boleh kosong!',
            '*.*string'   => 'data harus berupa huruf!',
            'username.unique' => 'username sudah tersedia, silahkan melakukan Log in',
            'email.unique' => 'email sudah tersedia, silahkan melakukan log in',
        ]
        );

        if ($validate->fails()){
            return response()->json([
                'status' => 422,
                'error' => $validate->errors()
            ]);
        }

        $user = User::create([
            'nama' => $fields['nama'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password'=> bcrypt($fields['password'])
        ]);

        $response = [
            'success' => true,
            'message' => 'Berhasil Registrasi, silahkan melakukan LogIn',
            'data' => $user 
        ];
        return response($response, 201);
    }
//

// login -- approved
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ], 
        [
            '*.required' =>'data tidak boleh kosong!',
        ]);

        if ($validate->fails()){
            return response()->json([
                'status' => 422,
                'error' => $validate->errors()
            ]);
        }

        // validasi data (ada/engga)
        if(Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ]))
        {
            $auth = Auth::user();
            $data['id'] = $auth->id;
            $data['nama'] = $auth->nama;
            $data['token'] = $auth->createToken('token')->plainTextToken;

            $response = [
                'success' => true,
                'message' => 'LogIn berhasil',
                'data' => $data
            ];
            return response($response, 201);
        }
        else
        {
            $response = [
                'success' => false,
                'message' => 'LogIn gagal, silahkan memasukan data yang benar!',
            ];
            return response($response, 400);
        }
    }
//

// log out 
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'berhasi logout, token expired!'
        ];
    }
//

// filter data
    public function allData(Request $request)
    {
        $data = book::select('*');

        $data = $data->orderBy('created_at', 'DESC')->get();
        
        $response = [
            'success' => true,
            'message' => 'Data Buku',
            'data' => $data 
        ];
        return response($response, 200);
    }

    public function onlyKategori(Request $request)
    {
        $data = kategori::with('buku.kategori')->get();

        if(!empty($request->req))
        {
            $data = $data->where('namaKategori', 'LIKE', $request->req);
        }
        
        $response = [
            'success' => true,
            'message' => 'Data Buku kategori',
            'data' => $data 
        ];

        return response($response, 200);

    }

    public function search($judul)
    {
        return book::where("judul", 'Like' , '%' . $judul . '%')->get();

        $response = [
            'success' => true,
            'message' => 'berikut Data buku'
        ];

        return response($response, 200);
    }

    public function get_details(Request $request)
    {
        $buku = book::select('cover', 'judul', 'sinopsis', 'tahunTerbit', 'harga');

        $buku = $buku->where('id', $request->id)->get();

        $response = [
            'success' => true,
            'message' => 'berikut details buku',
            'data' => $buku
        ];

        return response($response, 200); 
    }
//
}
