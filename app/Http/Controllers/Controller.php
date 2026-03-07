<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Task;

abstract class Controller
{
public function index()
{
    // Getting only the tasks for the logged-in user + Pagination
    return Task::where('user_id', auth()->id())->paginate(10);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $task = Task::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description' => $request->description,
    ]);

    return response()->json($task, 211);
}
}
