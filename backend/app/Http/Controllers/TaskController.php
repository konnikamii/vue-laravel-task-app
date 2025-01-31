<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\GetPaginatedTasksRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TaskController extends Controller
{
    /**
     * Display the task's details.
     */
    public function getTaskById(Task $task)
    {
        $this->authorizeTask($task);

        return response()->json($task);
    }

    /**
     * Create a new task.
     */
    public function createTask(CreateTaskRequest $request)
    {
        $user = auth()->user();
        if (! $user) {
            throw new NotFoundResourceException('Task not found!', 404);
        }
        $data = $request->validated();
        $data['owner_id'] = auth()->user()->id;
        $task = Task::create($data);

        return response()->json('Task created successfully!', 201);
    }

    /**
     * Update the task's details.
     */
    public function updateTask(Task $task, UpdateTaskRequest $request)
    {
        $this->authorizeTask($task);
        $data = $request->validated();
        $task->update($data);

        return response()->json('Task updated successfully!');
    }

    /**
     * Delete the task.
     */
    public function deleteTask(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();

        return response()->json('Task deleted successfully!');
    }

    /**
     * Get tasks for the current user with pagination and sorting.
     */
    public function getPaginatedTasks(GetPaginatedTasksRequest $request)
    {
        $user = auth()->user();
        if (! $user) {
            throw new NotFoundResourceException('User not found!', 404);
        }

        $data = $request->validated();
        $tasks = Task::where('owner_id', $user->id)
            ->orderBy($data['sort_by'], $data['sort_type'])
            ->paginate($data['page_size'], ['*'], 'page', $data['page']);

        return response()->json($tasks);
    }

    /**
     * Authorize the task.
     *
     * @return void
     *
     * @throws \Symfony\Component\Translation\Exception\NotFoundResourceException
     */
    protected function authorizeTask(Task $task)
    {
        $user = auth()->user();
        if (! $user || $task->owner_id !== $user->id) {
            throw new NotFoundResourceException('Task not found!', 404);
        }
    }
}
