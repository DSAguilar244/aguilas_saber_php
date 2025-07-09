<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\ReporteController;

Route::apiResource('productos', ProductoApiController::class);

Route::get('/reporte-productos', [ReporteController::class, 'productosPDF']);
