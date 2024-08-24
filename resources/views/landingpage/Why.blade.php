<x-layout>
  @section('title', 'PT. GES | Why Us')
  <body class="navigasi-page">
    <div class="Judulwhy">
      <h2>Why Us</h2>
    </div>
    @if($dataWhy)
    <div class="content-containerwhy">
      <div class="imageWhy">
        <img src="{{ asset('storage/images/' . $dataWhy[0]->Image_WhyUs) }}" id="imageWhy" alt="Why Us Image">
      </div>
      <div class="contentwhy">
        <h2 id="judulWhy">Why Choose Us</h2>
        <p id="parafWhy">{{ $dataWhy[0]->Paraf_WhyUs }}</p>
      </div>
    </div>
    @else
    <div class="content-container">
      <p>No data found for the given ID.</p>
    </div>
    @endif
  </body>
</x-layout>
