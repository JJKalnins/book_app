<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Sale;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Author::factory(20)->create();
        Book::factory(40)->create();

        Author::all()->each(function($author) {
                $randomFields= Book::all()->random( rand(0, 2) )->pluck('id');
                $author->book()->attach($randomFields);
        });

        Sale::factory(40)->create();
    }
}
