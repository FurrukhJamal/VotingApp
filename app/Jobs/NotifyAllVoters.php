<?php

namespace App\Jobs;

use App\Mail\IdeaStatusUpdatedMailable;
use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyAllVoters implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $idea;
    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $votes = $this->idea->votes;
        $users = [];
        foreach ($votes as $vote) {
            $users[] = ["name" => $vote->user->name, "email" => $vote->user->email];
        }
        // dd($users);
        foreach ($users as $user) {
            //send email
            Mail::to($user["email"])
                ->queue(new IdeaStatusUpdatedMailable($this->idea));
        }
    }
}
