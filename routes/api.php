<?php
namespace App\Http\Controllers;

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
Route::post('/login', [LogController::class,'login']);
Route::get('/reviews', [ReviewController::class,'index']);
Route::get('/umkms', [UmkmController::class,'index']);
Route::get('/umkm/{id}', [UmkmController::class,'show']);
Route::get('/province',[RajaOngkirController::class,'get_province']);
Route::get('/city/{id}',[RajaOngkirController::class,'get_city']);


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [LogController::class,'info']);
    Route::post('/logout', [LogController::class,'logout']);

    // review
    Route::post('/review', [ReviewController::class,'store']);
    Route::get('/review', [ReviewController::class,'show']);

    // umkm
    Route::post('/umkm', [UmkmController::class,'store']);
    Route::get('/umkm', [UmkmController::class,'show']);
    Route::put('/umkm/{id}', [UmkmController::class,'update']);
    Route::delete('/umkm/{id}', [UmkmController::class,'destroy']);
});
