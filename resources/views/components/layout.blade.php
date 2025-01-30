{{-- <!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        
        @vite(['resources/css/app.css','resources/js/app.js'])
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <title>Halaman Home</title>
        
    </head>
    <body class="h-full">

<div class="min-h-full">
    <x-navbar></x-navbar>
  
    <x-header>{{ $title }}</x-header>
    <main>
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{  $slot }}
        
      </div>
    </main>
  </div>

        <script src="js/script.js"></script>
    </body>
</html>

 --}}
 <!DOCTYPE html>
 <html lang="en" class="h-full bg-gray-100">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <title>@yield('title', 'MLI Store')</title>
     @vite('resources/css/app.css')
     <link rel="stylesheet" href="https://rsms.me/inter.css">
     <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
         });
     </script>
     <style>
         [x-cloak] { display: none !important; }
     </style>
 </head>
 <body class="h-full bg-gray-100">
     <div class="min-h-full">
         <x-sidebar></x-sidebar>
         <x-navbar></x-navbar>
 
         <div class="m-4 pl-16">
             @yield('content')
         </div>
     </div>
 </body>
 </html>
 