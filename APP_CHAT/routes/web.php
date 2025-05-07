<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

/*Login*/
Route::get('/login',[LoginController::class,'formLogin'])->name('formlogin');
Route::post('login',[LoginController::class,'login'])->name('login');

Route::post('/broadcast',[ChatController::class,'broadcastChat'])->name('broadcast.chat');



Route::middleware(['auth'])->group(function () {
    Route::get('/chat',[ChatController::class,'chat'])->name('chat');

    Route::get('/showmessage',[ChatController::class,'showMessage'])->name('showmessage');
});
