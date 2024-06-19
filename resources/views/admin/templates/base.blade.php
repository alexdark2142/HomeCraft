<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Главный</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href={{ asset('css/admin.css') }}>
</head>

<body class="bg-gray-100 pt-20">
    @include('admin.common.header')
    <main class="flex flex-col container text-black mx-auto p-2">
        @yield('main')
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <script src={{ asset('js/admin.js') }}></script>
</body>

</html>
