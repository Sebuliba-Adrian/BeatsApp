<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_register()
    {
        $data = [
            "name"=> "Rastern",
            "password"=>"qwerty123!",
            "c_password"=>"qwerty123!",
            "email"=>"qwertzn@email.com",
            "is_artist"=> false
        ];
        $response = $this->json(
            'POST',
            '/api/register',
                 $data
        );


        $response->assertStatus(201)->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'name',
                'email',
                'is_artist',
                'created_at',
                'updated_at',],
        ]);
        $this->assertDatabaseHas('users', ["name"=> "Rastern","email"=>"qwertzn@email.com", "is_artist"=> false]);
//        $this->assertInstanceOf( User::class, $response);
    }

    public function test_requires_password_email_and_name()
    {
        $data = [
            "name"=> "Rastern",
            "password"=>"qwerty123!",
            "c_password"=>"qwerty123!",
            "email"=>"qwertzn@email.com",
            "is_artist"=> false
        ];
        $this->json('post', '/api/register')
            ->assertStatus(422)
            ->assertJson([
                "message"=> "The given data was invalid.",
                "errors"=>[
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
                    'c_password'=> ['The c password field is required.']
                ]
            ]);
        $this->assertDatabaseMissing('users', $data);
    }

    public function test_require_password_confirmation()
    {
        $payload = [
            "name"=> "Rastern",
            "password"=>"qwerty123!",
            "c_password"=>"qwerty123",
            "email"=>"qwertzn@email.com",
            "is_artist"=> false,
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(422)
            ->assertJson([
                "message"=>"The given data was invalid.",
                "errors"=>[
                    "c_password"=>["The c password and password must match."]
                ]
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = factory(User::class)->create([
            "name"=> "Raster",
            "password"=>bcrypt('secret!'),
            "email"=>"qwerty!@email.com",
            "is_artist"=> false,
        ]);

        $payload = ['email' => 'qwerty@email.com', 'password' => 'secret'];

        $response=$this->json('POST', 'api/login', $payload)
            ->assertStatus(401)->assertJson(['error' => 'Unauthorized']);
    }


    public function test_user_login_successfully()
    {
        $user = factory(User::class)->create([
            "name"=> "Raster",
            "password"=>bcrypt('secret'),
            "email"=>"qwerty@email.com",
            "is_artist"=> false,
        ]);

        $payload = ['email' => 'qwerty@email.com', 'password' => 'secret'];

        $response=$this->json('POST', 'api/login', $payload)
            ->assertStatus(200)->assertJsonStructure(['success', 'access_token', 'expires_in']);
    }


    public function test_authorized_user_can_logout()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST', '/api/logout');
        $response->assertStatus(200)->assertJson([
            "success"=> true,
            "message"=> "Successfully logged out"
        ]);
    }

    public function test_authorized_user_can_view_profile()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET', '/api/profile');
        $response->assertStatus(200)->assertJsonStructure([
            "data"=>[
        "id",
        "name",
        "email",
        "role",
        "created_at",
        "updated_at",
        ]]);
    }
}
