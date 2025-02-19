<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RepairMinder - Login</title>
    <link rel="icon" href="{{ asset('icons/maskot.png') }}" type="image/x-icon">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Font Awesome (optional, for icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --tg-color-primary: linear-gradient(180deg, #2abdbb 4%, #00aeef 98.89%);
            --tg-color-section-body: linear-gradient(
                180deg,
                #2d2b6f 0%,
                #066a8a 128.75%
            );
            --tg-color-white-default: #ffffff;
            --tg-theme-primary: #3083dc;
        }
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
        .btn {
            user-select: none;
            -moz-user-select: none;
            background: var(--tg-color-primary);
            background-image: url("../images/templatemo-wave-header.jpg"),
                linear-gradient(#348cd2, #ffffff);
            border: medium none;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -o-border-radius: 10px;
            -ms-border-radius: 10px;
            border-radius: 10px;
            color: var(--tg-color-white-default);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0;
            line-height: 1;
            margin-bottom: 0;
            padding: 18px 24px;
            text-align: center;
            text-transform: uppercase;
            touch-action: manipulation;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
            vertical-align: middle;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        .btn::before {
            content: "";
            position: absolute;
            -webkit-transition-duration: 800ms;
            transition-duration: 800ms;
            width: 200%;
            height: 200%;
            top: 110%;
            left: 50%;
            background: var(--tg-color-section-body);
            -webkit-transform: translateX(-50%);
            -moz-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            -o-transform: translateX(-50%);
            transform: translateX(-50%);
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            -o-border-radius: 50%;
            -ms-border-radius: 50%;
            border-radius: 50%;
            z-index: -1;
        }
        .btn::after {
            font-weight: 900;
            margin-left: 8px;
            font-size: 20px;
            line-height: 0;
            transition: all 0.3s ease-out 0s;
        }
        .btn:hover,
        .btn:focus-visible {
            color: var(--tg-color-white-default);
            /* background: var(--tg-theme-primary); */
        }
        .btn:hover:before,
        .btn:focus-visible:before {
            top: -40%;
        }
        .btn.btn-login {
            width: 100%;
            border-radius: 11px;
            /* padding: 20px 26px; */
            justify-content: center;
            font-size: 18px;
        }
        .btn.btn-login:hover {
            background-color: var(--tg-theme-secondary);
        }
        a{
            color: var(--tg-theme-primary);
            outline: none;
            text-decoration: none;
            -webkit-transition: all 0.3s ease-out 0s;
            -moz-transition: all 0.3s ease-out 0s;
            -ms-transition: all 0.3s ease-out 0s;
            -o-transition: all 0.3s ease-out 0s;
            transition: all 0.3s ease-out 0s;
        }

        a:focus {
            text-decoration: none;
            outline: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            -ms-box-shadow: none;
            -o-box-shadow: none;
            box-shadow: none;
        }

        a:hover {
            color: var(--tg-theme-primary);
            text-decoration: none;
        }
    </style>
    
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <img src="{{ asset('icons/maskot.png') }}" alt="Remi Logo" class="small-icon">
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
                            <button type="submit" class="btn btn-login mb-3">Login</button>
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
