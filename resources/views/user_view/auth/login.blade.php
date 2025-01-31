<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RepairMinder - Login</title>
    <link rel="icon" href="{{ asset('icons/remi-icon.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/login-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        .input-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
        }
    
        .position-relative .form-control {
            padding-right: 2.5rem; /* Memberi ruang untuk ikon */
        }
    </style>
    
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <img src="{{ asset('icons/remi-icon.png') }}" alt="Remi Logo" class="small-icon">
                        <h3 class="text-center mb-4 fw-bold font-monospace">REPAIRMINDER</h3>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input name="email" id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="Masukkan email" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Password Field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input name="password" type="password" 
                                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                           id="floatingPassword" 
                                           placeholder="Password" 
                                           required>
                                    <i class="fa fa-eye-slash input-icon" id="togglePasswordIcon" onclick="togglePassword()"></i>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                            <!-- Forgot Password Link -->
                            <div class="mt-2 text-end">
                                <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted">Lupa Password?</a>
                            </div>
                        </form>
                        <!-- Register Link -->
                        <div class="mt-3 text-center">
                            <p>Belum memiliki akun? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Toggle the input type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the "show" class for the CSS pseudo-element
            this.classList.toggle('show');
        });
    </script> --}}

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("floatingPassword");
            var toggleIcon = document.getElementById("togglePasswordIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

    <!-- Bootstrap JS and Popper (needed for Bootstrap components) -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('sweetalert::alert')

</body>
</html>
