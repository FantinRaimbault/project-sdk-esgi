<?php

namespace App\Sdk\Platforms\Esgi;

use App\Sdk\Http\Request;
use App\Sdk\Http\HttpBuilder;
use App\Sdk\Platforms\FacadeMapper;
use App\Sdk\Platforms\AbstractPlatformSdk;

class EsgiSdk extends AbstractPlatformSdk
{

    const OAUTH_LINK = "http://localhost:8081/auth";
    const FETCH_ACCESS_TOKEN_URL = "http://oauth-server:8081/token";
    const API_URL = "http://oauth-server:8081";
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
                "grant_type" => "authorization_code",
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

    public function getUser($token)
    {
        $user = $this->callApi('/api', 'GET', $token);
        $mapper = new FacadeMapper(
            new EsgiMapper()
        );
        return $mapper->mapUser($user);
    }
}
