<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Prihodi
            ['name' => 'Plata',          'type' => 'income',  'color' => '#10B981', 'icon' => null],
            ['name' => 'Freelance',      'type' => 'income',  'color' => '#3B82F6', 'icon' => null],
            ['name' => 'Pokloni',        'type' => 'income',  'color' => '#8B5CF6', 'icon' => null],
            ['name' => 'Ostali prihodi', 'type' => 'income',  'color' => '#6B7280', 'icon' => null],

            // Rashodi
            ['name' => 'Hrana',          'type' => 'expense', 'color' => '#F59E0B', 'icon' => null],
            ['name' => 'Stan/Kirija',    'type' => 'expense', 'color' => '#EF4444', 'icon' => null],
            ['name' => 'Transport',      'type' => 'expense', 'color' => '#F97316', 'icon' => null],
            ['name' => 'Zdravlje',       'type' => 'expense', 'color' => '#EC4899', 'icon' => null],
            ['name' => 'Zabava',         'type' => 'expense', 'color' => '#A855F7', 'icon' => null],
            ['name' => 'Računi',         'type' => 'expense', 'color' => '#14B8A6', 'icon' => null],
            ['name' => 'Odjeća',         'type' => 'expense', 'color' => '#06B6D4', 'icon' => null],
            ['name' => 'Obrazovanje',    'type' => 'expense', 'color' => '#6366F1', 'icon' => null],
            ['name' => 'Ostali rashodi', 'type' => 'expense', 'color' => '#6B7280', 'icon' => null],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insertOrIgnore([
                'user_id'    => null,
                'name'       => $category['name'],
                'icon'       => $category['icon'],
                'color'      => $category['color'],
                'type'       => $category['type'],
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
