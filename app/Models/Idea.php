<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Idea extends Model
{
    use HasFactory;
    use Sluggable;



    const PAGINATION_COUNT = 10;

    protected $fillable = ["spam_reports", "category_id", "status_id", "slug", "description", "title", "user_id",];

    protected $with = ["user", "category", "status", "comments"];
    protected $withCount = ["votes", "comments"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function getStatusClass()
    {
        // $allStatuses = [
        //     "Open" => "bg-gray-200",
        //     "Considering" => "bg-purple-200 text-blue",
        //     "In Progress" => "bg-yellow text-white",
        //     "Implemented" => "bg-green text-white",
        //     "Closed" =>  "bg-red-500 text-white",
        // ];

        // return $allStatuses[$this->status->name];

        if ($this->status->name === "Open") {
            return "bg-gray-200";
        } else if ($this->status->name === "Considering") {
            return "bg-yellow-500 text-white";
        } else if ($this->status->name === "In Progress") {
            return "bg-sky-600 text-white";
        } else if ($this->status->name === "Implemented") {
            return "bg-green-200 text-white";
        } else if ($this->status->name === "Closed") {
            return "bg-red-500 text-white";
        }

        return "bg-gray-200";
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isVotedByUser(?User $user) // ? to make the argument optional 
    {
        if (!$user) {
            return false;
        }
        return Vote::where("user_id", $user->id)->where("idea_id", $this->id)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
