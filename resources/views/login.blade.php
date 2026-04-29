<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="icon" href="{{ asset('note.jpg') }}" type="image/jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('successModal')) {
                document.getElementById('successModal').classList.remove('hidden');
            }
        });
    </script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- NAVBAR (consistent branding) -->
    <nav class="bg-white border-b border-slate-100 shadow-sm px-6 py-3 fixed top-0 left-0 w-full z-50">
        <div class="max-w-6xl mx-auto flex justify-center items-center">
            <!-- Centered App name/logo -->
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-indigo-200 shadow-lg transform rotate-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="text-center">
                    <span class="text-xl font-black tracking-tight text-slate-800">Task management system</span>
                </div>
            </div>
        </div>
    </nav>
    <div class="h-16"></div>

    @if (session('success'))
        <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full text-center">
                <div class="text-green-600 text-3xl mb-2">&#10003;</div>
                <div class="text-lg font-semibold mb-2">{{ session('success') }}</div>
                <button onclick="document.getElementById('successModal').classList.add('hidden')" class="mt-4 px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600">OK</button>
            </div>
        </div>
    @endif

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
          Welcome Back!
    </h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/login" class="space-y-4">
        @csrf

        <input type="email"
               name="email"
               placeholder="Email Address"
               class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">

        <input type="password"
               name="password"
               placeholder="Password"
               class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold transition">
            Login
        </button>
    </form>

    <!-- Back to Register -->
    <div class="text-center mt-4">
        <a href="/register" class="text-blue-600 hover:underline">
            Don’t have an account?   Register
        </a>
    </div>

</div>

</body>
</html>
