<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_dashboard_loads_successfully(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(200);

        $response->assertSee('My Task Dashboard');
    }

    public function test_search_filters_tasks_by_title(): void
    {
        Task::create([
            'title' => 'Build Portfolio',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        Task::create([
            'title' => 'Learn Laravel',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        $response = $this->get('/home?search=Portfolio');

        $response->assertStatus(200);
        $response->assertSee('Build Portfolio');
        $response->assertDontSee('Learn Laravel');
    }

    public function test_status_filter_shows_matching_tasks(): void
    {
        Task::create([
            'title' => 'Pending Task Example',
            'status' => 'pending',
            'priority' => 'medium',
        ]);

        Task::create([
            'title' => 'Completed Task Example',
            'status' => 'completed',
            'priority' => 'medium',
        ]);

        $response = $this->get('/home?status=completed');

        $response->assertStatus(200);
        $response->assertSee('Completed Task Example');
        $response->assertDontSee('Pending Task Example');
    }

    public function test_priority_filter_shows_matching_tasks(): void
    {
        Task::create([
            'title' => 'High Priority Example',
            'status' => 'pending',
            'priority' => 'high',
        ]);

        Task::create([
            'title' => 'Low Priority Example',
            'status' => 'pending',
            'priority' => 'low',
        ]);

        $response = $this->get('/home?priority=high');

        $response->assertStatus(200);
        $response->assertSee('High Priority Example');
        $response->assertDontSee('Low Priority Example');
    }
}
