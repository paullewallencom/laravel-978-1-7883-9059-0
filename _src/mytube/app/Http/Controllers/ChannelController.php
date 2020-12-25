<?php

namespace App\Http\Controllers;

use App\Jobs\UploadChannelCoverImage;
use App\Jobs\UploadProfileImage;

use App\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
  
  public function show(Channel $channel){
    return view('channel.show', [
          'channel' => $channel 
    ]);
  }

  public function edit(Channel $channel){	

    	$this->authorize('edit', $channel);

        return view('channel.settings', [
    		'channel' => $channel
    	]);
 	}

 	public function update(Request $request, Channel $channel){

 		$this->authorize('update', $channel);

        $this->validate($request, [
            'name' => 'required|max:255|unique:channels,name,' . $channel->id,
            'slug' => 'required|max:255|alpha_num|unique:channels,slug,' . $channel->id
        ]);

        if($request->file('cover')){
           $request->file('cover')->move(storage_path() . "/uploads", $fileId = uniqid(true));
           $this->dispatch(new UploadChannelCoverImage($channel, $fileId));
        }

        if($request->file('avatar')){
          $request->file('avatar')->move(storage_path() . "/uploads", $fileId = uniqid(true));
          $this->dispatch(new UploadProfileImage($channel, $fileId));
        }


    	$channel->update([
    		'name' => $request->name,
    		'slug' => $request->slug,
    		'description' => $request->description
    	]);

   		return redirect()->to("/channel/{$channel->slug}/settings");

    }
}
