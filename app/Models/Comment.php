<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ["body", "user_id", "idea_id"];
    protected $with = ["user"];
    protected $appends = ["editableByUser", "ifAuthorIsAdmin"];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function getEditableByUserAttribute()
    {
        return $this->user->id == Auth::id();
    }

    public function getifAuthorIsAdminAttribute()
    {
        return $this->user->isAdmin();
    }
}
