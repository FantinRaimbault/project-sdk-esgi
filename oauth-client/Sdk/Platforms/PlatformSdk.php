<?php

namespace App\Sdk\Platforms;

interface PlatformSdk
{
    public function getOauthLink(): string;
    public function fetchAccessToken($code, $state);
    public function callApi(string $endpoint, string $method, string $token, array $params = []);
    public function getUser($token);
}