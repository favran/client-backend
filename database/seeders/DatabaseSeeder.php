<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::factory(100)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

/*
Аксессуары для одежды



Зонты
Кошельки и кредитницы
Носовые платки
Очки и футляры
Перчатки и варежки
Платки и шарфы
Ремни и пояса
Сумки и рюкзаки
Часы и ремешки
Чемоданы и защита багажа




 */
