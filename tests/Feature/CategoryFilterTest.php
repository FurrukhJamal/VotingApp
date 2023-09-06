<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CategoryFilterTest extends TestCase
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
    public function category_filter_for_status_Open_working()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaOpen = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $category1->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $category2->id,
            "status_id" => $statusOpen->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $category3->id,
            "status_id" => $statusOpen->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusOpen->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusOpen->id,
        ]);

        $this->get("/statusfilter/open?category=1")
            ->assertInertia(function (Assert $page) use ($ideaOpen) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaOpen) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaOpen) {
                                $page->where("title", $ideaOpen->title)
                                    ->where("description", $ideaOpen->description)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }

    /** @test */
    public function category_filter_for_status_considering_working()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaConsidering = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $category1->id,
            "description" => "description of title",
            "status_id" => $statusConsidering->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $category2->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $category3->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusConsidering->id,
        ]);

        $this->get("/statusfilter/considering?category=1")
            ->assertInertia(function (Assert $page) use ($ideaConsidering) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaConsidering) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaConsidering) {
                                $page->where("title", $ideaConsidering->title)
                                    ->where("description", $ideaConsidering->description)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }


    /** @test */
    public function category_filter_for_status_inprogress_working()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaInProgress = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $category1->id,
            "description" => "description of title",
            "status_id" => $statusInProgress->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $category2->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $category3->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusInProgress->id,
        ]);

        $this->get("/statusfilter/inprogress?category=1")
            ->assertInertia(function (Assert $page) use ($ideaInProgress) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaInProgress) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaInProgress) {
                                $page->where("title", $ideaInProgress->title)
                                    ->where("description", $ideaInProgress->description)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }

    /** @test */
    public function category_filter_for_status_implemented_working()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaImplemented = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $category1->id,
            "description" => "description of title",
            "status_id" => $statusImplemented->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $category2->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $category3->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusImplemented->id,
        ]);

        $this->get("/statusfilter/implemented?category=1")
            ->assertInertia(function (Assert $page) use ($ideaImplemented) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaImplemented) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaImplemented) {
                                $page->where("title", $ideaImplemented->title)
                                    ->where("description", $ideaImplemented->description)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }

    /** @test */
    public function category_filter_for_status_closed_working()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaClosed = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $category1->id,
            "description" => "description of title",
            "status_id" => $statusClosed->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $category2->id,
            "status_id" => $statusClosed->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $category3->id,
            "status_id" => $statusClosed->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusClosed->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $category4->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/closed?category=1")
            ->assertInertia(function (Assert $page) use ($ideaClosed) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaClosed) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaClosed) {
                                $page->where("title", $ideaClosed->title)
                                    ->where("description", $ideaClosed->description)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }
}
