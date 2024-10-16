<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('main');


Route::middleware("auth")->group(function () {
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::get('/chats', [\App\Http\Controllers\ChatController::class, 'index'])->name('chatIndex');
    Route::get('/chats/{id}/edit', [\App\Http\Controllers\ChatController::class, 'edit'])->name('chatEdit');
    Route::put('/chats/{id}', [\App\Http\Controllers\ChatController::class, 'update'])->name('chatUpdate');
    Route::get('/webhook-data', [\App\Http\Controllers\WebhookController::class, 'debug'])->name('webhookData');
});

Route::middleware("guest")->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login_process', [\App\Http\Controllers\AuthController::class, 'login_process'])->name(
        'login_process'
    );
});

