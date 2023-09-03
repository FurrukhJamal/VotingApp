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

class VoteButtonTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseTruncation;
    // public function testExample(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/')
    //                 ->assertSee('Laravel');
    //     });
    // }


    /** @test */
    function guest_if_clicks_vote_he_is_taken_to_login_page()
    {
        $categoryOne = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        $this->browse(function (Browser $browser) use ($idea) {
            $browser->visit('/')
                ->assertSee($idea->title)
                ->click("@VoteButton")
                ->waitForLocation(route("login"))
                ->assertPathIs("/login");
        });
    }

    /** @test */
    public function guest_if_clicks_vote_button_on_idea_page_he_is_taken_to_login_page()
    {
        $categoryOne = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        $this->browse(function (Browser $browser) use ($idea) {
            $browser->visit(route("idea.show", $idea))
                ->assertSee($idea->title)
                ->assertSee($idea->description)
                ->click("@IdeaPageVoteButton")
                ->waitForLocation("/login")
                ->assertPathIs("/login");
        });
    }

    /** @test */
    public function user_can_vote_on_single_idea_page()
    {
        $categoryOne = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->assertSee($idea->title)
                ->assertSee($idea->description)
                ->assertSee("0")
                ->assertSee("Votes")
                ->press("@IdeaPageVoteButton")
                ->waitForText("1")
                ->waitForText("Votes");
        });
    }

    /** @test */
    function logged_in_user_can_vote_for_an_idea()
    {
        $categoryOne = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit("/")
                ->waitForText("0")
                ->assertSee("0")
                ->click("@VoteButton")
                ->assertSee("1")
                ->assertSee("Votes");
        });
    }
}
