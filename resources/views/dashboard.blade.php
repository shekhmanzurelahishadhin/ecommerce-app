<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Ecommerce</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0fdfa;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            background: linear-gradient(135deg, #14b8a6, #0f766e);
            padding: 1rem 2rem;
            color: #d1fae5;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 1.2px;
            box-shadow: 0 2px 8px rgba(20, 184, 166, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
        }

        h1 {
            margin: 0 0 0.5rem 0;
            font-weight: 600;
            font-size: 2.5rem;
            color: #0f766e;
        }

        p {
            margin: 0 0 2rem 0;
            font-size: 1.1rem;
            color: #475569;
        }

        button {
            background-color: #0f766e;
            color: #d1fae5;
            border: none;
            padding: 0.5rem 1.4rem;
            font-size: 1rem;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            letter-spacing: 0.05em;
        }

        button:hover {
            background-color: #115e59;
        }
    </style>
</head>
<body>

<header>
    <div>Ecommerce Dashboard</div>
    <form id="logoutForm">
        <button type="submit">Logout</button>
    </form>
</header>

<main>
    <h1 id="welcome">Loading...</h1>
    <p id="email-info"></p>
</main>

<script>
    const token = localStorage.getItem('ecommerce_token');
    const welcomeEl = document.getElementById('welcome');
    const emailEl = document.getElementById('email-info');

    if (!token) {
        window.location.href = '/login';
    }

    fetch('/api/user', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
        .then(res => {
            if (!res.ok) {
                throw new Error('Unauthorized');
            }
            return res.json();
        })
        .then(user => {
            welcomeEl.textContent = `Welcome, ${user.name}`;
            emailEl.textContent = `Email: ${user.email}`;
        })
        .catch(() => {
            localStorage.removeItem('foodpanda_token');
            window.location.href = '/login';
        });


    document.getElementById('logoutForm').addEventListener('submit', function (e) {
        e.preventDefault();
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        }).finally(() => {
            localStorage.removeItem('ecommerce_token');
            removeFoodpandaToken()
            window.location.href = '/login';
        });
    });
    function sendMessageToFoodpanda(message) {
        return new Promise((resolve) => {
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = 'http://127.0.0.1:8001/token-handler';
            document.body.appendChild(iframe);

            iframe.onload = () => {
                iframe.contentWindow.postMessage(message, 'http://127.0.0.1:8001');
                resolve();
                setTimeout(() => document.body.removeChild(iframe), 100);
            };
        });
    }

    async function removeFoodpandaToken() {
        await sendMessageToFoodpanda({ action: 'remove_token' });
    }
</script>
</body>
</html>
