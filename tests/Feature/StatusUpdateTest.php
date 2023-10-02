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

class StatusUpdateTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function status_is_updating_via_the_post_request_at_endpoint()
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
            "status_id" => $statusOpen->id,
        ]);

        //adding comments to the idea
        Comment::factory(3)->create(["idea_id" => $idea->id]);

        $this->assertDatabaseMissing("comments", [
            "body" => "status changed from {$statusOpen->name} to {$statusConsidering->id}"
        ]);

        $this->assertDatabaseMissing("ideas", ["status_id" => $statusConsidering->id]);

        $response = $this->actingAs($admin)
            ->patch("/setstatus", [
                "status" => $statusConsidering->name,
                "ideaId" => $idea->id,
                "statusUpdateComment" => "",
                "notifyAllVoters" => false,

            ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas("ideas", ["status_id" => $statusConsidering->id]);
        $this->assertDatabaseCount("comments", 4);
        $this->assertDatabaseHas("comments", [
            "body" => "status changed from {$statusOpen->name} to {$statusConsidering->name}",
            "user_id" => $admin->id
        ]);
    }


    /** @test */
    public function only_admin_can_change_the_status()
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
            "status_id" => $statusOpen->id,
        ]);


        $response = $this->actingAs($user)
            ->patch("/setstatus", [
                "status" => $statusConsidering->name,
                "ideaId" => $idea->id,
                "statusUpdateComment" => "",
                "notifyAllVoters" => false,

            ]);
        $response->assertForbidden();
    }

    /** @test */
    public function status_is_updating_via_the_post_request_at_endpoint_with_a_custom_comment()
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
            "status_id" => $statusOpen->id,
        ]);

        //adding comments to the idea
        Comment::factory(3)->create(["idea_id" => $idea->id]);

        $comment = "This comment should be added when the status is changed by admin";

        $this->assertDatabaseMissing("comments", [
            "body" => $comment,
        ]);

        $this->assertDatabaseMissing("ideas", ["status_id" => $statusConsidering->id]);

        $response = $this->actingAs($admin)
            ->patch("/setstatus", [
                "status" => $statusConsidering->name,
                "ideaId" => $idea->id,
                "statusUpdateComment" => $comment,
                "notifyAllVoters" => false,

            ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas("ideas", ["status_id" => $statusConsidering->id]);
        $this->assertDatabaseCount("comments", 4);
        $this->assertDatabaseHas("comments", [
            "body" => $comment,
            "user_id" => $admin->id
        ]);
    }
}
