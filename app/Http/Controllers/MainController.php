<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        //Banner
        $banners = Banner::query();

//        $banners->where('city_id', '=', request('city_id', 1));

        $banners->where('status', '=', 'active');


        //Categories
        $categories = Category::query()
            ->where('status', '=', 'active')
            ->whereNull(['parent_id'])
            ->limit(request('limit', self::DEFAULT_LIMIT))
            ->get();

//        $randomProducts = DB::table('products')->groupBy('category_id')->get();

        return response()->json([
            'meta' => [
                'statusCode' => 200,
                'error' => false
            ],
            'response' => [
                'banner' => $banners->get(),
                'super_sale' => $superSale,
                'categories' => $categories,
//                'random_top' => $randomProducts
            ]
        ]);
    }
}
