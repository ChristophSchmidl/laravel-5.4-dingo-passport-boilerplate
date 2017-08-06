<?php

namespace Tests\Passport;

use Tests\TestCase;

class AuthorizedAccessTokenControllerTest extends TestCase
{

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
        $this->assertTrue(false);
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
        $this->assertTrue(false);
    }
}
