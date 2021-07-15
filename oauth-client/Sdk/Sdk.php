<?php

namespace App\Sdk;

use App\Sdk\Exceptions\SdkException;
use App\Sdk\Platforms\PlatformSdk;
use App\Sdk\SDKFactory\SdkFactory;

class Sdk
{

    private array $providers;
    private string $lastPlatformKey;

    /**
     * __construct
     *
     * @param  mixed $platforms
     * @return void
     */
    public function __construct(array $platforms)
    {
        foreach ($platforms as $platformKey => $settings) {
            $this->providers[$platformKey] = $this->createProvider($platformKey, $settings);
        }
    }


    /**
     * Get providers links
     *
     * @return array
     */
    public function getLinks(): array
    {
        $links = [];
        foreach ($this->providers as $platformKey => $provider) {
            $links[$platformKey] = $provider->getOauthLink();
        }
        return $links;
    }

    /**
     * Fetch access token
     * 
     * @param string $code
     * @param string $state
     * 
     * @return string
     */
    public function fetchAccessToken($code, $state): string
    {
        $token = null;
        foreach ($this->providers as $platformKey => $provider) {
            $token = $provider->fetchAccessToken($code, $state);
            if ($token) {
                $this->lastPlatformKey = $platformKey;
                return $token;
            }
        }
        throw new SdkException('can\'t fetch accessToken, verify your credentials app');
    }

    /**
     * Get logged User
     * 
     * @param string $token
     */
    public function getUser($token): array
    {
        $provider = $this->getLastProvider();
        return $provider->getUser($token);
    }

    /**
     * Get last platform used to get token 
     * 
     * @return PlatformSdk 
     */
    private function getLastProvider(): PlatformSdk
    {
        if (!$this->lastPlatformKey) {
            throw new SdkException('No latest provider');
        }
        return $this->providers[$this->lastPlatformKey];
    }

    /**
     * Create a provider
     * 
     * @param string $platformKey
     * @param array $settings
     * 
     * @return PlatformSdk
     */
    private function createProvider(string $platformKey, array $settings): PlatformSdk
    {
        return SdkFactory::make($platformKey, $settings);
    }
}
