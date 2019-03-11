<?php

namespace App\Http\Controllers;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller {
    const TWITCH_BASE_OAUTH_URL = 'https://id.twitch.tv/oauth2';
    const TWITCH_SCOPE          = 'user:edit+viewing_activity_read+openid+channel:read:subscriptions+bits:read+channel_subscriptions+channel_read';
    const RESPONSE_TYPE         = 'token';

    /**
     * LoginController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Returns authentication and authorization URL of twitch
     *
     * @return string
     */
    public function getAuthURL() {
        return $this->twitchAPI->getOauthApi()->getAuthUrl(urlencode($this->authRedirectURI),
                                                           self::RESPONSE_TYPE,
                                                           self::TWITCH_SCOPE);
    }
}