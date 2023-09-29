<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Idea;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        //
        // dd($request->user());
        $user = $request->user();
        $validated = $request->validate([
            "comment" => "required|min:12",
            "idea" => "required"
        ]);

        $ideaId = $validated["idea"]["id"];
        $idea = Idea::find($ideaId);
        Comment::create([
            "body" => $validated["comment"],
            "user_id" => $user->id,
            "idea_id" => $idea->id
        ]);

        session()->flash("message", "Comment Added Successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request)
    {
        // dd($request["commentId"]);
        $comment = Comment::find($request["commentId"]);
        // dd($request["commentId"]);
        if (Auth::guest() || $request->user()->cannot("update", $comment)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            "Comment" => "required|min:4"
        ]);

        $comment->update(["body" => $validated["Comment"]]);
        session()->flash("message", "Comment Updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        if (Auth::check() && $user->can("delete", $comment)) {
            $comment->delete();
            session()->flash("message", "Comment Deleted!");
        } else {
            abort(Response::HTTP_FORBIDDEN);
        }
    }
}
