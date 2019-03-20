<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations,RefreshDatabase;

    protected function authenticate()
    {
        $user = factory(User::class)->create([
            "name"=> "Raster",
            "password"=>bcrypt('secret'),
            "email"=>"qwerty@email.com",
        ]);
        $token = JWTAuth::fromUser($user);
        return $token;
    }

    protected function super_authenticate()
    {
        $user = factory(User::class)->create([
            "name"=> "Raster",
            "password"=>bcrypt('secret'),
            "email"=>"qwerty@email.com",
            "is_artist"=> true,
        ]);
        $token = JWTAuth::fromUser($user);
        return $token;
    }

    public function setUp(): void
    {
        parent::setUp();
        //Artisan::call('db:seed');


    }
}
