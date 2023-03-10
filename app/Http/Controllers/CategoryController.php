<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->with(['childrenRecursive'])
            ->where('status', '=', 'active')
            ->whereNull(['parent_id'])
            ->limit(request('limit', self::DEFAULT_LIMIT))
            ->get();

        return $this->respond(CategoryResource::collection($categories));
    }
}
