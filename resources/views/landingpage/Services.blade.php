<x-layout :dataPtges="$dataPtges ?? ''" :wa="$wa ?? ''">
    @section('title', 'PT. GES | Services')
    <body class="navigasi-page">
        <div class="container d-flex justify-content-center mb-5">
            <div class="card col-lg-8 col-md-10 col-sm-12 mb-4" style="margin-top:100px; border-radius:20px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-5">
                        <img src="{{ asset('storage/images/' . $dataService[0]->image_service) }}" alt="{{ $dataService[0]->title_service }}" class="img-fluid" style="max-width: 100%; height: 500px; border-radius:20px;">
                    </div>
                    <div class="col-12">
                        <h3 class="text-primary">{{ $dataService[0]->title_service }}</h3>
                        <p>{!! nl2br( e( $dataService[0]->content_service )) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-layout>