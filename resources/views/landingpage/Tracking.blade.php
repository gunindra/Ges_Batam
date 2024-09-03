<x-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/inputTags.css') }}">
    @endpush
    @stack('styles')
    @section('title', 'PT. GES | Tracking')

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
                                    <div class="col-8">
                                        <div class="d-flex align-items-center">
                                            <h1 style="font-size: 2rem; font-weight: bold;">Tracking Resi</h1>
                                        </div>
                                        <div class="d-flex mt-3 gap-3 align-items-center">
                                            <!-- Ubah align-items-start menjadi align-items-center -->
                                            <div class="flex-grow-1">
                                                <div class="mt-1">
                                                    <label for="tags" class="form-label"
                                                        style="font-weight: bold;">Masukkan No Resi</label>
                                                    <input type="text" class="form-control" id="tags"
                                                        name="tags" placeholder="Masukkan nomor resi"
                                                        style="padding: 10px; border-radius: 5px;">
                                                    <div id="err-tags" class="text-danger d-none" style="color: red;">
                                                        Silahkan Masukkan Nomor Resi</div>
                                                </div>
                                            </div>
                                            <div class="ml-2 mt-4">
                                                <!-- Beri margin agar ada jarak antara input dan tombol -->
                                                <button id="btnLacak" class="btn btn-primary"
                                                    style="padding: 10px 20px; font-weight: bold;">Lacak</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="trackingTableContainer" class="row mt-4 d-none">
                                        <div class="col-12">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No. Resi</th>
                                                        <th>No. DO</th>
                                                        <th>Status</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="trackingTableBody">
                                                    <!-- Data akan ditambahkan di sini melalui JavaScript -->
                                                </tbody>
                                            </table>
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
                    $('#tags').inputTags({
                        tags: [],
                        autocomplete: {
                            values: []
                        },
                        create: function() {
                            $('span', '#events').text('create');
                        },
                        update: function() {
                            $('span', '#events').text('update');
                        },
                        destroy: function() {
                            $('span', '#events').text('destroy');
                        },
                        selected: function() {
                            $('span', '#events').text('selected');
                        },
                        unselected: function() {
                            $('span', '#events').text('unselected');
                        },
                        change: function(elem) {
                            $('.results').empty().html('<strong>Tags:</strong> ' + elem.tags.join(', '));
                        }
                    });

                    $('#btnLacak').click(function() {
                        let noresiTags = $('#tags').val().trim();
                        if (noresiTags === '') {
                            $('#err-tags').removeClass('d-none');
                            return;
                        } else {
                            $('#err-tags').addClass('d-none');
                        }
                        $.ajax({
                            type: "GET",
                            url: "{{ route('lacakResi') }}",
                            data: {
                                noresiTags: noresiTags
                            },
                            success: function(response) {
                                let tableBody = $('#trackingTableBody');
                                tableBody.empty();

                                if (response.length > 0) {
                                    response.forEach(function(item) {
                                        let row = `<tr>
                        <td>${item.no_resi}</td>
                        <td>${item.no_do}</td>
                        <td>${item.status}</td>
                        <td>${item.keterangan}</td>
                        `;
                                        tableBody.append(row);
                                    });

                                    $('#trackingTableContainer').removeClass('d-none');
                                } else {
                                    $('#trackingTableContainer').removeClass(
                                    'd-none'); // Pastikan container tetap muncul meski data kosong

                                    let emptyRow =
                                        `<tr><td colspan="5" class="text-center">Data tidak ditemukan</td></tr>`;
                                    tableBody.append(emptyRow);
                                }
                            },
                            error: function() {
                                showMessage("error", "Terjadi kesalahan, coba lagi nanti.");
                            }
                        });
                    });

                });
            </script>
            @push('scripts')
                <script src="{{ asset('js/index.js') }}"></script>
            @endpush
            @stack('scripts')
        @endsection

    </body>
</x-layout>
