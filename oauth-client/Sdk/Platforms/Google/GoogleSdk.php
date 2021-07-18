<?php

namespace App\Sdk\Platforms\Google;

use App\Sdk\Http\Request;
use App\Sdk\Http\HttpBuilder;
use App\Sdk\Platforms\FacadeMapper;
use App\Sdk\Platforms\AbstractPlatformSdk;

class GoogleSdk extends AbstractPlatformSdk
{

    const OAUTH_LINK = "https://accounts.google.com/o/oauth2/v2/auth";
    const FETCH_ACCESS_TOKEN_URL = "https://oauth2.googleapis.com/token";
    const API_URL = "https://www.googleapis.com/oauth2/v2";
    const AUTHORIZATION = "Authorization: Bearer";

    /**
     * __construct
     *
     * @param  mixed $settings
     * @return void
     */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
    }

    /**
     * Get Oauth Link
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
                "scope" => join(" ", $this->scope),
                "redirect_uri" => $this->redirectUri,
                "state" => uniqid()
            ]
        ]);
    }

    /**
     * Fetch Access Token
     *
     * @param  mixed $code
     * @param  mixed $state
     * @return array
     */
    public function fetchAccessToken($code, $state)
    {
        $result = Request::send([
            "http" => [
                "method" => "POST",
                "header" => "Accept: application/json",
                "content" => http_build_query([
                    "grant_type" => "authorization_code",
                    "client_id" => $this->appId,
                    "client_secret" => $this->appSecret,
                    "code" => $code,
                    "redirect_uri" => $this->redirectUri,
                ]),
            ],
            "url" => self::FETCH_ACCESS_TOKEN_URL
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
    public function callApi(string $endpoint, string $method, string $token, array $body = [], array $params = []): array
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

    /**
     * Get logged User
     *
     * @param  mixed $token
     * @return array
     */
    public function getUser($token)
    {
        $user = $this->callApi('/userinfo', 'GET', $token);
        $mapper = new FacadeMapper(
            new GoogleMapper()
        );
        return $mapper->mapUser($user);
    }
}
