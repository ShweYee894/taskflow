<?php

namespace Tests\Feature;

use APP\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_task_can_be_created(): void
    {
        $response = $this->post('/tasks', [
            'title' => 'Prepare Developer Portfolio',
            'description' => 'Build a portfolio using Laravel',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'title' => 'Prepare Developer Portfolio',
            'status' => 'pending',
            'priority' => 'high',
        ]);
    }

    public function test_task_requires_a_title(): void
    {
        $response = $this->post('/tasks', [
            'title' => '',
            'description' => 'A task without a title',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_task_can_be_updated(): void
    {
        $task = Task::create([
            'title' => 'Original Task Title',
            'description' => 'Original description',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $response = $this->put("/tasks/{$task->id}/update", [
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'status' => 'completed',
            'priority' => 'high',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'completed',
            'priority' => 'high',
        ]);
    }

    public function test_task_update_rejects_invalid_status(): void
    {
        $task = Task::create([
            'title' => 'Original Task Title',
            'description' => 'Original description',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        $response = $this->put("/tasks/{$task->id}/update", [
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'status' => 'cancelled',
            'priority' => 'high',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Original Task Title',
            'description' => 'Original description',
            'status' => 'pending',
            'priority' => 'high',
        ]);
    }

    public function test_task_can_be_deleted(): void
    {
        $task = Task::create([
            'title' => 'Task to Delete',
            'description' => 'This task will be removed',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $response = $this->delete("/tasks/{$task->id}");

        $response->assertRedirect();

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_task_rejects_invalid_status(): void
    {
        $response = $this->post('/tasks', [
            'title' => 'Prepare Portfolio',
            'description' => 'Work on portfolio',
            'status' => 'started',
            'priority' => 'high',
            'due_date' => null,
        ]);

        $response->assertSessionHasErrors('status');
    }

    public function test_task_update_rejects_invalid_priority(): void
    {
        $response = $this->post('/tasks', [
            'title' => 'Prepare Portfolio',
            'description' => 'Work on portfolio',
            'status' => 'pending',
            'priority' => 'urgent',
            'due_date' => null,
        ]);

        $response->assertSessionHasErrors('priority');
    }

    public function test_task_rejects_title_longer_than_100_characters(): void
    {
        $response = $this->post('/tasks', [
            'title' => str_repeat('A', 101),
            'description' => 'Work on portfolio',
            'status' => 'pending',
            'priority' => 'medium',
            'due_date' => null,
        ]);

        $response->assertSessionHasErrors('title');
    }
}
