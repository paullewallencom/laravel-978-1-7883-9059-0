<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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

    public function channels(){
        return $this->hasMany(Channel::class);
    }

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }

    public function subscribedChannels(){
        return $this->belongsToMany(Channel::class, 'subscriptions');
    }

    public function isSubscribedTo(Channel $channel){
        return (bool)$this->subscriptions()->where('channel_id', $channel->id)->count();
    }

    public function ownsChannel(Channel $channel){
        return (bool)$this->channels()->where('id', $channel->id)->count();
    }

    public function subscriptionFeed($limit = 10){
        return $this->subscribedChannels()->with(['videos' => function($query) use ($limit){
            $query->visible()->take($limit);
        }])->get()->pluck('videos')->flatten()->sortByDesc('created_at');
    }
}
