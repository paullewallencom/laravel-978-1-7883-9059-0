<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function show(Request $request, Channel $channel){
        $response = [
            'count' => $channel->subscriptionCount(),
            'userSubscribed' => false,
            'canSubscribe' => false
        ];

        if($request->user()){
            $response = array_merge($response, [
                'userSubscribed' => $request->user()->isSubscribedTo($channel),
                'canSubscribe' => !$request->user()->ownsChannel($channel)
            ]);
        }

        return response()->json([
            'data' => $response
        ], 200);
    }

    public function store(Request $request, Channel $channel){
        $this->authorize('subscribe', $channel);
        
        $request->user()->subscriptions()->create([
            'channel_id' => $channel->id
        ]);

        return response()->json(null, 200);
    }

    public function destroy(Request $request, Channel $channel){
        $this->authorize('unsubscribe', $channel);
        
        $request->user()->subscriptions()->where('channel_id', $channel->id)->delete();
        return response()->json(null, 200);
    }
}
