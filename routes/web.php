<?php

use App\Facades\Telegram;
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

Route::get('/', function (){
    Telegram::message(7755461236, 'test');
    return view('welcome');
});

Route::get('/webhook-data', function (\App\Telegram\Webhook\Webhook $webhook){
    dd(\Illuminate\Support\Facades\Cache::get('webhook-data'));
});
