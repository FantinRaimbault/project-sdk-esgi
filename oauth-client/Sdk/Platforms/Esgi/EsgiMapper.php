<?php

namespace App\Sdk\Platforms\Esgi;

use App\Sdk\Platforms\Mapper;


class EsgiMapper implements Mapper
{

    public function __construct()
    {
    }
    
    /**
     * Map user
     *
     * @param  mixed $user
     * @return void
     */
    public function mapUser($user): array
    {
        return [
            "firstname" => $user['firstname'] ?? null,
            "lastname" => $user['lastname'] ?? null,
            "email" => $user['email'] ?? null
        ];
    }
}
