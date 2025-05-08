<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\PenjualanController;
use App\Http\Controllers\Api\RegisterController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', App\Http\Controllers\Api\RegisterController::class )->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class )->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class )->name('logout');

Route::post('/register1', RegisterController::class )->name('register1');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



//level tabel
 Route::get('levels', [LevelController::class, 'index']);
 Route::post('levels', [LevelController::class, 'store']);
 Route::get('levels/{level}', [LevelController::class, 'show']);
 Route::put('levels/{level}', [LevelController::class, 'update']);
 Route::delete('levels/{level}', [LevelController::class, 'destroy']);

 //user tabel
 Route::get('users', [UserController::class, 'index']);
 Route::post('users', [UserController::class, 'store']);
 Route::get('users/{user}', [UserController::class, 'show']);
 Route::put('users/{user}', [UserController::class, 'update']);
 Route::delete('users/{user}', [UserController::class, 'destroy']);
 
 //katgeori tabel
 Route::get('kategori', [KategoriController::class, 'index']);
 Route::post('kategori', [KategoriController::class, 'store']);
 Route::get('kategori/{kategori}', [KategoriController::class, 'show']);
 Route::put('kategori/{kategori}', [KategoriController::class, 'update']);
 Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy']);
 
 //barang tabel
 Route::get('barang', [BarangController::class, 'index']);
 Route::post('barang', [BarangController::class, 'store']);
 Route::get('barang/{barang}', [BarangController::class, 'show']);
 Route::put('barang/{barang}', [BarangController::class, 'update']);
 Route::delete('barang/{barang}', [BarangController::class, 'destroy']);
 
 
 //supplier tabel
 Route::get('supplier', [SupplierController::class, 'index']);
 Route::post('supplier', [SupplierController::class, 'store']);
 Route::get('supplier/{supplier}', [SupplierController::class, 'show']);
 Route::put('supplier/{supplier}', [SupplierController::class, 'update']);
 Route::delete('supplier/{supplier}', [SupplierController::class, 'destroy']);

 //penjualan model
 Route::get('/penjualan', [PenjualanController::class, 'index']);
 Route::post('/penjualan', [PenjualanController::class, 'store']);
 Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show']);
 Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update']);
 Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy']);

 