<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSearchController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Models\Product;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get_image/{id}', function ($id) {
    $product = Product::find($id);
    return response()->download(storage_path('app/'.$product->image), null, [], null);
});


Route::get('get_all_books',[ProductSearchController::class,'allBooks']);
Route::post('update_product/{id}',[ProductController::class,'productUpdate'])->middleware('auth:sanctum');
Route::apiresource('products', ProductController::class);

Route::post('login', LoginController::class);    
Route::post('logout', LogoutController::class)->middleware('auth:sanctum');

Route::post('search',[ProductSearchController::class,'search']);
Route::post('elasticsearch',[ProductSearchController::class,'elasticsearch']);
Route::get('get_genre',[ProductSearchController::class,'genreFilter']);
Route::post('get_genre_filtered_data',[ProductSearchController::class,'genreFilterData']);
