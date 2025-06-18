<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Login</h2>

                    <div id="response" class="alert d-none" role="alert"></div>

                    <form id="loginForm">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <p class="mt-3 text-center">
                        Don't have an account? <a href="{{ route('register.form') }}">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('loginForm').onsubmit = async function(e) {
        e.preventDefault();

        const form = e.target;
        const data = {
            email: form.email.value,
            password: form.password.value
        };

        const responseBox = document.getElementById('response');
        responseBox.classList.add('d-none');
        responseBox.classList.remove('alert-success', 'alert-danger');
        responseBox.innerText = '';

        try {
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await res.json();
            console.log(result);
            if (!res.ok) {
                responseBox.innerText = result.message || 'Login failed';
                responseBox.classList.add('alert', 'alert-danger', 'mt-3');
            } else {
                responseBox.innerText = result.message;
                responseBox.classList.add('alert', 'alert-success', 'mt-3');
                if (result.token) {
                    localStorage.removeItem('token');
                    localStorage.setItem('token', result.token);
                    setTimeout(() => {
                        window.location.href = "{{ route('quotation.form') }}";
                    }, 1000);
                }
            }

            responseBox.classList.remove('d-none');

        } catch (err) {
            console.error(err);
            responseBox.innerText = 'Network or unexpected error.';
            responseBox.classList.add('alert', 'alert-danger', 'mt-3');
            responseBox.classList.remove('d-none');
        }
    };
</script>
</body>
</html>