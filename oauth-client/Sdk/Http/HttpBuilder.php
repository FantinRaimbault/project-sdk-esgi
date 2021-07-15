<?php

namespace App\Sdk\Http;

class HttpBuilder
{

    /**
     * Build url
     *
     * @param  mixed $params
     * @return string
     */
    public static function  build($params): string
    {
        ["url" => $url, "queryParams" => $queryParams] = $params;
        return "$url?" . http_build_query($queryParams);
    }
}
