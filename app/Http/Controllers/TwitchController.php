<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class TwitchController extends Controller {
    /**
     * TwitchController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets user-id and subscribe to user events, real-time.
     *
     * @param string $streamerName
     * @param string $accessToken
     *
     * @return array|bool
     */
    public function pubsubSubscribeToStreamer(string $streamerName, string $accessToken) {
        try {
            $userInfo = $this->twitchAPI->getStreamsApi()->getStreamForUsername($streamerName)->getBody()->getContents();
        } catch (GuzzleException $e) {
            Log::critical(sprintf('Unable to get user-info for: %s', $streamerName));

            return false;
        }

        $userInfo = json_decode($userInfo);
        $userID   = 0;
        $message  = '';
        if (!empty($userInfo->data)) {
            $userData = reset($userInfo->data);
            $userID   = $userData->user_id;

            Log::debug('Found user with id: ' . $userID);

            $message = sprintf('%s: %s, %s => %s viewers', $userData->user_name, $userData->title,
                               $userData->type, $userData->viewer_count);
        }

        $this->subscribeToStream($userID, $accessToken);

        return [
            'user_id' => $userID, 'message' => $message
        ];
    }

    /**
     * Subscribe to streamer's stream events
     *
     * @param string $twitchID
     * @param string $bearer
     */
    public function subscribeToStream(string $twitchID, string $bearer) {
        $this->twitchAPI->getWebhooksSubscriptionApi()->subscribeToStream($twitchID, $bearer, $this->streamCallbackURI);
    }
}