<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DjangoController;
use App\Http\Controllers\FlaskController;
use App\Http\Controllers\ExpressController;
use App\Http\Controllers\FlaskInventarioController;

// Auth (local en Laravel)
Route::post('/register', [UserController::class, 'register']);
Route::post('/login',    [UserController::class, 'login']);
Route::post('/logout',   [UserController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/password_reset', [UserController::class, 'password_reset']);

// Express → Firebase (Usuarios)
Route::get('/usuarios',       [ExpressController::class, 'index'])->middleware('auth:sanctum');
Route::post('/usuarios',      [ExpressController::class, 'store'])->middleware('auth:sanctum');
Route::get('/usuarios/{id}',  [ExpressController::class, 'show'])->middleware('auth:sanctum');
Route::put('/usuarios/{id}',  [ExpressController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/usuarios/{id}',[ExpressController::class,'destroy'])->middleware('auth:sanctum');

// Django → Productos (MySQL)
Route::get('/productos',        [DjangoController::class, 'index'])->middleware('auth:sanctum');
Route::post('/productos',       [DjangoController::class, 'store'])->middleware('auth:sanctum');
Route::get('/productos/{id}',   [DjangoController::class, 'show'])->middleware('auth:sanctum');
Route::put('/productos/{id}',   [DjangoController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/productos/{id}',[DjangoController::class, 'destroy'])->middleware('auth:sanctum');

// Flask → Pedidos (MySQL)
Route::get('/pedidos',        [FlaskController::class, 'index'])->middleware('auth:sanctum');
Route::post('/pedidos',       [FlaskController::class, 'store'])->middleware('auth:sanctum');
Route::get('/pedidos/{id}',   [FlaskController::class, 'show'])->middleware('auth:sanctum');
Route::put('/pedidos/{id}',   [FlaskController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/pedidos/{id}',[FlaskController::class, 'destroy'])->middleware('auth:sanctum');

// Flask Inventario → Inventario (PostgreSQL)
Route::get('/inventario',        [FlaskInventarioController::class, 'index'])->middleware('auth:sanctum');
Route::post('/inventario',       [FlaskInventarioController::class, 'store'])->middleware('auth:sanctum');
Route::get('/inventario/{id}',   [FlaskInventarioController::class, 'show'])->middleware('auth:sanctum');
Route::put('/inventario/{id}',   [FlaskInventarioController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/inventario/{id}',[FlaskInventarioController::class, 'destroy'])->middleware('auth:sanctum');