<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    protected $fillable = [
    	'user_id',
    	'ip_address'
    ];

    public function scopeByUser($query, User $user)
	{
	    return $query->where('user_id', $user->id);
	}

	public function scopelatestByUser($query, User $user)
	{
	    return $query->byUser($user)->orderBy('created_at', 'desc')->take(1);
	}

	public function scopelatestByIp($query, $ip)
	{
	    return $query->where('ip_address', $ip)->orderBy('created_at', 'desc')->take(1);
	}
}
