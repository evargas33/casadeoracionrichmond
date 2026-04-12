<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Worship Service', 'type' => 'event', 'color' => '#1a2e4a'],
            ['name' => 'Conference',      'type' => 'event', 'color' => '#c9a84c'],
            ['name' => 'Retreat',         'type' => 'event', 'color' => '#2e7d32'],
            ['name' => 'Youth',           'type' => 'event', 'color' => '#7b1fa2'],
            ['name' => 'Children',        'type' => 'event', 'color' => '#e65100'],
            ['name' => 'Bible School',    'type' => 'event', 'color' => '#1565c0'],
            ['name' => 'Ministries',      'type' => 'page',  'color' => '#1a2e4a'],
            ['name' => 'Resources',       'type' => 'page',  'color' => '#37474f'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, ['slug' => Str::slug($cat['name'])])
            );
        }
    }
}
