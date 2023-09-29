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

class DeleteCommentTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function guest_can_not_delete_a_comment()
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

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $comment = Comment::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id,
        ]);

        $response = $this->post(route("comment.destroy", $comment));
        $response->assertRedirectToRoute("login");
    }

    /** @test */
    public function user_can_not_delete_others_comment()
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

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $comment = Comment::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id,
        ]);

        $response = $this->actingAs($otherUser)->post(route("comment.destroy", $comment));
        $response->assertForbidden();
    }

    /** @test */
    public function comment_is_getting_deleted()
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

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //some random comments for this idea
        Comment::factory(3)->create(["idea_id" => $idea->id]);

        $comment = Comment::factory()->create([
            "idea_id" => $idea->id,
            "body" => "This is the commment that will be deleted",
            "user_id" => $user->id
        ]);

        $this->assertDatabaseHas("comments", ["body" => $comment->body]);
        $this->actingAs($user)
            ->post(route("comment.destroy", $comment->id))
            ->assertSuccessful();

        $this->assertDatabaseMissing("comments", ["body" => $comment->body]);
    }
}
