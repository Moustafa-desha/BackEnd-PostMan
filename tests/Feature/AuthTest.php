<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testLoginWithAdminAccount()
    {
        $data = [
            'email' => 'desha@test.com',
            'password' => '123456789',
        ];
       $user = $this->json('post','/api/auth/login',$data);
       $user->assertStatus(200);
       $user->assertJson(['data' => ['role' => 'Admin']]);
    }
}
