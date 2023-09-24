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

class SpamLinkTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    // public function testExample(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->assertSee('Laravel');
    //     });
    // }

    use DatabaseTruncation;

    /** @test */
    public function random_user_does_not_see_spam_filter_link()
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
        $idea = Idea::factory()->create(["user_id" => $user->id]);

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->loginAs($user)->visit(route("idea.index"))
                ->waitFor("@otherFiltersDiv", 20)
                ->assertDontSeeIn("@otherFiltersDiv", "Spam");
        });
    }

    /** @test */
    public function admin_see_spam_filter_link_option()
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
        $idea = Idea::factory()->create(["user_id" => $user->id]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)->visit(route("idea.index"))
                ->waitFor("@otherFiltersDiv", 20)
                ->press("@otherFiltersDiv")
                ->waitfor("@spamFilterLink", 10)
                ->assertSeeIn("@spamFilterLink", "Spam");
        });
    }
}
