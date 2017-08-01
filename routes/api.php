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
    $api->get('users', function() {
    	return "Hello World";
    });
});

// Laravel's native API thingy
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


