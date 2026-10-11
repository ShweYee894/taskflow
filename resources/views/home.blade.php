@extends('layouts.app')

@section('title', 'Home | TaskFlow')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="fw-bold mb-1">My Task Dashboard</h1>
        <p class="text-muted mb-0">
            Organize your work and track your progress.
        </p>
    </div>

    <a href="{{route('tasks.create')}}" class="btn btn-primary mb-4">
        + Create Task
    </a>

</div>

@if(session('success'))
<div class="alert alert-success">
    {{session('success')}}
</div>
@endif

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    Total Tasks
                </h5>
                <h2>
                    {{$totalTasks}}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    Completed Tasks
                </h5>
                <h2>
                    {{$completedTasks}}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    Pending Tasks
                </h5>
                <h2>
                    {{$pendingTasks}}
                </h2>
            </div>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Overall Progress</h5>
            <span class="fw-bold">
                {{$completionPercentage}} %
            </span>
        </div>

        <div class="progress" role="progressbar" aria-label="Task completion progress" aria-valuenow="{{$completionPercentage}}" aria-valuemin="0" aria-valuemax="100" style="height: 12px;">
            <div class="progress-bar bg-success" style="width: {{ $completionPercentage }}%;"></div>
        </div>

        <p class="text-muted small mt-2 mb-0">
            {{$completedTasks}} of {{$totalTasks}} tasks completed
        </p>
    </div>
</div>
</div>
<form action="{{route('home')}}" method="GET" class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <div class="row g-3">
            <!-- Search -->
            <div class="col-md-4">
                <label for="search" class="form-label">
                    Search tasks
                </label>

                <input type="text" name="search" id="search" class="form-control" placeholder="Enter task title..." value="{{request('search')}}">
            </div>

            <!-- Status filter -->
            <div class="col-md-3">
                <label for="status" class="form-label">
                    Status
                </label>

                <select name="status" id="status" class="form-select">
                    <option value="">All statuses</option>

                    <option value="pending"
                        @selected(request('status')==='pending' )>
                        Pending
                    </option>

                    <option value="pending"
                        @selected(request('status')==='in_progress' )>
                        In Progress
                    </option>

                    <option value="pending"
                        @selected(request('status')==='completed' )>
                        Completed
                    </option>
                </select>
            </div>

            <!-- Priority filter -->
            <div class="col-md-3">
                <label for="priority" class="form-label">
                    Priority
                </label>

                <select name="priority" id="priority" class="form-select">
                    <option value="">All priorities</option>

                    <option value="low"
                        @selected(request('priority')==='low' )>
                        Low
                    </option>

                    <option value="medium"
                        @selected(request('priority')==='medium' )>
                        Medium
                    </option>

                    <option value="high"
                        @selected(request('priority')==='high' )>
                        High
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    Search
                </button>

                <a href="{{route('home')}}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </div>
    </div>
</form>
@if($tasks->isEmpty())
<a href="{{route('tasks.create')}}" class="btn btn-primary mb-3">
    Create Your First Task
</a>
@endif


@forelse($tasks as $task)
<div class="card shadow-sm border-0 mb-3 task-card">
    <div class="card-body p-4">
        <div class="row align-items-center g-4">

            {{-- LEFT COLUMN: Task details --}}
            <div class="col-md-8">
                <h5 class="card-title fw-bold mb-2">
                    {{ $task->title }}
                </h5>

                <p class="card-text text-secondary mb-3">
                    {{ $task->description ?: 'No description provided.' }}
                </p>

                <div class="d-flex flex-wrap gap-2">
                    @if($task->status === 'completed')
                    <span class="badge bg-success">Completed</span>
                    @elseif($task->status === 'in_progress')
                    <span class="badge bg-warning text-dark">In Progress</span>
                    @else
                    <span class="badge bg-secondary">Pending</span>
                    @endif

                    @if($task->priority === 'high')
                    <span class="badge bg-danger">High Priority</span>
                    @elseif($task->priority === 'medium')
                    <span class="badge bg-primary">Medium Priority</span>
                    @else
                    <span class="badge bg-info text-dark">Low Priority</span>
                    @endif
                </div>
            </div>

            {{-- RIGHT COLUMN: Due date and actions --}}
            <div class="col-md-4">
                <div class="border-start ps-sm-4">

                    <div class="small text-muted mb-3">
                        <i class="bi bi-calendar3 me-1"></i>

                        @if($task->due_date)
                        Due:
                        {{ \Illuminate\Support\Carbon::parse($task->due_date)->format('M d, Y') }}

                        @if(
                        \Illuminate\Support\Carbon::parse($task->due_date)->isBefore(today())
                        && $task->status !== 'completed'
                        )
                        <div class="mt-2">
                            <span class="badge bg-danger">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Overdue
                            </span>
                        </div>
                        @endif
                        @else
                        No due date set
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a
                            href="{{ route('tasks.edit', $task->id) }}"
                            class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil-square me-1"></i>
                            Edit
                        </a>

                        <form
                            action="{{ route('tasks.destroy', $task->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash me-1"></i>
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@empty
<div class="text-center py-5">
    <i class="bi bi-clipboard-check display-5 text-secondary"></i>
    <h5 class="mt-3">No tasks found</h5>
    <p class="text-muted mb-0">
        Create your first task or adjust your search and filters.
    </p>
</div>
@endforelse
@endsection