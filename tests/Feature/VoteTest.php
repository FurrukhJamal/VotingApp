<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    use RefreshDatabase;

    /** @test */
    public function test_when_user_voting_its_showing_in_db()
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

        $response = $this->postJson("/api/vote", [
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            "success" => "idea added"
        ]);


        $this->assertDatabaseHas("votes", [
            "idea_id" => $idea->id,
            "user_id" => $user->id
        ]);

        $response = $this->postJson("/api/deletevote", [
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        $response->assertSuccessful();
        $response->assertJson([
            "success" => "Vote was deleted"
        ]);

        $this->assertDatabaseMissing("votes", [
            "idea_id" => $idea->id,
            "user_id" => $user->id
        ]);
    }
}
