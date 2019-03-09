<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use NewTwitchApi;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    const REDIRECT_URI          = '#/home';
    const STREAM_CALLBACK       = 'api/callback';

    protected $twitchAPI;
    protected $streamCallbackURI;
    protected $authRedirectURI;

    /**
     * Instantiates Twitch SDK and other required objects.
     *
     * Controller constructor.
     */
    public function __construct() {
        $this->streamCallbackURI = sprintf('%s/%s', URL::to('/'), self::STREAM_CALLBACK);
        $this->authRedirectURI   = sprintf('%s/%s', URL::to('/'), self::REDIRECT_URI);
        $this->twitchAPI         =
            new NewTwitchApi\NewTwitchApi(new NewTwitchApi\HelixGuzzleClient(env('TWITCH_CLIENT_ID')),
                                          env('TWITCH_CLIENT_ID'), env('TWITCH_SECRET'));
    }
}
