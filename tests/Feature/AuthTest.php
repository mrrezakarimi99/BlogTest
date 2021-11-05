<?php

namespace Tests\Feature;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /** @test */
    function login_successful()
    {
        $user = User::factory()->create();

        $data = [
            'password' => 'password'
        ];

        $result = $this->post('api/login', $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'token',
                    'token_type'
                ]
            ]);

        return $result->json();
    }

    /** @test */
    function login_with_validation_error()
    {
        $user = User::factory()->create();

        $data1 = [
            'password' => 'password'
        ];

        $data2 = [
            'email' => $user->email,
        ];

        $this->post('api/login', $data1,['accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['email']);

        $this->post('api/login', $data2, ['accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['password']);

    }

    /** @test */
    function login_bad_credential_error()
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'wrongPass',
        ];

        $this->post('api/login', $data ,['accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSimilarJson([
                'message' => 'invalid info!!'
            ]);

    }

    /** @test */
    function logout_successful()
    {
        $loginResponse = $this->login_successful();

        $token = 'Bearer ' . $loginResponse['data']['token'];

        $this->delete('api/logout',[], ['accept' => 'application/json', 'Authorization' => $token])
            ->assertStatus(Response::HTTP_OK)
            ->assertSimilarJson([
                'message' => 'log out success',
            ]);
    }

    /** @test */
    function logout_use_invalid_token()
    {
        $loginResponse = $this->login_successful();

        $token = 'Bearer invalid';

        $this->delete('api/logout',[], ['accept' => 'application/json', 'Authorization' => $token])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSimilarJson(['message' => 'Unauthenticated.']);
    }
}
