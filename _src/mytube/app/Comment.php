<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
    	'body',
    	'user_id',
    	'parent_id'
    ];

    protected $appends = [
    	'created_at_human'
    ];

    public function video(){
    	return $this->belongsTo(Video::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function replies(){
    	return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    public function scopeLatestFirst($query){
    	return $query->orderBy('created_at', 'desc');
    }

    public function getCreatedAtHumanAttribute(){
    	return $this->created_at->diffForHumans();
    }
}
