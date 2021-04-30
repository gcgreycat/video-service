<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth')->group(function () {
    Route::post('/subscription/create', [\App\Http\Controllers\SubscriptionController::class, 'create']);
    Route::put('/subscription/edit', [\App\Http\Controllers\SubscriptionController::class, 'edit']);
    Route::delete('/subscription/delete', [\App\Http\Controllers\SubscriptionController::class, 'delete']);

    Route::get('/video/list', [\App\Http\Controllers\VideoController::class, 'list']);
});

Route::post('/login', [\App\Http\Controllers\Api\LoginController::class, 'login'])->name('api.login');

Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
})->name('api.fallback.404');
