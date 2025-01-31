<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting user details.
     *
     * @return void
     */
    public function testGetUserById()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $user->id,
                     'email' => $user->email,
                 ]);
    }

   
    /**
     * Test getting all users.
     *
     * @return void
     */
    public function testGetAllUsers()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        // Test with type 'user_tasks'
        $response = $this->postJson('/api/users', ['type' => 'user_tasks']);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'email',
                         'created_at',
                         'updated_at',
                         'tasks' => [
                             '*' => [
                                 'id',
                                 'title',
                                 'description',
                                 'created_at',
                                 'updated_at',
                             ],
                         ],
                     ],
                 ]);

        // Test with type 'default'
        $response = $this->postJson('/api/users', ['type' => 'default']);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'email', 'created_at', 'updated_at'],
                 ]);
    }


    /**
     * Test changing the user's password.
     *
     * @return void
     */
    public function testChangePassword()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/change-password', [
            'old_password' => 'oldpassword123',
            'new_password' => 'newpassword123', 
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Password changed successfully!']);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /**
     * Test authentication.
     *
     * @return void
     */
    public function testAuth()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/auth');

        $response->assertStatus(200)
                 ->assertExactJson(['Authenticated successfully!']);
    }
}