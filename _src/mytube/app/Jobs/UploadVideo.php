<?php

namespace App\Jobs;

use File;
use Storage;
use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Video;

class UploadVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filename;
    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video, $filename)
    {
        $this->filename = $filename;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = storage_path() . "/uploads/" . $this->filename;

        if($s3Url = Storage::disk('videosS3')->put($this->filename, fopen($file, 'r+'))){
            File::delete($file);

            $elasticTranscoder = ElasticTranscoderClient::factory([
                'credentials' => [
                    'key' => env('AWS_KEY'),
                    'secret' => env('AWS_SECRET')
                ],
                'region' => 'us-east-1',
                'version' => 'latest'
            ]);

            $job = $elasticTranscoder->createJob([
                'PipelineId' => '1495155938783-j98zvf',
                'Input' => array(
                    'Key' => $this->filename,
                    'FrameRate' => 'auto',
                    'Resolution' => 'auto',
                    'AspectRatio' => 'auto',
                    'Interlaced' => 'auto',
                    'Container' => 'auto',
                ),
                'Outputs' => array(
                    array(
                        'Key' => $this->video->uid . '.mp4',
                        'ThumbnailPattern' => 'thumbs-' . $this->video->uid . '-{count}',
                        'Rotate' => 'auto',
                        'PresetId' => '1351620000001-000010',
                    ),
                ),
            ]);

            $jobInfo = $job->get('Job');

            $this->video->update([
                'job_id' => $jobInfo['Id'],
                'status' => 'Transcoding'
            ]);

        }
    }
}

