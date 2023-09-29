<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditCommentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    use DatabaseTruncation;

    /** @test */
    public function only_the_author_of_the_comment_can_edit_a_comment()
    {
        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //adding a commet
        Comment::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        $this->browse(function (Browser $browser) use ($idea, $user) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->assertSeeIn("@editCommentSection", "...");
        });

        $this->browse(function (Browser $browser) use ($idea, $otherUser) {
            $browser->loginAs($otherUser)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->assertMissing("@editCommentSection");
        });
    }

    /** @test */
    public function the_edit_comment_modal_is_getting_the_coorect_comment_to_edit()
    {
        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //creating 3 comments on this idea from various users
        Comment::factory(3)->create(["idea_id" => $idea->id]);

        //creating comments by two users 
        $comment1 = Comment::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        $comment2 = Comment::factory()->create([
            "user_id" => $otherUser->id,
            "idea_id" => $idea->id
        ]);

        $this->browse(function (Browser $browser) use ($user, $idea, $comment1) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->press("@editCommentSection")
                ->waitFor("@editCommentButton", 20)
                ->press("@editCommentButton")
                ->waitFor("@editCommentModalTextArea", 20)
                ->screenshot("editcommentScreen")
                ->assertSeeIn("@editCommentModalTextArea", $comment1->body);
        });
    }

    /** @test */
    public function comment_is_getting_edited()
    {
        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);



        //creating comments by two users 
        $comment1 = Comment::factory()->create([
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);


        $this->browse(function (Browser $browser) use ($user, $idea, $comment1) {
            $browser->loginAs($user)
                ->visit(route("idea.show", $idea))
                ->waitFor("@ideaFunctions", 20)
                ->press("@editCommentSection")
                ->waitFor("@editCommentButton", 20)
                ->press("@editCommentButton")
                ->waitFor("@editCommentModalTextArea", 20)
                ->type("@editCommentModalTextArea", "comment updated")
                ->screenshot("updatedCommentText")
                ->press("@commentUpdateButton")
                ->waitFor("@notificationTab", 20)
                ->assertSeeIn("@notificationTab", "Comment Updated!");
        });

        $this->assertDatabaseHas("comments", ["body" => "comment updated"]);
    }

    /** @test */
    public function user_can_not_see_delete_a_comment_option_for_others_comments()
    {
        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //some random comments for this idea
        Comment::factory(3)->create(["idea_id" => $idea->id]);

        $comment = Comment::factory()->create([
            "idea_id" => $idea->id,
            "body" => "This is the commment that will be deleted",
            "user_id" => $user->id
        ]);

        $this->browse(
            function (Browser $browser) use ($otherUser, $idea) {
                $browser->loginAs($otherUser)
                    ->visit(route("idea.show", $idea))
                    ->waitFor("@ideaFunctions", 20)
                    ->assertMissing("@editCommentSection");
            }
        );
    }

    /** @test */
    public function user_is_getting_the_correct_warning_message_before_deletion_and_idea_is_deleting()
    {
        $cat1 = Category::factory()->create(["name" => "Category 1"]);
        $cat2 = Category::factory()->create(["name" => "Category 2"]);
        Category::factory()->create(["name" => "Category 3"]);
        Category::factory()->create(["name" => "Category 4"]);

        $statusOpen = Status::factory()->create(["name" => "Open"]);
        $statusConsidering = Status::factory()->create(["name" => "Considering"]);
        $statusInProgress = Status::factory()->create(["name" => "In Progress"]);
        $statusImplemented = Status::factory()->create(["name" => "Implemented"]);
        $statusClosed = Status::factory()->create(["name" => "Closed"]);

        $admin = User::factory()->create(["email" => "furrukhjamal@yahoo.com"]);

        $otherUser = User::factory()->create();

        $user = User::factory()->create();

        $idea = Idea::factory()->create([
            "title" => "Idea created by user",
            "description" => "This idea should be in db",
            "user_id" => $user->id,
            "status_id" => $statusConsidering->id,
        ]);

        //some random comments for this idea
        Comment::factory(3)->create(["idea_id" => $idea->id]);

        $comment = Comment::factory()->create([
            "idea_id" => $idea->id,
            "body" => "This is the commment that will be deleted",
            "user_id" => $user->id
        ]);

        $this->browse(
            function (Browser $browser) use ($user, $idea) {
                $browser->loginAs($user)
                    ->visit(route("idea.show", $idea))
                    ->waitFor("@ideaFunctions", 20)
                    ->press("@editCommentSection")
                    ->waitFor("@deleteCommentButton", 20)
                    ->press("@deleteCommentButton")
                    ->waitFor("@deleteResourceConfirmation", 20)
                    ->assertSeeIn("@deleteResourceConfirmation", "Are you sure you want to delete the comment")
                    ->press("@deleteResourceConfirmationButton")
                    ->waitFor("@notificationTab", 20)
                    ->assertSeeIn("@notificationTab", "Comment Deleted!");
            }
        );
    }
}
