<?php

namespace App\Http\Controllers;

use App\Jobs\UploadVideo;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function index(){
    	return view('upload');
    }

    public function store(Request $request){
    	$channel = $request->user()->channels()->first();
    	$video = $channel->videos()->where('uid', $request->uid)->firstOrFail();

    	$request->file('video')->move(storage_path() ."/uploads", $video->filename);

    	$this->dispatch(new UploadVideo($video, $video->filename));

    	return response()->json(null, 200);
    }
}
