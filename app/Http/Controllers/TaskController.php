<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Optional: Search/Filter by status or due_date
        // Use an explicit query on the Task model to avoid any hidden relation issues
        $query = Task::where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }

        $tasks = $query->latest('due_date')->paginate(5);
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress,completed',
            'due_date' => 'required|date',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Task Created Successfully!');
    }

    public function update(Request $request, Task $task)
    {
        // Ensure user owns the task
        if ($task->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in-progress,completed',
            'due_date' => 'required|date',
        ]);

        $task->update($validated);

        return redirect()->route('dashboard')->with('success', 'Task Updated!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        $task->delete();
        return redirect()->route('dashboard')->with('success', 'Task Deleted');
    }

    /**
     * Show the form for editing the specified task.
     * (Web interface - simple edit page)
     */
    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tasks.edit', compact('task'));
    }
}