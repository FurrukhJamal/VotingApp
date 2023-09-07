<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::factory()->create([
            "name" => "Furrukh Jamal",
            "email" => "furrukhjamal@yahoo.com"
        ]);

        User::factory(9)->create();


        Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);


        Idea::factory(120)->create();

        for ($i = 1; $i <= 10; $i++) {
            for ($j = 1; $j <= 50; $j++) {
                if ($j % 2 == 0) {
                    Vote::factory()->create([
                        "user_id" => $i,
                        "idea_id" => $j
                    ]);
                }
            }
        }
    }
}
