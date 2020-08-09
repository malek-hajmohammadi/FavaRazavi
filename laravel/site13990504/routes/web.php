<?php


use Illuminate\Http\Request;
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
Route::get('/todos','TodoController@index')->name('todo.index');
Route::get('/todos/create','TodoController@create');
Route::post('/todos/create','TodoController@store');
Route::get('/todos/{todo}/edit','TodoController@edit');
Route::patch('/todos/{todo}/update','TodoController@update')->name('todo.update');



Route::get('/', function () {
    return view('home');
});
Route::get('/user', 'UserController@index');
Route::post('/upload','UserController@uploadAvatar');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
