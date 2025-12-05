<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Prewed | Login</title>
    <link rel="stylesheet" href="{{ asset('asset/HOMEPAGE/FITUR/login.css') }}">
</head>
<style>
    
</style>
<body>
    <div class="login-container">
        <div class="login-box">
            <!-- Logo -->
            <img src="{{ asset('asset/PICTURESET/LOGOSPLOGIN.png') }}" alt="Studio Prewed Logo" class="logo">

            <h2>Login</h2>

            <form action="{{ route('login.verify') }}" method="POST">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn-login">Login</button>
            </form>

            <p>Don’t have account?</p>
            <a href="{{ route('Registrasi') }}" class="btn-login">Register Here</a>
            <a href="{{ url('/') }}" class="btn-login">Login As Guest</a>
        </div>
    </div>
</body>
</html>

