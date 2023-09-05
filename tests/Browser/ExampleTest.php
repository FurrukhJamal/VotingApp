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
    public function go_back_link_taking_to_correct_page()
    {
        $cat = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $idea1 = Idea::factory()->create([
            "title" => "First Title",
            "user_id" => $user->id,
            "category_id" => $cat->id,
            "status_id" => $statusConsidering->id
        ]);

        $idea2 = Idea::factory()->create([
            "status_id" => $statusOpen->id,
            "user_id" => $user->id,
            "category_id" => $cat->id,
        ]);

        $this->browse(function (Browser $browser) use ($idea1) {
            $browser->visit(route("idea.index"))
                ->waitFor("@statusFilterConsidering")
                ->click("@statusFilterConsidering")
                ->waitForText($idea1->title, 30)
                ->screenshot("consideringLink")
                ->clickLink($idea1->title)
                ->waitFor("@goBackLink", 30)
                ->clickLink("Go Back")
                ->waitForText($idea1->title)
                ->screenshot("screenback")
                ->waitForLocation(route("status.considering"), 30)
                ->assertPathIs("/statusfilter/considering");
        });
    }
}
