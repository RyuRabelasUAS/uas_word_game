<!DOCTYPE html>
<html>
<head>
    <title>Session Test</title>
</head>
<body>
    <h1>Session Test</h1>

    <p>CSRF Token: <code>{{ csrf_token() }}</code></p>
    <p>Session ID: <code>{{ session()->getId() }}</code></p>

    <form method="POST" action="/test-session">
        @csrf
        <button type="submit">Test Form Submit</button>
    </form>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
</body>
</html>
