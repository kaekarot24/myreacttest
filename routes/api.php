<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("user-signup", [UserController::class, 'userSignup']);
Route::post("user-login",  [UserController::class, 'userLogin']);
Route::post("user-update", [UserController::class, 'userUpdate']);
Route::get("user/{email}", [UserController::class, 'userDetail']);
