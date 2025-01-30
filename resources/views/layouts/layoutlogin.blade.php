<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MLI Store')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100">
    <div class="min-h-full">
        <!-- Navbar Only -->
        <x-navbar></x-navbar> 

        <!-- Content -->
        <div class="bg-gray-200">
            @yield('content') <!-- Tempat untuk konten halaman -->
        </div>
    </div>
</body>
</html>