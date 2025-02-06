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
                                        <i id="togglePasswordIcon" class="fas fa-eye input-icon"></i>
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
                                        <i id="togglePasswordConfirmIcon" class="fas fa-eye input-icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-login">Register</button>
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
