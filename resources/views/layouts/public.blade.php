<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? '50Fifty Sweepstakes' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap for public pages --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body style="background: #F5F5F5;">
    @yield('content')

    <footer class="text-center mt-5 py-4 bg-dark text-white">
        © {{ date('Y') }} 50Fifty WIN — All rights reserved.
    </footer>
</body>
</html>
