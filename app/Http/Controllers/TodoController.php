<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();
    $status = $request->query('status');

    $todos = $user->todos()
        ->orderBy('created_at', 'desc')
        ->get();

    // 🔥 STEP 1: COMPUTE + SYNC DB STATUS
    foreach ($todos as $todo) {

        if ($todo->status === 'completed') {
            $newStatus = 'completed';

        } elseif ($todo->due_date && now()->gt($todo->due_date)) {
            $newStatus = 'overdue';

        } elseif ($todo->start_date && now()->gte($todo->start_date)) {
            $newStatus = 'pending';

        } else {
            $newStatus = 'todo';
        }

        // 🔥 UPDATE DB ONLY IF DIFFERENT (IMPORTANT OPTIMIZATION)
        if ($todo->status !== $newStatus) {
            $todo->update([
                'status' => $newStatus
            ]);

            // also update local object so UI is correct instantly
            $todo->status = $newStatus;
        }

        // keep for UI filtering
        $todo->computed_status = $newStatus;
    }

    // 🔥 STEP 2: FILTER BASED ON COMPUTED STATUS
    if ($status) {
        $todos = $todos->filter(function ($todo) use ($status) {
            return $todo->computed_status === $status;
        });
    }

    // 🔥 STEP 3: COUNTERS (NOW FROM UPDATED DATA)
    $allTodos = $user->todos()->get();

    foreach ($allTodos as $todo) {

        if ($todo->status === 'completed') {
            $todo->computed_status = 'completed';

        } elseif ($todo->status === 'overdue') {
            $todo->computed_status = 'overdue';

        } elseif ($todo->status === 'pending') {
            $todo->computed_status = 'pending';

        } else {
            $todo->computed_status = 'todo';
        }
    }

    $total = $allTodos->count();
    $todoCount = $allTodos->where('computed_status', 'todo')->count();
    $pendingCount = $allTodos->where('computed_status', 'pending')->count();
    $overdueCount = $allTodos->where('computed_status', 'overdue')->count();
    $completedCount = $allTodos->where('computed_status', 'completed')->count();

    return view('todos.index', compact(
        'todos',
        'total',
        'todoCount',
        'pendingCount',
        'overdueCount',
        'completedCount',
        'status'
    ));
}
public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'start_date' => 'nullable|date',
        'due_date' => 'nullable|date|after_or_equal:start_date',
        'description' => 'nullable|string|max:255',
    ]);

    Todo::create([
        'title' => $request->title,
        'completed' => false,
        'status' => 'todo' ,
        'user_id' => Auth::id(),
        'start_date' => $request->start_date,
        'due_date' => $request->due_date,
        'description' => $request->description,

    ]);

    return redirect()->back();
}

public function delete($id)
{
    $todo = Todo::findOrFail($id);
    $todo->delete();

    return back();
}
public function updateStatus(Request $request, $id)
{
    $todo = Todo::findOrFail($id);

    $todo->update([
        'status' => $request->status,
    ]);

    return back();
}
public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => 'nullable|date',
        'due_date' => 'nullable|date|after_or_equal:start_date',
        'description' => 'nullable|string|max:255',
    ]);

    $todo = Todo::findOrFail($id);

    $todo->update([
        'title' => $request->title,
        'start_date' => $request->start_date,
        'due_date' => $request->due_date,
        'description' => $request->description,
    ]);

    return redirect('/todos');
}
public function complete($id)
{
    $todo = Todo::findOrFail($id);
    $todo->update([
        'status' => 'completed',
        'completed' => true,
        'completed_at' => now(),
    ]);

    return back();
}
private function syncStatus($todo)
{
    if ($todo->status === 'completed') {
        return 'completed';
    }

    if ($todo->due_date && now()->gt($todo->due_date)) {
        return 'overdue';
    }

    if ($todo->start_date && now()->gte($todo->start_date)) {
        return 'pending';
    }

    return 'todo';
}
}
