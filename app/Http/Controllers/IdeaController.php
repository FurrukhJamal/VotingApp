<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;

use App\Http\Requests\UpdateIdeaRequest;
use App\Jobs\NotifyAllVoters;
use App\Mail\IdeaStatusUpdatedMailable;
use App\Models\Category;
use App\Models\Status;
use App\Models\Vote;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(HttpRequest $request)
    {
        $ideas = Idea::latest("id")->simplePaginate(10);   //for eagerload you can add with("user", "category") before simplePagination
        // dd($request->query());

        /** all the conditions to display ideas based on all ideas filter along with category, topVoted and user filters */
        if ($request["user"] == "true") {
            // dd("hitting");
            $this->middleware("auth");
            if (Auth::check()) {
                // dd("hitting");
                if ($request["user"] == "true" && $request["category"]) {
                    $ideas = Idea::where("user_id", Auth::id())
                        ->where("category_id", $request["category"])
                        ->simplePaginate(10);
                } else if ($request["user"] == "true" && $request["category"] && $request["otherfilters"] == "topvoted") {
                    $ideas = Idea::orederBy("votes_count", "desc")
                        ->where("user_id", Auth::id())
                        ->where("category_id", $request["category"])
                        ->simplePaginate(10);
                } else {
                    $ideas = Idea::where("user_id", Auth::id())->simplePaginate(10);
                }
            } else {
                //user not logged in
                return redirect(route("login"));
            }
        } else if ($request["category"] && $request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["otherfilters"] == "topvoted") {
            //case when other filters for top voted is selected just from all ideas page
            $ideas = Idea::orderBy("votes_count", "desc")
                ->simplePaginate(10);
        }

        /**END OF all the conditions to display ideas based on all ideas filter along with category, topVoted and user filters */


        foreach ($ideas->items() as $item) {
            // dd($item);
            $item["profileLink"] = $item->user->getAvatar();
            $item["statusClass"] = $item->getStatusClass();
            $item["isVotedByUser"] = $item->isVotedByUser(Auth::user());
        }

        $avatar = "https://www.gravatar.com/avatar?d=mp";
        $isAdmin = false;

        if (Auth::user()) {
            $avatar = Auth::user()->getAvatar();

            $isAdmin = Auth::user()->isAdmin();
        }

        // dd(Status::getStatusCounts());
        return Inertia::render("HomePage", [
            "ideas" => $ideas,
            "categories" => fn () => Category::all(),   //for partial reloads
            "avatar" => function () use ($avatar) {    //for partial reloads
                return $avatar;
            },
            "statusCounts" => fn () => Status::getStatusCounts(),
            "isAdmin" => function () use ($isAdmin) {
                return $isAdmin;
            },
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        dd($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIdeaRequest $request)
    {
        // dd($request["category"]);
        $validated = $request->validate([
            "title" => "required",
            "category" => "required",
            "description" => "required"
        ]);
        $cat = Category::where("name", $request["category"])->first();

        $idea = $request->user()->ideas()->create([
            ...$validated,
            "status_id" => 1,
            "category_id" => $cat->id,
        ]);

        //adding one vote for the created idea by user
        Vote::create(["user_id" => $request->user()->id, "idea_id" => $idea->id]);

        session()->flash("message", "Idea Added");
        return redirect(route("idea.index"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        $idea["profileLink"] = $idea->user->getAvatar();
        $idea["isVotedByUser"] = $idea->isVotedByUser(Auth::user());
        $idea["statusClass"] = $idea->getStatusClass();

        $avatar = "https://www.gravatar.com/avatar?d=mp";
        $isAdmin = false;

        if (Auth::user()) {
            $avatar = Auth::user()->getAvatar();

            $isAdmin = Auth::user()->isAdmin();
        }

        return Inertia::render("IdeaPage", [
            "idea" => $idea,
            "categories" => Category::all(),
            "avatar" => $avatar,
            "statusCounts" => fn () => Status::getStatusCounts(),
            "isAdmin" => function () use ($isAdmin) {
                return $isAdmin;
            },
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIdeaRequest $request)
    {
        if ($request["ideaUpdate"]) {
            //for when idea is edited
            // dd($request["user"]);
            $validated = $request->validate([
                "title" => "required",
                "description" => "required"
            ]);
            // dd($validated);
            $idea = Idea::where("title", $validated["title"])
                ->where("description", $validated["description"])
                ->first();
            dd($idea);

            $idea->update($validated);
        } else {
            // dd($request);
            $validated = $request->validate([
                "status" => "required",
            ]);

            $idea = Idea::find($request["ideaId"]);
            $newStatus = Status::where("name", $request["status"])->first();
            // dd($newStatus);
            $idea->update(["status_id" => $newStatus->id]);

            if ($request["notifyAllVoters"]) {
                // $this->notifyAllVoters($idea);
                NotifyAllVoters::dispatch($idea);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        //
    }


    /** Functions for Status Filters */
    public function statusFilterOpen(HttpRequest $request)
    {
        // dd($request["otherfilters"]);
        //get status id for Open
        $status = Status::where("name", "Open")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        if ($request["user"] == "true") {
            $ideas = $this->getUserBasedIdeas($request, $status);
            if ($ideas == null) {
                return redirect()->route("login");
            }
        } else if ($request["category"] && $request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
            // dd($ideas);
        } else if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->simplePaginate(10);
        }
        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterConsidering(HttpRequest $request)
    {
        // dd($request->query());
        $status = Status::where("name", "Considering")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        if ($request["user"] == "true") {
            $ideas = $this->getUserBasedIdeas($request, $status);
            if ($ideas == null) {
                return redirect()->route("login");
            }
        } else if ($request["category"] && $request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->simplePaginate(10);
        }



        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterInProgress(HttpRequest $request)
    {
        $status = Status::where("name", "In Progress")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        if ($request["user"] == "true") {
            $ideas = $this->getUserBasedIdeas($request, $status);
            if ($ideas == null) {
                return redirect()->route("login");
            }
        } else if ($request["category"] && $request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }


    public function statusFilterImplemented(HttpRequest $request)
    {
        $status = Status::where("name", "Implemented")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        if ($request["user"] == "true") {
            $ideas = $this->getUserBasedIdeas($request, $status);
            if ($ideas == null) {
                return redirect()->route("login");
            }
        } else if ($request["category"] && $request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterClosed(HttpRequest $request)
    {
        $status = Status::where("name", "Closed")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        if ($request["user"] == "true") {
            $ideas = $this->getUserBasedIdeas($request, $status);
            if ($ideas == null) {
                return redirect()->route("login");
            }
        } else if ($request["category"] && $request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        } else if ($request["otherfilters"] == "topvoted") {
            $ideas = Idea::orderBy("votes_count", "desc")
                ->where("status_id", $status->id)
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }
    /** End of Functions for Status Filters */

    /**Search */
    public function search(HttpRequest $request)
    {
        //dd($request["search_query"]);
        $ideas = Idea::where("title", "like", "%" . $request["search_query"] . "%")->simplePaginate(10);
        foreach ($ideas->items() as $item) {
            // dd($item);
            $item["profileLink"] = $item->user->getAvatar();
            $item["statusClass"] = $item->getStatusClass();
            $item["isVotedByUser"] = $item->isVotedByUser(Auth::user());
        }

        $avatar = "https://www.gravatar.com/avatar?d=mp";
        if (Auth::user()) {
            $avatar = Auth::user()->getAvatar();
        }



        return Inertia::render("HomePage", [
            "ideas" => $ideas,
            "categories" => fn () => Category::all(),   //for partial reloads
            "avatar" => function () use ($avatar) {    //for partial reloads
                return $avatar;
            },
            "statusCounts" => fn () => Status::getStatusCounts(),
        ]);
    }





    /** Helpers */
    // protected function notifyAllVoters($idea)
    // {
    //     $votes = $idea->votes;
    //     $users = [];
    //     foreach ($votes as $vote) {
    //         $users[] = ["name" => $vote->user->name, "email" => $vote->user->email];
    //     }
    //     // dd($users);
    //     foreach ($users as $user) {
    //         //send email
    //         Mail::to($user["email"])
    //             ->queue(new IdeaStatusUpdatedMailable($idea));
    //     }
    // }


    protected function getUserBasedIdeas(HttpRequest $request, Status $status)
    {
        // dd("hitting");
        $this->middleware("auth");
        if (Auth::check()) {
            // dd("hitting");
            if ($request["category"]) {
                $ideas = Idea::where("user_id", Auth::id())
                    ->where("category_id", $request["category"])
                    ->where("status_id", $status->id)
                    ->simplePaginate(10);
            } else {
                $ideas = Idea::where("user_id", Auth::id())
                    ->where("status_id", $status->id)
                    ->simplePaginate(10);
            }
            return $ideas;
        } else {
            //user not logged in
            // dd("hitting");
            return null;
        }
    }






    protected function returnFilteredIdeas($ideas)
    {
        foreach ($ideas->items() as $item) {
            // dd($item);
            $item["profileLink"] = $item->user->getAvatar();
            $item["statusClass"] = $item->getStatusClass();
            $item["isVotedByUser"] = $item->isVotedByUser(Auth::user());
        }

        $avatar = "https://www.gravatar.com/avatar?d=mp";
        if (Auth::user()) {
            $avatar = Auth::user()->getAvatar();
        }

        return Inertia::render("HomePage", [
            "ideas" => $ideas,
            "categories" => fn () => Category::all(),   //for partial reloads
            "avatar" => function () use ($avatar) {    //for partial reloads
                return $avatar;
            },
            "statusCounts" => fn () => Status::getStatusCounts(),
        ]);
    }
}
