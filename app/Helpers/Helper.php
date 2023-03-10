<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Helper
{
    public static function setUniqueSlug(Model $model, string $slugValue, string $slugAttribute = 'slug'): string
    {
        if ($model::where($slugAttribute, $slug = Str::slug($slugValue))
            ->where('id', '<>', $model->id)
            ->exists()
        ) {
            $original = $slug;

            $count = 2;

            while ($model::where($slugAttribute, $slug)->where('id', '<>', $model->id)->exists()) {
                $slug = "{$original}-" . $count++;
            }

            return $slug;
        }

        return $slug;
    }
}
