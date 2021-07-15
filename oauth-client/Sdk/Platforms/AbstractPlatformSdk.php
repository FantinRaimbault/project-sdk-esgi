<?php

namespace App\Sdk\Platforms;

use App\Sdk\Exceptions\SdkException;

abstract class AbstractPlatformSdk implements PlatformSdk
{
    protected string $appId;
    protected string $appSecret;
    protected string $redirectUri;
    protected array $scope;
    
    /**
     * __construct
     *
     * @param  mixed $settings
     * @return void
     */
    public function __construct(array $settings)
    {
        if(!$settings['appId']) {
            throw new SdkException("Required app id");
        }
        if(!$settings['appSecret']) {
            throw new SdkException("Required app secret");
        }
        $this->appId = $settings['appId'];
        $this->appSecret = $settings['appSecret'];
        $this->redirectUri = $settings['redirectUri'] ?? '';
        $this->scope = $settings['scope'] ?? [];
    }
}
