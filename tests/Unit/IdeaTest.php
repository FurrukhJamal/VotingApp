<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IdeaTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }

    use RefreshDatabase;
    /** @test */
    public function check_if_idea_voted_for_by_user()
    {
        $categoryOne = Category::factory()->create(['name' => "Category one"]);
        $categoryTwo = Category::factory()->create(['name' => "Category two"]);

        Status::factory()->create(["name" => "Open"]);
        Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $user = User::factory()->create();
        $user2 = User::factory()->create();


        $idea = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "first title",
            "category_id" => $categoryOne->id,
            "description" => "description of title"
        ]);



        Vote::factory()->create([
            "idea_id" => $idea->id,
            "user_id" => $user->id
        ]);

        $this->assertTrue($idea->isVotedByUser($user));
        $this->assertFalse($idea->isVotedByUser($user2));
        $this->assertFalse($idea->isVotedByUser(null)); //for when user is not logged in
    }
}
