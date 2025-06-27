<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Ecommerce Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #f0fdfa, #ccfbf1);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #0f766e;
            font-size: 1.8rem;
        }

        input {
            width: 93%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: border 0.2s ease;
        }

        input:focus {
            border-color: #14b8a6;
            outline: none;
            box-shadow: 0 0 0 2px rgba(20, 184, 166, 0.2);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #14b8a6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0f766e;
        }

        .error {
            color: #ef4444;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Ecommerce Login</h2>
    <div class="error" id="error-message"></div>
    <input type="email" id="email" placeholder="Email" />
    <input type="password" id="password" placeholder="Password" />
    <button id="loginBtn" onclick="loginToBothApps()">Login</button>
</div>

<script>
    const token = localStorage.getItem('ecommerce_token');
    if (token) {
        window.location.href = '/dashboard';
    }
    async function loginToBothApps() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = '';

        try {
            document.getElementById('loginBtn').textContent = 'Loading...';

            const resA = await fetch('http://localhost:8000/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const dataA = await resA.json();
            if (!resA.ok) {
                throw new Error(dataA.message || 'Ecommerce login failed');
            }

            localStorage.setItem('ecommerce_token', dataA.token);

            const resB = await fetch('http://localhost:8001/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const dataB = await resB.json();
            if (!resB.ok) {
                throw new Error(dataB.message || 'Foodpanda login failed');
            }
            localStorage.setItem('foodpanda_token', dataB.token);
            await storeFoodpandaToken(dataA.token, dataB.token);
            document.getElementById('loginBtn').textContent = 'Login';

            window.location.href = '/dashboard';
        } catch (err) {
            errorDiv.textContent = err.message;
        }
    }
    function sendMessageToFoodpanda(message) {
        return new Promise((resolve) => {
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = 'http://127.0.0.1:8001/token-handler';
            document.body.appendChild(iframe);

            iframe.onload = () => {
                iframe.contentWindow.postMessage(message, 'http://127.0.0.1:8001');
                resolve();
                setTimeout(() => document.body.removeChild(iframe), 1000);
            };
        });
    }

    async function storeFoodpandaToken(ecommerceToken, foodpandaToken) {
        await sendMessageToFoodpanda({ action: 'store_token', ecommerceToken, foodpandaToken});
    }

</script>
</body>
</html>
