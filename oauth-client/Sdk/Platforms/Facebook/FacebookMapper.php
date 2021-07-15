<?php

namespace App\Sdk\Platforms\Facebook;

use App\Sdk\Platforms\Mapper;

class FacebookMapper implements Mapper
{

    public function __construct()
    {
    }
    
    /**
     * Map user
     *
     * @param  mixed $user
     * @return array
     */
    public function mapUser($user): array
    {
        return [
            "firstname" => $user['first_name'] ?? null,
            "lastname" => $user['last_name'] ?? null,
            "email" => $user['email'] ?? null
        ];
    }
}
