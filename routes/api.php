<?php

use App\Http\Controllers\UserController;

use App\Http\Requests\RegisterRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('verify/{token}' , [AuthController::class, 'verify'])->name('verify');
Route::post('forgot-password' , [AuthController::class, 'forgotPassword']);
Route::post('reset-password' , [AuthController::class, 'resetPassword']);


Route::middleware(['auth:sanctum','verified' ])->group(function () {
    Route::get('user', [UserController::class, 'show']);
});








