<!DOCTYPE html>
<html>

<head>
    <title>Application Installation</title>
</head>

<body>
    <h1>Application Installation</h1>

    <form method="POST" action="{{ route('install.validate') }}">
        @csrf
        <div>
            <label for="license_key">License Key:</label>
            <input type="text" name="license_key" id="license_key" required>
        </div>
        <button type="submit">Install</button>

        @if(session('error'))
            <div style="color: red;">
                {{ session('error') }}
            </div>
        @endif
    </form>
</body>

</html>