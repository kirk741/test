<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/registration', [UserController::class, 'create']);
Route::post('/authorization', [UserController::class, 'login'])->name('login');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth:api');

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now()
    ]);
});
