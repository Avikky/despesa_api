<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register', 'AuthController@register');
});

Route::group(['middleware' => 'api','prefix' => 'expenses'], function ($router) {
    Route::get('all', 'API\ExpensesController@index');
    Route::get('single/{id}', 'API\ExpensesController@show');
    Route::post('store', 'API\ExpensesController@store');
    Route::put('update/{id}', 'API\ExpensesController@update');
    Route::delete('delete/{id}', 'API\ExpensesController@destroy');
});

Route::group(['middleware' => 'api','prefix' => 'expense-category'], function ($router) {
    Route::get('all', 'ExpenseCategoryController@index');
    Route::get('single/{id}', 'ExpenseCategoryController@show');
    Route::post('store', 'ExpenseCategoryController@store');
    Route::put('update/{id}', 'ExpenseCategoryController@update');
    Route::delete('delete/{id}', 'ExpenseCategoryController@destroy');
});

Route::group(['middleware' => 'api','prefix' => 'income'], function ($router) {
    Route::get('all', 'IncomeController@index');
    Route::get('single/{id}', 'IncomeController@show');
    Route::post('store', 'IncomeController@store');
    Route::put('update/{id}', 'IncomeController@update');
    Route::delete('delete/{id}', 'IncomeController@destroy');
});






