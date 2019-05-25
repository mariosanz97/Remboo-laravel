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
Route::get('/recomendadorUresult', 'HomeController@recomendador_user_user_result');
Route::get('/calcular_correlacion_user', 'HomeController@calcular_correlacion_user_user');

Route::get('/recomendadorUser', 'HomeController@recomendador_user0');

Route::get('/valorarPelicula', 'HomeController@valorarPeliculaController');
Route::get('/valorar', 'HomeController@valorar');

