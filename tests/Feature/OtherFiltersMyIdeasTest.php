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

class OtherFiltersMyIdeasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function My_ideas_displaying_correctly()
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

        $otherUser = User::factory()->create();

        $ideaFirst = Idea::factory()->create([
            "title" => "Idea created by other user",
            "description" => "This idea should not be displayed for in the response",
            "user_id" => $otherUser->id
        ]);

        $user = User::factory()->create();

        $UsersIdea = Idea::factory()->create([
            "title" => "This idea should be displayed",
            "user_id" => $user->id,
        ]);

        $this->actingAs($user)
            ->get("?otherfilters=myideas")
            ->assertInertia(function (Assert $page) use ($UsersIdea) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($UsersIdea) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($UsersIdea) {
                                $page->where("title", $UsersIdea->title)
                                    ->etc();
                            });
                    })
                    ->etc();
            });
    }
}
