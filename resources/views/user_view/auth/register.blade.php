<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RepairMinder - Register</title>
    <link rel="icon" href="{{ asset('icons/maskot.png') }}" type="image/x-icon">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/register-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Font Awesome (optional, for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center mb-4 fw-bold font-monospace">Register</h3>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input name="name" type="text" id="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input name="email" type="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Masukkan email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <div class="password-group">
                                    <input name="password" type="password" id="password" class="form-control" placeholder="Masukkan password">
                                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'togglePasswordIcon')">
                                        <i id="togglePasswordIcon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password</label>
                                <div class="password-group">
                                    <input name="password_confirmation" type="password" id="password_confirmation" class="form-control" placeholder="Konfirmasi password">
                                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmIcon')">
                                        <i id="togglePasswordConfirmIcon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Sudah memiliki akun? <a href="{{ route('login') }}" class="text-link">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional Custom JS for Password Toggle -->
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Bootstrap JS and Popper (needed for Bootstrap components) -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
