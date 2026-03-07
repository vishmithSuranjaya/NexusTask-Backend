<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //show only the tasks for the logged-in user + Pagination
        return Task::where('user_id', auth()->id())->withTrashed()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the incoming request
    $validated = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority'    => 'required|in:low,medium,high',
        'due_date'    => 'nullable|date',
    ]);

    // 2. Create the task through the authenticated user relationship
    // This automatically sets the user_id for us
    $task = $request->user()->tasks()->create($validated);

    // 3. Return the new task as JSON to the React frontend
    return response()->json($task, 201);    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Find the task
    $task = Task::where('user_id', auth()->id())->findOrFail($id);

    // 2. Validate the incoming status (and any other fields you want to allow)
    $validated = $request->validate([
        'status' => 'required|in:pending,completed',
        'title' => 'sometimes|string|max:255',
        'description' => 'sometimes|nullable|string',
        'priority' => 'sometimes|in:low,medium,high',
    ]);

    // 3. Update and save
    $task->update($validated);

    return response()->json($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    // Find the task by ID, but ONLY if it belongs to the logged-in user
    // This prevents User A from deleting User B's tasks by guessing the ID
    $task = Task::where('user_id', auth()->id())->find($id);

    // Check if the task exists
    if (!$task) {
        return response()->json(['message' => 'Task not found or unauthorized'], 404);
    }

    // This triggers Soft Delete if you have the "SoftDeletes" trait in your Task model
    $task->delete(); 

    return response()->json(['message' => 'Task moved to trash']);
}

    public function trash() {
    return auth()->user()->tasks()->onlyTrashed()->latest()->get();
}

public function restore($id) {
    $task = auth()->user()->tasks()->onlyTrashed()->findOrFail($id);
    $task->restore();
    return response()->json(['message' => 'Task restored successfully']);
}
    
}
