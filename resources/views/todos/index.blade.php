<!DOCTYPE html>
<html>

@php
    $status = request()->query('status');
@endphp

<head>
    <title>Todo App</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-[#f6f7fb] min-h-screen flex flex-col">

<!-- NAVBAR (glass + sticky modern feel) -->
<nav class="bg-white/80 backdrop-blur-md border-b shadow-sm px-6 py-4 fixed top-0 left-0 w-full z-50">
    <div class="max-w-6xl mx-auto flex justify-between items-center">

        <div class="text-lg font-semibold text-gray-800">
            Hello {{ Auth::user()->name }}
        </div>

        <form method="POST" action="/logout">
            @csrf
            <button class="bg-red-500 hover:bg-red-600 transition text-white px-4 py-2 rounded-lg shadow-sm">
                Logout
            </button>
        </form>

    </div>
</nav>

<div class="h-20"></div>


<!-- MAIN WRAPPER -->
<div class="max-w-3xl w-full mx-auto px-4 flex flex-col items-center">



    <!-- DASHBOARD (FORCED HORIZONTAL ROW) -->
    <div class="flex flex-wrap md:flex-nowrap gap-4 mb-10 w-full justify-between">

    <div class="flex-1 bg-white border rounded-xl shadow-sm hover:shadow-md transition p-5 text-center min-w-[140px] mx-1">
        <p class="text-sm text-gray-500">Total Tasks</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">
            {{ $todos->count() }}
        </p>
    </div>

    <div class="flex-1 bg-white border rounded-xl shadow-sm hover:shadow-md transition p-5 text-center min-w-[140px] mx-1">
        <p class="text-sm text-gray-500">Completed</p>
        <p class="text-3xl font-bold text-green-600 mt-1">
            {{ $todos->where('status', 'completed')->count() }}
        </p>
    </div>

    <div class="flex-1 bg-white border rounded-xl shadow-sm hover:shadow-md transition p-5 text-center min-w-[140px] mx-1">
        <p class="text-sm text-gray-500">Pending</p>
        <p class="text-3xl font-bold text-yellow-500 mt-1">
            {{ $todos->where('status', 'pending')->count() }}
        </p>
    </div>

    <div class="flex-1 bg-white border rounded-xl shadow-sm hover:shadow-md transition p-5 text-center min-w-[140px] mx-1">
        <p class="text-sm text-gray-500">Todo</p>
        <p class="text-3xl font-bold text-gray-700 mt-1">
            {{ $todos->where('status', 'todo')->count() }}
        </p>
    </div>

</div>

    <!-- HEADER (sticky feel section title) -->
    <div class="sticky top-20 z-10 bg-[#f6f7fb] py-4 mb-6 w-full">
        <h1 class="text-2xl font-bold text-gray-800">My Tasks</h1>
        <p class="text-sm text-gray-500 mt-1">Organize your day like a pro ⚡</p>
    </div>


    <!-- ADD FORM (card style like Notion) -->
    <div class="bg-white border rounded-xl p-6 shadow-sm mb-8 hover:shadow-md transition w-full max-w-xl mx-auto flex flex-col items-stretch">

        <form method="POST" action="/todos" class="space-y-3">
            @csrf

            <input type="text"
                   name="title"
                   placeholder="Task title..."
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none">

            <textarea name="description"
                      rows="3"
                      placeholder="Add description..."
                      class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-400 outline-none"></textarea>

            <div class="flex gap-2 flex-wrap">

                <input type="date"
                       name="start_date"
                       class="border rounded-lg px-3 py-2 text-sm">

                <input type="date"
                       name="due_date"
                       class="border rounded-lg px-3 py-2 text-sm">

                <button class="ml-auto bg-green-500 hover:bg-green-600 transition text-white px-5 py-2 rounded-lg">
                    Add Task
                </button>

            </div>

        </form>

    </div>


    <!-- FILTERS -->
    <div class="flex gap-2 mb-8 flex-wrap w-full justify-center">

        @foreach([
            '' => 'All',
            'todo' => 'Todo',
            'pending' => 'Pending',
            'overdue' => 'Overdue',
            'completed' => 'Completed'
        ] as $key => $label)

            <a href="/todos{{ $key ? '?status='.$key : '' }}"
               class="px-4 py-2 rounded-full text-sm transition
               {{ $status === $key ? 'bg-gray-900 text-white' : 'bg-white border text-gray-600 hover:bg-gray-100' }}">
                {{ $label }}
            </a>

        @endforeach

    </div>



        <!-- TODO LIST -->
        <div class="flex flex-col items-center w-full">
            <div class="w-full flex flex-col gap-5 max-w-xl">
                @forelse ($todos as $todo)
                @php
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
                    if ($status && $status !== $computedStatus) continue;
                @endphp
                <!-- TASK CARD -->
                <div class="bg-white border rounded-2xl p-6 shadow-md hover:shadow-lg transition duration-300 w-full mx-auto max-w-xl flex flex-col gap-3">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <!-- LEFT -->
                        <div class="flex gap-3 flex-1 items-start">
                            <div class="w-2.5 h-2.5 mt-2 rounded-full
                                {{ $computedStatus == 'completed' ? 'bg-green-500' :
                                   ($computedStatus == 'pending' ? 'bg-yellow-400' :
                                   ($computedStatus == 'overdue' ? 'bg-red-500' : 'bg-gray-400')) }}">
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 {{ $computedStatus == 'completed' ? 'line-through text-gray-400' : '' }} mb-1">
                                    {{ $todo->title }}
                                </p>
                                @if($todo->description)
                                    <p class="text-sm text-gray-500 mb-1">
                                        {{ $todo->description }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400">
                                    {{ $computedStatus }} •
                                    Start: {{ $todo->start_date ?? '-' }} |
                                    Due: {{ $todo->due_date ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <!-- ACTIONS -->
                        <div class="flex items-center gap-2 mt-3 sm:mt-0">
                            <!-- Edit Button -->
                            <a href="?{{ http_build_query(array_merge(request()->query(), ['edit' => $todo->id])) }}" class="text-blue-600 text-sm px-3 py-1 rounded-lg border border-blue-100 hover:bg-blue-50 transition">Edit</a>
                            <form method="POST" action="/todos/{{ $todo->id }}/delete">
                                @csrf
                                <button class="text-red-500 text-sm">✖</button>
                            </form>
                            <form method="POST" action="/todos/{{ $todo->id }}/complete">
                                @csrf
                                @if($todo->status !== 'completed')
                                    <button class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded-lg">
                                        Done
                                    </button>
                                @else
                                    <span class="text-green-600 text-xs font-semibold">✔</span>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="text-center text-gray-400 py-10 w-full">
                        No tasks found
                    </div>
                @endforelse
            </div>
            <!-- Edit Modal (Pure PHP) -->
            @if(request('edit'))
                @php
                    $editTodo = $todos->firstWhere('id', request('edit'));
                @endphp
                @if($editTodo)
                    <div class="fixed inset-0 z-50 flex items-center justify-center">
                        <!-- Overlay -->
                        <a href="?{{ http_build_query(request()->except('edit')) }}" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></a>
                        <!-- Modal -->
                        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto p-6 flex flex-col gap-4 animate-fade-in">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-lg font-bold text-gray-800">Edit Task</h2>
                                <a href="?{{ http_build_query(request()->except('edit')) }}" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</a>
                            </div>
                            <form action="/todos/{{ $editTodo->id }}/update" method="POST" class="flex flex-col gap-3">
                                @csrf
                                <input type="text" name="title" class="border rounded-lg px-4 py-2 w-full" placeholder="Task title" value="{{ $editTodo->title }}" required>
                                <textarea name="description" class="border rounded-lg px-4 py-2 w-full" rows="3" placeholder="Description">{{ $editTodo->description }}</textarea>
                                <div class="flex gap-2">
                                    <input type="date" name="start_date" class="border rounded-lg px-3 py-2 text-sm w-1/2" value="{{ $editTodo->start_date }}">
                                    <input type="date" name="due_date" class="border rounded-lg px-3 py-2 text-sm w-1/2" value="{{ $editTodo->due_date }}">
                                </div>
                                <div class="flex justify-end gap-2 mt-2">
                                    <a href="?{{ http_build_query(request()->except('edit')) }}" class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">Cancel</a>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endif
        </div>

</div>

</body>
</html>
