<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful user registration.
     *
     * @return void
     */
    public function testSuccessfulUserRegistration()
    {
        $response = $this->postJson('/api/register', [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertExactJson(['Successfully registered!']);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    /**
     * Test user registration with validation errors.
     *
     * @return void
     */
    public function testUserRegistrationValidationErrors()
    {
        $response = $this->postJson('/api/register', [
            'username' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422)
        ->assertJson([
            'detail' => 'The username field is required.',
        ]);
    }

    /**
     * Test successful user login.
     *
     * @return void
     */
    public function testSuccessfulUserLogin()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                 ]);
    }

    /**
     * Test user login with invalid credentials.
     *
     * @return void
     */
    public function testUserLoginWithInvalidCredentials()
    {
        $user = User::create([
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertExactJson(['detail' => 'Invalid credentials']);
    }
}