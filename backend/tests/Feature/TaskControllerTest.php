<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting task details.
     *
     * @return void
     */
    public function testGetTaskById()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['owner_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->getJson("/api/task/{$task->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $task->id,
                     'title' => $task->title,
                     'description' => $task->description,
                 ]);
    }

    /**
     * Test creating a task.
     *
     * @return void
     */
    public function testCreateTask()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/task', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            "completed" => true,
            "due_date" => "2024-12-12" 
        ]);

        $response->assertStatus(201)
                 ->assertExactJson(['Task created successfully!']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            "completed" => true,
            "due_date" => "2024-12-12",
            'owner_id' => $user->id,
        ]);
    }

    /**
     * Test updating a task.
     *
     * @return void
     */
    public function testUpdateTask()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['owner_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->putJson("/api/task/{$task->id}", [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200)
                 ->assertExactJson(['Task updated successfully!']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'description' => 'Updated Description',
        ]);
    }

    /**
     * Test deleting a task.
     *
     * @return void
     */
    public function testDeleteTask()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['owner_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->deleteJson("/api/task/{$task->id}");

        $response->assertStatus(200)
                 ->assertExactJson(['Task deleted successfully!']);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test getting paginated tasks.
     *
     * @return void
     */
    public function testGetPaginatedTasks()
    {
        $user = User::factory()->create();
        Task::factory()->count(15)->create(['owner_id' => $user->id]);

        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/tasks', [
            'sort_by' => 'created_at',
            'sort_type' => 'desc',
            'page_size' => 10,
            'page' => 1,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'description', 'created_at', 'updated_at'],
                     ],
                     'total', 
                 ]);
    }
}