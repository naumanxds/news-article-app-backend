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
        Tag::create(['name' => 'space']);
        Tag::create(['name' => 'health']);
        Tag::create(['name' => 'weather']);
        Tag::create(['name' => 'sports']);
        Tag::create(['name' => 'politics']);
        Tag::create(['name' => 'taxes']);
    }
}
