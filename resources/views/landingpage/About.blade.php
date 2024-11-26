<x-layout :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''">
  @section('title', 'PT. GES | About Us')

  <body class="navigasi-page">
    <div id="About" class="JudulAbout">
      <h2>About Us</h2>
    </div>

    @if($dataPtges)
    <div class="content-container">
      <div class="contentAbout">
        <h2>What they say about us</h2>
        <p id="parafAbout">
          {!! nl2br(e($dataPtges->Paragraph_AboutUs)) !!}
        </p>
      </div>
      <div class="imageAbout" id="imageAbout">
        <img src="{{ asset('storage/images/' . $dataPtges->Image_AboutUs) }}" alt="About Us Image" style="border-radius:30px;">
      </div>
    </div>
    @else
    <div class="content-container">
      <p class="text-secondary">No Content Available</p>
      <div class="imageAbout" id="imageAbout">
        <img src="{{ asset('/img/default.jpg') }}" alt="About Us Image" style="border-radius:30px; height:350px; width:500px;">
      </div>
    </div>
    @endif
  </body>
</x-layout>
