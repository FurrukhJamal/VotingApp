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

class FilteredCategoryPaginationTest extends DuskTestCase
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
    public function filtered_category_pagination_working_for_single_statues()
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

        $ideaLastInDisplay = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "title Of Idea related to category 1 and would be first on pagination page 2 of opened status",
            "category_id" => $category1->id,
            "description" => "Idea related to category 1 and would be first on pagination page 2 of opened status",
            "status_id" => $statusOpen->id,
        ]);

        Idea::factory(9)->create([
            "user_id" => $user->id,
            "title" => "Some title",
            "category_id" => $category1->id,
            "description" => "Some description",
            "status_id" => $statusOpen->id,
        ]);

        $ideaOpenLatest = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Latest Opened Idea",
            "category_id" => $category1->id,
            "description" => "Latest OPened Idea",
            "status_id" => $statusOpen->id,
        ]);

        $ideaLatest = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Latest idea",
            "category_id" => $category2->id,
            "description" => "description of idea that is not opened and not category 1",
            "status_id" => $statusClosed->id,
        ]);


        $this->browse(function (Browser $browser) use ($ideaLatest, $ideaOpenLatest, $ideaLastInDisplay) {
            $browser->visit("/")
                ->waitFor("@statusFilterOpen")
                ->waitFor("@ideaOnTopOfPage")
                ->waitFor("@titleOnTopOfPage", 20)
                ->assertSeeIn("@titleOnTopOfPage", $ideaLatest->title)
                ->waitForTextIn("@descriptionOnTopOfPage", $ideaLatest->description)
                ->click("@statusFilterOpen")
                ->waitFor("@ideaOnTopOfPage")
                ->waitForTextIn("@titleOnTopOfPage", $ideaOpenLatest->title)
                ->waitForTextIn("@descriptionOnTopOfPage", $ideaOpenLatest->description)
                ->click("@paginationNextButton")
                ->waitForTextIn("@titleOnTopOfPage", $ideaLastInDisplay->title, 10)
                ->waitForTextIn("@descriptionOnTopOfPage", $ideaLastInDisplay->description, 10)
                ->screenshot("endingScreen");
        });
    }

    /** @test */
    public function pagination_for_all_statuses_filtered_by_category_working()
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




        $ideaLastInDisplay = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "title Of Idea related to category 1 and would be first on pagination page 2 of all ideas when category filter selected",
            "category_id" => $category1->id,
            "description" => "Idea related to category 1 and would be first on pagination page 2 of all ideas",
            "status_id" => $statusOpen->id,
        ]);

        $ideaOpenPage2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "A categort 2 idea that should appear on page 2 if no category filters are applied",
            "category_id" => $category2->id,
            "description" => "description of categort 2 idea that should appear on page 2 if no category filters are applied",
            "status_id" => $statusOpen->id,
        ]);



        Idea::factory(9)->create([
            "user_id" => $user->id,
            "title" => "Some title",
            "category_id" => $category1->id,
            "description" => "Some description",
            "status_id" => $statusOpen->id,
        ]);



        $ideaLatestOfCategory1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "This is top most idea that should display on page 1",
            "category_id" => $category1->id,
            "description" => "description of idea that is category 1 and would be first on page 1 of all ideas",
            "status_id" => $statusClosed->id,
        ]);


        $this->browse(function (Browser $browser) use ($ideaLatestOfCategory1, $ideaOpenPage2) {
            $browser->visit("/")
                ->waitFor("@statusFilterOpen")
                ->waitFor("@ideaOnTopOfPage")
                ->waitFor("@titleOnTopOfPage", 20)
                ->screenshot("paginationsecondtestinitialstate")
                ->assertSeeIn("@titleOnTopOfPage", $ideaLatestOfCategory1->title)
                ->assertSeeIn("@descriptionOnTopOfPage", $ideaLatestOfCategory1->description)
                ->click("@paginationNextButton")
                ->waitFor("@titleOnTopOfPage", 20)
                ->screenshot("paginationTest2AfterClickingPaginatioLink")
                ->waitForTextIn("@titleOnTopOfPage", $ideaOpenPage2->title, 20)
                ->waitForTextIn("@descriptionOnTopOfPage", $ideaOpenPage2->description, 20)
                ->screenshot("screenForPage2OfPaginationAll");
        });

        $this->browse(function (Browser $browser) use ($ideaLastInDisplay) {
            $browser->visit("/")
                ->waitFor("@categoriesButton", 20)
                ->press("@categoriesButton")
                ->screenshot("errorStaleElement")
                ->whenAvailable("@Category1Button", function (Browser $browser) use ($ideaLastInDisplay) {
                    $browser->screenshot("category1Pressed")
                        ->waitFor("@Category1Button", 20)
                        ->click("@Category1Button")
                        ->waitFor("@ideaOnTopOfPage", 20)
                        ->press("@@paginationNextButton")
                        ->waitForTextIn("@titleOnTopOfPage", $ideaLastInDisplay->title, 20)
                        ->waitForTextIn("@descriptionOnTopOfPage", $ideaLastInDisplay->description, 20)
                        ->screenshot("allIdeaCategoryPaginationNext");
                }, 20);
            // ->waitFor("@Category1Button")
            // ->click("@Category1Button")
            // ->waitFor("@ideaOnTopOfPage", 20)
            // ->click("@paginationNextButton")
            // ->waitForTextIn("@titleOnTopOfPage", $ideaOpenPage2->title, 20)
            // ->waitForTextIn("@descriptionOnTopOfPage", $ideaOpenPage2->description, 20);
        });
    }
}
