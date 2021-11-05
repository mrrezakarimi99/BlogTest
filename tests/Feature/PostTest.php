<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tests\TestCase;

class PostTest extends TestCase
{
    /** @test */
    function login_successful(bool $is_even = false)
    {
        $user = User::factory()->create();
        $user->id = 1;
        if ($is_even) {
            $user->id = 2;
        }
        $user->save();
        $data = [
            'email'    => $user->email,
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
    function create_with_odd_user_id_failed()
    {
        $loginResponse = $this->login_successful();
        $token = 'Bearer ' . $loginResponse['data']['token'];

        $data = [
            'title'     => 'title',
            'content'   => 'content',
            'thumbnail' => UploadedFile::fake()->image('avatar.png')
        ];

        $this->post('api/post', $data, ['accept' => 'application/json', 'Authorization' => $token])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function create_with_even_user_id_successfully()
    {
        $loginResponse = $this->login_successful(true);
        $token = 'Bearer ' . $loginResponse['data']['token'];

        $data = [
            'title'     => 'title',
            'content'   => 'content',
            'thumbnail' => UploadedFile::fake()->image('avatar.png')
        ];

        $this->post('api/post',$data, ['accept' => 'application/json', 'Authorization' => $token])
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'content',
                    'user',
                    'thumbnail_link',
                ]
            ]);
    }

    /** @test */
    function create_with_validation_error()
    {
        $loginResponse = $this->login_successful(true);
        $token = 'Bearer ' . $loginResponse['data']['token'];

        $data1 = [
            'title' => 'title'
        ];
        $data2 = [
            'content' => 'content'
        ];
        $data3 = [
            'thumbnail' => UploadedFile::fake()->image('avatar.png')
        ];

        $this->post('api/post', $data1, ['accept' => 'application/json', 'Authorization' => $token])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['content' , 'thumbnail']);

        $this->post('api/post', $data2, ['accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['title' , 'thumbnail']);

        $this->post('api/post', $data3, ['accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['title' , 'content']);

    }
}
