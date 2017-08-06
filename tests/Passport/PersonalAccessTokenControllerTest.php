<?php

namespace Tests\Passport;

use App\Models\User;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;
use Tests\TestCase;

class PersonalAccessTokenControllerTest extends TestCase
{

    protected $tokenRepository;

    public function setUp() {

        parent::setUp();

        $this->tokenRepository = $this->app->make(TokenRepository::class);
    }

    /**
     * Get all of the personal access tokens for the authenticated user.
     *
     * Action: \Laravel\Passport\Http\Controllers\PersonalAccessTokenController@forUser
     * URI: oauth/personal-access-tokens
     * Method: GET|HEAD
     *
     * @test
     */
    public function get_all_personal_access_tokens_for_authenticated_user_successfully()
    {
        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

        $personalAccessTokenResult = $user->createToken("Personal Access Token");
        $accessToken = $personalAccessTokenResult->accessToken; // @type string
        $token = $personalAccessTokenResult->token; // @type \Laravel\Passport\Token

        $user->withAccessToken($token);

        $this->assertInternalType("string", $accessToken);

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $this->assertNotNull($this->tokenRepository->findForUser($token->id, $user->id));

        /*
         * Or use something like
         *
         * Passport::actingAs(
         *      factory(User::class)->create(),
         *      ['create-servers']
         * );
         *
         */

        $response = $this->json('get', '/oauth/personal-access-tokens', [], $headers);

        //dd($response);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'user_id',
                    'client_id',
                    'name',
                    'scopes',
                    'revoked',
                    'created_at',
                    'updated_at',
                    'expires_at',
                    'client' => [
                        'id',
                        'user_id',
                        'name',
                        'redirect',
                        'personal_access_client',
                        'password_client',
                        'revoked',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * Create a new personal access token for the user.
     *
     * Action: \Laravel\Passport\Http\Controllers\PersonalAccessTokenController@store
     * URI: oauth/personal-access-tokens
     * Method: POST
     *
     * @test
     */
    public function create_personal_access_token_for_user_successfully()
    {
        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

        $personalAccessTokenResult = $user->createToken("Personal Access Token");
        $accessToken = $personalAccessTokenResult->accessToken; // @type string
        $token = $personalAccessTokenResult->token; // @type \Laravel\Passport\Token

        $user->withAccessToken($token);

        $this->assertInternalType("string", $accessToken);

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $this->assertNotNull($this->tokenRepository->findForUser($token->id, $user->id));

        // Defining scopes. This should normally be placed into
        // AuthServiceProvider->boot()
        Passport::tokensCan([
            'can-place-orders' => 'Being able to place orders',
            'can-destroy-world' => 'Can destroy the whole f****** world',
        ]);


        $response = $this->json('post', '/oauth/personal-access-tokens', [
            "name" => "Awesome Token",
            "scopes" => [
                'can-place-orders',
                'can-destroy-world'
            ]
        ], $headers);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'accessToken',
                'token' => [
                    'id',
                    'user_id',
                    'client_id',
                    'name',
                    'scopes',
                    'revoked',
                    'created_at',
                    'updated_at',
                    'expires_at'
                ]
            ]);
    }

    /**
     * Revoke the given token.
     *
     * Action: \Laravel\Passport\Http\Controllers\PersonalAccessTokenController@destroy
     * URI: oauth/personal-access-tokens/{token_id}
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

        $personalAccessTokenResult = $user->createToken("Personal Access Token");
        $accessToken = $personalAccessTokenResult->accessToken; // @type string
        $token = $personalAccessTokenResult->token; // @type \Laravel\Passport\Token

        $user->withAccessToken($token);

        $this->assertInternalType("string", $accessToken);

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $tokenFromDB = $this->tokenRepository->findForUser($token->id, $user->id);
        $this->assertNotNull($tokenFromDB);

        $this->assertFalse($this->tokenRepository->isAccessTokenRevoked($tokenFromDB->id));

        $response = $this->json('delete', 'oauth/personal-access-tokens/' . $tokenFromDB->id, [], $headers);

        $response->assertStatus(200);

        $this->assertTrue($this->tokenRepository->isAccessTokenRevoked($tokenFromDB->id));
    }

}
