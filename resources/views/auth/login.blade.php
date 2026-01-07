<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Online</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.3rem;
            color: #333;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 0.3rem;
            font-size: 0.9rem;
        }

        .form-group input:focus {
            outline: none;
            border-color: #333;
        }

        .login-btn {
            width: 100%;
            padding: 0.6rem;
            background-color: #333;
            color: #ffffff;
            border: none;
            border-radius: 0.3rem;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 1rem;
        }

        .login-btn:hover {
            background-color: #555;
        }

        .error-message {
            color: #d32f2f;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>TOKO ONLINE</h1>
            <p>Login to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Login Button -->
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
