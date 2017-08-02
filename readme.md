<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel 5.4 Dingo Boilerplate

This repository serves as a base for implementing RESTful APIs with <a href="https://github.com/laravel/framework">Laravel</a> and <a href="https://github.com/dingo/api">Dingo</a>. The latest release of Dingo, nameley <a href="https://github.com/dingo/api/releases/tag/v1.0.0-beta8">v1.0.0-beta8</a> supports Laravel 5.4. 

### What has been done so far?

- [x] Install the latest Dingo version (v1.0.0-beta8) which is compatible with Laravel 5.4
- [x] Put ```Dingo\Api\Provider\LaravelServiceProvider::class``` into the ```providers``` array of ```config/app.php```
- [x] Run ```php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"```
- [x] Make Dingo Facades available: ```'DingoApi' => Dingo\Api\Facade\API::class```, ```'DingoRoute' => Dingo\Api\Facade\Route::class```
- [x] Update ```.env.example``` and insert minimum amount of ```API_*``` constants to make Dingo work
- [x] Install <a href="https://github.com/barryvdh/laravel-cors">CORS Middleware</a>
- [x] Make CORS available to all routes. You can change that behaviour by updating ```app/Http/Kernel.php``` and put ```\Barryvdh\Cors\HandleCors::class``` into your ```middlewareGroups``` instead of ```middleware```
- [x] Make CORS configuration available as ```config/cors.php```

### How do I use it?

- Clone the repo
- Copy ```.env.example``` to ```.env``` and alter it to your preferences
- Run ```composer install```
- See if it works by visiting ```<APP_URL>/api/test```