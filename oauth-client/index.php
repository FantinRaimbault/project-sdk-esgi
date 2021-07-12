<?php

/**
 * @author Raimbault Fantin
 * @author Maziarz Oliwier
 * 
 * Esgi Sdk Project
 */

namespace App;

use App\Sdk\Sdk;

require_once './vendor/autoload.php';

// Set your credentials in .env !
const CLIENT_ID = "client_60e45dab622421.74690925";
const CLIENT_SECRET = "eb90190c1295b483b49063f74b7e06b171e56478";

try {

    $sdk = new Sdk([
        "esgi" => [
            "appId" => CLIENT_ID,
            "appSecret" => CLIENT_SECRET,
            "redirectUri" => "https://localhost/success",
            "scope" => [
                "email"
            ]
        ],
    ]);

} catch (\Exception $e) {
    die(var_dump($e));
}
