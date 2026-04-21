<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User::factory(10)->create();

        // Categories - only create if none exist
if (Category::count() === 0) {
    Category::factory(10)->create();
}

// Products - only create if none exist
if (Product::count() === 0) {
    Product::factory(50)->create();
}

// Admin user - use updateOrCreate to avoid duplicates
User::updateOrCreate(
    ['email' => 'admin@admin.com'],
    [
        'name' => 'Admin User',
        'email_verified_at' => now(),
        'password' => bcrypt('Admin123!'),
        'is_admin' => true,
        'remember_token' => \Illuminate\Support\Str::random(10),
    ]
);

// Prices - upsert based on lookup_key
DB::table('prices')->upsert(
    [
        ['plan' => 'Basic',    'price' => '40',  'currency' => 'euro', 'lookup_key' => 'basic-monthly'],
        ['plan' => 'Medium',   'price' => '80',  'currency' => 'euro', 'lookup_key' => 'medium-monthly'],
        ['plan' => 'Advanced', 'price' => '120', 'currency' => 'euro', 'lookup_key' => 'advanced-monthly'],
    ],
    ['lookup_key'],         // unique key to match on
    ['plan', 'price', 'currency']  // columns to update if record exists
);
    }
}
