<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Route should return 201 - Created
     *
     * @test
     */
    public function registers_successfully()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'test1337',
            'password_confirmation' => 'test1337',
        ];

        $response = $this->json('post', '/api/register', $payload);

        //$response->dump();

        $response->assertStatus(201)->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'meta' => [
                    'status_code',
                    'status_text',
                    'message'
                ]
            ])
        ->assertJson([
            'data' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'meta' => [
                'status_code' => 201,
                'status_text' => 'Created',
                'message' => trans("auth.register.success")
            ]
        ]);
    }

    /**
     * Route should return 422 - Unprocessable Entity
     *
     * @test
     */
    public function requires_password_email_and_name()
    {
        $response = $this->json('post', '/api/register');

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
                'status_code' => 422
            ])->assertJsonStructure([
                'message',
                'errors',
                'status_code'
            ]);
    }

    /**
     * Route should return 422 - Unprocessable Entity
     * @test
     */
    public function requires_password_confirmation()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'test1337',
        ];

        $response = $this->json('post', '/api/register', $payload);

        //$response->dump();

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'password' => ['The password confirmation does not match.'],
                ]
            ])->assertJsonStructure([
                'message',
                'errors',
                'status_code'
            ]);
    }

    /**
     * Route should return 422 - Unprocessable Entity
     * @test
     */
    public function requires_unique_email()
    {
        $user = User::create([
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => bcrypt("test1337")
        ]);

        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'test1337',
            'password_confirmation' => 'test1337',
        ];

        $response = $this->json('post', '/api/register', $payload);

        //$response->dump();

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ]
            ])->assertJsonStructure([
                'message',
                'errors',
                'status_code'
            ]);
    }

}
