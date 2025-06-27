<!DOCTYPE html>
<html>
<head>
    <title>Token Handler</title>
</head>
<body>
<script>
    window.addEventListener('message', event => {
        if (event.origin !== 'http://127.0.0.1:8001') return;

        const { action, token } = event.data;

        if (action === 'store_token') {
            localStorage.setItem('ecommerce_token', token);
            console.log('Ecommerce token stored');
        }

        if (action === 'remove_token') {
            localStorage.removeItem('ecommerce_token');
            console.log('Ecommerce token removed');
        }
    });
</script>
</body>
</html>
