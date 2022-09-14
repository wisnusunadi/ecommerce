<?php

use App\Http\Controllers\API\ProdukCategoryController;
use App\Http\Controllers\API\ProdukController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\TransactionController;
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



Route::get('products',[ProdukController::class,'all']);
Route::get('categories',[ProdukCategoryController::class,'all']);
Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user',[UserController::class,'fetch']);
    Route::post('user',[UserController::class,'updateProfile']);
    Route::post('logout',[UserController::class,'logout']);

    Route::get('transactions',[TransactionController::class,'all']);
    Route::post('checkout',[TransactionController::class,'checkout']);

});
