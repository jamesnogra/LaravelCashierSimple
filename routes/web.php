<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/pay/{plan}/{plan_id}', 'PaymentsController@pay')->name('pay');
Route::get('/pay/{plan}/{plan_id}', 'PaymentsController@pay')->name('pay');
Route::get('/cancel/{plan}', 'PaymentsController@cancel')->name('cancel');
Route::get('/user/invoice/{invoice_id}', 'PaymentsController@invoice')->name('invoice');