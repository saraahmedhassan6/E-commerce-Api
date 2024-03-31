<?php

use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $category = Category::latest('id')->first();
    return view('welcome',compact('category'));
});
Route::controller( ProductController::class)->prefix('product')->group(function(){
    Route::post('test','store')->name('testStore');
    Route::put('imagetest/{id}','TestUpdate')->name('TestUpdate');

});

Route::get('/updateTest', function () {
    $product = Product::latest('id')->first();
    return view('update',compact('product'));
});
