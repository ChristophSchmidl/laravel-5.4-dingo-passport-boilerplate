<?php

namespace Tests\Passport;

use App\Models\User;
use Laravel\Passport\Client;
use Laravel\Passport\TokenRepository;
use Tests\TestCase;

class AuthorizedAccessTokenControllerTest extends TestCase
{
    /*
    |--------------------------------------------------------------------------
    | Important Notes
    |--------------------------------------------------------------------------
    |
    | Testing the AuthorizedAccessTokenController is difficult. The controllers'
    | forUser() method is just concerned about tokens which are NOT firstParty.
    | There is a check like:
    | return ! $token->client->firstParty() && ! $token->revoked;
    | FirstParty tokens are checked by Client->firstParty():
    | return $this->personal_access_client || $this->password_client;
    | Revoking the token is not concerned about firstParty though.
    |
    */


    protected $tokenRepository;

    public function setUp() {

        parent::setUp();

        $this->tokenRepository = $this->app->make(TokenRepository::class);
    }

    /**
     * Get all of the authorized tokens for the authenticated user.
     *
     * Action: \Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser
     * URI: oauth/tokens
     * Method: GET|HEAD
     *
     * @test
     */
    public function get_all_access_tokens_for_authenticated_user_successfully()
    {
        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

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

        /*
         * We have to get a valid password grant token in order to
         * get all tokens. We will just use this route now because
         * the whole functionality is encapsulated by the Oauth2 Server
         * which Passport is using in the background. We could also try
         * to generate a Password Grant Type Token manually by using
         * accessTokenRepository->persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
         * and
         * refreshTokenRepository->persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
         */
        $tokenResponse = $this->json('post', '/oauth/token', $payload);

        $tokenResponse->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token'
            ]);

        $accessToken = $tokenResponse->json()['access_token'];

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $response = $this->json('get', '/oauth/tokens', [], $headers);

        $response->assertStatus(200)->assertJson([
        ]);
    }

    /**
     * Revoke the given token.
     *
     * Action: \Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy
     * URI: oauth/tokens/{token_id}
     * Method: DELETE
     *
     * @test
     */
    public function revoke_given_token_successfully()
    {
        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

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

        $tokenResponse = $this->json('post', '/oauth/token', $payload);

        $tokenResponse->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token'
            ]);

        $accessToken = $tokenResponse->json()['access_token'];

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $tokenFromDB = $user->tokens()->first();
        $this->assertNotNull($tokenFromDB);

        $this->assertFalse($this->tokenRepository->isAccessTokenRevoked($tokenFromDB->id));

        $response = $this->json('delete', '/oauth/tokens/' . $tokenFromDB->id, [], $headers);

        $response->assertStatus(200);

        $this->assertTrue($this->tokenRepository->isAccessTokenRevoked($tokenFromDB->id));
    }
}
