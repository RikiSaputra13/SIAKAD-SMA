<!DOCTYPE html>
<html>
<head>
    <title>Login - SMA Pangeran Jayakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: none;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border-color: #ddd;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #500a9e;
        }
        .password-container {
            position: relative;
        }
        .password-container .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="card shadow" style="width: 400px;">
        <h4 class="text-center mb-4" style="color: #333;">Login Sistem Akademik</h4>
        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label" style="color: #555;">Email / Username</label>
                <input type="text" name="email" id="email" class="form-control" autocomplete="off" required>
            </div>
            <div class="mb-4 password-container">
                <label for="password" class="form-label" style="color: #555;">Password</label>
                <input type="text" name="password" id="password" class="form-control" required>
                <i class="fas fa-eye-slash toggle-password"></i>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trik untuk menonaktifkan autofill browser
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                // Tunda sedikit sebelum mengubah tipe input kembali ke password
                setTimeout(function() {
                    passwordInput.setAttribute('type', 'password');
                }, 100);
            }

            // Fungsionalitas lihat password
            const togglePassword = document.querySelector('.toggle-password');
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>