<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountUserController;
use App\Http\Controllers\Auth\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//-----------------------------------------//Login Registration//-----------------------------------------//
Route::controller(AuthController::class)->group(function(){
    Route::post('registration','register')->name('user.register');
    Route::get('verifyEmail/{token}','accountVerify')->name('user.verify');
    Route::post('login','login')->name('user.login');
});
//-----------------------------------------//Account//-----------------------------------------//
Route::controller(AccountController::class)->group(function(){
    Route::post('create','addAccount')->name('accound.add');
    Route::patch('update/{id}','updateAccount')->name('account.update');
    Route::delete('delete/{id}','deleteAccount')->name('account.delete');
    Route::get('index','index')->name('account.index');
    Route::get('show/{id}','show')->name('account.show');
});




// Route::apiResource('my',AccountController::class);