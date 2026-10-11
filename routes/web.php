<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboar with search and filtering

Route::get('/home', [DashboardController::class, 'index' ])->name('home');

Route::get('about', function () {
    return "Hello, I'm a full-stack developer.";
});

Route::get('/contact', function () {
    return 'Contact TaskFlow';
})->name('contact');  // named route

Route::get('/tasks/create', function () {
    return view('tasks.create');
})->name('tasks.create');

Route::post('/tasks', function (Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:100',
        'description' => 'nullable|string',
        'status' => 'required|in:pending,in_progress,completed',
        'priority' => 'required|in:low,medium,high',
        'due_date' => 'nullable|date',
    ]);

    Task::create($validated);

    return redirect()
        ->route('home')
        ->with('success', 'Task created successfully!');
})->name('tasks.store');

Route::get('/tasks/{id}/edit', function ($id) {
    $task = Task::findOrFail($id);

    return view('tasks.edit', [
        'task' => $task,
    ]);
})->name('tasks.edit');

Route::put('/tasks/{id}/update', function (Request $request, $id) {
    $task = Task::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:pending,in_progress,completed',
        'priority' => 'required|in:low,medium,high',
        'due_date' => 'nullable|date',
    ]);

    $task->update($validated);

    return redirect()
        ->route('home')
        ->with('success', 'Task updated successfully');
})->name('tasks.update');

Route::delete('/tasks/{id}', function ($id) {
    $task = Task::findOrFail($id);

    $task->delete();

    return redirect()
        ->route('home')
        ->with('success', 'Task deleted successfully!');
})->name('tasks.destroy');
