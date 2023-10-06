<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Comment;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "emailhash",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ["userAvatar"];

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    // public function votes()
    // {
    //     return $this->belongsToMany(Idea::class, "votes");
    // }

    public function getAvatar()  //getAvatarAttribute if you want to access it like user->getAvatar
    {
        // $randomint = rand(1, 36);
        $firstChar = $this->email[0];
        if (is_numeric($firstChar)) {
            $avatarNumber = ord($firstChar) - 21;
        } else {
            $avatarNumber = ord($firstChar) - 96;
        }

        // $avatarNumber = 47; for testing

        return "https://gravatar.com/avatar/" . md5($this->email)
            . "?s=200"
            . "&d=https://i1.wp.com/s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-{$avatarNumber}.png";
    }



    public function isAdmin()
    {
        return in_array($this->email, ["furrukhjamal@yahoo.com", "fj@ex.com"]);
    }

    public function canUpdateIdea(Idea $idea)
    {
        return $this->can("update", $idea);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getuserAvatarAttribute()
    {
        return $this->getAvatar();
    }

    public function setHasNotifications()
    {
        $this->has_notifications = true;
        $this->save();
    }

    public function setHasNoNotifications()
    {
        $this->has_notifications = false;
        $this->save();
    }

    public function addOneMoreNotification()
    {
        $this->numNotification++;
        $this->save();
    }

    public function resetNumberOfNotifications()
    {
        $this->numNotification = 0;
        $this->save();
    }
}
