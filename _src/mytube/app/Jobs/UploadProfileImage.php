<?php

namespace App\Jobs;

use Image;
use Storage;
use File;
use App\Channel; 


use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadProfileImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $channel;
    public $fileId;

    public function __construct(Channel $channel, $fileId)
    {
        $this->channel = $channel;
        $this->fileId = $fileId;
    }

    public function handle()
    {
        $path = storage_path() . "/uploads/" . $this->fileId;
        $filename = $this->fileId . ".jpg";

        Image::make($path)->encode('jpg')->fit(40, 40, function($c){
            $c->upsize();
        })->save($path);

        if(Storage::disk('imagesS3')->put('channel/avatar/' . $filename, fopen($path, 'r+'), 'public')){
            File::delete($path);
        }


        $this->channel->update([
            'avatar' => $filename
        ]);
    }

}
