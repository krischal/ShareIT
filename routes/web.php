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
// The root uri of application


Route::get('/', 'SharedDataController@index')->name('showSharedDataPage');
Route::get('/allData', 'SharedDataController@shareddatas')->name('loadSharedData');
Route::post('/addData', 'SharedDataController@store')->name('storeSharedData');
Route::delete('/delData/{sharedDataItem}', 'SharedDataController@destroy')->name('deleteSharedData');
