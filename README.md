# SDK-ESGI-PROJECT 3IW1 : Raimbault Fantin & Maziarz Oliwier
Projet SDK pour l'école ESGI

## Run app
```
cd ./oauth-client && composer install
cd .. && docker compose up
```

## Use it
```php
use App\Sdk\Sdk;

$sdk = new Sdk([
    "facebook" => [
            "appId" => FBCLIENT_ID,
            "appSecret" => FBCLIENT_SECRET,
            "redirectUri" => "https://localhost/success",
            "scope" => [
                "email",
        ]
    ],
    ...
]);

$sdk->getLinks()
$sdk->fetchAccessToken($code, $state);
$sdk->getUser($token);
```

## Create a new provider
Create folder with the name of your provider and file with the name of your provider followed by Sdk in Platforms folder.
```
ex: MyProvider/MyProviderSdk.
```

and extends from PlatformSdk

```php
use App\Sdk\Platforms\AbstractPlatformSdk;
class MyProviderSdk extends AbstractPlatformSdk {...}
```

Create a mapper class inside your provider folder to help you format data

and implements from Mapper

```php
use App\Sdk\Platforms\Mapper;
class MyProviderMapper implements Mapper {...}
```
