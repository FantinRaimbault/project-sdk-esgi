<?php

namespace App\Sdk\SDKFactory;

use App\Sdk\Exceptions\SdkException;
use App\Sdk\Platforms\PlatformSdk;

class SdkFactory
{    
    /**
     * Make provider
     *
     * @param  mixed $platformKey
     * @param  mixed $settings
     * @return PlatformSdk
     */
    public static function make(string $platformKey, array $settings): PlatformSdk
    {
        $platformKeyUcFirst = ucfirst(strtolower($platformKey));
        $pathClass = "\\App\\Sdk\\Platforms\\$platformKeyUcFirst\\$platformKeyUcFirst" . "Sdk";
        if (!class_exists($pathClass)) {
            throw new SdkException("SDK Platform : $platformKey not found !");
        }
        return new $pathClass($settings);
    }
}
