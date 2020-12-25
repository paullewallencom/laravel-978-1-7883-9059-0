<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

	use Searchable; 

    protected $fillable = [
		'name',
		'slug',
		'description',
		'cover',
		'avatar'
	];

	public function user(){
		return $this->belongsTo(User::class);
	}

	public function getRouteKeyName(){
		return 'slug';
	}

	public function getCoverImage(){
    	if($this->cover){
            return config('mytube.buckets.images') . "channel/cover/" . $this->cover;
        }

        return config('mytube.buckets.images') . "default-cover.jpg";
    }

    public function getAvatarImage(){
    	if($this->avatar){
            return config('mytube.buckets.images') . "channel/avatar/" . $this->avatar;
        }

        return config('mytube.buckets.images') . "default-avatar.jpg";
    }

	public function videos(){
		return $this->hasMany(Video::class);
	}

	public function subscriptions(){
		return $this->hasMany(Subscription::class);
	}

	public function subscriptionCount(){
		return $this->subscriptions->count();
	}
}
