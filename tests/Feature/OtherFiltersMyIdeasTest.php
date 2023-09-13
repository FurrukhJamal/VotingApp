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
            ->get("?user=true")
            ->assertInertia(function (Assert $page) use ($UsersIdea) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($UsersIdea) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($UsersIdea) {
                                $page->where("title", $UsersIdea->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }

    /** @test */
    public function my_ideas_paginating_correctly()
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

        Idea::factory(10)->create(["user_id" => $user->id]);

        $otherUser = User::factory()->create();

        $otherIdea = Idea::factory()->create([
            "title" => "This title will not show",
            "user_id" => $otherUser->id
        ]);

        $pageTwoIdea = Idea::factory()->create([
            "title" => "This idea should be the first on page 2",
            "user_id" => $user->id
        ]);


        $this->actingAs($user)
            ->get("?user=true&page=2")
            ->assertInertia(function (Assert $page) use ($pageTwoIdea) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($pageTwoIdea) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($pageTwoIdea) {
                                $page->where("title", $pageTwoIdea->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }

    /** @test */
    public function ideas_filter_on_all_status_based_on_categories_for_user_displaying()
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
        $otherUser = User::factory()->create();
        Idea::factory(5)->create([
            "user_id" => $otherUser->id,
            "category_id" => $cat1->id,
        ]);

        //now create ideas for user for the same category
        $idea = Idea::factory()->create([
            "title" => "These should be in the returned data",
            "user_id" => $user->id,
            "category_id" => $cat1->id,
        ]);

        Idea::factory(5)->create([
            "user_id" => $user->id,
            "category_id" => $cat1->id,
        ]);

        $this->actingAs($user)
            ->get("?user=true&category=1")
            ->assertInertia(function (Assert $page) use ($idea) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea) {
                        $page->has("data", 6)
                            ->has("data.0", function (Assert $page) use ($idea) {
                                $page->where("title", $idea->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }



    /** @test */
    public function ideas_filter_on_all_status_based_on_categories_for_user_pagination_working()
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
        $otherUser = User::factory()->create();
        Idea::factory(5)->create([
            "user_id" => $otherUser->id,
            "category_id" => $cat1->id,
        ]);

        //now create ideas for user for the same category
        Idea::factory(10)->create([
            "user_id" => $user->id,
            "category_id" => $cat1->id,
        ]);


        $idea = Idea::factory()->create([
            "title" => "This should be on the top of page 2",
            "user_id" => $user->id,
            "category_id" => $cat1->id,
        ]);

        $this->actingAs($user)
            ->get("?user=true&category=1&page=2")
            ->assertInertia(function (Assert $page) use ($idea) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($idea) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($idea) {
                                $page->where("title", $idea->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }

    /** @test */
    public function Guest_when_goes_to_user_filtered_endpoint_they_see_a_login_page()
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
        $otherUser = User::factory()->create();
        Idea::factory(5)->create([
            "user_id" => $otherUser->id,
            "category_id" => $cat1->id,
        ]);

        $response = $this->get("?user=true&category=1&page=2");
        $response->assertLocation(route("login"));
    }

    /** @test */
    public function users_top_voted_category_filtered_endpoint_working()
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
        $otherUser = User::factory()->create();

        $users11 = User::factory(11)->create();
        $ideaMostVoted = Idea::factory()->create([
            "title" => "this will be the most voted idea",
            "description" => "this wont show in the return data",
            "category_id" => $cat2->id,
            "user_id" => $otherUser->id
        ]);

        //creating its votes
        foreach ($users11 as $User) {
            Vote::factory()->create([
                "user_id" => $User->id,
                "idea_id" => $ideaMostVoted->id
            ]);
        }

        $targetIdea = Idea::factory()->create([
            "title" => "this will be the most voted idea targeted idea",
            "description" => "this should be on top of returned data show in the return data",
            "category_id" => $cat1->id,
            "user_id" => $user->id,
        ]);

        //creating some more ideas
        Idea::factory(5)->create(["category_id" => $cat1->id, "user_id" => $user->id]);

        $this->actingAs($user)
            ->get("?user=true&category=1&otherfilters=topvoted")
            ->assertInertia(function (Assert $page) use ($targetIdea) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($targetIdea) {
                        $page->has("data", 6)
                            ->has("data.0", function (Assert $page) use ($targetIdea) {
                                $page->where("title", $targetIdea->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }
}
