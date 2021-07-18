<?php

namespace App\Sdk\Platforms\Google;

use App\Sdk\Platforms\Mapper;


class GoogleMapper implements Mapper
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
    public function mapUser($user)
    {
        return [
            "firstname" => $user['given_name'],
            "lastname" => $user['family_name'],
            "email" => $user['email']
        ];
    }
}
