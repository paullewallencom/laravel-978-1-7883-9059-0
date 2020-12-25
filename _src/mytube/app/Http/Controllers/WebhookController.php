<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Aws\Sns\Exception\InvalidSnsMessageException;
use App\Video;

class WebhookController extends Controller
{
    public function index(){
    	$message = Message::fromRawPostData();
		$validator = new MessageValidator();


		try {
		   $validator->validate($message);
		} catch (InvalidSnsMessageException $e) {
		   // Pretend we're not here if the message is invalid.
		   http_response_code(404);
		   error_log('SNS Message Validation Error: ' . $e->getMessage());
		   die();
		}

		// Check the type of the message and handle the subscription.
		if ($message['Type'] === 'SubscriptionConfirmation') {
		   // Confirm the subscription by sending a GET request to the SubscribeURL
		   file_get_contents($message['SubscribeURL']);
		}

		if ($message['Type'] === 'Notification') {
		   
			$payload = json_decode($message['Message']);

			if($payload->state == "COMPLETED"){

				$video = Video::where('job_id', $payload->jobId)->firstOrFail();

				$video->update([
					'processed' => true,
					'status' => "Completed"
				]);

			}

		}

    }
}
