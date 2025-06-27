
# Laravel Multi-App Auth: Ecommerce & Foodpanda

This project demonstrates **multi-app authentication** using **Laravel Sanctum**, allowing synchronized login and logout between two Laravel applications (`ecommerce` and `foodpanda`) through `iframe` + `postMessage` technique and token-based API auth.

---

## 🛠 Requirements

- PHP >= 8.1
- Composer
- Laravel = 12
- Node.js (for frontend assets if needed)
- MySQL
- Browser with `localStorage` support

---

## 📁 Application URLs

| App Name  | URL                   | Port  |
|-----------|------------------------|-------|
| Ecommerce | http://localhost:8000  | 8000  |
| Foodpanda | http://localhost:8001  | 8001  |

---

## 🔧 Installation Steps (Both Apps)

Repeat these steps in **both** `ecommerce` and `foodpanda` directories:

```bash
git clone https://github.com/shekhmanzurelahishadhin/ecommerce-app.git
git clone https://github.com/shekhmanzurelahishadhin/foodpanda-app.git
```

```bash
cd ecommerce         # or foodpanda
composer install
cp .env.example .env
php artisan key:generate
```

Update `.env` values:

For `ecommerce`:
```
APP_NAME=Ecommerce
APP_URL=http://localhost:8000
```

For `foodpanda`:
```
APP_NAME=Foodpanda
APP_URL=http://localhost:8001
```

Set up your database credentials and then run:

```bash
php artisan migrate
```

---

## 🚀 Run on Different Ports

**Terminal 1 (Ecommerce):**

```bash
cd ecommerce
php artisan serve --port=8000
```

**Terminal 2 (Foodpanda):**

```bash
cd foodpanda
php artisan serve --port=8001
```

---

## 🔐 Sanctum API Authentication Setup

Run in both apps:

```bash
php artisan install:api
```


## 📦 Routes Overview

### API Routes (`routes/api.php`)

```php
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/logout', [AuthController::class, 'apiLogout']);
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());
```

### Web Routes (`routes/web.php`)

```php
Route::view('/login', 'login')->name('login');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/token-handler', 'token-handler');
```

---

## 🌐 Cross-App Token Sharing

- Ecommerce login (`http://localhost:8000/login`) logs in both apps via API.
- Ecommerce stores its token in `localStorage` as `ecommerce_token`.
- Ecommerce sends a postMessage using an invisible iframe to foodpanda (`http://localhost:8001/token-handler`).
- Foodpanda saves its token as `foodpanda_token`.
- Logout in ecommerce also sends a postMessage to foodpanda to clear the token.

---

## 📥 Storage Keys in Browser

| App        | Token Key          |
|------------|--------------------|
| Ecommerce  | `ecommerce_token`  |
| Foodpanda  | `foodpanda_token`  |

---

## 🧪 Testing the Flow

1. Visit: `http://localhost:8000/login`
2. Enter credentials and log in.
3. Token will be saved in both apps.
4. Visit `http://localhost:8001/dashboard` and you'll already be logged in.
5. Logout from either app will remove both tokens.

---

## 📂 Key Files

| File                          | Purpose                            |
|-------------------------------|-------------------------------------|
| `login.blade.php`            | Form to login and sync both apps   |
| `dashboard.blade.php`        | Authenticated UI using token       |
| `token-handler.blade.php`    | Receives and stores token via postMessage |
| `AuthController.php`         | Handles login/logout API           |

---


## 👨‍💻 Author

Shekh Manzur Elahi
