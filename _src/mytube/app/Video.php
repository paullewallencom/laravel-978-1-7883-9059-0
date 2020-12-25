<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
   	protected $fillable = [
    	'uid',
    	'name',
    	'description',
    	'visibility',
	    'filename',
	    'job_id',
	    'status',
	    'processed',
        'allow_votes',
        'allow_comments'
    ];

    public function getRouteKeyName(){
    	return 'uid';
    }

    public function getThumbnail()
    {
        if (!$this->isProcessed()) {
            return config('mytube.buckets.images') . 'default.jpg';
        }

        return config('mytube.buckets.images') . 'thumbs-' . $this->uid . '-00001.png';
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function views(){
        return $this->hasMany(VideoView::class);
    }

    public function viewCount(){
        return $this->views->count();
    }

    public function votes(){
        return $this->morphMany(Vote::class, 'votable');
    }

    public function upVotes(){
        return $this->votes->where('type', 'up');
    }
    
    public function downVotes(){
        return $this->votes->where('type', 'down');
    }

    public function voteFromUser(User $user){
        return $this->votes()->where('user_id', $user->id);
    }

    public function votesAllowed()
    {
        return (bool) $this->allow_votes;
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function commentsAllowed(){
        return (bool) $this->allow_comments;
    }
}
