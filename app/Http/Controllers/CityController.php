<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = City::query()
            ->where('status', '=', 'active')
            ->limit(request('limit', self::DEFAULT_LIMIT))
            ->get();

        return $this->respond(CityResource::collection($categories));
    }
}
