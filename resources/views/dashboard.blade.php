<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            padding: 2rem;
        }
        header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        h1 {
            color: #333;
        }
        button {
            padding: 0.6rem 1rem;
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body>
<header>
    <h1>Welcome to Dashboard</h1>
    <button onclick="logoutFromBothApps()">Logout</button>
</header>
<div id="user-info">
    Loading user info...
</div>

<script>
    async function fetchUserInfo() {
        const token = localStorage.getItem('ecommerce_token');
        if (!token) {
            return window.location.href = '/login';
        }

        const res = await fetch('http://localhost:8000/api/user', {
            headers: {
                Authorization: 'Bearer ' + token
            }
        });

        if (!res.ok) return window.location.href = '/login';

        const user = await res.json();
        document.getElementById('user-info').innerHTML = `
                <p><strong>Name:</strong> ${user.name}</p>
                <p><strong>Email:</strong> ${user.email}</p>
            `;
    }

    fetchUserInfo();

    async function logoutFromBothApps() {
        const ecommerceToken = localStorage.getItem('ecommerce_token');
        const foodpandaToken = localStorage.getItem('foodpanda_token');

        if (ecommerceToken) {
            await fetch('http://localhost:8000/api/logout', {
                method: 'POST',
                headers: {
                    Authorization: 'Bearer ' + ecommerceToken
                }
            });
        }

        if (foodpandaToken) {
            await fetch('http://localhost:8001/api/logout', {
                method: 'POST',
                headers: {
                    Authorization: 'Bearer ' + foodpandaToken
                }
            });
        }

        localStorage.removeItem('ecommerce_token');
        // localStorage.removeItem('foodpanda_token');
        removeFoodpandaToken();
        window.location.href = '/login';
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

    async function removeFoodpandaToken() {
        await sendMessageToFoodpanda({ action: 'remove_token' });
    }
</script>
</body>
</html>
