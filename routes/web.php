<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\EventoController;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/criar-evento', [EventoController::class, 'criarEvento']);
    Route::delete('/excluir-evento/{id}', [EventoController::class, 'excluirEvento']);
    Route::put('/editar-evento/{nomeEvento}', [EventoController::class, 'editarEvento']);
    Route::get('/', function () { return view('index'); });

});


Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');


Route::get('/eventos', [EventoController::class, 'VisualizarEvento']);
Route::get('/eventos/{id}', [EventoController::class, 'VisualizarEventoEsp']);


Auth::routes();

