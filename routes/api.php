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
Route::middleware('isApiUser')->group(function() {
// stripe PAYMENT
Route::get('/allPayment', 'PaymentController@allPayment');
//Cash PAYMENT
Route::get('/allCashPayment', 'CashController@allCashPayment');
//Product
Route::post('newglassess', 'CategoryController@newglassess');
Route::get('/newglassess/show/{id}', 'CategoryController@show');
Route::post('/newglassess/update/{id}', 'CategoryController@update');
Route::get('/newglassess/delete/{id}', 'CategoryController@delete');
Route::get('/allglassess', 'CategoryController@allglassess');
Route::get('/solidglassess', 'CategoryController@solidglassess');
//Route::get('/availableglassess', 'CategoryController@availableglassess');
//general-Api-relative
Route::post('/relative', 'RelativeController@store');
Route::post('/relative/update/{id}', 'RelativeController@update');
Route::get('/relative/delete/{id}', 'RelativeController@delete');
//general-Api-Cities
Route::post('/city', 'CityController@store');
Route::post('/city/update/{id}', 'CityController@update');
Route::get('/city/delete/{id}', 'CityController@delete');
//general-Api-roles
Route::post('/role', 'CodeController@store');
Route::get('/role', 'CodeController@index');
Route::post('/role/update/{id}', 'CodeController@update');
Route::get('/role/delete/{id}', 'CodeController@delete');
Route::post('/UserRole/update/{id}', 'CodeController@updateUserRole');
Route::get('/allUser', 'ApiAuthController@allUser');
//general-Api-FeedBack
Route::get('/FeedBack', 'FeedbackController@index');
//general-Api-Reports
Route::get('/Report', 'ReportController@index');
});
// login/register
Route::post('/handle-register', 'ApiAuthController@handleRegister');
Route::post('/handle-login', 'ApiAuthController@handleLogin');
Route::post('/logout', 'ApiAuthController@logout');
//general-Api-Profile
Route::post('/changePassword', 'CodeController@changePassword');
Route::post('/EditProfile/update/{id}', 'CodeController@EditProfile');
//general-Api-FeedBack
Route::post('/FeedBack', 'FeedbackController@store');
//general-Api-Reports
Route::post('/Report', 'ReportController@store');
// stripe PAYMENT
Route::post('payment-intent', 'PaymentController@CreatePayIntent');
Route::post('store-intent', 'PaymentController@storeStripePayment');
//Cash PAYMENT
Route::post('cashOnDeleviry', 'CashController@cashOnDeleviry');
//new update
Route::get('/city', 'CityController@index');
Route::get('/relative', 'RelativeController@index');
