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

class AdminSetStatusButtonTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseTruncation;

    //  public function testExample(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->assertSee('Laravel');
    //     });
    // }
    /** @test */
    public function guest_does_not_see_admins_set_status_button()
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
            "title" => "Idea created by a user",
            "description" => "This idea should be displayed on the single idea page",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $this->browse(function (Browser $browser) use ($idea) {
            $browser->visit(route("idea.show", $idea))
                ->waitFor("@goBackLink", 20)
                ->assertMissing("@adminSetStatusButton");
        });
    }

    /** @test */
    public function random_does_not_see_admins_set_status_button()
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
            "title" => "Idea created by a user",
            "description" => "This idea should be displayed on the single idea page",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@goBackLink", 20)
                ->assertMissing("@adminSetStatusButton");
        });
    }

    /** @test */
    public function admin_does_see_admins_set_status_button()
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

        $user = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $idea = Idea::factory()->create([
            "title" => "Idea created by a user",
            "description" => "This idea should be displayed on the single idea page",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@goBackLink", 20)
                ->screenshot("image_to-see-admin-has-setStatus-button")
                ->assertSeeIn("@adminSetStatusButton", "Set Status");
        });
    }
}
