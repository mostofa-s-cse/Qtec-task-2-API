<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\ShortenUrlController;
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

// Route::post('/auth/register', [UserController::class, 'createUser']);
// Route::post('/auth/login', [UserController::class, 'loginUser']);
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group([
    'prefix' => 'v1/auth',
], function ($router) {
    Route::post('/register', [UserController::class, 'createUser']); //http://127.0.0.1:8000/api/v1/auth/register with bearer token
    Route::post('/login', [UserController::class, 'loginUser']); //http://127.0.0.1:8000/api/v1/auth/login with bearer token
});
    
Route::middleware(['auth:sanctum'])->prefix('v1/auth')->group(function () {
    Route::get('employee-list', [UserController::class, 'getall']);
    Route::resource('shortenurl', ShortenUrlController::class); // http://127.0.0.1:8000/api/v1/auth/shortenurl with bearer token
    Route::get('{shortUrl}', [ShortenUrlController::class, 'redirectShortUrl']); // http://127.0.0.1:8000/api/v1/auth/{shortUrl} with bearer token
    Route::post('logout',[UserController::class,'logout']);
});