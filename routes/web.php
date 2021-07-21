<?php

use App\Http\Controllers\HouseController;
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

Route::get('/houses/sync', [HouseController::class, 'sync']);
Route::post('/houses/search', [HouseController::class, 'search']);
Route::post('/houses/search/around', [HouseController::class, 'searchAround']);
