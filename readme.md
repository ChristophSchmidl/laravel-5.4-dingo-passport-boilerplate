<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel 5.4 Dingo Passport Boilerplate

This repository serves as a base for implementing RESTful APIs with <a href="https://github.com/laravel/framework">Laravel</a>, <a href="https://laravel.com/docs/5.4/passport">Laravel Passport</a> and <a href="https://github.com/dingo/api">Dingo</a>. The latest release of Dingo, nameley <a href="https://github.com/dingo/api/releases/tag/v1.0.0-beta8">v1.0.0-beta8</a> supports Laravel 5.4.

### Laravel Passport - Notes

* In order to see which migration files Passport is actually using, ```Passport::ignoreMigrations()``` has been put into the ```register```method of ```AppServiceProvider```. Running ```php artisan vendor:publish --tag=passport-migrations``` puts the default Passport migrations into ```database/migrations``` folder.
* ```creae_oauth_auth_codes_table.php```
	* Structure:
		```
        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->integer('user_id');
            $table->integer('client_id');
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });
		```
	* Purpose: test
	* Associated Model: ```Laravel\Passport\AuthCode```
		* Methods:
			```
		    /**
		     * Get the client that owns the authentication code.
		     *
		     * @return \Illuminate\Database\Eloquent\Relations\HasMany
		     */
		    public function client();
			```

* ```create_oauth_access_tokens_table.php```
	* Structure:
		```
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->integer('user_id')->index()->nullable();
            $table->integer('client_id');
            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();
        });
		```
	* Purpose: test
    * Associated Model: ```Laravel\Passport\Token```
        * Methods:
            ```
            /**
             * Get the client that the token belongs to.
             *
             * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
             */
            public function client();

            /**
             * Get the user that the token belongs to.
             *
             * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
             */
            public function user();

            /**
             * Determine if the token has a given scope.
             *
             * @param  string  $scope
             * @return bool
             */
            public function can($scope);

            /**
             * Determine if the token is missing a given scope.
             *
             * @param  string  $scope
             * @return bool
             */
            public function cant($scope);

            /**
             * Revoke the token instance.
             *
             * @return void
             */
            public function revoke();

            /**
             * Determine if the token is a transient JWT token.
             *
             * @return bool
             */
            public function transient();
            ```

* ```create_oauth_refresh_tokens_table.php```
	* Structure:
		```
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('access_token_id', 100)->index();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });
		```
	* Purpose: test
    * Associated Model: test

* ```create_oauth_clients_table.php```
	* Structure:
		```
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->nullable();
            $table->string('name');
            $table->string('secret', 100);
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });
		```
	* Purpose: test
    * Associated Model: test

* ```create_oauth_personal_access_clients_table.php```
	* Structure:
		```
        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->index();
            $table->timestamps();
        });
		```
	* Purpose: test
    * Associated Model: test




### What has been done so far?

- [x] Install the latest Dingo version (v1.0.0-beta8) which is compatible with Laravel 5.4
- [x] Put ```Dingo\Api\Provider\LaravelServiceProvider::class``` into the ```providers``` array of ```config/app.php```
- [x] Run ```php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"```
- [x] Make Dingo Facades available: ```'DingoApi' => Dingo\Api\Facade\API::class```, ```'DingoRoute' => Dingo\Api\Facade\Route::class```
- [x] Update ```.env.example``` and insert minimum amount of ```API_*``` constants to make Dingo work
- [x] Install <a href="https://github.com/barryvdh/laravel-cors">CORS Middleware</a>
- [x] Make CORS available to all routes. You can change that behaviour by updating ```app/Http/Kernel.php``` and put ```\Barryvdh\Cors\HandleCors::class``` into your ```middlewareGroups``` instead of ```middleware```
- [x] Make CORS configuration available as ```config/cors.php```
- [x] Moved the User-model into namespace ```App\Models``` and adjusted all config files so everything works as before.
- [x] Install Passport via ``composer require laravel/passport```
- [x] Register ```PassportServiceProvider``` by adding ```Laravel\Passport\PassportServiceProvider::class``` to the ```providers`` array of ```config/app.php```
- [x] In order to see which migration files Passport is actually using, ```Passport::ignoreMigrations()``` has been put into the ```register```method of ```AppServiceProvider```. Running ```php artisan vendor:publish --tag=passport-migrations``` puts the default Passport migrations into ```database/migrations``` folder.
- [x] Run ```php artisan passport:install``` This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens.
- [x] Added ```Laravel\Passport\HasApiTokens``` to ```App\Models\User```
- [x] In order to get some default routes by Passport, ```Pasport::routes``` method has been put into the ```boot``` method of ```AuthServiceProvider```. You can delete this method and create your own routes if you wish to do so.
- [x] In the ```config/auth.php``` configuration file, the driver option of the api authentication guard to has been set to ``passport``. This will instruct your application to use Passport's TokenGuard when authenticating incomng API requests.
- [x] Added custom ```DingoPassportServiceProvider```

### How do I use it?

- Clone the repo
- Copy ```.env.example``` to ```.env``` and alter it to your preferences
- Run ```composer install```