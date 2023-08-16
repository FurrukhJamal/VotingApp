<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
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

        $ideaOne = Idea::factory()->create([
            "title" => "first title",
            "category_id" => $categoryOne->name,
            "description" => "description of title"
        ]);

        $ideaTwo = Idea::factory()->create([
            "title" => "Second title",
            "description" => "description of idea",
            "category_id" => $categoryTwo->name,
        ]);

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

        $ideaOne = Idea::factory()->create([
            "title" => "first title",
            "description" => "description of title",
            "category_id" => $categoryOne->name,
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
        Idea::factory(Idea::PAGINATION_COUNT + 1)->create();

        $ideaOne = Idea::find(1);
        $ideaOne->title = "My First Idea";

        $ideaOne->save();

        $response = $this->get("/");
        $response->assertSee($ideaOne->title);

        $ideaLast = Idea::find(Idea::PAGINATION_COUNT + 1);
        $ideaLast->title = "last idea";
        $ideaLast->save();

        $response->assertDontSee($ideaLast->title);

        $response = $this->get("/?page=2");

        $response->assertSee($ideaLast->title);
        $response->assertDontSee($ideaOne->title);
    }

    /** @test */
    public function idea_with_same_title_have_defferent_slug()
    {
        $ideaOne = Idea::factory()->create([
            "title" => "Same title",
            "description" => "description of title"
        ]);

        $ideaTwo = Idea::factory()->create([
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
}
