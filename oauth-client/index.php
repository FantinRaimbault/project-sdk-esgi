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

const FBCLIENT_ID = "504709260751316";
const FBCLIENT_SECRET = "ee4c830bfca9bc6d519021306eec408b";

const GITHUBCLIENT_ID = "10f9aa180dedc9beee5f";
const GITHUBCLIENT_SECRET = "a585951da4c560eb7cf786b10ebf706c65ddc5ff";
const GITHUB_APP = "esgi-sdk";

try {

    // instanciate Sdk class and pass array config
    // for each provider, appId and appSecret are required !
    $sdk = new Sdk([
        "facebook" => [
            "appId" => FBCLIENT_ID,
            "appSecret" => FBCLIENT_SECRET,
            "redirectUri" => "https://localhost/success",
            "scope" => [
                "email",
            ]
        ],
        "esgi" => [
            "appId" => CLIENT_ID,
            "appSecret" => CLIENT_SECRET,
            "redirectUri" => "https://localhost/success",
            "scope" => [
                "email"
            ]
        ],
        "github" => [
            "appId" => GITHUBCLIENT_ID,
            "appSecret" => GITHUBCLIENT_SECRET,
            "app" => GITHUB_APP, // app is optional
            "scope" => [
                "user:email", // uncheck "Keep my email addresses private" in your github profile to get your email
            ]
        ],
    ]);

    $route = strtok($_SERVER['REQUEST_URI'], '?');
    switch ($route) {
        // Login route
        case '/auth-code':
            echo '<h1>Login with Auth-Code</h1>';
            foreach ($sdk->getLinks() as $platformKey => $link) {
                echo "<a href='$link'>Connect with : $platformKey</a><br/>";
            }
            break;
        // Success route
        case '/success':
            ["code" => $code, "state" => $state] = $_GET;
            $token = $sdk->fetchAccessToken($code, $state);
            $user = $sdk->getUser($token);
            var_dump($user);
            break;
        default:
            echo 'not_found';
            break;
    }
} catch (\Exception $e) {
    die(var_dump($e));
}
