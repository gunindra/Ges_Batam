<x-layout>

    <body class="navigasi-page">
        <div class="Judul">
            <div class="container" style="padding-top: 100px; padding-bottom: 100px">
                <div class="row">
                    <div class="col-12">
                        <div class="card"
                            style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; border-radius: 8px;">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Section Masukkan Resi -->
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <h1 style="font-size: 2rem; font-weight: bold;">Tracking Resi</h1>
                                        </div>
                                        <div class="d-flex mt-3 gap-3 align-items-center">
                                            <!-- Ubah align-items-start menjadi align-items-center -->
                                            <div class="flex-grow-1">
                                                <div class="mt-1">
                                                    <label for="trackingResi" class="form-label"
                                                        style="font-weight: bold;">Masukkan No Resi</label>
                                                    <input type="text" class="form-control" id="trackingResi"
                                                        placeholder="Masukkan nomor resi"
                                                        style="padding: 10px; border-radius: 5px;">
                                                    <div id="err-trackingResi" class="text-danger d-none"
                                                        style="color: red;">Silahkan Masukkan Nomor Resi</div>
                                                </div>
                                            </div>
                                            <div class="ml-2 mt-4">
                                                <!-- Beri margin agar ada jarak antara input dan tombol -->
                                                <button id="btnLacak" class="btn btn-primary mt-2"
                                                    style="padding: 10px 20px; font-weight: bold;">Lacak</button>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Section View Status -->
                                    <div class="col-6" id="statusSection" style="margin-top: 20px;">
                                        <h2 style="font-size: 1.5rem; font-weight: bold;">Status Pengiriman</h2>
                                        <div class="card"
                                            style="box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; border-radius: 5px;">
                                            <div class="card-body" id="trackingInfo">
                                                <!-- Default content when no resi is entered -->
                                                <p class="text-muted" style="color: #6c757d;">Masukkan nomor resi untuk
                                                    melihat status pengiriman.</p>
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

                        if (noresi === '') {
                            $('#err-trackingResi').removeClass('d-none');
                            return;
                        } else {
                            $('#err-trackingResi').addClass('d-none');
                        }

                        $.ajax({
                            type: "GET",
                            url: "{{ route('lacakResi') }}",
                            data: {
                                noresi: noresi
                            },
                            success: function(response) {
                                let contentHTML = '';

                                if (response.length > 0) {
                                    let data = response[0];

                                    contentHTML = `
                        <div style="display: flex; justify-content: space-between;">
                            ${data.no_resi ? `
                                            <div style="flex: 1;">
                                                <label class="form-label" style="font-weight: bold;">No Resi :</label>
                                                <p style="font-weight: bold;">${data.no_resi}</p>
                                            </div>
                                            ` : ''}
                            ${data.nama_pembeli ? `
                                            <div style="flex: 1;">
                                                <label class="form-label" style="font-weight: bold;">Customer :</label>
                                                <p style="font-weight: bold;">${data.nama_pembeli}</p>
                                            </div>
                                            ` : ''}
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                            ${data.status_name ? `
                                            <div style="flex: 1;">
                                                <label class="form-label" style="font-weight: bold;">Status :</label>
                                                <span style="display: inline-block; background-color: #17a2b8; color: white; padding: 5px 10px; border-radius: 5px;">${data.status_name}</span>
                                            </div>
                                            ` : ''}
                            ${data.nama_supir ? `
                                            <div style="flex: 1;">
                                                <label class="form-label" style="font-weight: bold;">Nama Driver :</label>
                                                <p style="font-weight: bold;">${data.nama_supir}</p>
                                            </div>
                                            ` : ''}
                        </div>
                        ${data.full_address || data.alamat ? `
                                        <div style="margin-top: 20px;">
                                            <label class="form-label" style="font-weight: bold;">Alamat :</label>
                                            <p style="font-weight: bold;">${data.alamat ? data.alamat : ''}${data.full_address ? `, ${data.full_address}` : ''}</p>
                                        </div>
                                        ` : ''}
                        ${data.no_wa ? `
                                        <div style="margin-top: 10px;">
                                            <label class="form-label" style="font-weight: bold;">No. Telepon :</label>
                                            <p style="font-weight: bold;">${data.no_wa}</p>
                                        </div>
                                        ` : ''}
                    `;
                                } else {
                                    contentHTML = `
                        <div class="alert alert-danger" role="alert" style="padding: 10px; border-radius: 5px;">
                            Data tidak ditemukan untuk nomor resi <strong>"${noresi}"</strong>.
                        </div>
                    `;
                                }

                                $('#trackingInfo').html(contentHTML);
                            },
                            error: function() {
                                showMessage("error", "Terjadi kesalahan, coba lagi nanti.");
                            }
                        });
                    });
                });
            </script>
        @endsection
    </body>
</x-layout>
