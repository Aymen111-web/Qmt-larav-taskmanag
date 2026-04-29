<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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

    <!-- Navbar Title -->
    <nav class="w-full bg-white border-b shadow-sm py-4 flex justify-center items-center fixed top-0 left-0 z-20">
        <span class="text-2xl font-extrabold text-gray-900 text-center">To do App</span>
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
        Create Account
    </h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="/register" class="space-y-4">
        @csrf

        <input type="text"
               name="name"
               placeholder="Full Name"
               class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">

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
            Register
        </button>
    </form>

    <!-- Login Button (NOT form submit) -->
    <a href="/login"
       class="block text-center mt-4 text-blue-600 hover:underline">
        Already have an account? Login
    </a>

</div>

</body>
</html>
