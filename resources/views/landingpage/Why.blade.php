<x-layout :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''">
  @section('title', 'PT. GES | Why Us')
  <body class="navigasi-page">
    <div class="Judulwhy">
      <h2>Why Us</h2>
    </div>
    @if($dataPtges)
    <div class="content-containerwhy">
      <div class="imageWhy">
        <img src="{{ asset('storage/images/' . $dataPtges->Image_WhyUs) }}" id="imageWhy" alt="Why Us Image">
      </div>
      <div class="contentwhy">
        <h2 id="judulWhy">Why Choose Us</h2>
        <p id="parafWhy" style=" word-break: break-word; ">{!! nl2br( e( $dataPtges->Paragraph_WhyUs )) !!}</p>
      </div>
    </div>
    @else
    <div class="content-containerwhy">
    <div class="imageWhy">
    <img src="{{ asset('/img/default.jpg') }}" alt="Why Us Image" style="height:350px; width:500px;">
      </div>
     
    <div class="contentwhy">
      <p>-</p>
    </div>
    </div>
    
    @endif
  </body>
</x-layout>
