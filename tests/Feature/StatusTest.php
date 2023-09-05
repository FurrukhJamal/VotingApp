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

class StatusTest extends TestCase
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
    public function ideas_of_all_statuses_are_shown_when_all_ideas_filter_is_clicked()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/all")
            ->assertInertia(function (Assert $page) use ($idea1, $idea2, $idea3, $idea4, $idea5) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea1, $idea2, $idea3, $idea4, $idea5) {
                        $page->where("current_page", 1)
                            ->has("data.0", function (Assert $page) use ($idea5) {
                                $page->where("id", $idea5->id)
                                    ->where("title", $idea5->title)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($idea4) {
                                $page->where("id", $idea4->id)
                                    ->where("title", $idea4->title)
                                    ->etc();
                            })
                            ->has("data.2", function (Assert $page) use ($idea3) {
                                $page->where("id", $idea3->id)
                                    ->where("title", $idea3->title)
                                    ->etc();
                            })
                            ->has("data.3", function (Assert $page) use ($idea2) {
                                $page->where("id", $idea2->id)
                                    ->where("title", $idea2->title)
                                    ->etc();
                            })
                            ->has("data.4", function (Assert $page) use ($idea1) {
                                $page->where("id", $idea1->id)
                                    ->where("title", $idea1->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }

    /** @test */
    public function open_status_filter_showing_the_correct_ideas()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/open")
            ->assertInertia(function (Assert $page) use ($idea1, $statusOpen) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea1, $statusOpen) {
                        $page->where("current_page", 1)
                            ->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($idea1, $statusOpen) {
                                $page->where("id", $idea1->id)
                                    ->where("title", $idea1->title)
                                    ->where("status_id", $statusOpen->id)
                                    ->etc();
                            })

                            ->etc();
                    });
            });
    }

    /** @test */
    public function cosidering_status_filter_show_correct_ideas()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/considering")
            ->assertInertia(function (Assert $page) use ($idea2, $statusConsidering) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea2, $statusConsidering) {
                        $page->where("current_page", 1)
                            ->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($idea2, $statusConsidering) {
                                $page->where("id", $idea2->id)
                                    ->where("title", $idea2->title)
                                    ->where("status_id", $statusConsidering->id)
                                    ->etc();
                            })

                            ->etc();
                    });
            });
    }

    /** @test */
    public function inprogress_filter_for_status_showing_the_correct_ideas()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/inprogress")
            ->assertInertia(function (Assert $page) use ($idea3, $statusInProgress) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea3, $statusInProgress) {
                        $page->where("current_page", 1)
                            ->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($idea3, $statusInProgress) {
                                $page->where("id", $idea3->id)
                                    ->where("title", $idea3->title)
                                    ->where("status_id", $statusInProgress->id)
                                    ->etc();
                            })

                            ->etc();
                    });
            });
    }

    /** @test */
    public function implemented_filter_for_status_showing_the_correct_ideas()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/implemented")
            ->assertInertia(function (Assert $page) use ($idea4, $statusImplemented) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea4, $statusImplemented) {
                        $page->where("current_page", 1)
                            ->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($idea4, $statusImplemented) {
                                $page->where("id", $idea4->id)
                                    ->where("title", $idea4->title)
                                    ->where("status_id", $statusImplemented->id)
                                    ->etc();
                            })

                            ->etc();
                    });
            });
    }

    /** @test */
    public function closed_filter_for_status_showing_the_correct_ideas()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title",
            "status_id" => $statusOpen->id,
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusConsidering->id,
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Third title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusInProgress->id,
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Forth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusImplemented->id,
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Fivth title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/closed")
            ->assertInertia(function (Assert $page) use ($idea5, $statusClosed) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea5, $statusClosed) {
                        $page->where("current_page", 1)
                            ->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($idea5, $statusClosed) {
                                $page->where("id", $idea5->id)
                                    ->where("title", $idea5->title)
                                    ->where("status_id", $statusClosed->id)
                                    ->etc();
                            })

                            ->etc();
                    });
            });
    }
}
