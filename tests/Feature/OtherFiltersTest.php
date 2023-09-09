<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;


class OtherFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function most_voted_idea_coming_on_top()
    {

        $users = User::factory(10)->create();
        $user = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Most voted idea",
            "description" => "This will be the most voted idea",
            "category_id" => $cat1->id,
            "status_id" => $statusOpen->id
        ]);

        //create 7 more ideas
        Idea::factory(7)->create();

        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $idea->id
            ]);
        }



        $this->get("/?otherfilters=topvoted")
            ->assertInertia(function (Assert $page) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) {
                        $page->where("current_page", 1)
                            ->has("data", 8)
                            ->etc();
                    })
                    ->etc();
            });
    }
}
