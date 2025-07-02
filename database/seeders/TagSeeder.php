<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create(['name' => 'nasa']);
        Tag::create(['name' => 'movies']);
        Tag::create(['name' => 'elon musk']);
        Tag::create(['name' => 'facebook']);
        Tag::create(['name' => 'tesla']);
        Tag::create(['name' => 'gold']);
        Tag::create(['name' => 'health']);
        Tag::create(['name' => 'food']);
        Tag::create(['name' => 'weather']);
        Tag::create(['name' => 'stock market']);
    }
}
