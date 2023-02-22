<?php

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

Route::get('/test', [\App\Http\Controllers\TestController::class,"Test"]);

Route::group(["prefix" => "admin"],function(){

    Route::get('/auth', [\App\Http\Controllers\AuthController::class,"auth"]) -> name('admin.auth');
    Route::post('/authenticate', [\App\Http\Controllers\AuthController::class,"authenticate"]);
    Route::group(["middleware" => 'auth'],function (){
        Route::get('/panel',function (){
            return view("admin.employees");
        });
        Route::get('/panel/getEmployee',[\App\Http\Controllers\Admin\EmployeeController::class,"show"])->name("admin.panel.getemployee");
        Route::post('/panel/remove',[\App\Http\Controllers\Admin\EmployeeController::class,"remove"])->name("admin.panel.delemployee");
        Route::get('/panel/edit/{id}',[\App\Http\Controllers\Admin\EmployeeController::class,"edit"]);
        Route::get('/panel/create',[\App\Http\Controllers\Admin\EmployeeController::class,"create"]);
        Route::post('/panel/delete',[\App\Http\Controllers\Admin\EmployeeController::class,"delete"]);
        Route::get("/panel/edit/names/get",[\App\Http\Controllers\Admin\EmployeeController::class, "getNames"]);
        Route::post("/panel/edit/employee/edit",[\App\Http\Controllers\Admin\EmployeeController::class, "editEmployee"])->name("employee.edit");
        Route::post("/panel/load/img",[\App\Http\Controllers\Admin\EmployeeController::class, "loadTempImage"]);
    });


});


