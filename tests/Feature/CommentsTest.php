<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class CommentsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_add_a_comment_to_an_idea()
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
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //adding a comment
        $comment = "this is a comment to an idea";
        $this->assertDatabaseCount("comments", 0);

        $response = $this->actingAs($user)
            ->post(route("comment.store"), ["comment" => $comment, "idea" => $idea]);


        $response->assertSuccessful();

        $this->assertDatabaseCount("comments", 1)
            ->assertDatabaseHas("comments", ["body" => $comment]);
    }

    /** @test */
    public function guest_can_not_add_a_comment_to_an_idea()
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
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //adding a comment
        $comment = "this is a comment to an idea";
        $this->assertDatabaseCount("comments", 0);

        $response = $this->post(route("comment.store"), ["comment" => $comment, "idea" => $idea]);


        $response->assertRedirectToRoute("login");
    }

    /** @test */
    public function comments_for_an_idea_are_beign_send_correctly_from_the_endpoint()
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
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $comment1 = Comment::factory()->create(
            [
                "body" => "This comment should be on the top",
                "idea_id" => $idea->id
            ]
        );

        $comment2 = Comment::factory()->create(
            [
                "body" => "This comment should be in middle",
                "idea_id" => $idea->id
            ]
        );

        $comment3 = Comment::factory()->create(
            [
                "body" => "This comment should be at the bottom",
                "idea_id" => $idea->id
            ]
        );

        $this->get(route("idea.show", $idea))
            ->assertInertia(function (Assert $page) use ($idea, $comment1, $comment2, $comment3) {
                $page->component("IdeaPage")
                    ->has("idea", function (Assert $idea) use ($comment1, $comment2, $comment3) {
                        $idea->has("comments", 3)
                            ->has("comments.0", function (Assert $comment) use ($comment1) {
                                $comment->where("body", $comment1->body)
                                    ->etc();
                            })
                            ->has("comments.1", function (Assert $comment) use ($comment2) {
                                $comment->where("body", $comment2->body)
                                    ->etc();
                            })
                            ->has("comments.2", function (Assert $comment) use ($comment3) {
                                $comment->where("body", $comment3->body)
                                    ->etc();
                            })
                            ->where("comments_count", 3)
                            ->etc();
                    })
                    ->etc();
            });
    }

    /** @test */
    public function validation_for_adding_comments_working()
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
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //adding a comment
        $comment = "";

        $response = $this->actingAs($user)
            ->post(route("comment.store"), ["comment" => $comment, "idea" => $idea]);


        $response->assertInvalid(["comment"]);
    }
}
