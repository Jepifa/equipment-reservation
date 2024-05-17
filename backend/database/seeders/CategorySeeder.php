<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class CategorySeeder
 *
 * This class represents a seeder for populating the categories table with initial data.
 * It seeds the categories table with two sample categories: Category 1 and Category 2.
 *
 * @author Jean-Pierre Faucon
 * @version 1.0
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Category 1',
        ]);

        Category::create([
            'name' => 'Category 2',
        ]);
    }
}
