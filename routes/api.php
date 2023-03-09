<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\Student;
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
Route::controller(AuthController::class)->group(function () {
    Route::post('registration', 'register')->name('user.register');
    Route::get('verifyEmail/{token}', 'accountVerify')->name('user.verify');
    Route::post('login', 'login')->name('user.login');
    Route::post('forgotpassword', 'sendMailForForgotPassword')->name('user.mailVerify');
    Route::post('resetpassword', 'resetPassword')->name('user.resetpassword');
});



Route::middleware(['auth:sanctum'])->group(function () {

    //User Profile & changePassword
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::post('changepassword', 'changePassword')->name('user.changepassword');
        Route::get('userprofile/{id}', 'userProfile')->name('userProfile');
        Route::get('get/{id}', 'get')->name('user.get');
        Route::get('list-of-account/{id}', 'listOfAccount')->name('user.listOfAccount');
        Route::get('list-of-account-users/{id}', 'listOfAccountUser')->name('user.listOfAccountUser');
        Route::get('list-of-transactions/{id}', 'listOfTransactions')->name('user.listOfTransactions');
    });

    //Account//
    Route::controller(AccountController::class)->prefix('account')->group(function () {
        Route::get('list', 'list')->name('account.index');
        Route::post('create', 'create')->name('account.add');
        Route::patch('update/{id}', 'update')->name('account.update');
        Route::get('get/{id}', 'get')->name('account.show');
        Route::delete('delete/{id}', 'destroy')->name('account.delete');
        Route::get('list-of-account-users/{id}', 'listOfAccountUser')->name('user.listOfAccountUser');
        Route::get('list-of-transactions/{id}', 'listOfTransactions')->name('account.listOfTransactions');
    });

    //Account-User//
    Route::controller(AccountUserController::class)->prefix('accountUser')->group(function () {
        Route::get('list', 'list')->name('accountUser.list');
        Route::post('create', 'create')->name('accountUser.create');
        Route::patch('update/{id}', 'update')->name('accountUser.update');
        Route::get('get/{id}', 'get')->name('accountUser.get');
        Route::delete('delete/{accountUser}', 'destroy')->name('accountUser.delete');
        Route::get('list-of-transactions/{id}', 'listOfTransactions')->name('account.listOfTransactions');
    });

    //Transaction//
    Route::controller(TransactionController::class)->prefix('transaction')->group(function () {
        Route::get('list', 'list')->name('transaction.list');
        Route::post('create', 'create')->name('transaction.create');
        Route::patch('update/{transaction}', 'update')->name('transaction.update');
        Route::get('get/{transaction}', 'get')->name('transaction.get');
        Route::delete('delete/{transaction}', 'destroy')->name('transaction.delete');
        Route::get('get-account-user/{id}', 'getAccountUser')->name('transaction.getAccountUser');
        Route::get('get-account/{id}', 'getAccount')->name('transaction.getAccount');
    });


    //school//
    Route::controller(SchoolController::class)->middleware(['admin'])->prefix('school')->group(function(){
        Route::get('list','list')->name('school.list');
        Route::post('create','create')->name('school.create');
        Route::patch('update/{id}','update')->name('school.update');
        Route::get('get/{id}','get')->name('school.get');
        Route::delete('delete/{id}','destroy')->name('school.delete');
    });

    //teachear
    Route::controller(TeacherController::class)->middleware(['admin'])->prefix('teacher')->group(function(){
        Route::get('list','list')->name('teacher.list');
        Route::post('create','create')->name('teacher.create');
      
    });
    Route::controller(TeacherController::class)->middleware('teacher')->prefix('teacher')->group(function(){
        Route::get('get','get')->name('teacher.get');
        Route::Patch('update/{id?}','update')->name('teacher.update');
        Route::delete('delete/{id}','destroy')->name('teacher.delete');
    });


    // //student
    // Route::controller(StudentController::class)->middleware('teacher')->prefix('student')->group(function(){
    //     Route::get('list','list')->name('student.list');
    //     Route::post('create','create')->name('student.create');
    // });

    Route::controller(StudentController::class)->prefix('student')->group(function(){
        Route::middleware(['teacher'])->group(function(){
            Route::get('list','list')->name('student.list');
            Route::post('create','create')->name('student.create');
        });
        Route::Patch('update/{id?}','update')->name('student.update');
        Route::get('get','get')->name('student.get');
        Route::delete('delete/{id}','destroy')->name('student.delete');
    });

    //subject
    Route::controller(SubjectController::class)->prefix('subject')->group(function(){
        Route::middleware(['admin'])->group(function(){
            Route::post('create', 'create')->name('subject.add');
            Route::patch('update/{id}', 'update')->name('subject.update');
            Route::delete('delete/{id}', 'destroy')->name('subject.delete');
        });

        Route::get('list', 'list')->name('subject.index');
        Route::get('get/{id}', 'get')->name('subject.show');
    });    
});

Route::controller(EmployeeController::class)->prefix('employee')->group(function()
{
    Route::get('list','list')->name('employee.list');
    Route::get('get/{query}/{value}','get')->name('employee.get');
});
