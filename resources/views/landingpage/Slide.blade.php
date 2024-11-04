<x-layout :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''">
    @section('title', 'PT. GES | Heropage')

    <body class="navigasi-page">
    <div class="JudulSlide text-primary" style="margin-top:100px; text-align:center; font-family: Roboto;">
        <h2 style=" word-wrap: break-word; word-break: break-word; max-width: 1500px; margin-left: 50px; margin-right: 50px;">
            {{ $dataHeropage[0]->title_heropage }}
        </h2>
    </div>
    <div class="contentSlide mb-5 mt-3 ms-5" style="max-width: 1500px; word-wrap: break-word; word-break: break-word ;margin-right: 50px;">
        <p>
            {!! nl2br(e($dataHeropage[0]->content_heropage)) !!}
        </p>
    </div>
</body>
</x-layout>