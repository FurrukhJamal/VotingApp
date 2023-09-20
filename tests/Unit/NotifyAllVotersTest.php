<?php

namespace Tests\Unit;

use App\Jobs\NotifyAllVoters;
use App\Mail\IdeaStatusUpdatedMailable;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyAllVotersTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }
    use RefreshDatabase;

    /** @test */
    public function voters_getting_notified()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();


        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $idea = Idea::factory()->create([
            "user_id" => $user1->id,
            "title" => "Most voted In Closed idea",
            "description" => "This will be the most voted Closed idea",
            "category_id" => $cat1->id,
            "status_id" => $statusClosed->id
        ]);

        Vote::factory()->create([
            "user_id" => $user1->id,
            "idea_id" => $idea->id
        ]);

        Vote::factory()->create([
            "user_id" => $user2->id,
            "idea_id" => $idea->id
        ]);

        Mail::fake();

        NotifyAllVoters::dispatch($idea);

        Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) use ($user1) {
            return $mail->hasTo($user1->email) && $mail->hasSubject("Idea Status Updated");
        });

        Mail::assertQueued(IdeaStatusUpdatedMailable::class, function ($mail) use ($user2) {
            return $mail->hasTo($user2->email) && $mail->hasSubject("Idea Status Updated");
        });
    }
}
