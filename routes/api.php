<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    StatusApiController,
    ProductController
};

//Status do sistema
Route::get('/', [StatusApiController::class, 'status']);


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);


/**
 * ROTAS AUTENTICADAS
*/
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AuthController::class,'userAuthenticated']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    //Rotas de produtos
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{code}', [ProductController::class, 'show']);
    Route::delete('products/{code}', [ProductController::class, 'destroy']);
    Route::put('products/{code}', [ProductController::class, 'update']);
});

