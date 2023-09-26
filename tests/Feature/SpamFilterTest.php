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

class SpamFilterTest extends TestCase
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
    public function ideas_voted_as_spams_are_displaying_correctly()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();

        Idea::factory(7)->create(["user_id" => $user->id]);

        $ideaSpammedMost = Idea::factory()->create([
            "title" => "Idea should be top on spam list",
            "description" => "top most spam voted idea",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 3,
        ]);

        $ideaMiddle = Idea::factory()->create([
            "title" => "Idea should be 2nd from the top on spam list",
            "description" => "2nd most spam voted idea",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 2,
        ]);

        $ideaLast = Idea::factory()->create([
            "title" => "Idea should be last on spam list",
            "description" => "last most spam voted idea",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 1,
        ]);

        //The top most idea is the last in data iteration 
        $this->actingAs($admin)
            ->get("/getspam")
            ->assertInertia(function (Assert $page) use ($ideaSpammedMost, $ideaMiddle, $ideaLast) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaSpammedMost, $ideaMiddle, $ideaLast) {
                        $page->has("data", 3)
                            ->has("data.0", function (Assert $page) use ($ideaSpammedMost) {
                                $page->where("title", $ideaSpammedMost->title)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($ideaMiddle) {
                                $page->where("title", $ideaMiddle->title)
                                    ->etc();
                            })
                            ->has("data.2", function (Assert $page) use ($ideaLast) {
                                $page->where("title", $ideaLast->title)
                                    ->etc();
                            })
                            ->etc();
                    });
            });
    }


    /** @test */
    public function ideas_voted_as_spams_cannot_be_seen_by_anyone_else()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();

        Idea::factory(7)->create(["user_id" => $user->id]);

        $ideaSpammedMost = Idea::factory()->create([
            "title" => "Idea should be top on spam list",
            "description" => "top most spam voted idea",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 3,
        ]);


        $response = $this->actingAs($user)
            ->get("/getspam");

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_mark_an_idea_as_spam()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();

        Idea::factory(7)->create(["user_id" => $user->id]);

        $idea = Idea::factory()->create([
            "title" => "Idea should be top on spam list",
            "description" => "top most spam voted idea",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route("idea.voteSpam"), ["idea" => $idea]);
        $response->assertSuccessful();
        //casting another vote
        $response = $this->actingAs($user)
            ->post(route("idea.voteSpam"), ["idea" => $idea]);
        $response->assertSuccessful();

        $this->assertDatabaseHas("ideas", ["spam_reports" => 2]);
    }

    /** @test */
    public function can_not_mark_an_idea_as_spam_if_not_logged_in()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();


        $idea = Idea::factory()->create([
            "title" => "Idea should be top on spam list",
            "description" => "top most spam voted idea",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $response = $this->post(route("idea.voteSpam"), ["idea" => $idea]);
        $response->assertRedirectToRoute("login");

        $this->assertDatabaseCount("ideas", 1);
        $this->assertDatabaseHas("ideas", ["spam_reports" => 0]);
    }

    /** @test */
    public function spam_ideas_correctly_paginating()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();

        Idea::factory(10)->create(["spam_reports" => 10, "user_id" => $user->id]);

        $idea = Idea::factory()->create([
            "title" => "Idea should be top on spam list page 2",
            "description" => "top most spam voted idea on page 2",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 7,
        ]);

        $this->actingAs($admin)
            ->get("/getspam?page=2")
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
    public function admin_can_mark_an_idea_as_not_spam()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();


        $idea = Idea::factory()->create([
            "title" => "Idea should be top on spam list page 2",
            "description" => "top most spam voted idea on page 2",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 7,
        ]);

        $response = $this->actingAs($admin)
            ->post("/markasnotspam", ["idea" => $idea]);

        $response->assertSuccessful();

        $this->assertDatabaseMissing("ideas", ["spam_reports" => 7]);
        $this->assertDatabaseHas("ideas", ["spam_reports" => 0]);
    }

    /** @test */
    public function other_users_can_not_mark_an_idea_as_not_spam()
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

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $user = User::factory()->create();


        $idea = Idea::factory()->create([
            "title" => "Idea should be top on spam list page 2",
            "description" => "top most spam voted idea on page 2",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "spam_reports" => 7,
        ]);

        $response = $this->actingAs($user)
            ->post("/markasnotspam", ["idea" => $idea]);

        $response->assertForbidden();

        $this->assertDatabaseHas("ideas", ["spam_reports" => 7]);
    }
}
