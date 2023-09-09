<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class CategoryDisplayTest extends DuskTestCase
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
    public function correct_category_is_beign_displayed_on_the_category_based_urls()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();




        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "title Of Idea related to category 1 and would be first on pagination page 2 of all ideas when category filter selected",
            "category_id" => $category1->id,
            "description" => "Idea related to category 1 and would be first on pagination page 2 of all ideas",
            "status_id" => $statusOpen->id,
        ]);

        $this->browse(function (Browser $browser) use ($category2) {
            $browser->visit("/?category=2")
                ->waitFor("@categoriesButton", 20)
                ->assertSeeIn("@categoriesButton", $category2->name);
        });
    }

    /** @test */
    public function category_displaying_properly_within_a_paginated_status_filterd_page()
    {
        $category1 = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(['name' => "Category two"]);
        $category3 = Category::factory()->create(['name' => "Category three"]);
        $category4 = Category::factory()->create(['name' => "Category four"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();




        $idea = Idea::factory(31)->create([
            "user_id" => $user->id,
            "category_id" => $category1->id,
            "status_id" => $statusOpen->id,
        ]);

        $this->browse(function (Browser $browser) use ($category1) {
            $browser->visit("/statusfilter/open/1?page=3")
                ->screenshot("categorydisplayImage")
                ->waitFor("@categoriesButton", 20)
                ->assertSeeIn("@categoriesButton", $category1->name);
        });
    }
}
