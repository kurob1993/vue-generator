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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'GeneratorController@index')->name('generator.index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/generate', 'GeneratorController@generate')->name('generate');
Route::get('/columnsTable', 'GeneratorController@getTableColumns')->name('columnsTable');
