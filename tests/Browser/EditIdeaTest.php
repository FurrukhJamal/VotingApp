<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditIdeaTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseTruncation;

    /** @test */
    public function user_can_see_the_edit_idea_button()
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

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,

        ]);


        $this->browse(function (Browser $browser) use ($user, $idea) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->screenshot("editideascreen1")
                ->press("@ideaFunctions")
                ->pause("5000")
                ->screenshot("editIdeaScreen2")
                ->whenAvailable("@editIdeaButton", function (Browser $browser) {
                    $browser->screenshot("editideascreen2");
                }, 20);
        });
    }

    /** @test */
    public function user_can_not_see_the_edit_idea_button_for_others_idea()
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

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,

        ]);

        $otherUser = User::factory()->create();


        $this->browse(function (Browser $browser) use ($otherUser, $idea) {
            $browser->loginAs($otherUser)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->press("@ideaFunctions")
                ->pause("5000")
                ->assertDontSeeIn("@ideaFunctions", "Edit Idea")
                ->assertSeeIn("@ideaFunctions", "Mark as Spam");
        });
    }


    /** @test */
    public function user_can_not_see_the_edit_idea_button_after_an_Hour()
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

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
            "created_at" => now()->subHours(2),
        ]);

        //Carbon::setTestNow(now()->addHours(2));

        $this->browse(function (Browser $browser) use ($user, $idea) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->press("@ideaFunctions")
                ->pause("5000")
                ->assertDontSeeIn("@ideaFunctions", "Edit Idea")
                ->assertSeeIn("@ideaFunctions", "Delete Idea");
        });

        //Carbon::setTestNow();
    }
}
