@extends('layouts.app')

@section('title','Edit Task')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="mb-4">Edit New Task</h2>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{route('tasks.update', $task->id)}}" method="POST">
                         @csrf 
                         @method('PUT')

                         <div class="mb-3">
                            <label for="title" class="form-label">
                                Task Title
                            </label>
                            <input type="text" name="title" id="title" class="form-control" value="{{old('title',$task->title)}}" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea name="description" id="description" class="form-control" rows="4" required>{{old('description',$task->description)}}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">
                                    Status
                                </label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" @selected(old( 'status' , $task->status) == 'pending')>Pending</option>
                                    <option value="in_progress" @selected(old('status', $task->status) == 'in_progress')>In Progress</option>
                                    <option value="completed"  @selected(old('status', $task->status) == 'completed')>Completed</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">
                                    Priority
                                </label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="low" @selected(old('priority', $task->priority) == 'low')>Low</option>
                                    <option value="medium" @selected(old('priority', $task->priority) == 'medium')>Medium</option>
                                    <option value="high" @selected(old('priority', $task->priority) == 'high')>High</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="due_date" class="form-label">
                                    Due Date
                                </label>
                                <input type="date" name="due_date" id="due_date" class="form-control" value="{{ old('due_date', $task->due_date ? \Illuminate\Support\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Edit Task</button>

                                <a href="{{route('home')}}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
