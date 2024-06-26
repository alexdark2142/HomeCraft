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

<body>
<main class="container">
    <div class="admin-form">
        <div id="error-messages" role="alert" class="error-messages">
            <strong>Error!</strong>
            <ul id="error-list"></ul>
        </div>

        <h1>Hi Admin</h1>

        <form id="login-form" method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" id="login" name="login" value="{{ old('login') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Sign in</button>
        </form>
    </div>
</main>

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const errorMessages = document.getElementById('error-messages');
        const errorList = document.getElementById('error-list');
        errorList.innerHTML = ''; // Очищуємо список помилок перед новими додаваннями

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
                    errorMessages.style.display = 'block'; // Показуємо блок з помилками
                }
            });
    });
</script>
</body>

</html>
