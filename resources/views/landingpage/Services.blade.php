<x-layout>
    @section('title', 'PT. GES | Services')
    <body class="navigasi-page">
        <div class="container d-flex justify-content-center mb-5">
            <div class="card col-lg-8 col-md-10 col-sm-12 mb-4" style="margin-top:100px;">
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center mb-5">
                        <img src="{{ asset('storage/images/' . $dataService[0]->image_service) }}" alt="{{ $dataService[0]->judul_service }}" class="img-fluid" style="max-width: 100%; height: auto;">
                    </div>
                    <div class="col-12">
                        <h3 class="text-primary">{{ $dataService[0]->judul_service }}</h3>
                        <p>{{ $dataService[0]->isi_service }}</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</x-layout>