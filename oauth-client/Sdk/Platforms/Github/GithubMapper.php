<?php

namespace App\Sdk\Platforms\Github;

use App\Sdk\Platforms\Mapper;


class GithubMapper implements Mapper
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
            "firstname" => $user['login'] ?? null,
            "lastname" => $user['login'] ?? null,
            "email" => $user['email']
        ];
    }
}
