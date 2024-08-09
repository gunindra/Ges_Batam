
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/"><img src="/img/logo4.png" width="100px"></a>
            <button aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-bs-target="#navbarSupportedContent" data-bs-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent" >
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('PTGes') ? 'active' : '' }}" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('About') ? 'active' : '' }}" href="/About">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('Why') ? 'active' : '' }}" href="/Why">Why Us</a>
                </li>
                @if (!Route::is('About') && !Route::is('Why'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('Services') ? 'active' : '' }}" href="#Services">Service</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('#Contact') ? 'active' : '' }}" href="#Contact">Contact Us</a>
                </li>
                @endif
            </ul>
            </div>
        </div>
    </nav>