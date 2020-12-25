<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request){

        $videos = $request->user()->videos()->latestFirst()->paginate(10);

        return view('videos.index', [
            'videos' => $videos
        ]);

    }

    public function show(Video $video){
        return view('video.show', [
            'video' => $video
        ]);
    }

    public function store(Request $request){
 
        $this->validate($request, [
             'name' => 'required|max:255'
        ]);


        $uid = uniqid(true);

        $channel = $request->user()->channels()->first();

        $video = $channel->videos()->create([
            'uid' => $uid,
            'name' => $request->name,
            'description' => $request->description,
            'visibility' => $request->visibility,
            'filename' => "{$uid}.{$request->extension}"
        ]);

        return response()->json([
            'data' => [
				'id' => $video->id,
                'uid' => $uid
            ]
        ]);
    }

    public function edit(Video $video){

        $this->authorize('edit', $video);

        return view('videos.edit', [
            'video' => $video
        ]);

    }

    public function update(Request $request, Video $video){
        $this->authorize('update', $video);
        
        $this->validate($request, [
             'name' => 'required|max:255'
        ]);

        $video->update([
            'name' => $request->name,
            'description' => $request->description,
			'visibility' => $request->visibility,
            'allow_votes' => $request->has('allow_votes'),
            'allow_comments' => $request->has('allow_comments'),
        ]);


        if($request->ajax()){
            return response()->json(null, 200);
        }

        return redirect()->back();
 
    }

    public function delete(Video $video){

        $this->authorize('delete', $video);

        $video->delete();

        return redirect()->back();


    }
}
