<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;
use App\Models\Transaction;
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



//Login Registration//
Route::controller(AuthController::class)->group(function(){
    Route::post('registration','register')->name('user.register');
    Route::get('verifyEmail/{token}','accountVerify')->name('user.verify');
    Route::post('login','login')->name('user.login');
    Route::post('forgotpassword','sendMailForForgotPassword')->name('user.mailVerify');
    Route::post('resetpassword','resetPassword')->name('user.resetpassword');
    Route::post('changepassword','changePassword')->name('user.changepassword')->middleware('auth:sanctum');
    Route::get('userprofile','userProfile')->name('userProfile')->middleware('auth:sanctum');
});



Route::middleware(['auth:sanctum'])->group(function(){
    
    //Account//
    Route::prefix('account')->group(function () {
        Route::controller(AccountController::class)->group(function(){
            Route::post('create','create')->name('accound.add');
            Route::patch('update/{id}','update')->name('account.update');
            Route::delete('delete/{id}','destroy')->name('account.delete');
            Route::get('list','list')->name('account.index');
            Route::get('get/{id}','get')->name('account.show');
        });
    });

    //Account-User//
    Route::prefix('accountUser')->group(function () {
        Route::controller(AccountUserController::class)->group(function(){
            Route::get('list','list')->name('accountUser.list');
            Route::post('create','create')->name('accountUser.create');
            Route::patch('update/{accountUser}','update')->name('accountUser.update');
            Route::get('get/{accountUser}','get')->name('accountUser.get');
            Route::delete('delete/{accountUser}','destroy')->name('accountUser.delete');
        });
    });
   
    //Transaction//
    Route::prefix('transaction')->group(function () {
        Route::controller(TransactionController::class)->group(function(){
            Route::get('list','list')->name('transaction.list');
            Route::post('create','create')->name('transaction.create');
            Route::patch('update/{transaction}','update')->name('transaction.update');
            Route::get('get/{transaction}','get')->name('transaction.get');
            Route::delete('delete/{transaction}','destroy')->name('transaction.delete');
        });
    });
});


 //Route::apiResource('accountUser',AccountUserController::class)->middleware('auth:sanctum');
 //Route::apiResource('transction',TransactionController::class)->middleware('auth:sanctum');