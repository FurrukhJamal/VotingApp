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
    public function most_voted_idea_coming_on_top_for_all_ideas()
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
        $user2 = User::factory()->create();
        $idea2 = Idea::factory()->create([
            "title" => "second custom title"
        ]);

        Vote::factory()->create(
            [
                "user_id" => $user2->id,
                "idea_id" => $idea2->id
            ]
        );



        $this->get("/?otherfilters=topvoted")
            ->assertInertia(function (Assert $page) use ($idea, $idea2) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea, $idea2) {
                        $page->where("current_page", 1)
                            ->has("data", 9)
                            ->has("data.0", function (Assert $page) use ($idea) {
                                $page->where("title", $idea->title)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($idea2) {
                                $page->where("title", $idea2->title)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }

    /** @test */
    public function pagination_for_most_voted_idea_working_for_all_ideas()
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
            "title" => "Idea with 9 votes",
            "description" => "This should be on top of page 2",
            "category_id" => $cat1->id,
            "status_id" => $statusOpen->id
        ]);

        $ideas = Idea::factory(10)->create();
        foreach ($users as $user) {
            foreach ($ideas as $idea) {
                Vote::factory()->create([
                    "user_id" => $user->id,
                    "idea_id" => $idea->id
                ]);
            }
        }

        $ideaZeroVote = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Idea with 0 vote would be second on page 2",
            "description" => "This should be on second number of page 2",
            "category_id" => $cat1->id,
            "status_id" => $statusOpen->id
        ]);


        $this->get("/?otherfilters=topvoted&page=2")
            ->asssertInertia(function (Assert $page) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) {
                        $page->has("data", 2)
                            ->etc();
                    })
                    ->etc();
            });
    }
}
