<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CommentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseTruncation;
    /** @test */
    public function user_can_submit_their_comments()
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

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->press("@commentReplyButton")
                ->waitFor("@commentSection", 15)
                ->type("@commentSection", "This is a new comment")
                ->press("@submitCommentButton")
                ->waitFor("@notificationTab", 20)
                ->assertSeeIn("@notificationTab", "Comment Added Successfully")
                ->assertMissing("@commentSection");
        });
    }

    /** @test */
    public function guest_do_not_see_the_reply_button()
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

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->assertMissing("@commentSection");
        });
    }
}
