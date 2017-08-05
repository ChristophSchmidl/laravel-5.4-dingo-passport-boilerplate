<?php

namespace Tests\Feature;

use App\Models\User;
use GuzzleHttp\Client;
use Laravel\Passport\Token;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;


    /**
     * Route should return 200 - OK
     * with a PersonalAccessToken
     *
     * @test
     */
    public function login_with_email_and_password_successfully()
    {
        /*
        $client = new Client();
        $response = $client->request('POST', env("APP_URL") . 'api/login', [
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
        */


        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

        $payload = [
            'email' => 'john@example.com',
            'password' => 'test1337'
        ];

        $response = $this->json('post', '/api/login', $payload);

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                'access_token',
            ],
            'meta' => [
                'status_code',
                'status_text',
                'message'
            ],
        ]);

        // Also check if the access_token is present in the database?
    }

    /**
     * Route should return 200 - OK
     * with a PersonalAccessToken
     *
     * @test
     */
    public function logout_successfully()
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
            'Authorization' => 'Bearer ' . $accessToken,
        ];

        $this->assertTrue($this->tokenExistsForUser($token->id, $user->id));


        $response = $this->json('get', '/api/logout', [], $headers);

        //$response->dump();

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
            ],
            'meta' => [
                'status_code',
                'status_text',
                'message'
            ],
        ])->assertJson([
            'data' => [

            ],
            'meta' => [
                'status_code' => 200,
                'status_text' => "OK",
                'message' => trans("auth.logout.success")
            ]
        ]);

        $this->assertFalse($this->tokenExistsForUser($token->id, $user->id));
    }


    /**
     * Route should return 401 - Unauthenticated
     *
     * @test
     */
    public function logout_requires_valid_access_token()
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
            'Authorization' => 'Bearer ' . "wrong",
        ];

        $this->assertTrue($this->tokenExistsForUser($token->id, $user->id));


        $response = $this->json('get', '/api/logout', [], $headers);


        $response->assertStatus(401)->assertJsonStructure([
            'message',
            'status_code'
        ])->assertJson([
            'message' => "Unauthenticated.",
            'status_code' => 401,
        ]);

        $this->assertTrue($this->tokenExistsForUser($token->id, $user->id));
    }


    private function tokenExistsForUser($tokenId, $userId)
    {
        $tokenFromDB = Token::find($tokenId);

        if($tokenFromDB) {
            $userFromToken = $tokenFromDB->user()->first();

            if($userFromToken) {
                if($userFromToken->id == $userId) return true;
            }
            return false;
        }
        return false;
    }
}
