<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'cardiology']);
        Category::create(['name' => 'neurology']);
        Category::create(['name' => 'pediatrics']);
        Category::create(['name' => 'surgery']);
        Category::create(['name' => 'radiology']);
        Category::create(['name' => 'general']);
    }
}
