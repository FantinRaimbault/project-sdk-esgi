<?php

namespace App\Sdk\Http;

class Request
{
    /**
     * Send request
     *
     * @param  mixed $options
     * @return string|false
     */
    public static function send(array $options)
    {
        $context = stream_context_create([
            'http' => $options['http']
        ]);
        $url = HttpBuilder::build([
            "url" => $options["url"],
            "queryParams" => $options['queryParams'] ?? []
        ]);
        return file_get_contents($url, false, $context);
    }
}
