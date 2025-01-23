<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RepairMinder - Register</title>
    <link rel="icon" href="{{ asset('icons/remi-icon.png') }}" type="image/x-icon">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/register-page.css') }}">
    <!-- Font Awesome (optional, for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center mb-4">Register</h3>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}" placeholder="Enter your name">
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Enter Email">
                            @error('email')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <div class="password-group">
                                <input name="password" type="password" id="password" class="form-control" placeholder="Enter password">
                                <button type="button" class="toggle-password" onclick="togglePassword('password', 'togglePasswordIcon')">
                                    <i id="togglePasswordIcon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password Confirmation</label>
                            <div class="password-group">
                                <input name="password_confirmation" type="password" id="password_confirmation" class="form-control" placeholder="Confirm password">
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
                        <p>Already have an account? <a href="{{ route('login') }}" class="text-link">Login</a></p>
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
</body>
</html>
