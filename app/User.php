<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favouriteListings()
    {
        return $this->morphedByMany(Listing::class, 'favouriteable')
        ->withPivot(['created_at'])
        ->orderByPivot('created_at', 'desc');
    }

    public function viewedListings()
    {
        return $this->belongsToMany(Listing::class, 'user_listing_views')->withTimestamps()->withPivot(['count', 'id']); //brings back all tables user has viewed/timestamps
    }

    public function listings()
    {
      return $this->hasMany(Listing::class);
    }
}
