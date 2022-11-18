<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DesignController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TownController;

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

/**Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
middleware('auth:sanctum')-> 
Route::get('/designs', [PatternController::class, 'index']);
*/
Route::post('/login', [UserController::class, 'login']);

Route::get('/department', [DepartmentController::class, 'index']);
Route::get('/town/department/{id}', [TownController::class, 'department']);
Route::get('/greeting', function () {
    return 'Hello World';
});