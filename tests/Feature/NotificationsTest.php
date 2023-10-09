<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function notifications_are_been_correctly_recieved()
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

        $comment1 = Comment::factory()->create(["body" => "This is a first comment", "idea_id" => $idea->id]);
        $comment2 = Comment::factory()->create(["body" => "This is a Second comment", "idea_id" => $idea->id]);

        //adding the comment via post
        $this->actingAs($user)
            ->post(route("comment.store"), ["comment" => $comment1->body, "idea" => $idea]);

        $this->actingAs($user)
            ->post(route("comment.store"), ["comment" => $comment2->body, "idea" => $idea]);

        $response = $this->actingAs($user)
            ->postJson(route("notifications.comments"), ["user" => $user]);

        $response->assertJson(function (AssertableJson $json) use ($comment1, $comment2) {
            $json->where("user_hasNotifications", 1)
                ->has("notifications", 2)
                ->has("notifications.0", 2)
                ->has("notifications.0", function (AssertableJson $notifications) use ($comment1) {
                    $notifications->has("data", function (AssertableJson $data) use ($comment1) {
                        $data->where("comment_body", $comment1->body)
                            ->etc();
                    })
                        ->etc();
                })
                ->has("notifications.1", function (AssertableJson $notifications) use ($comment2) {
                    $notifications->has("data", function (AssertableJson $data) use ($comment2) {
                        $data->where("comment_body", $comment2->body)
                            ->etc();
                    })
                        ->etc();
                })
                ->etc();
        });
    }

    /** @test */
    public function endpoit_for_marking_notifications_as_read_is_working()
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

        $comment1 = Comment::factory()->create(["body" => "This is a first comment", "idea_id" => $idea->id]);
        $comment2 = Comment::factory()->create(["body" => "This is a Second comment", "idea_id" => $idea->id]);

        //adding the comment via post
        $this->actingAs($user)
            ->post(route("comment.store"), ["comment" => $comment1->body, "idea" => $idea]);

        $this->actingAs($user)
            ->post(route("comment.store"), ["comment" => $comment2->body, "idea" => $idea]);

        $this->actingAs($user)
            ->postJson(route("notifications.markread"), ["user" => $user]);

        $response = $this->actingAs($user)
            ->postJson(route("notifications.comments"), ["user" => $user]);

        $response->assertJson(function (AssertableJson $json) use ($comment1, $comment2) {
            $json->where("user_hasNotifications", 0)
                ->has("notifications", 0)
                ->etc();
        });
    }
}
