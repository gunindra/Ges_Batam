<x-layout>
  @section('title', 'PT. GES | About Us')

  <body class="navigasi-page">
    <div class="JudulAbout">
      <h2>About Us</h2>
    </div>

    @if($dataAbout)
    <div class="content-container">
      <div class="contentAbout">
        <h2>What they say about us</h2>
        <p id="parafAbout">
          {{ $dataAbout[0]->Paraf_AboutUs }}
        </p>
      </div>
      <div class="imageAbout" id="imageAbout">
        <img src="{{ asset('storage/images/' . $dataAbout[0]->Image_AboutUs) }}" alt="About Us Image" style="border-radius:30px;">
      </div>
    </div>
    @else
    <div class="content-container">
      <p>No data found for the given ID.</p>
    </div>
    @endif
  </body>
</x-layout>
