<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;


class ExampleTest extends DuskTestCase
{

    use DatabaseTruncation;
    /**
     * A basic browser test example.
     */
    /** @test */
    public function guest_does_not_see_add_idea_form(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Add an idea')
                ->assertSee("Log In To Add An Idea");
        });
    }

    public function test_if_user_can_log_in()
    {
        $user = User::factory()->create([
            "email" => "fj@ex.com"
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type("email", $user->email)
                ->type("password", "password")
                ->press("Log in")
                ->waitForLocation('/dashboard')
                ->assertPathIs("/dashboard");
        });
    }

    /** @test */
    public function guest_does_not_see_a_add_idea_form()
    {
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit("/")
                ->assertSee("Let us know what you like");
        });
    }

    /** @test */
    public function add_idea_validation_is_working()
    {
        $user = User::factory()->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute("idea.index")
                ->type("title", "")
                ->type("@description", "")
                ->press("submit")
                // ->assertHasErrors(["title", "category", "description"])
                ->waitForText("The title field is required")
                ->waitForText("The category field is required")
                ->waitForText("The description field is required");
        });
    }

    /** @test */
    public function add_idea_working()
    {
        Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit("/")
                ->type("title", "a dusk idea")
                ->type("@description", "a dusk description")
                ->click("@category-select-button")
                ->waitFor("@category-select-item")
                ->click("@category-select-item")
                ->press("submit")
                ->waitForLocation("/")
                ->assertPathIs("/")
                ->waitForText("a dusk idea")
                ->waitForText("a dusk description");
        });
    }

    /** @test */
    public function check_button_for_voted_idea_is_disablled_by_user()
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

        Vote::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $idea) {
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($idea->title)
                ->waitForText($idea->description)
                ->assertButtonDisabled("@VoteButton");
        });
    }

    /** @test */
    function vote_button_is_disabled_when_user_visits_idea_page_and_has_already_voted()
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

        Vote::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $idea) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitForText($idea->title)
                ->waitForText($idea->description)
                ->assertButtonDisabled("@IdeaPageVoteButton");
        });
    }
}
