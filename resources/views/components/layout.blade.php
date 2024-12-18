<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PT. GES Logistic - Solusi pengiriman cepat dan ekonomis dari Cina ke Indonesia. Hanya di GesBatam.">
    <meta name="keywords" content="PT GES Logistic, logistik, pengiriman barang, Cina ke Indonesia, cepat dan ekonomis">
    <meta name="robots" content="index, follow">
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">
    <meta name="geo.position" content="-6.200000;106.816666">
    <meta name="ICBM" content="-6.200000, 106.816666">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="PT. GES Logistic - Pengiriman Cepat dan Ekonomis dari Cina">
    <meta property="og:description" content="Nikmati layanan pengiriman cepat dan hemat dari Cina ke Indonesia bersama GesBatam.">
    <meta property="og:url" content="https://www.gesbatam.com/">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://www.gesbatam.com/img/logo4.png">

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="PT. GES Logistic - Pengiriman Cepat dan Ekonomis dari Cina">
    <meta name="twitter:description" content="Layanan pengiriman cepat dan hemat dari Cina ke Indonesia bersama GesBatam.">
    <meta name="twitter:image" content="https://www.gesbatam.com/img/logo4.png">
    <link rel="icon" type="image/x-icon" href="{{ asset('/logo.svg') }}">

    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Moon+Dance&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body style="background-color:#F8EDFF;">

    <x-navbar></x-navbar>

    <main>
        {{ $slot }}
    </main>

    <x-footer :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''"></x-footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f89fc2c44e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async=""></script>
    <script src="/js/script.js"></script>
    @yield('script')
</body>
</html>
