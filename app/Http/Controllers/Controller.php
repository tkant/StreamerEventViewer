<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use NewTwitchApi;
use Illuminate\Support\Facades\Log;

/**
 * Class Controller, base class for having common shared properties and methods
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    const REDIRECT_URI          = '#/home';
    const STREAM_CALLBACK       = 'api/callback';

    /** @var NewTwitchApi\NewTwitchApi $twitchAPI */
    protected $twitchAPI;
    /** @var string $streamCallbackURI */
    protected $streamCallbackURI;
    /** @var string $authRedirectURI */
    protected $authRedirectURI;

    /**
     * Instantiates Twitch SDK and other required objects.
     *
     * Controller constructor.
     */
    public function __construct() {
        $this->streamCallbackURI = sprintf('%s/%s', url('/', [], true), self::STREAM_CALLBACK);
        $this->authRedirectURI   = sprintf('%s/%s', url('/', [], true), self::REDIRECT_URI);
        $this->twitchAPI         = new NewTwitchApi\NewTwitchApi(new NewTwitchApi\HelixGuzzleClient(
            env('TWITCH_CLIENT_ID')), env('TWITCH_CLIENT_ID'), env('TWITCH_SECRET')
        );

        Log::debug(sprintf('StreamCallBackURL: %s, AuthRedirectURI: %s', $this->streamCallbackURI,
                           $this->authRedirectURI));
    }
}
