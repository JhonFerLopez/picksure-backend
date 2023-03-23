<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageproductsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\CategoriesController as Category;
use App\Http\Controllers\QualifyController;


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
middleware('auth:sanctum')-> */

//Auth
Route::post('/auth/token', [AuthController::class, 'login']);

//Users
Route::get('/user/like_category', [UserController::class, 'showLikeCategory']);
Route::post('/user/like_category', [UserController::class, 'createLikeCategory']);
Route::delete('/user/like_category', [UserController::class, 'deleteLikeCategory']);
Route::post('/user/like_imageproduct', [UserController::class, 'createLikeImageproduct']);
Route::get('/user/like_imageproduct', [UserController::class, 'showLikeImageproduct']);
Route::delete('/user/like_imageproduct', [UserController::class, 'deleteLikeImageproduct']);

//Categories
Route::get('/categories', [Category::class, 'index']);
Route::get('/categories/{prefijo}', [Category::class, 'idioma']);

//Departments
Route::middleware('auth:sanctum')->get('/department', [DepartmentController::class, 'index']);
//Route::get('/department', [DepartmentController::class, 'index']);

//Towns
Route::get('/town/department/{id}', [TownController::class, 'department']);

//Qualifies
Route::get('/qualify', [QualifyController::class, 'index']);
Route::get('/qualify/{id}', [QualifyController::class, 'rateApp']);
Route::post('/qualify/{id}', [QualifyController::class, 'createQualifyApp']);

//Images
Route::get('/imageproducts', [ImageproductsController::class, 'index']);
Route::get('/imageproducts/get/{id}/', [ImageproductsController::class, 'showOne']);
Route::get('/imageproducts/category', [ImageproductsController::class, 'categoryId']);
Route::get('/imageproducts/search', [ImageproductsController::class, 'search']);

Route::get('/greeting', function () {
    return 'Hello World';
});