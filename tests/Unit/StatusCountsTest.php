<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class StatusCountsTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }
    use RefreshDatabase;
    /** @test */
    public function status_counts_showing_correctly()
    {
        $user = User::factory()->create();
        $category1 = Category::factory()->create(["name" => "Category 1"]);
        $category2 = Category::factory()->create(["name" => "Category 2"]);
        $category3 = Category::factory()->create(["name" => "Category 3"]);
        $category4 = Category::factory()->create(["name" => "Category 4"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering =  Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title One",
            "description" => "description One",
            "status_id" => $statusOpen->id,
            "category_id" => $category1->id
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Two",
            "description" => "description One",
            "status_id" => $statusConsidering->id,
            "category_id" => $category2->id
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Three",
            "description" => "description One",
            "status_id" => $statusInProgress->id,
            "category_id" => $category3->id
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Four",
            "description" => "description One",
            "status_id" => $statusImplemented->id,
            "category_id" => $category4->id
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Five",
            "description" => "description One",
            "status_id" => $statusClosed->id,
            "category_id" => $category1->id
        ]);

        $idea6 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Five",
            "description" => "description One",
            "status_id" => $statusClosed->id,
            "category_id" => $category1->id
        ]);

        $this->get("/")
            ->assertInertia(function (Assert $page) {
                $page->component("HomePage")
                    ->has("statusCounts", 6)
                    ->where("statusCounts.all_counts", 6)
                    ->where("statusCounts.statusOpen", 1)
                    ->where("statusCounts.statusClosed", 2)
                    ->where("statusCounts.statusInProgress", 1)
                    ->where("statusCounts.statusImplemented", 1)
                    ->where("statusCounts.statusConsidering", 1)
                    ->etc();
            });
    }
}
