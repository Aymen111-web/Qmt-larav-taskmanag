<!DOCTYPE html>
<html>

@php
    $status = request()->query('status');
@endphp

<head>
    <title>Todo App</title>
    @vite('resources/css/app.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('note.jpg') }}" type="image/jpeg">
</head>

<body class="bg-[#f6f7fb] min-h-screen flex flex-col" style="font-size: 17px;">

<!-- NAVBAR -->
<nav class="bg-white border-b border-slate-100 shadow-sm px-6 py-3 fixed top-0 left-0 w-full z-50">
    <div class="max-w-6xl mx-auto flex justify-between items-center">

        <!-- Left side: App name/logo -->
        <div class="flex items-center gap-4">
            <div style="width: 36px; height: 36px; margin-right: 12px; background-color: #000000; color: #ffffff; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.25); transform: rotate(3deg); flex-shrink: 0;">
                <svg width="24" height="24" fill="none" stroke="#ffffff" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <span style="font-size: 1.4rem; font-weight: 900;" class="tracking-tight text-slate-800">To do App</span>
        </div>

        <!-- Right side: Profile button -->
        <div class="relative" id="profile-dropdown">
            <button onclick="toggleDropdown()" class="flex items-center gap-2.5 px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-xl transition-all duration-200 border border-slate-200 hover:border-indigo-200 focus:outline-none shadow-sm group">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span style="font-size: 1.25rem;">Profile</span>
                <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdown-menu" class="hidden absolute right-0 mt-2 w-52 bg-white border border-slate-100 rounded-2xl shadow-xl py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-200">
                <div class="px-4 py-3 border-b border-slate-50 mb-1">
                    <p style="font-size: 0.8rem;" class="text-slate-400 font-medium uppercase tracking-wider mb-0.5">Signed in as</p>
                    <p style="font-size: 1rem;" class="font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                    <p style="font-size: 0.9rem;" class="text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>

                <div class="px-2">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 text-red-600 font-semibold hover:bg-red-50 rounded-xl transition-colors flex items-center gap-3 group" style="font-size: 1rem;">
                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</nav>

<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdown-menu');
    menu.classList.toggle('hidden');
}
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('profile-dropdown');
    const menu = document.getElementById('dropdown-menu');
    if (dropdown && !dropdown.contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>

<div style="height: 100px; width: 100%; display: block; flex-shrink: 0;"></div>

<!-- MAIN WRAPPER — has side padding so content never touches screen edges -->
<div class="w-full mx-auto flex flex-col items-center relative" style="max-width: 1400px; padding-left: 1.25rem; padding-right: 1.25rem;">

    <!-- SINGLE TOGGLE BUTTON -->
    <div class="w-full flex mb-4">
        <button id="mode-toggle-btn" onclick="toggleViewMode()" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2 rounded-xl border-2 border-green-500 transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow-md focus:outline-none" style="font-size: 1.15rem;">
            <svg id="toggle-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span id="toggle-label">Add Task</span>
        </button>
    </div>

    <!-- DASHBOARD -->
    <div class="flex flex-wrap md:flex-nowrap gap-4 mb-14 w-full justify-between animate-in fade-in slide-in-from-top-4 duration-500">

        <div class="flex-1 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-6 text-center min-w-[150px]">
            <p style="font-size: 1.25rem;" class="font-semibold text-slate-400 uppercase tracking-wider">Total Tasks</p>
            <p style="font-size: 2.5rem;" class="font-black text-indigo-600 mt-1">{{ $total }}</p>
        </div>

        <div class="flex-1 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-6 text-center min-w-[150px]">
            <p style="font-size: 1.25rem;" class="font-semibold text-slate-400 uppercase tracking-wider">Todo</p>
            <p style="font-size: 2.5rem;" class="font-black text-slate-600 mt-1">{{ $todoCount }}</p>
        </div>

        <div class="flex-1 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-6 text-center min-w-[150px]">
            <p style="font-size: 1.25rem;" class="font-semibold text-slate-400 uppercase tracking-wider">Pending</p>
            <p style="font-size: 2.5rem;" class="font-black text-amber-500 mt-1">{{ $pendingCount }}</p>
        </div>

        <div class="flex-1 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-6 text-center min-w-[150px]">
            <p style="font-size: 1.25rem;" class="font-semibold text-slate-400 uppercase tracking-wider">Overdue</p>
            <p style="font-size: 2.5rem;" class="font-black text-red-500 mt-1">{{ $overdueCount }}</p>
        </div>

        <div class="flex-1 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md transition-all p-6 text-center min-w-[150px]">
            <p style="font-size: 1.25rem;" class="font-semibold text-slate-400 uppercase tracking-wider">Completed</p>
            <p style="font-size: 2.5rem;" class="font-black text-emerald-500 mt-1">{{ $completedCount }}</p>
        </div>

    </div>

    <!-- ADD FORM -->
    <div class="add-mode-element bg-white border rounded-xl p-6 shadow-sm mb-8 hover:shadow-md transition w-full flex flex-col items-stretch animate-in fade-in zoom-in-95 duration-300">

        <form method="POST" action="/todos" class="space-y-4">
            @csrf

            <input type="text"
                   name="title"
                   placeholder="Task title..."
                   style="font-size: 1.05rem;"
                   class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-400 outline-none">

            <textarea name="description"
                      rows="3"
                      placeholder="Add description..."
                      style="font-size: 1.05rem;"
                      class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-400 outline-none"></textarea>

            <div class="flex gap-2 flex-wrap">

                <input type="date"
                       name="start_date"
                       style="font-size: 1rem;"
                       class="border rounded-lg px-3 py-2">

                <input type="date"
                       name="due_date"
                       style="font-size: 1rem;"
                       class="border rounded-lg px-3 py-2">

                <button class="ml-auto bg-green-500 hover:bg-green-600 transition text-white px-5 py-2 rounded-lg flex items-center justify-center gap-2" style="min-width: 180px; width: 180px; font-size: 1.05rem;">
                    Add Task
                </button>

            </div>

        </form>

    </div>

    <!-- FILTERS -->
    <div class="view-mode-element flex gap-2 mt-16 pt-10 border-t border-slate-200/60 mb-4 flex-wrap w-full justify-center animate-in fade-in duration-300" style="display: none;">

        @php
            $filterStyles = [
                '' => 'background-color: #111827; color: #ffffff; border-color: #111827;',
                'todo' => 'background-color: #111827; color: #ffffff; border-color: #111827;',
                'pending' => 'background-color: #facc15; color: #422006; border-color: #facc15;',
                'overdue' => 'background-color: #ef4444; color: #ffffff; border-color: #ef4444;',
                'completed' => 'background-color: #22c55e; color: #ffffff; border-color: #22c55e;',
            ];
        @endphp

        @foreach([
            '' => 'All',
            'todo' => 'Todo',
            'pending' => 'Pending',
            'overdue' => 'Overdue',
            'completed' => 'Completed'
        ] as $key => $label)
            @php $isActive = ($key === '' && !$status) || $status === $key; @endphp
            <a href="/todos{{ $key ? '?status='.$key : '?view=true' }}"
               class="px-4 py-2 rounded-full border transition
               {{ !$isActive ? 'bg-white border-slate-200 text-gray-600 hover:bg-gray-100' : '' }}"
               style="font-size: 1.25rem; {{ $isActive ? $filterStyles[$key] : '' }}">
                {{ $label }}
            </a>
        @endforeach

    </div>

    <!-- TODO LIST -->
    <div class="view-mode-element flex flex-col items-center w-full animate-in fade-in duration-300" style="display: none;">
        <div class="w-full flex flex-col gap-5">
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
            <div class="bg-white border rounded-2xl p-6 shadow-md hover:shadow-lg transition duration-300 w-full flex flex-col gap-3">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <!-- LEFT -->
                    <div class="flex gap-3 flex-1 items-start">
                        <div class="w-3 h-3 mt-2 rounded-full flex-shrink-0
                            {{ $computedStatus == 'completed' ? 'bg-green-500' :
                               ($computedStatus == 'pending' ? 'bg-yellow-400' :
                               ($computedStatus == 'overdue' ? 'bg-red-500' : 'bg-gray-400')) }}">
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 {{ $computedStatus == 'completed' ? 'line-through text-gray-400' : '' }} mb-1" style="font-size: 1.08rem;">
                                {{ $todo->title }}
                            </p>
                            @if($todo->description)
                                <p class="text-gray-500 mb-1" style="font-size: 1rem;">
                                    {{ $todo->description }}
                                </p>
                            @endif
                            <p class="text-gray-400" style="font-size: 0.9rem;">
                                {{ $computedStatus }} •
                                Start: {{ $todo->start_date ?? '-' }} |
                                Due: {{ $todo->due_date ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <!-- ACTIONS -->
                    <div class="flex items-center gap-2 mt-3 sm:mt-0">
                        <a href="?{{ http_build_query(array_merge(request()->query(), ['edit' => $todo->id])) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-lg transition" style="font-size: 1.05rem;">Edit</a>
                        <form method="POST" action="/todos/{{ $todo->id }}/delete">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg transition" style="font-size: 1.05rem;">Delete</button>
                        </form>
                        <form method="POST" action="/todos/{{ $todo->id }}/complete">
                            @csrf
                            @if($todo->status !== 'completed')
                                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded-lg" style="font-size: 1.05rem;">
                                    Done
                                </button>
                            @else
                                <span class="text-green-600 font-semibold" style="font-size: 1rem;">✔</span>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center text-gray-400 py-10 w-full" style="font-size: 1.05rem;">
                    No tasks found
                </div>
            @endforelse
        </div>

        <!-- Edit Modal -->
        @if(request('edit'))
            @php $editTodo = $todos->firstWhere('id', request('edit')); @endphp
            @if($editTodo)
                <div class="fixed inset-0 z-50 flex items-center justify-center">
                    <a href="?{{ http_build_query(request()->except('edit')) }}" class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></a>
                    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-auto p-6 flex flex-col gap-4 animate-fade-in">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="font-bold text-gray-800" style="font-size: 1.2rem;">Edit Task</h2>
                            <a href="?{{ http_build_query(request()->except('edit')) }}" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</a>
                        </div>
                        <form action="/todos/{{ $editTodo->id }}/update" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input type="text" name="title" class="border rounded-lg px-4 py-2 w-full" style="font-size: 1rem;" placeholder="Task title" value="{{ $editTodo->title }}" required>
                            <textarea name="description" class="border rounded-lg px-4 py-2 w-full" style="font-size: 1rem;" rows="3" placeholder="Description">{{ $editTodo->description }}</textarea>
                            <div class="flex gap-2">
                                <input type="date" name="start_date" class="border rounded-lg px-3 py-2 w-1/2" style="font-size: 1rem;" value="{{ $editTodo->start_date }}">
                                <input type="date" name="due_date" class="border rounded-lg px-3 py-2 w-1/2" style="font-size: 1rem;" value="{{ $editTodo->due_date }}">
                            </div>
                            <div class="flex justify-end gap-2 mt-2">
                                <a href="?{{ http_build_query(request()->except('edit')) }}" class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100" style="font-size: 1rem;">Cancel</a>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow" style="font-size: 1rem;">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>

<script>
    let isViewMode = false;

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('edit') || urlParams.has('status') || urlParams.has('view')) {
            toggleViewMode(true);
        } else {
            toggleViewMode(false);
        }
    });

    function toggleViewMode(forceView = null) {
        if (forceView !== null) {
            isViewMode = forceView;
        } else {
            isViewMode = !isViewMode;
        }

        const viewElements = document.querySelectorAll('.view-mode-element');
        const addElements  = document.querySelectorAll('.add-mode-element');
        const btn   = document.getElementById('mode-toggle-btn');
        const icon  = document.getElementById('toggle-icon');
        const label = document.getElementById('toggle-label');

        if (isViewMode) {
            addElements.forEach(el  => el.style.display = 'none');
            viewElements.forEach(el => el.style.display = 'flex');
            btn.style.backgroundColor = '#6366f1';
            btn.style.borderColor     = '#6366f1';
            btn.style.color           = '#ffffff';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>';
            label.textContent = 'Add Task';
        } else {
            viewElements.forEach(el => el.style.display = 'none');
            addElements.forEach(el  => el.style.display = 'flex');
            btn.style.backgroundColor = '#22c55e';
            btn.style.borderColor     = '#22c55e';
            btn.style.color           = '#ffffff';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>';
            label.textContent = 'View Tasks';
        }
    }
</script>

</body>
</html>
