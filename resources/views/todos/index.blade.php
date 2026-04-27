<!DOCTYPE html>
<html>

@php
    $status = request()->query('status');
@endphp

<head>
    <title>Todo App</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

<!-- NAVBAR -->
<nav class="bg-white border-b shadow-sm px-4 py-4 flex flex-col sm:flex-row sm:items-center">
    <div class="flex w-full items-center justify-between">

        <div class="flex items-center w-1/3">
            <span class="text-x2 font-bold text-gray-1000">
                Hello {{ Auth::user()->name }}
            </span>
        </div>

        <div class="flex items-right w-1/3 justify-end">
            <span class="text-xl font-bold text-gray-900 text-center">
                To do App
            </span>
        </div>

    </div>
</nav>

<!-- DASHBOARD -->
<div class="flex justify-center gap-12 mb-8">
    <div class="flex flex-col items-center w-32 bg-white rounded-xl shadow p-4 border">
        <span class="text-sm text-gray-500 mb-1">Total Tasks</span>
        <span class="text-3xl font-extrabold text-blue-700">{{ $todos->count() }}</span>
    </div>

    <div class="flex flex-col items-center w-32 bg-white rounded-xl shadow p-4 border">
        <span class="text-sm text-gray-500 mb-1">Completed</span>
        <span class="text-3xl font-extrabold text-green-600">
            {{ $todos->where('status', 'completed')->count() }}
        </span>
    </div>

    <div class="flex flex-col items-center w-32 bg-white rounded-xl shadow p-4 border">
        <span class="text-sm text-gray-500 mb-1">Pending</span>
        <span class="text-3xl font-extrabold text-yellow-500">
            {{ $todos->where('status', 'pending')->count() }}
        </span>
    </div>

    <div class="flex flex-col items-center w-32 bg-white rounded-xl shadow p-4 border">
        <span class="text-sm text-gray-500 mb-1">Todo</span>
        <span class="text-3xl font-extrabold text-gray-700">
            {{ $todos->where('status', 'todo')->count() }}
        </span>
    </div>
</div>

<!-- MAIN -->
<main class="flex-1 max-w-3xl mx-auto w-full p-6 mt-8">

    <!-- HEADER -->
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">My Tasks</h2>
        <p class="text-gray-500 mt-1">Stay focused, finish strong ⚡</p>
    </div>

    <!-- ADD TODO -->
    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-6">

        <form method="POST" action="/todos"
              class="flex flex-1 gap-2 bg-white p-4 rounded-2xl shadow-sm border">

            @csrf

            <input type="text" name="title" placeholder="Task..."
                   class="flex-1 border rounded-lg px-4 py-2">

             <textarea name="description" placeholder="Description..."
    class="w-full border rounded-lg px-4 py-2 text-sm"></textarea>
            <input type="date" name="start_date"
                   class="border rounded-lg px-2 py-2 text-sm">

            <input type="date" name="due_date"
                   class="border rounded-lg px-2 py-2 text-sm">

            <button class="bg-green-500 text-white px-4 rounded-lg">
                Add
            </button>

        </form>

        <form method="POST" action="/logout">
            @csrf
            <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg shadow">
                Logout
            </button>
        </form>

    </div>

    <!-- FILTERS (NOW BASED ON SYSTEM B STATUS) -->
    <div class="flex justify-center gap-2 mb-6">

        <a href="/todos"
           class="px-4 py-2 rounded-full text-sm {{ !$status ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-700' }}">
            All
        </a>

        <a href="/todos?status=todo"
           class="px-4 py-2 rounded-full text-sm {{ $status == 'todo' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-700' }}">
            Todo
        </a>

        <a href="/todos?status=pending"
           class="px-4 py-2 rounded-full text-sm {{ $status == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            Pending
        </a>

        <a href="/todos?status=overdue"
           class="px-4 py-2 rounded-full text-sm {{ $status == 'overdue' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700' }}">
            Overdue
        </a>

        <a href="/todos?status=completed"
           class="px-4 py-2 rounded-full text-sm {{ $status == 'completed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Completed
        </a>

    </div>

    <!-- TODO LIST -->
    <div class="space-y-3">

        @forelse ($todos as $todo)

        @php
            // SYSTEM B LOGIC (NO DB DEPENDENCY FOR STATUS)
            if ($todo->status === 'completed') {
                $computedStatus = 'completed';
            } elseif ($todo->start_date && now()->lt($todo->start_date)) {
                $computedStatus = 'todo';
            } elseif ($todo->due_date && now()->gt($todo->due_date)) {
                $computedStatus = 'overdue';
            } elseif ($todo->start_date && now()->gte($todo->start_date)) {
                $computedStatus = 'pending';
            } else {
                $computedStatus = 'todo';
            }

            // FILTER LOGIC
            if ($status && $status !== $computedStatus) {
                continue;
            }
        @endphp

        <div class="bg-blue-100 border border-blue-100 rounded-xl p-4 flex items-center justify-between shadow-sm hover:shadow-md transition">

            <!-- LEFT -->
            <div class="flex items-center gap-3 flex-1">

                <div class="w-2.5 h-2.5 rounded-full
                    {{ $computedStatus == 'completed'
                        ? 'bg-green-500'
                        : ($computedStatus == 'pending'
                            ? 'bg-yellow-400'
                            : ($computedStatus == 'overdue'
                                ? 'bg-red-500'
                                : 'bg-gray-400')) }}">
                </div>

                <div>
                    <p class="text-sm font-medium
                        {{ $computedStatus == 'completed' ? 'line-through text-gray-400' : 'text-gray-800' }}">
                        {{ $todo->title }}
                    </p>
@if($todo->description)
<p class="text-xs text-gray-500 mt-1">
    {{ $todo->description }}
</p>
@endif
                    <p class="text-xs text-gray-400">
                        {{ $computedStatus }}
                    </p>

                    <p class="text-xs text-gray-400">
                        Start: {{ $todo->start_date ?? '-' }} |
                        Due: {{ $todo->due_date ?? '-' }}
                    </p>
                </div>

            </div>

            <!-- RIGHT ACTIONS (UNCHANGED) -->
            <div class="flex items-center gap-3">

                <!-- EDIT -->
                <form method="POST" action="/todos/{{ $todo->id }}/update"
                      class="flex items-center gap-1">

                    @csrf

                    <input type="text"
                           name="title"
                           value="{{ $todo->title }}"
                           class="w-28 text-sm border rounded-md px-2 py-1 focus:ring-1 focus:ring-blue-400">

                    <button class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                        Edit
                    </button>

                </form>

                <!-- DELETE -->
                <form method="POST" action="/todos/{{ $todo->id }}/delete">
                    @csrf
                    <button class="text-red-500 hover:text-red-700 text-sm font-bold">
                        ✖
                    </button>
                </form>
                <form method="POST" action="/todos/{{ $todo->id }}/complete">
    @csrf

    @if($todo->status !== 'completed')
    <form method="POST" action="/todos/{{ $todo->id }}/complete">
        @csrf

        <button type="submit"
            class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded-lg">
            complate
        </button>
    </form>
@else
    <span class="text-green-600 text-xs font-semibold">
        ✔ Done
    </span>
@endif
</form>

            </div>

        </div>

        @empty
        <div class="text-center py-10 text-gray-500">
            No tasks found 🚀
        </div>
        @endforelse

    </div>

</main>

</body>
</html>
