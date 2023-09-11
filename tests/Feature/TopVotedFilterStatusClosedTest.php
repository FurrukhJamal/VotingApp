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

class TopVotedFilterStatusClosedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function most_voted_idea_coming_on_top_for_Closed_Statuses()
    {

        $users = User::factory(10)->create();
        $user = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $ideaClosed = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Most voted In Closed idea",
            "description" => "This will be the most voted Closed idea",
            "category_id" => $cat1->id,
            "status_id" => $statusClosed->id
        ]);

        //create 7 more ideas
        Idea::factory(7)->create(["status_id" => $statusClosed->id]);

        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaClosed->id
            ]);
        }
        $user2 = User::factory()->create();
        $idea2 = Idea::factory()->create([
            "title" => "second custom title",
            "status_id" => $statusClosed->id
        ]);

        Vote::factory()->create(
            [
                "user_id" => $user2->id,
                "idea_id" => $idea2->id
            ]
        );



        $this->get("/statusfilter/closed?otherfilters=topvoted")
            ->assertInertia(function (Assert $page) use ($ideaClosed, $idea2) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaClosed, $idea2) {
                        $page->where("current_page", 1)
                            ->has("data", 9)
                            ->has("data.0", function (Assert $page) use ($ideaClosed) {
                                $page->where("title", $ideaClosed->title)
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
    public function pagination_for_most_voted_idea_working_for_Closed_Status()
    {
        $users = User::factory(10)->create();
        $user = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $ideaClosed = Idea::factory()->create([
            "user_id" => $user->id,
            "title" => "Idea with 1 votes",
            "description" => "This should be on top of page 2",
            "category_id" => $cat1->id,
            "status_id" => $statusClosed->id
        ]);

        Vote::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $ideaClosed->id
        ]);

        $ideas = Idea::factory(10)->create(["status_id" => $statusClosed->id]);
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
            "status_id" => $statusClosed->id
        ]);


        $this->get("/statusfilter/closed?otherfilters=topvoted&page=2")
            ->assertInertia(function (Assert $page) use ($ideaZeroVote, $ideaClosed) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaZeroVote, $ideaClosed) {
                        $page->has("data", 2)
                            ->has("data.0", function (Assert $page) use ($ideaClosed) {
                                $page->where("title", $ideaClosed->title)
                                    ->where("description", $ideaClosed->description)
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
    public function top_voted_idea_along_with_filtered_category_displaying_correctly_for_Closed_status()
    {
        $users = User::factory(10)->create();
        $user1 = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        /*category 2 is the main category*/

        $ideaWithMaxVotes = Idea::factory()->create([
            "title" => "Idea with Max Votes",
            "description" => "This idea will not appear in the reurned data",
            "category_id" => $cat1->id,
            "user_id" => $user1->id,
            "status_id" => $statusClosed->id,

        ]);

        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaWithMaxVotes->id
            ]);
        }

        $ideaOfCat2ClosedWithMaxVotes = Idea::factory()->create([
            "title" => "Idea with Max Votes of Category 2",
            "description" => "This idea will appear on top in the reurned data",
            "category_id" => $cat2->id,
            "user_id" => $user1->id,
            "status_id" => $statusClosed->id
        ]);

        $counter = 0;
        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaOfCat2ClosedWithMaxVotes->id
            ]);

            $counter++;
            if ($counter == 4) {
                //4 votes
                break;
            }
        }

        $ideaOfCat2ClosedWith3Votes = Idea::factory()->create([
            "title" => "Idea with second Highest Votes of Category 2",
            "description" => "This idea will appear in 2nd position in the reurned data",
            "category_id" => $cat2->id,
            "user_id" => $user1->id,
            "status_id" => $statusClosed->id
        ]);

        $counter = 0;
        foreach ($users as $user) {
            Vote::factory()->create([
                "user_id" => $user->id,
                "idea_id" => $ideaOfCat2ClosedWith3Votes->id
            ]);

            $counter++;
            if ($counter == 3) {
                //3 votes
                break;
            }
        }


        $this->get("/statusfilter/closed?otherfilters=topvoted&category=2")
            ->assertInertia(function (Assert $page) use ($ideaOfCat2ClosedWithMaxVotes, $ideaOfCat2ClosedWith3Votes) {
                $page->component("HomePage")
                    ->has("ideas", function (Assert $page) use ($ideaOfCat2ClosedWithMaxVotes, $ideaOfCat2ClosedWith3Votes) {
                        // dd($page);
                        $page->has("data", 2)
                            ->has("data.0", function (Assert $page) use ($ideaOfCat2ClosedWithMaxVotes) {
                                $page->where("title", $ideaOfCat2ClosedWithMaxVotes->title)
                                    ->where("description", $ideaOfCat2ClosedWithMaxVotes->description)
                                    ->etc();
                            })
                            ->has("data.1", function (Assert $page) use ($ideaOfCat2ClosedWith3Votes) {
                                $page->where("title", $ideaOfCat2ClosedWith3Votes->title)
                                    ->etc();
                            })
                            ->etc();
                    })
                    ->etc();
            });
    }


    /** @test */
    public function top_voted_in_Closed_status_ideas_with_category_filtered_pagination_correctly()
    {
        $users = User::factory(10)->create();
        $user1 = $users->get(1);

        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $ideas = Idea::factory(10)->create([
            "category_id" => $cat2->id,
            "status_id" => $statusClosed->id
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
            "status_id" => $statusClosed->id
        ]);

        $ideaForSecondPage = Idea::factory()->create([
            "title" => "This will be on top of second page",
            "category_id" => $cat2->id,
            "status_id" => $statusClosed->id,
        ]);

        $this->get("/statusfilter/closed?otherfilters=topvoted&category=2&page=2")
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
