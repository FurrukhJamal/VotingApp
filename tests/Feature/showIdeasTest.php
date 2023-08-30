<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowIdeasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    /** @test */
    public function list_of_ideas_shows_on_main_page()
    {
        // adding Tests for category addition
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaOne = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);

        $ideaTwo = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->id,
        ]);

        //dd($ideaOne);

        $response = $this->get(route("idea.index"));
        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);


        // adding Tests for category addition
        $response->assertSee($categoryOne->name);
        $response->assertSee($categoryTwo->name);
    }

    /** @test */
    public function single_ideas_shows_on_idea_page()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $category2 = Category::factory()->create(["name" => "Category 2"]);
        $category3 = Category::factory()->create(["name" => "Category 3"]);
        $category4 = Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();

        $ideaOne = Idea::factory()->create([
            "title" => "first title",
            "description" => "description of title",
            "category_id" => $categoryOne->id,
            "status_id" => $statusOpen->id,
            "user_id" => $user->id
        ]);


        $response = $this->get(route("idea.show", $ideaOne));
        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($categoryOne->name);
    }

    /** @test */
    public function ideas_pagination_works()
    {
        $user = User::factory()->create();

        Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        Idea::factory(Idea::PAGINATION_COUNT + 1)->create([
            "user_id" => $user->id
        ]);



        $ideaOne = Idea::find(1);
        $ideaOne->title = "My First Idea";

        $ideaOne->save();

        $ideaLast = Idea::find(Idea::PAGINATION_COUNT + 1);
        $ideaLast->title = "Furrukh last idea";
        $ideaLast->save();

        $response = $this->get(route("idea.index"));

        //dd(" ");
        $response->assertDontSee($ideaOne->title);
        $response->assertSee($ideaLast->title);

        $response = $this->get("/?page=2");

        $response->assertDontSee($ideaLast->title);
        $response->assertSee($ideaOne->title);
    }

    /** @test */
    public function idea_with_same_title_have_defferent_slug()
    {
        $user = User::factory()->create();

        $ideaOne = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Same title",
            "description" => "description of title"
        ]);

        $ideaTwo = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Same title",
            "description" => "description of idea"
        ]);

        $response = $this->get(route("idea.show", $ideaOne));
        $response->assertSuccessful();

        $this->assertTrue(request()->path() === "ideas/same-title");

        $response = $this->get(route("idea.show", $ideaTwo));
        $response->assertSuccessful();

        $this->assertTrue(request()->path() === "ideas/same-title-2");
    }

    /** @test */
    public function check_different_statuses_are_showing_together_with_different_classes()
    {
        $user = User::factory()->create();
        $category1 = Category::factory()->create(["name" => "Category 1"]);
        $category2 = Category::factory()->create(["name" => "Category 2"]);
        $category3 = Category::factory()->create(["name" => "Category 3"]);
        $category4 = Category::factory()->create(["name" => "Category 4"]);


        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering =  Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $idea1 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title One",
            "description" => "description One",
            "status_id" => $statusOpen->id,
            "category_id" => $category1->id
        ]);

        $idea2 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Two",
            "description" => "description One",
            "status_id" => $statusConsidering->id,
            "category_id" => $category2->id
        ]);

        $idea3 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Three",
            "description" => "description One",
            "status_id" => $statusInProgress->id,
            "category_id" => $category3->id
        ]);

        $idea4 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Four",
            "description" => "description One",
            "status_id" => $statusImplemented->id,
            "category_id" => $category4->id
        ]);

        $idea5 = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Title Five",
            "description" => "description One",
            "status_id" => $statusClosed->id,
            "category_id" => $category1->id
        ]);

        // TODO : need to fix these tests
        $response = $this->get(route("idea.index"));
        $response->assertSuccessful();
        // usleep(1000000); //one second wait
        $response->assertSee($statusImplemented->name);
        $response->assertSee('12', false);
        $response->assertSee($idea5->title);
        $response->assertSeeInOrder([$idea5->title, $idea4->title, $idea3->title, $idea2->title, $idea1->title]);
        // $response->assertSee('<h3 class="font-semibold text-base">Add an idea</h3>');
        // $response->assertSee('<div class="flex justify-center text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4">In Progress</div>', false);
        // $response->assertSee('<div class="flex justify-center bg-yellow text-white text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4">In Progress</div>', false);
        // $response->assertSee('<div class="flex justify-center bg-green text-white text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4">Implemented</div>', false);
        // $response->assertSee('<div class="flex justify-center bg-gray-200 text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4">Open</div>', false);
        // $response->assertSee('<div class="flex justify-center bg-purple-200 text-blue text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4">Considering</div>', false);
        // $response->assertSee('<div class="flex justify-center bg-red-500 text-white text-xxs items-center font-bold uppercase rounded-full h-7 text-center py-2 px-4">Closed</div>', false);
        // End TODO
    }
}
