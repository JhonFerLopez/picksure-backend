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
Route::middleware('auth:sanctum')->get('/user/like_category/{user_id}/{lang_id}', [UserController::class, 'showLikeCategory']);
Route::post('/user/like_category', [UserController::class, 'createLikeCategory']);
Route::delete('/user/like_category/{user_id}/{category_id}', [UserController::class, 'deleteLikeCategory']);
Route::post('/user/like_imageproduct/{user_id}/{img_id}', [UserController::class, 'createLikeImageproduct']);
Route::middleware('auth:sanctum')->get('/user/like_imageproduct/{user_id}/{lang_id}', [UserController::class, 'showLikeImageproduct']);
Route::delete('/user/like_imageproduct/{user_id}/{img_id}', [UserController::class, 'deleteLikeImageproduct']);
Route::post('/user/create_users', [UserController::class, 'CreateUser']);
Route::delete('/user/delete_users/{user_id}', [UserController::class, 'DeleteUser']);
Route::put('/user/Update_users/{user_id}', [UserController::class, 'UpdateUser']);
Route::get('/user/show_users/{user_id}', [UserController::class, 'ShowInfoUser']);
//Categories
Route::get('/categories/{language}', [Category::class, 'index']);
Route::get('/categories/user/{language}', [Category::class, 'categoryUser']);

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
Route::get('/imageproducts/{language}', [ImageproductsController::class, 'index']);
Route::get('/imageproducts/{language}/{image_id}', [ImageproductsController::class, 'showOne']); 
Route::get('/imageproducts/category/{language}/{category_id}', [ImageproductsController::class, 'categoryId']);
Route::get('/imageproducts/filter/search/{language}', [ImageproductsController::class, 'search']);

Route::get('/greeting', function () {
    return 'Hello World';
});