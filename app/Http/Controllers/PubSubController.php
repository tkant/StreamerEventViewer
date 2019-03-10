<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;
use Pusher\PusherException;

/**
 * Callback handlers from twitch to this application
 *
 * Class PubSubController
 * @package App\Http\Controllers
 */
class PubSubController extends Controller {
    const CHALLENGE       = 'hub_challenge';
    const PUSHER_AUTH_KEY = 'c66297d877b979b6d1f5';
    const PUSHER_SECRET   = 'e9196fb772117b9cc01a';
    const PUSHER_APP_ID   = 731624;

    private $pusher = null;

    /**
     * @param Request $request
     *
     * @return bool
     * @throws \HttpRequestException
     */
    public function eventCallback(Request $request) {
        Log::debug('Getting message from twitch..');

        switch ($request->method()) {
            case Request::METHOD_GET:
                $params = $request->query();
                if (isset($params[self::CHALLENGE])) {
                    return $params[self::CHALLENGE];
                }

                break;

            case Request::METHOD_POST:
                if (!empty($request->json()->get('data'))) {
                    foreach ($request->json()->get('data') as $k => $v) {
                        $message = sprintf('%s: %s, %s => %s viewers', $v['user_name'], $v['title'], $v['type'],
                                           $v['viewer_count']);
                        // Submit message to channel by user_name of streamer
                        $this->sendToPusher($v['user_name'], $message);
                    }
                }

                break;

            default:
                throw new \HttpRequestException(sprintf('Request method %s not supported!', $request->method()));
        }

        return response()->json(['message' => 'Messages published!'], 200);
    }

    /**
     * @param string $channel
     * @param string $message
     */
    protected function sendToPusher(string $channel, string $message) {
        $options = array(
            'cluster' => 'ap2',
            'useTLS'  => true
        );

        $data['message'] = $message;

        try {
            if (is_null($this->pusher)) {
                $this->pusher = new Pusher(
                    self::PUSHER_AUTH_KEY,
                    self::PUSHER_SECRET,
                    self::PUSHER_APP_ID,
                    $options
                );
            }

            $this->pusher->trigger($channel, 'stream_changed', $data);
        } catch (PusherException $e) {
            Log::critical('Unable post message to pusher: ' . $e->getMessage());
        }
    }
}