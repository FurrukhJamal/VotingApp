<?php

namespace Tests\Feature;

use App\Models\Idea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class showIdeasTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function list_of_ideas_shows_on_main_page()
    {
        $ideaOne = Idea::factory()->create([
            "title" => "first title",
            "description" => "description of title"
        ]);

        $ideaTwo = Idea::factory()->create([
            "title" => "Second title",
            "description" => "description of idea"
        ]);

        $response = $this->get(route("idea.index"));
        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->description);
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->description);
    }
}
