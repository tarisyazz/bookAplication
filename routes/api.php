<?php

use App\Http\Controllers\API\authController;
use App\Http\Controllers\API\bukuController;
use App\Http\Controllers\API\kategoriController;
use App\Http\Controllers\API\novelController;
use App\Http\Controllers\API\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [authController::class, 'registrasi'])->name('register.registrasi');
Route::post('/login', [authController::class, 'login'])->name('login.login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // route::resource('kategori', kategoriController::class, [
    //     'except' => ['show']
    // ]);
    Route::get('/buku', [authController::class, 'allData']);
    Route::get('/kategori/buku', [authController::class, 'onlyKategori']);
    Route::get('/search/{judul}', [authController::class, 'search']);
    Route::get('/details/buku/{judul}', [authController::class, 'details']);

    Route::get('/details-buku/buku/{id}', [authController::class, 'get_details']);


    route::post('/logout', [authController::class, 'logout']);
});


// trash 
    // Route::get('/user/buku', [authController::class, 'allData']);

    // Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    //     return $request->user();
    // });

    // Route::group(['middleware' => ['auth:sanctum']], function () {
    //     Route::resource('kategori', kategoriController::class, [
    //         'except' => ['show']
    //       //  'only' =>'store','update','destroy'
    //     ]);
    //     Route::resource('category', CategoryController::class, [
    //         'except' => ['show']
    //     ]);
    // });
//

// -- CRUD 
    // ini user
    Route::post('/novel-user/show', [userController::class, 'show_user']);
    Route::post('/novel-user/create', [userController::class, 'create_user']);
    Route::put('/novel-user/update/{id}', [userController::class, 'update_user']);
    route::delete('/novel-user/delete/{id}', [userController::class, 'delete_user']);
    Route::get('/novel-user/saw/{id}', [userController::class, 'saw_user']);

    // ini kategori
    Route::post('/novel-kategori/show', [kategoriController::class, 'show_kategori'])->name('novel-kategori.show_kategori');
    Route::post('/novel-kategori/create', [kategoriController::class, 'create_kategori'])->name('novel-kategori.create_kategori');
    Route::put('/novel-kategori/update/{id}', [kategoriController::class, 'update_kategori'])->name('novel-kategori.update_kategori');
    Route::delete('/novel-kategori/delete/{id}', [kategoriController::class, 'delete_kategori'])->name('novel-kategori.delete_kategori');
    Route::get('/novel-kategori/saw/{id}', [kategoriController::class, 'saw_kategori'])->name('novel-kategori.saw_kategori');

    // ini buku
    Route::post('/novel-buku/show', [bukuController::class, 'show_buku']);
    Route::post('/novel-buku/create', [bukuController::class, 'create_buku']);
    
//

// -- kemarin --
    Route::post('/novel/store', [novelController::class, 'store'])->name('novel.store');
    // Route::get('/novel', [novelController::class, 'index'])->name('novel.index');
    route::get('/latihan', [novelController::class, 'latihan'])->name('novel.latihan');
    route::get('novel/update/{id}', [novelController::class, 'update'])->name('novel.update');
//
