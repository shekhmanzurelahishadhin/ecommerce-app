<!DOCTYPE html>
<html>
<head>
    <title>Ecommerce Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 1rem;
        }
        input {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 0.6rem;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #4338ca;
        }
        .error {
            color: red;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Ecommerce Login</h2>
    <div class="error" id="error-message"></div>
    <input type="email" id="email" placeholder="Email" />
    <input type="password" id="password" placeholder="Password" />
    <button onclick="loginToBothApps()">Login</button>
</div>

<script>
    const token = localStorage.getItem('ecommerce_token');
    if (token) {
        // Optionally verify token validity via API or just redirect
        window.location.href = '/dashboard';
    }
    async function loginToBothApps() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = '';

        try {
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

            // localStorage.setItem('foodpanda_token', dataB.token);
            await storeFoodpandaToken(dataB.token);

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
                // Optionally remove iframe after a delay to avoid race conditions:
                setTimeout(() => document.body.removeChild(iframe), 1000);
            };
        });
    }

    async function storeFoodpandaToken(token) {
        await sendMessageToFoodpanda({ action: 'store_token', token });
    }

</script>
</body>
</html>
