<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MainController,
    CategoryController,
    CityController,
    BannerController,
    ShopController,
    ImageController,
    ProductController,
};

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

Route::get('/', function () {
    return response()->json('Home');
});

Route::get('/info', static function () {

    $products = \App\Models\Product::query()->distinct()->select([
        'products.*',
        'images.url as image_url'
    ])
        ->join('images', function ($join) {
            $join->on('images.product_id', '=', 'products.id')->where('images.is_primary', '=', 1);
        })
        ->orderByDesc('discount')
        ->take(2)
        ->get();

    return $products;
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('main', [MainController::class, 'index']);

    Route::get('productsByCategorySlug', [ProductController::class, 'getProductsByCategorySlug']);

    Route::get('categories', [CategoryController::class, 'index']);

    Route::get('cities', [CityController::class, 'index']);

    Route::get('banners', [BannerController::class, 'index']);

    Route::get('shops', [ShopController::class, 'index']);

    Route::get('images', [ImageController::class, 'index']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product}', [ProductController::class, 'show']);

    Route::get('/superSale', [ProductController::class, 'superSale']);
    Route::get('/randomCategory', [ProductController::class, 'randomFromCategory']);

    Route::get('/shop/{id}', [ProductController::class, 'shop']);

});
