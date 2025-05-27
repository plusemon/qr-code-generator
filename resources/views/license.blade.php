<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activation Key Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom gradient background */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right top, #6EE7B7, #3B82F6, #9333EA);
            /* Green, Blue, Purple gradient */
            min-height: 100vh;
            /* Ensure the gradient covers the whole viewport height */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            /* Add some padding for smaller screens */
        }
    </style>
</head>

<body class="antialiased">
    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Enter Activation Key</h1>
        <p class="text-center p-3 mb-4 rounded-lg bg-yellow-100 text-yellow-800 border border-yellow-300">
            Please active this application by valid activation key above.
        </p>

        <form action="{{ route('license.activate') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="activation_key" class="block text-sm font-medium text-gray-700 mb-2">Activation Key</label>
                <input type="text" id="activation_key" name="activation_key" value="{{ old('activation_key') }}"
                    placeholder="e.g., ABC-123-XYZ"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
            </div>
            @if (isset($errors) && $errors->has('activation_key'))
                <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">
                    @error('activation_key') {{ $message }} @enderror
                </div>
            @endif
            <div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Activate
                </button>
            </div>
        </form>
        <div class="mt-5 text-center">
            <p>Support Center: 01995329555 / bdemon00@gmail.com</p>
        </div>
    </div>
</body>

</html>