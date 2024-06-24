<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главный</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body class="bg-gray-100 pt-10">
<main class="flex flex-col container text-black mx-auto p-2">
    <div class="w-form mx-auto bg-white border rounded-lg shadow-xl p-5">
        <h1 class="text-3xl font-medium mb-5">Hi Admin</h1>

        <div id="error-messages" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 hidden" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="list-disc pl-5" id="error-list"></ul>
        </div>

        <form id="login-form" method="POST" action="{{ route('login.post') }}" class="mt-10">
            @csrf
            <div class="flex flex-col mb-5">
                <label for="login" class="text-gray-600 font-semibold mb-2">Login</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}" class="rounded p-2 bg-white shadow-xl border-b-2 border-gray-300">
            </div>
            <div class="flex flex-col mb-3">
                <label for="password" class="text-gray-600 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" class="rounded p-2 bg-white shadow-xl border-b-2 border-gray-300">
            </div>
            <div class="flex items-center mb-5">
                <input type="checkbox" id="check" name="remember">
                <label for="check" class="text-gray-600 font-semibold ml-2">Remember me</label>
            </div>
            <div class="flex items-center justify-center">
                <button
                    type="submit"
                    class="
                        bg-black
                        font-bold
                        rounded-md
                        text-white
                        px-10 py-3
                        hover:shadow-xl
                        transition
                        duration-150
                        transform
                        hover:scale-105
                    "
                >Sign in</button>
            </div>
        </form>
    </div>
</main>

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const errorMessages = document.getElementById('error-messages');
        const errorList = document.getElementById('error-list');
        errorList.innerHTML = '';
        errorMessages.classList.add('hidden');

        const form = event.target;
        const formData = new FormData(form);
        formData.append('status', 'admin');

        axios.post(form.action, formData)
            .then(response => {
                window.location.href = '/admin';
            })
            .catch(error => {
                if (error.response && error.response.status === 400) {
                    const errors = error.response.data;
                    for (const key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            const errorMessage = errors[key][0];
                            const li = document.createElement('li');
                            li.innerText = errorMessage;
                            errorList.appendChild(li);
                        }
                    }
                    errorMessages.classList.remove('hidden');
                }
            });
    });
</script>
</body>

</html>
