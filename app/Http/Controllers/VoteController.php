<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VoteController extends Controller
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
    public function store(StoreVoteRequest $request)
    {
        // dd($request["user_id"]);
        //since its a fetch call I have to do the authorization manually
        try {
            $vote = Vote::create($request->json()->all());

            if ($vote) {
                return ["success" => "idea added"];
            }
        } catch (Exception $e) {
            return ["error" => "vote could not be added"];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = $request->json()->all();

        $vote = Vote::where("user_id", $data["user_id"])
            ->where("idea_id", $data["idea_id"])
            ->first();

        if ($vote) {
            $vote->delete();
            return ["success" => "Vote was deleted"];
        }

        return ["error" => "could not delete a vote"];
    }
}
