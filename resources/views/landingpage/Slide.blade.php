<x-layout :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''">
    @section('title', 'PT. GES | Heropage')
<body class="navigasi-page">
<div class="JudulSlide text-primary" style="margin-top:100px; text-align:center;  font-family: cursive;  ">
    <h2 style="font-size:70px;">{{ $dataHeropage[0]->title_heropage }}</h2>
</div>
    <div class="mb-5 mt-3 ms-5">
        <p>
        {!! nl2br( e($dataHeropage[0]->content_heropage)) !!}
        </p>
    </div>
</body>
</x-layout>
