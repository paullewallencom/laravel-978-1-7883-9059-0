<?php

namespace App\Http\Controllers;

use App\Video;
use App\VideoView;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function store(Request $request, Video $video){
	    if(!$video->canBeAccessed($request->user())){
	        return;
	    }

	    if($request->user()){
	        $lastUserView = $video->views()->latestByUser($request->user())->first();

	        if($this->withinBuffer($lastUserView)){
	            return;
	        }
	    }

	    $lastIpView = $video->views()->latestByIp($request->ip())->first();

	    if($this->withinBuffer($lastIpView)){
	        return;
	    }

	    $video->views()->create([
	        'user_id' => $request->user() ? $request->user()->id : null,
	        'ip_address' => $request->ip()
	    ]);

	    return response()->json(null, 200);
	}

	public function withinBuffer($view){
        return $view && $view->created_at->diffInSeconds(Carbon::now()) < 30;
	}
}
