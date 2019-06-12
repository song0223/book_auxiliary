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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/', 'HomeController@search');
Route::get('/search/{t?}/{type?}/{book_id?}/{query?}', ['as' => 'home.search', 'uses' => 'HomeController@search']);
Route::get('/b/{id}', ['as' => 'home.book', 'uses' => 'HomeController@book']);
Route::get('/d/{id}/{query?}', ['as' => 'home.desc', 'uses' => 'HomeController@desc']);
Route::get('/i', ['as' => 'home.import', 'uses' => 'HomeController@import']);
Route::any('/ib', ['as' => 'home.import-book', 'uses' => 'HomeController@importBook']);
