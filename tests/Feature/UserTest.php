<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected $tenancy = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_user()
    {
        // $user = User::first();
        $response = $this->get('/users');
        $response->assertStatus(200);
    }

    public function test_add_user()
    {
        $user = User::first();
        
        $response = $this->get('/users');

    }
}
