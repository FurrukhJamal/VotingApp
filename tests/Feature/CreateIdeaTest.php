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

class CreateIdeaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_idea_form_does_not_show_when_logged_out()
    {
        $user = User::factory()->create();

        Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        Idea::factory()->create([
            "user_id" => $user->id,
        ]);
        // $response = $this->actingAs(User::factory()->create())->get(route("idea.index"));
        $response = $this->get(route("idea.index"));
        $response->assertSuccessful();
        // $response->assertSee("Log In To Add An Idea");
        // $response->assertSee("Let us know what you like");
        // $response->assertSee();
    }

    /** @test */
    public function inertia_first_test()
    {
        $user = User::factory()->create();

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
            "title" => "First inertia test",
            "description" => "inertiaDes",
            "category_id" => $cat1->id,
            "status_id" => $statusOpen->id
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "First inertia test",
            "description" => "inertiaDes",
            "category_id" => $cat1->id,
            "status_id" => $statusOpen->id
        ]);
        $this->get("/")->assertInertia(
            function (Assert $page) use ($idea, $idea2) {
                $page
                    ->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea, $idea2) {
                        $page->where("current_page", 1)
                            ->has("data", 2)
                            ->has("data.0", function (Assert $page) use ($idea, $idea2) {
                                $page->where("id", $idea2->id)

                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($idea, $idea2) {
                                $page->where("id", $idea->id)
                                    ->etc();
                            })
                            ->etc();
                    });
            }
        );
    }
}
