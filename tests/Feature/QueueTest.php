<?php

namespace Tests\Feature;

use App\Jobs\NotifyAllVoters;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    /** @test */
    public function queue_is_working()
    {
        $user = User::factory()->create(["email" => "furrukhjamal@yahoo.com", "name" => "Furrukh"]);


        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $ideaClosed = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Most voted In Closed idea",
            "description" => "This will be the most voted Closed idea",
            "category_id" => $cat1->id,
            "status_id" => $statusClosed->id
        ]);

        Queue::fake();

        Queue::assertNothingPushed();


        $response = $this->actingAs($user)->patch("/setstatus", [
            "status" => $statusClosed->name,
            "ideaId" => 1,
            "notifyAllVoters" => true
        ]);

        // $response->assertRedirectToRoute("idea.show", $ideaClosed);
        $response->assertSuccessful();
        Queue::assertPushed(NotifyAllVoters::class);
    }
}
