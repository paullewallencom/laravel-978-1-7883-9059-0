<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Video;

class HomepageController extends Controller
{
    public function index(){
    	$videos = Video::latestFirst()->paginate(10);

    	return view('homepage', [
    		'videos' => $videos
    	]);
    }
}
