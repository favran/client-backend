<?php

namespace App\Http\Controllers;

use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = Banner::query()->where('status', '=', 'active');

        $banners->when(request('city_id', Banner::DEFAULT_CITY), function ($query, $cityId) {
            $query->where('city_id', '=', $cityId);
        });
        $banners->inRandomOrder();

        $data = $banners->first();

        return $this->respond(new BannerResource($data));
    }
}
