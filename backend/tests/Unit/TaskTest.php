<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the fillable attributes.
     *
     * @return void
     */
    public function testFillableAttributes()
    {
        $task = new Task();

        $this->assertEquals([
            'title',
            'description',
            'completed',
            'due_date',
            'owner_id',
        ], $task->getFillable());
    }

    /**
     * Test the casts attributes.
     *
     * @return void
     */
    public function testCastsAttributes()
    {
        $task = new Task();

        $this->assertEquals([
            'completed' => 'boolean',
            'owner_id' => 'integer',
            'id' => 'int',
        ], $task->getCasts());
    }

    /**
     * Test the owner relationship.
     *
     * @return void
     */
    public function testOwnerRelationship()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['owner_id' => $user->id]);

        $this->assertInstanceOf(User::class, $task->owner);
        $this->assertEquals($user->id, $task->owner->id);
    }
}