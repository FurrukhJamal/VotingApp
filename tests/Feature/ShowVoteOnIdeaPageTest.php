<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;;

use Tests\TestCase;

class ShowVoteOnIdeaPageTest extends TestCase
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
    public function vote_showing_in_single_votes_screen()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();
        $user2 = User::factory()->create();


        $ideaOne = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        Vote::factory()->create([
            "idea_id" => $ideaOne->id,
            "user_id" => $user->id
        ]);

        Vote::factory()->create([
            "idea_id" => $ideaOne->id,
            "user_id" => $user2->id
        ]);

        $this->get(route("idea.show", $ideaOne))
            ->assertInertia(function (Assert $page) {
                $page->component("IdeaPage")
                    ->has("idea")
                    ->has("idea.votes_count")
                    ->where("idea.votes_count", 2)
                    ->etc();
            });
    }

    /** @test */
    public function votes_showing_for_all_the_ideas_in_index_page()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();
        $user2 = User::factory()->create();


        $ideaOne = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        $ideaTwo = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        Vote::factory()->create([
            "idea_id" => $ideaOne->id,
            "user_id" => $user->id
        ]);

        Vote::factory()->create([
            "idea_id" => $ideaOne->id,
            "user_id" => $user2->id
        ]);

        $this->get(route("idea.index", $ideaOne))
            ->assertInertia(function (Assert $page) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) {
                        $page->has("data")
                            ->has("data.0", function (Assert $page) {
                                $page->where("votes_count", 0)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) {
                                $page->where("votes_count", 2)
                                    ->etc();
                            })
                            ->etc();
                    })

                    ->etc();
            });
    }
}
