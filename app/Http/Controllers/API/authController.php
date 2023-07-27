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

    public function get_registrasi(Request $request)
    {
        $fields = $request->all();

        $validate = Validator::make($request->all(), [
            'nama' => 'required | string',
            'username' => 'required | unique:users',
            'email' => 'required | unique:users',
            'password' => 'required',
            'jenisKelamin' => 'required',
            'tempatLahir' =>'required',
            'tanggalLahir' => 'required',
            'alamat' => 'required'
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
            'password'=> bcrypt($fields['password']),
            'jenisKelamin' => $fields['jenisKelamin'],
            'tempatLahir' =>$fields['tempatLahir'],
            'tanggalLahir' => $fields['tanggalLahir'],
            'alamat' => $fields['alamat']
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
            $data = User::select('*')->get();
            $data = $data->where('id', $auth->id);
            // $data['id'] = $auth->id;
            // $data['nama'] = $auth->nama;
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
    // ini buat show semua data 
    public function allData()
    {
        $data = book::select('*');
        
        if(!empty($data))
        {
            $show = $data->orderBy('created_at', 'DESC')->get();
            
            $response = [
                'success' => true,
                'message' => 'Data Buku',
                'data' => $show
            ];
            return response($response, 200);
        }
    }

    // ini kategori yang dulu
    public function onlyKategori()
    {
        $show = kategori::with('buku.kategori')->select('*')->get();

        $response = [
            'success' => true,
            'message' => 'Data Buku kategori',
            'data' => $show
        ];
        return response($response, 200);
    }

    // select data per kategori
    public function getDataKategori($kategori)
    {
        $coba = kategori::select('*')->with('buku.kategori');

        if(!empty($coba))
        {
            $data = $coba->where('namaKategori', 'ilike',  $kategori )->get();

            $response = [
                'success' => true,
                'message' => 'Data Buku kategori',
                'data' => $data 
            ];

            return response($response, 200);
        }
        $response = [
            'success' => false,
            'message' => 'data tidak ditemukan!',
            'data' => null
        ];
        return response($response, 400);

    }

    // ini buat liat data buku by id
    public function get_details($id)
    {
        $buku = book::select('*')->get();
        $validasi = book::select('id');
    
        if(!empty($validasi))
        {
            $data = $buku->where('id', $id);
    
            $response = [
                'success' => true,
                'message' => 'berikut details buku',
                'data' => $data
            ];
            return response($response, 200); 
        }
        $response = [
            'success' => false,
            'message' => 'data tidak ditemukan!',
            'data' => null
        ];
        return response($response, 400);
    }

    // ini fitur search
    public function search($judul)
    {
        // if($judul != null)
        // {
            $data = book::where('judul', 'like', '%' . $judul . '%')->get();

            $response = [
                'success' => true,
                'message' => 'Data Buku',
                'data' => $data
            ];
            return response($response, 200);
            // }
            // else
            // {
            //     $response = [
            //         'success' => false,
            //         'message' => 'Data tidak ditemukan!',
            //         'data' => null 
            //     ];
            //     return response($response, 422);
            // }
    }
    //
//
}
