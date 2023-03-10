<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Product\IndexProductResource;
use App\Http\Resources\Product\RandomCategoryResource;
use App\Http\Resources\Product\ShopProductResource;
use App\Http\Resources\Product\ShowProductResource;
use App\Http\Resources\Product\SuperSaleResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->with(['category', 'city', 'primary_image']);

        $products = $products->paginate(self::DEFAULT_LIMIT);

        return $this->respond(IndexProductResource::collection($products));
    }

    public function show($product): JsonResponse
    {
        $product = Product::query()->with([
            'category',
            'city',
            'images'
        ])->bySlug($product)->firstOrFail();

        return $this->respond(new ShowProductResource($product));
    }

    public function getProductsByCategorySlug(): JsonResponse
    {
        $products = Category::query()->select([
            'products.*',
            'categories.id as category_id',
            'categories.name as category_name',
            'categories.slug as category_slug',
            'categories.status as category_status',
            'categories.image as category_image',
        ])->leftJoin('products', function ($join) {
            $join->on('products.category_id', '=', 'categories.id');
        });

        $products->where('categories.slug', '=', request('categorySlug'));

        $products = $products->limit(20)->get();

        return $this->respond($products);
    }

    public function shop(int $id): JsonResponse
    {
        $products = Product::query()
            ->with([
                'shop'
            ])
            ->where('shop_id', '=', $id)
            ->get();

        return $this->respond(ShopProductResource::collection($products));
    }

    public function superSale()
    {
        $categories = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $productsCollection = new Collection();

        foreach ($categories as $category) {
            $products = Product::query()
                ->with([
                    'primary_image',
                    'shop'
                ])
                ->where('status', '=', 'active')
                ->whereIn('category_id', [$category])
                ->take(2)
                ->get();
            $productsCollection = $productsCollection->merge($products);
        }

        return $this->respond(SuperSaleResource::collection($productsCollection));
    }

    /**
     * @return JsonResponse
     */
    public function randomFromCategory(): JsonResponse
    {
        $categories = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $productsCollection = new Collection();

        foreach ($categories as $category) {
            $products = Product::with([
                'primary_image',
                'shop'
            ])
                ->where('products.status', '=', 'active')
                ->whereIn('products.category_id', [$category])
                ->orderByDesc('discount')
                ->take(2)
                ->get();

            $productsCollection = $productsCollection->merge($products);
        }

        return $this->respond(RandomCategoryResource::collection($productsCollection));
    }
}
