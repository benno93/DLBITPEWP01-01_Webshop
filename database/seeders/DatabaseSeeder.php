<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// PHP Klassen einbinden
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $category1 = Category::create([
           'name' => 'Apple'
        ]);

        Product::create([
            'category_id' => $category1->id,
            'name' => 'MacBook Pro',
            'stock' => 10,
            'price' => 1499,
        ]);

        $category2 = Category::create([
            'name' => 'Lenovo'
        ]);

        Product::create([
            'category_id' => $category2->id,
            'name' => 'ThinkPad T14',
            'stock' => 15,
            'price' => 899,
        ]);

        $category3 = Category::create([
            'name' => 'HP'
        ]);

        Product::create([
            'category_id' => $category3->id,
            'name' => 'EliteBook',
            'stock' => 5,
            'price' => 499,
        ]);

        $category4 = Category::create([
            'name' => 'Surface'
        ]);

        Product::create([
            'category_id' => $category4->id,
            'name' => 'Surface Book',
            'stock' => 10,
            'price' => 999,
        ]);
    }
}
