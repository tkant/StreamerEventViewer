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
     * @return int
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
        if (!empty($userInfo->data)) {
            Log::debug('Found user with id: ' . reset($userInfo->data)->user_id);

            $userID = reset($userInfo->data)->game_id;
        }

        $this->subscribeToStream($userID, $accessToken);

        return $userID;
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