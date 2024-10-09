<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Admin</title>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
        <!-- Uppy CSS -->
        <link href="https://releases.transloadit.com/uppy/v4.4.0/uppy.min.css" rel="stylesheet">
        <link rel="stylesheet" href={{ asset('css/admin.css') }}>
    </head>

    <body class="bg-gray-100 pt-20">
        @include('admin.common.header')
        <main class="main-content flex flex-col text-black mx-auto p-2">
            @yield('main')
        </main>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
        <script src="https://releases.transloadit.com/uppy/v4.4.0/uppy.min.mjs" type="module"></script>
        <script type="module" src="/js/admin.js"></script>
    </body>
</html>
