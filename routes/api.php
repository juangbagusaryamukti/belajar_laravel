<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
use App\Models\Movie;

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
Route::post("register_admin",[AuthController::class,"register"]);
Route::get("/get_user",[AuthController::class,"getUser"]);
Route::get("/get_detail_user/{id}",[AuthController::class,"getDetailUser"]);
Route::put("/update_user/{id}",[AuthController::class,"update_user"]);
Route::delete("/hapus_user/{id}",[AuthController::class,"hapus_user"]);

Route::post("register_category",[CategoryController::class,"register"]);
Route::get("/get_category",[CategoryController::class,"getCategory"]);
Route::get("/get_detail_category/{id}",[CategoryController::class,"getDetailCategory"]); 
Route::put("/update_category/{id}",[CategoryController::class,"update_Category"]);
Route::delete("/hapus_category/{id}",[CategoryController::class,"hapus_Category"]);

Route::post("register_movie",[MovieController::class,"register"]);
Route::get("/get_movie",[MovieController::class,"getMovie"]);
Route::get("/get_detail_movie/{id}",[MovieController::class,"getDetailMovie"]); 
Route::put("/update_movie/{id}",[MovieController::class,"update_Movie"]);
Route::delete("/hapus_movie/{id}",[MovieController::class,"hapus_Movie"]);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/category', function (Request $request) {
    return $request->category();
});
Route::middleware('auth:sanctum')->get('/movie', function (Request $request) {
    return $request->Movie();
});