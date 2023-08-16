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

    protected $fillable = ["slug", "description", "title", "user_id"];

    protected $with = ["user", "category"];

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
}
