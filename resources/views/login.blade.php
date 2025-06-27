<!DOCTYPE html>
<html>
<head>
    <title>Login - Ecommerce</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }

        .login-box button:hover {
            background: #0056b3;
        }

        .error-msg {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login to Ecommerce</h2>

    <form id="login-form" method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="error-msg" id="error-msg"></div>
</div>

<script>
    $('#login-form').submit(function(e) {
        e.preventDefault();
        $.post("{{ route('login') }}", $(this).serialize())
            .done(function(res) {
                console.log(res);
                localStorage.setItem('sso_token', res.token);
                window.open(res.sso_url, '_blank');
                window.location.href = '/dashboard';
            })
            .fail(function(err) {
                alert('Login failed');
            });
    });
</script>
</body>
</html>
