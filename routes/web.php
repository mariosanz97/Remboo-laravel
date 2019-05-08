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
    return view('home');
});

Route::get('/recomendadorU', 'HomeController@recomendador_user_user');
Route::get('/calcular_correlacion', 'HomeController@calcular_correlacion');

Route::get('/recomendadorI', 'HomeController@recomendador_item_item');
Route::get('/recomendadorUser', 'HomeController@recomendador_user0');

