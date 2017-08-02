<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(){
        parent::setup();
        Artisan::call('migrate');
        Artisan::call("passport:install");
        Artisan::call("db:seed");
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_login_with_credentials()
    {
        $client = new Client();
        $response = $client->request('POST', env("APP_URL") . '/api/login', [
            'json' => [
                'email' => 'test@example.com',
                'password' => 'test1337'
            ]
        ]);

        $response_array = json_decode((string) $response->getBody(), true);

        $this->assertArraySubset([
            'status_code' => 200,
            'status_text' => "OK",
        ], $response_array);

        $this->assertArrayHasKey("personal_access_token", $response_array);
        $this->assertArrayHasKey("message", $response_array);

        $this->assertInternalType("string", $response_array["message"]);
        $this->assertInternalType("string", $response_array["personal_access_token"]);
    }
}
