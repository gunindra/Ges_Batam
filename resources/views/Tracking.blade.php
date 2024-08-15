<x-layout>

    <body class="navigasi-page">
        <div class="Judul">
            <div class="container" style="padding-top: 100px; padding-bottom: 100px">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Section Masukkan Resi -->
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <h1>Trancking Resi</h1>
                                        </div>
                                        <div class="d-flex mt-3">
                                            <div class="flex-grow-1">
                                                <label for="trackingResi" class="form-label">Masukkan No Resi</label>
                                                <input type="text" class="form-control" id="trackingResi"
                                                    value="">
                                                <div id="err-trackingResi" class="text-danger mt-1 d-none">Silahkan
                                                    Masukkan Nomer Resi</div>
                                            </div>
                                            <button id="btnLacak"
                                                class="btn btn-primary ms-3 align-self-end">Lacak</button>
                                        </div>
                                    </div>

                                    <!-- Section View Status -->
                                    <div class="col-6">
                                        <h2>Status Pengiriman</h2>
                                        <div class="d-flex">
                                            <div class="col-3">
                                                <div class="mt-3">
                                                    <label class="form-label">No Resi :</label>
                                                    <p id="noResiView" class="mb-1">1234567890</p>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="form-label">Costumer :</label>
                                                    <p id="namaCostumer" class="mb-1">Tandrio</p>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <div class="d-flex mt-3">
                                                    <label class="form-label px-2">Status:</label>
                                                    <p id="statusPengiriman" class="badge-delivering mb-1">Delivering</p>
                                                </div>

                                                <div class="d-flex mt-3">
                                                    <label class="form-label px-2">Nama Driver:</label>
                                                    <p id="namaBarang" class="mb-1">Narto</p>
                                                </div>
                                                <div class="d-flex mt-3">
                                                    <label class="form-label px-2">No. Telfon:</label>
                                                    <p id="namaSupir" class="mb-1">0821391432942</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @section('script')
            <script>
                $(document).ready(function() {


                    $('#btnLacak').click(function() {
                        let noresi = $('#trackingResi').val();
                        $.ajax({
                            type: "GET",
                            url: "{{ route('lacakResi') }}",
                            data: {
                                noresi : noresi
                            },
                            success: function (response) {

                            }
                        });

                    })

                });
            </script>
        @endsection
    </body>
</x-layout>
