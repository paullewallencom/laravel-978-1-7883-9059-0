<?php

namespace App\Http\Controllers;

use App\Video;
use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, Video $video){
        return response()->json([
            'comments' => $this->packageComments($video)
        ]);
    }

    public function store(Request $request, Video $video){
        $this->authorize('comment', $video);
        
        $comment = $video->comments()->create([
            'body' => $request->body,
            'parent_id' => $request->parent_id ? $request->parent_id : null,
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'comment' => $this->transformComment($comment)
        ]);
    }

    public function destroy(Request $request, Video $video, Comment $comment){
        $this->authorize('delete', $comment);
        
        $comment->delete();

        return response()->json(null, 200);
    }

    private function transformChannel($channel){
        return [
            'id' => $channel->id,
            'slug' => $channel->slug,
            'name'  => $channel->name,
            'avatar' => $channel->getAvatarImage()
        ];
    }

    private function transformComment($comment){

        $channel = $this->transformChannel($comment->user->channels()->first());

        return [
            'id' => $comment->id,
            'user_id' => $comment->user_id,
            'body' => $comment->body,
            'created_at_human' => $comment->created_at_human,
            'channel' => $channel,
            'replies' => []
        ];
    }

    private function packageComments(Video $video){

        $comments = $video->comments()
                          ->where('parent_id', null)
                          ->latestFirst()
                          ->get();
   
        $payload = [];

        foreach($comments as $comment){

            $newComment = $this->transformComment($comment);

            $replies = $comment->replies()->latestFirst()->get();

            foreach($replies as $reply){

                $r = $this->transformComment($reply);

                $newComment['replies'][] = $r;

            }

            $payload[] = $newComment;
        }

        return $payload;
    }
}
