<x-layout :contact="$contact">
    @section('title', 'PT. GES | Carousel')
<body class="navigasi-page">
<div class="JudulSlide text-primary" style="margin-top:100px; text-align:center;  font-family: cursive;  ">
    <h2 style="font-size:70px;">{{ $dataCarousel[0]->judul_carousel }}</h2>
</div>
    <div class="mb-5 mt-3 ms-5">
        <p>
        {{ $dataCarousel[0]->isi_carousel }}
        </p>
    </div>
</body>
</x-layout>
