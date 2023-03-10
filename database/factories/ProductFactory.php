<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
//    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->numberBetween(100, 2000),
            'final_price' => $this->faker->numberBetween(100, 2000),
            'discount' => $this->faker->numberBetween(100, 200),
            'sku' => 1000000 + $this->faker->unique()->numberBetween(1, 1000000),
            'status' => 'active',
            'description' => $this->faker->text,
            'brand' => $this->faker->company,
            'color' => $this->faker->colorName,
            'size' => 'x',
            'country' => $this->faker->country,
            'delivery' => 1,
            'category_id' => $this->faker->numberBetween(1, 20),
            'city_id' => $this->faker->numberBetween(1, 20),
            'shop_id' => $this->faker->numberBetween(1, 20),
            'image_id' => $this->faker->numberBetween(1, 20)
        ];
    }
}
