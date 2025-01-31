<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the fillable attributes.
     *
     * @return void
     */
    public function testFillableAttributes()
    {
        $user = new User();

        $this->assertEquals([
            'username',
            'email',
            'password',
        ], $user->getFillable());
    }

    /**
     * Test the hidden attributes.
     *
     * @return void
     */
    public function testHiddenAttributes()
    {
        $user = new User();

        $this->assertEquals([
            'password',
            'remember_token',
        ], $user->getHidden());
    }

    /**
     * Test the casts attributes.
     *
     * @return void
     */
    public function testCastsAttributes()
    {
        $user = new User();

        $this->assertEquals([
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'id' => 'int',
        ], $user->getCasts());
    }

    /**
     * Test the tasks relationship.
     *
     * @return void
     */
    public function testTasksRelationship()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['owner_id' => $user->id]);

        $this->assertInstanceOf(Task::class, $user->tasks->first());
        $this->assertEquals($task->id, $user->tasks->first()->id);
    }

    /**
     * Test the getJWTIdentifier method.
     *
     * @return void
     */
    public function testGetJWTIdentifier()
    {
        $user = User::factory()->create();

        $this->assertEquals($user->getKey(), $user->getJWTIdentifier());
    }

    /**
     * Test the getJWTCustomClaims method.
     *
     * @return void
     */
    public function testGetJWTCustomClaims()
    {
        $user = new User();

        $this->assertEquals([], $user->getJWTCustomClaims());
    }
}