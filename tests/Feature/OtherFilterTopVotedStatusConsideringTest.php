<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;


class OtherFilterTopVotedStatusConsideringTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function most_voted_idea_coming_on_top_for_Considering_Statuses()
    {

        $users = User::factory(10)->create();
        $user = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $ideaConsidering = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Most voted Considering idea",
            "description" => "This will be the most voted Considering idea",
            "category_id" => $cat1->id,
            "status_id" => $statusConsidering->id
        ]);

        //create 7 more ideas
        Idea::factory(7)->create(["status_id" => $statusConsidering->id]);

        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaConsidering->id
            ]);
        }
        $user2 = User::factory()->create();
        $idea2 = Idea::factory()->create([
            "title" => "second custom title",
            "status_id" => $statusConsidering->id
        ]);

        Vote::factory()->create(
            [
                "user_id" => $user2->id,
                "idea_id" => $idea2->id
            ]
        );



        $this->get("/statusfilter/considering?otherfilters=topvoted")
            ->assertInertia(function (Assert $page) use ($ideaConsidering, $idea2) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaConsidering, $idea2) {
                        $page->where("current_page", 1)
                            ->has("data", 9)
                            ->has("data.0", function (Assert $page) use ($ideaConsidering) {
                                $page->where("title", $ideaConsidering->title)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($idea2) {
                                $page->where("title", $idea2->title)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }



    /** @test */
    public function pagination_for_most_voted_idea_working_for_Considering_Status()
    {
        $users = User::factory(10)->create();
        $user = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $ideaConsidering = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Idea with 1 votes",
            "description" => "This should be on top of page 2",
            "category_id" => $cat1->id,
            "status_id" => $statusConsidering->id
        ]);

        Vote::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $ideaConsidering->id
        ]);

        $ideas = Idea::factory(10)->create(["status_id" => $statusConsidering->id]);
        foreach ($users as $voter) {
            foreach ($ideas as $Idea) {
                Vote::factory()->create([
                    "user_id" => $voter->id,
                    "idea_id" => $Idea->id
                ]);
            }
        }

        $ideaZeroVote = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Idea with 0 vote would be second on page 2",
            "description" => "This should be on second number of page 2",
            "category_id" => $cat1->id,
            "status_id" => $statusConsidering->id
        ]);


        $this->get("/statusfilter/considering?otherfilters=topvoted&page=2")
            ->assertInertia(function (Assert $page) use ($ideaZeroVote, $ideaConsidering) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaZeroVote, $ideaConsidering) {
                        $page->has("data", 2)
                            ->has("data.0", function (Assert $page) use ($ideaConsidering) {
                                $page->where("title", $ideaConsidering->title)
                                    ->where("description", $ideaConsidering->description)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($ideaZeroVote) {
                                $page->where("title", $ideaZeroVote->title)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }

    /** @test */
    public function top_voted_idea_along_with_filtered_category_displaying_correctly_for_Considering_status()
    {
        $users = User::factory(10)->create();
        $user1 = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        /*category 2 is the main category*/

        $ideaWithMaxVotes = Idea::factory()->create([
            "title" => "Idea with Max Votes",
            "description" => "This idea will not appear in the reurned data",
            "category_id" => $cat1->id,
            "user_id" => $user1->id,
            "status_id" => $statusOpen->id,

        ]);

        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaWithMaxVotes->id
            ]);
        }

        $ideaOfCat2ConsideringWithMaxVotes = Idea::factory()->create([
            "title" => "Idea with Max Votes of Category 2",
            "description" => "This idea will appear on top in the reurned data",
            "category_id" => $cat2->id,
            "user_id" => $user1->id,
            "status_id" => $statusOpen->id
        ]);

        $counter = 0;
        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaOfCat2ConsideringWithMaxVotes->id
            ]);

            $counter++;
            if ($counter == 4) {
                //4 votes
                break;
            }
        }

        $ideaOfCat2ConsideringWith3Votes = Idea::factory()->create([
            "title" => "Idea with second Highest Votes of Category 2",
            "description" => "This idea will appear in 2nd position in the reurned data",
            "category_id" => $cat2->id,
            "user_id" => $user1->id,
            "status_id" => $statusOpen->id
        ]);

        $counter = 0;
        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaOfCat2ConsideringWith3Votes->id
            ]);

            $counter++;
            if ($counter == 3) {
                //3 votes
                break;
            }
        }


        $this->get("/statusfilter/open?otherfilters=topvoted&category=2")
            ->assertInertia(function (Assert $page) use ($ideaOfCat2ConsideringWithMaxVotes, $ideaOfCat2ConsideringWith3Votes) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaOfCat2ConsideringWithMaxVotes, $ideaOfCat2ConsideringWith3Votes) {
                        // dd($page);
                        $page->has("data", 2)
                            ->has("data.0", function (Assert $page) use ($ideaOfCat2ConsideringWithMaxVotes) {
                                $page->where("title", $ideaOfCat2ConsideringWithMaxVotes->title)
                                    ->where("description", $ideaOfCat2ConsideringWithMaxVotes->description)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($ideaOfCat2ConsideringWith3Votes) {
                                $page->where("title", $ideaOfCat2ConsideringWith3Votes->title)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }


    /** @test */
    public function top_voted_in_Considering_status_ideas_with_category_filtered_pagination_correctly()
    {
        $users = User::factory(10)->create();
        $user1 = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        Status::factory()->create(["name" => "In Progress"]);
        Status::factory()->create(["name" => "Implemented"]);
        Status::factory()->create(["name" => "Closed"]);

        $ideas = Idea::factory(10)->create([
            "category_id" => $cat2->id,
            "status_id" => $statusConsidering->id
        ]);

        foreach ($users as $user) {
            foreach ($ideas as $idea) {
                Vote::factory()->create([
                    "user_id" => $user->id,
                    "idea_id" => $idea->id
                ]);
            }
        }

        //creating 10 more ideas
        Idea::factory(10)->create([
            "category_id" => $cat1->id,
            "status_id" => $statusConsidering->id
        ]);

        $ideaForSecondPage = Idea::factory()->create([
            "title" => "This will be on top of second page",
            "category_id" => $cat2->id,
            "status_id" => $statusConsidering->id,
        ]);

        $this->get("/statusfilter/considering?otherfilters=topvoted&category=2&page=2")
            ->assertInertia(function (Assert $page) use ($ideaForSecondPage) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaForSecondPage) {
                        $page->has("data", 1)
                            ->has("data.0", function (Assert $page) use ($ideaForSecondPage) {
                                $page->where("title", $ideaForSecondPage->title)
                                    ->where("description", $ideaForSecondPage->description)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }
}
