<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StackController;
use App\Http\Controllers\KeyValueStoreController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('stack', [StackController::class, 'getFromStack']);
Route::post('stack', [StackController::class, 'addToStack']);


Route::post('key-value', [KeyValueStoreController::class, 'addToStore']);
Route::get('key-value/{key}', [KeyValueStoreController::class, 'getValue']);
Route::delete('key-value/{key}', [KeyValueStoreController::class, 'deleteValue']);