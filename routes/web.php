<?php

use App\Http\Middleware\Autenticado;
use App\Http\Middleware\Trazabilidad;

use App\Http\Controllers\pqrsController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\inicioController;
use App\Http\Controllers\gestionController;
use App\Http\Controllers\trazabilidadController;

use Illuminate\Support\Facades\Route;

//login
Route::get('/login', [loginController::class, 'login']);
Route::post('/login', [loginController::class, 'ingresar']);
Route::get('/logout', [loginController::class, 'logout']);

Route::get('/', [pqrsController::class, 'index']);
Route::post('/', [pqrsController::class, 'radicar']);
Route::get('/consultar', [pqrsController::class, 'consultar']);
Route::get('/consultar_pqrs/{data}', [pqrsController::class, 'consultar_pqrs']);
Route::post('/conforme', [pqrsController::class, 'conforme']);

Route::get('/inicio', [inicioController::class, 'inicio'])->middleware('autenticado');
Route::get('/consulta_general/{data}', [inicioController::class, 'consulta_general'])->middleware('autenticado');

Route::get('/trazabilidad', [trazabilidadController::class, 'index']);/*->middleware('autenticado', 'trazabilidad');*/
Route::get('/trasladar/{pqrs}', [trazabilidadController::class, 'info_traslado']);/*->middleware('autenticado', 'trazabilidad');*/
Route::post('/trasladar', [trazabilidadController::class, 'trasladar']);/*->middleware('autenticado', 'trazabilidad');*/

Route::get('/informacion_pqrs/{id}', [gestionController::class, 'informacion'])->middleware('autenticado');
Route::post('/respuesta', [gestionController::class, 'respuesta']);

Route::get('/prueba', [pqrsController::class, 'prueba']);