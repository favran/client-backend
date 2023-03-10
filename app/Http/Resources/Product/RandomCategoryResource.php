<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Imageresource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RandomCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'final_price' => $this->final_price,
            'discount' => $this->discount,
            'sku' => $this->sku,
            'status' => $this->status,
            'description' => $this->description,
            'brand' => $this->brand,
            'color' => $this->color,
            'size' => $this->size,
            'country' => $this->country,
            'delivery' => $this->delivery,
            'image' => (new Imageresource($this->primary_image))->url ?? "https://img.mvideo.ru/Big/small_pic/65/30063307bb.jpg",
            'category' => $this->category->name,
            'shop' => $this->shop->name ?? 'korvon'
        ];
    }
}
