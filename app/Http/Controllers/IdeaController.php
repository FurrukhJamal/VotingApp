<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Http\Requests\StoreIdeaRequest;

use App\Http\Requests\UpdateIdeaRequest;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        //if category is selected
        if ($request["category"] && $request["otherfilters"] == "topvoted") {
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

        // dd(Status::getStatusCounts());
        return Inertia::render("HomePage", [
            "ideas" => $ideas,
            "categories" => fn () => Category::all(),   //for partial reloads
            "avatar" => function () use ($avatar) {    //for partial reloads
                return $avatar;
            },
            "statusCounts" => fn () => Status::getStatusCounts(),
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

        $request->user()->ideas()->create([
            ...$validated,
            "status_id" => 1,
            "category_id" => $cat->id,
        ]);
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

        $avatar = "https://www.gravatar.com/avatar?d=mp";
        if (Auth::user()) {
            $avatar = Auth::user()->getAvatar();
        }

        return Inertia::render("IdeaPage", [
            "idea" => $idea,
            "categories" => Category::all(),
            "avatar" => $avatar,
            "statusCounts" => fn () => Status::getStatusCounts(),
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
    public function update(UpdateIdeaRequest $request, Idea $idea)
    {
        //
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
        //get status id for Open
        $status = Status::where("name", "Open")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
        if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        }
        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterConsidering(HttpRequest $request)
    {
        // dd($request->query());
        $status = Status::where("name", "Considering")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
        //if category is selected
        if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterInProgress(HttpRequest $request)
    {
        $status = Status::where("name", "In Progress")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        //if category is selected
        if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }


    public function statusFilterImplemented(HttpRequest $request)
    {
        $status = Status::where("name", "Implemented")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        //if category is selected
        if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterClosed(HttpRequest $request)
    {
        $status = Status::where("name", "Closed")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);

        //if category is selected
        if ($request["category"]) {
            $ideas = Idea::latest("id")
                ->where("status_id", $status->id)
                ->where("category_id", $request["category"])
                ->simplePaginate(10);
        }

        return $this->returnFilteredIdeas($ideas);
    }
    /** End of Functions for Status Filters */

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
