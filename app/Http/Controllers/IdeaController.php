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
    public function index()
    {
        $ideas = Idea::latest("id")->simplePaginate(10);   //for eagerload you can add with("user", "category") before simplePagination
        // dd($ideas);

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

    // public function getStatusCounts()
    // {
    //     // return Idea::query()->selectRaw("count(*) as all_counts")
    //     //     ->selectRaw("count(CASE WHEN `status_id` = 1 THEN 1 END) as statusOpen")
    //     //     ->selectRaw("count(CASE WHEN `status_id` = 2 THEN 1 END) as statusConsidering")
    //     //     ->selectRaw("count(CASE WHEN `status_id` = 3 THEN 1 END) as statusInProgress")
    //     //     ->selectRaw("count(CASE WHEN `status_id` = 4 THEN 1 END) as statusImplemented")
    //     //     ->selectRaw("count(CASE WHEN `status_id` = 5 THEN 1 END) as statusClosed")
    //     //     ->first()
    //     //     ->toArray();

    //     $counters = Idea::select(
    //         DB::raw('count(*) as all_counts'),
    //         DB::raw('count(CASE WHEN status_id = 1 THEN 1 END) as statusOpen'),
    //         DB::raw('count(CASE WHEN status_id = 2 THEN 1 END) as statusConsidering'),
    //         DB::raw('count(CASE WHEN status_id = 3 THEN 1 END) as statusInProgress'),
    //         DB::raw('count(CASE WHEN status_id = 4 THEN 1 END) as statusImplemented'),
    //         DB::raw('count(CASE WHEN status_id = 5 THEN 1 END) as statusClosed')
    //     )->first()->toArray();

    //     return $counters;
    // }

    /** Functions for Status Filters */
    public function statusFilterOpen()
    {
        //get status id for Open
        $status = Status::where("name", "Open")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterConsidering()
    {
        $status = Status::where("name", "Considering")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterInProgress()
    {
        $status = Status::where("name", "In Progress")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
        return $this->returnFilteredIdeas($ideas);
    }


    public function statusFilterImplemented()
    {
        $status = Status::where("name", "Implemented")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
        return $this->returnFilteredIdeas($ideas);
    }

    public function statusFilterClosed()
    {
        $status = Status::where("name", "Closed")->first();
        $ideas = Idea::latest("id")->where("status_id", $status->id)->simplePaginate(10);
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
