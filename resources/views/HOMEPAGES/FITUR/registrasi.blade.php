<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Studio Prewed</title>
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/FITUR/registrasi.css') }}">
</head>
<body>
    <div class="overlay">
        <div class="register-box">
            <!-- Bagian kiri -->
            <div class="left">
                <img src="{{ asset('asset/PICTURESET/LOGOSPREGISTRASIDARK.png') }}" alt="Studio Prewed Logo" class="logo">
            </div>

            <!-- Bagian kanan -->
            <div class="right">
                <h2 class="form-title">Registrasi</h2>
                <form action="{{ route('register') }}" method="POST">
                     @csrf
                    <input type="text" name="name" placeholder="Nama" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                    <button type="submit">Registrasi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
