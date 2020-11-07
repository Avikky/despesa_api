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
    Route::post('search', 'API\ExpensesController@searchExpense');
    Route::post('store', 'API\ExpensesController@store');
    Route::put('update/{id}', 'API\ExpensesController@update');
    Route::delete('delete/{id}', 'API\ExpensesController@destroy');
});

Route::group(['middleware' => 'api','prefix' => 'expense-category'], function ($router) {
    Route::get('all', 'API\ExpenseCategoryController@index');
    Route::get('single/{id}', 'API\ExpenseCategoryController@show');
    Route::post('store', 'API\ExpenseCategoryController@store');
    Route::put('update/{id}', 'API\ExpenseCategoryController@update');
    Route::delete('delete/{id}/', 'API\ExpenseCategoryController@destroy');
});

Route::group(['middleware' => 'api','prefix' => 'income'], function ($router) {
    Route::get('all', 'API\IncomeController@index');
    Route::get('single/{id}', 'API\IncomeController@show');
    Route::post('store', 'API\IncomeController@store');
    Route::put('update/{id}', 'API\IncomeController@update');
    Route::delete('delete/{id}', 'API\IncomeController@destroy');
});

Route::group(['middleware' => 'api','prefix' => 'customer'], function ($router) {
    Route::get('all', 'API\CustomerController@index');
    Route::get('single/{id}', 'API\CustomerController@show');
    Route::post('store', 'API\CustomerController@store');
    Route::put('update/{id}', 'API\CustomerController@update');
    Route::delete('delete/{id}', 'API\CustomerController@destroy');
});

Route::group(['middleware' => 'api','prefix' => 'settings'], function ($router) {
    Route::put('reset-password', 'API\SettingsController@changePassword');
    Route::put('update-profile', 'API\SettingsController@updateProfile');
});

Route::group(['middleware' => 'api','prefix' => 'opening-balance'], function ($router) {
    Route::post('add', 'API\BalanceController@addOpeninBalance');
    Route::get('current', 'API\BalanceController@getCurrentOpeningBal');
    Route::get('last', 'API\BalanceController@getLastOpeningBal');
    Route::get('general', 'API\BalanceController@getGeneralOpeningBal');
    Route::put('edit/{id}', 'API\BalanceController@editOpeningBalance');
    Route::put('reuse-bal/{id}', 'API\BalanceController@reusingOpeningBalance');

    Route::delete('delete/{id}', 'API\BalanceController@destroy');
});


Route::group(['middleware' => 'api','prefix' => 'report'], function ($router) {
    Route::get('all', 'API\ReportController@index');
    Route::post('generate', 'API\ReportController@generateReport');
    Route::post('generate', 'API\ReportController@generateReport');
});





