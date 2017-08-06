<?php

namespace Tests\Passport;

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
        $this->assertTrue(false);
    }
}
