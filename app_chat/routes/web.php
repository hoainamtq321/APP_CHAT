<?php

use App\Http\Controllers\AuthController;
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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

/*
Route::get('/', function () {
    return view('home');
});*/

/*

Route::get('/',[ChatController::class,'index'])->name('user.login');
Route::post('/broadcast', [ChatController::class, 'broadcastChat'])->name('broadcast.chat');

*/

Route::get('/login',[AuthController::class,'fromlogin'])->name('fromlogin');
Route::post('/login',[AuthController::class,'login'])->name('login');

Route::get('/register',[AuthController::class,'fromRegister'])->name('fromRegister');
Route::post('/register',[AuthController::class,'register'])->name('register');

Route::group(['middleware'=>'auth'],function(){
    Route::get('/message', [ChatController::class, 'message'])->name('broadcast.message');
    Route::post('/sendMail',[ChatController::class,'sendMail'])->name('broadcast.sendMail');

    Route::get('/conversation', [ChatController::class, 'conversation'])->name('broadcast.conversation');

    Route::GET('/showMessage',[ChatController::class,'showMessage'])->name('broadcast.showMessage');
    Route::GET('/showOldMess',[ChatController::class,'showOldMess'])->name('broadcast.showOldMess');
});