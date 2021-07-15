<?php

namespace App\Sdk\Platforms\Facebook;

use App\Sdk\Http\Request;
use App\Sdk\Http\HttpBuilder;
use App\Sdk\Platforms\FacadeMapper;
use App\Sdk\Platforms\AbstractPlatformSdk;
use App\Sdk\Platforms\Facebook\FacebookMapper;

class FacebookSdk extends AbstractPlatformSdk
{

    const OAUTH_LINK = "https://facebook.com/v2.10/dialog/oauth";
    const FETCH_ACCESS_TOKEN_URL = "https://graph.facebook.com/oauth/access_token";
    const API_URL = "https://graph.facebook.com";
    const AUTHORIZATION = "Authorization: Bearer";

    public function __construct(array $settings)
    {
        parent::__construct($settings);
    }
    
    /**
     * Get Oauth link
     *
     * @return string
     */
    public function getOauthLink(): string
    {
        return HttpBuilder::build([
            "url" => self::OAUTH_LINK,
            "queryParams" => [
                "response_type" => "code",
                "client_id" => $this->appId,
                "redirect_uri" => $this->redirectUri,
                "scope" => join(",", $this->scope),
                "state" => uniqid(),
            ]
        ]);
    }
    
    /**
     * Fetch access token
     *
     * @param  mixed $code
     * @param  mixed $state
     * @return void
     */
    public function fetchAccessToken($code, $state)
    {
        $result = Request::send([
            "http" => [
                "method" => "GET",
                "header" => "Accept: application/json",
            ],
            "url" => self::FETCH_ACCESS_TOKEN_URL,
            "queryParams" => [
                "redirect_uri" => $this->redirectUri,
                "client_id" => $this->appId,
                "client_secret" => $this->appSecret,
                "code" => $code,
                "state" => $state
            ]
        ]);
        return json_decode($result, true)["access_token"];
    }

    /**
     * Send a call to Api provider
     *
     * @param  mixed $endpoint
     * @param  mixed $method
     * @param  mixed $token
     * @param  mixed $body
     * @param  mixed $params
     * @return array
     */
    public function callApi(string $endpoint, string $method, string $token, array $body = [], array $params = [])
    {
        $result = Request::send([
            "http" => [
                "method" => $method,
                "header" => self::AUTHORIZATION . " " . $token,
                "content" => http_build_query($body),
            ],
            "queryParams" => $params,
            "url" => self::API_URL . "$endpoint",
        ]);
        return json_decode($result, true);
    }

    function getUser($token)
    {
        $user = $this->callApi('/me', 'GET', $token, [], [
            "fields" => "first_name,last_name,email,birthday"
        ]);
        $mapper = new FacadeMapper(
            new FacebookMapper()
        );
        return $mapper->mapUser($user);
    }
}
