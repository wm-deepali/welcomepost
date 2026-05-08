<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('getCountryProducts', [App\Http\Controllers\API\UserController::class, 'getCountryProducts']);
Route::get('getCategoryProducts', [App\Http\Controllers\API\UserController::class, 'getCategoryProducts']);
Route::post('create_user_profile', [App\Http\Controllers\API\UserController::class, 'create_user_profile'])->name('create_user_profile');
Route::post('create_employs', [App\Http\Controllers\API\UserController::class, 'create_employs'])->name('create_employs');
Route::post('login', [App\Http\Controllers\API\UserController::class, 'login'])->name('login');
Route::post('getUserInformation', [App\Http\Controllers\API\UserController::class, 'getUserInformation'])->name('getUserInformation');
Route::get('get_employs', [App\Http\Controllers\API\UserController::class, 'get_employs'])->name('get_employs');
Route::post('update_employs', [App\Http\Controllers\API\UserController::class, 'update_employs'])->name('update_employs');
Route::post('delete_employs', [App\Http\Controllers\API\UserController::class, 'delete_employs'])->name('delete_employs');
Route::post('employ_login', [App\Http\Controllers\API\UserController::class, 'employ_login'])->name('employ_login');

Route::get('get_products', [App\Http\Controllers\API\UserController::class, 'get_products'])->name('get_products');
Route::post('create_products', [App\Http\Controllers\API\UserController::class, 'create_products'])->name('create_products');
Route::post('add_to_cart', [App\Http\Controllers\API\UserController::class, 'add_to_cart'])->name('add_to_cart');
Route::post('user_product_history', [App\Http\Controllers\API\UserController::class, 'user_product_history'])->name('user_product_history');
Route::get('user_shopping_cart/{id}', [App\Http\Controllers\API\UserController::class, 'user_shopping_cart'])->name('user_shopping_cart');
Route::post('user_cart_count', [App\Http\Controllers\API\UserController::class, 'user_cart_count'])->name('user_cart_count');
Route::post('user_billing', [App\Http\Controllers\API\UserController::class, 'user_billing'])->name('user_billing');
Route::post('create_category', [App\Http\Controllers\API\UserController::class, 'create_category'])->name('create_category');
Route::get('get_category', [App\Http\Controllers\API\UserController::class, 'get_category'])->name('get_category');


Route::get('get_answer', [App\Http\Controllers\API\UserController::class, 'get_answer'])->name('get_answer');
Route::post('post_answer', [App\Http\Controllers\API\UserController::class, 'post_answer'])->name('post_answer');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
