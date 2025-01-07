<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\API\PlatController;
use App\Http\Controllers\Api\ChangePasswordsController;

Route::get('/plats', [PlatController::class, 'index']);
Route::post('/cart', [CartController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/change-password', [ChangePasswordsController::class, 'changePassword']);

Route::middleware('auth:sanctum')->get('/home', [AuthController::class, 'home']);
Route::get('/dashboard', [UserController::class, 'index']);

