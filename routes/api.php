<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Create Dingo Router
$api = app('Dingo\Api\Routing\Router');

// Create a Dingo Version Group
$api->version('v1', function ($api) {

    $api->get('test', function() {
    	return "Hello World";
    });

    $api->post("login", 'App\Http\Controllers\Api\V1\AuthController@issuePersonalAccessToken');

    // Protected routes
    $api->group(['middleware' => 'auth:api'], function ($api) {
        $api->get('profile', 'App\Http\Controllers\Api\V1\AuthController@profile');
        $api->get('logout', 'App\Http\Controllers\Api\V1\AuthController@deletePersonalAccessToken');
    });

});

// Laravel's native API thingy
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

