<?php

namespace Tests\Passport;

use App\Models\User;
use Laravel\Passport\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccessTokenControllerTest extends TestCase
{

    /**
     * Authorize a client to access the user's account.
     *
     * Action: \Laravel\Passport\Http\Controllers\AccessTokenController@issueToken
     * URI: oauth/token
     * Method: POST
     *
     * @test
     */
    public function issue_token_with_email_and_password_successfully()
    {
        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

        /*
         * Get the first Password Grant Client.
         * You could also use the ClientRepository and use
         * clientRepository->find(2) if you know the id beforehand.
         *
         * Or you create a PasswordGrantClient on-the-fly by using
         * clientRepository->createPasswordGrantClient($userId, $name, $redirect)
         */
        $client = Client::where("password_client", true)->first();

        // Password Grant Type
        $payload = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => 'test1337',
            'scope' => ''
        ];

        $response = $this->json('post', '/oauth/token', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token'
            ]);


    }
}
