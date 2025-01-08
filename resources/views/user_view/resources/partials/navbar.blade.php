<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-lg-5 me-0" href="index.html">
            <img src="images/sijates.png" class="logo-image img-fluid" alt="templatemo pod talk">
        </a>

        <form action="#" method="get" class="custom-form search-form flex-fill me-3" role="search">
            <div class="input-group input-group-lg">
                <!-- <input name="search" type="search" class="form-control" id="search" placeholder="Search"
                    aria-label="Search"> -->

                <!-- <button type="submit" class="form-control" id="submit">
                    <i class="bi-search"></i>
                </button> -->
            </div>
        </form>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-lg-auto">
                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link" href="about.html">Tentang Kami</a>
                </li>

                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarLightDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Pages</a>

                    <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="navbarLightDropdownMenuLink">
                        <li><a class="dropdown-item" href="listing-page.html">Listing Page</a></li>

                        <li><a class="dropdown-item active" href="detail-page.html">Detail Page</a></li>
                    </ul>
                </li> -->

                <!-- <li class="nav-item">
                    <a class="nav-link" href="contact.html">Kontak</a>
                </li> -->
            </ul>

            @auth
            <div class="ms-4">
                <a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn custom-btn custom-border-btn smoothscroll">Log Out</a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
            </form>
            @else
            <div class="ms-4">
                <a href="{{route('login')}}" class="btn custom-btn custom-border-btn smoothscroll">Log In</a>
            </div>
            @endauth
        </div>
    </div>
</nav>
