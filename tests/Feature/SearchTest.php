<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;


class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    use RefreshDatabase;
    /** @test */
    public function search_returning_correct_results()
    {
        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaFirst = Idea::factory()->create([
            "title" => "Idea created by awesome user",
            "description" => "This idea should come back as a result cuz of awesome",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        Idea::factory(6)->create(["user_id" => $user->id]);

        $this->get("/search?search_query=awesome")
            ->assertInertia(function (Assert $page) use ($ideaFirst) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaFirst) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaFirst) {
                                $page->where("title", $ideaFirst->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }
}
