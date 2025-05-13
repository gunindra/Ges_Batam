@extends('layout.main')

@section('title', 'Accounting | Journal')

@section('main')

    <style>
        .dataTables_length,
        .dataTables_filter {
            display: none;
        }

        #menuBkk .table,
        #menuBkm .table,
        #jurnalUmum .table {
            width: 100% !important;
        }

        footer {
            margin-top: 100px;
        }
    </style>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Journal</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Accounting</li>
                <li class="breadcrumb-item active" aria-current="page">Journal</li>
            </ol>
        </div>

        <!-- Modal Filter Tanggal -->
        <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
            aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFilterTanggalTitle">Filter Tanggal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-3">
                                    <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal:</label>
                                    <div class="d-flex align-items-center">
                                        <input type="date" id="startDate" class="form-control"
                                            placeholder="Pilih tanggal mulai" style="width: 200px;">
                                        <span class="mx-2">sampai</span>
                                        <input type="date" id="endDate" class="form-control"
                                            placeholder="Pilih tanggal akhir" style="width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="saveFilterTanggal" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="jurnalModal" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Jurnal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>No Jurnal:</strong> <span id="modalNoJurnal"></span></p>
                        <p><strong>Tanggal:</strong> <span id="modalTanggal"></span></p>
                        <p><strong>Deskripsi:</strong> <span id="modalDeskripsi"></span></p>
                        <p><strong>Total Debit:</strong> <span id="modalTotalDebit"></span></p>
                        <p><strong>Total Credit:</strong> <span id="modalTotalCredit"></span></p>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Akun</th>
                                    <th>Deskripsi</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Memo</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemList"></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total</th>
                                    <th id="modalTotalDebitFooter"></th>
                                    <th id="modalTotalCreditFooter"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-links nav-link active" aria-current="page" href="#" data-tab="jurnalUmum">Jurnal
                            Umum</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links nav-link" href="#" data-tab="menuBkk">Menu Jurnal BKK</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-links nav-link" href="#" data-tab="menuBkm">Menu Jurnal BKM</a>
                    </li>
                </ul>
                <div class="tab-content container-fluid mt-3" id="container-wrapper">
                    <!-- Jurnal Umum -->
                    <div id="jurnalUmum" class="tab-pane fade show active">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex mb-2 mr-3 float-right">
                                            <a class="btn btn-primary" href="{{ route('addjournal') }}?code_type=Jurnal">
                                                <span class="pr-2"><i class="fas fa-plus"></i></span>Buat Journal
                                            </a>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <!-- Search -->
                                            <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                                class="form-control rounded-3" placeholder="Search">
                                            <select class="form-control ml-2" id="filterStatus" style="width: 200px;">
                                                <option value="" selected disabled>Pilih Filter</option>
                                                @foreach ($uniqueStatuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter
                                                Tanggal</button>
                                            <button type="button" class="btn btn-outline-primary ml-2"
                                                id="btnResetDefault" onclick="window.location.reload()">Reset</button>
                                        </div>
                                        <div id="containerJournal" class="table-responsive px-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="tableJournal">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No. Journal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table Data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jurnal BKK -->
                    <div id="menuBkk" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex mb-2 mr-3 float-right">
                                            <a class="btn btn-primary" href="{{ route('addjournal') }}?code_type=BKK"
                                                id="">
                                                <span class="pr-2"><i class="fas fa-plus"></i></span>Buat Journal
                                            </a>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <!-- Search -->
                                            <input id="txSearch2" type="text" style="width: 250px; min-width: 250px;"
                                                class="form-control rounded-3" placeholder="Search">
                                            <select class="form-control ml-2" id="filterStatusBkk" style="width: 200px;">
                                                <option value="" selected disabled>Pilih Filter</option>
                                                @foreach ($uniqueStatuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter
                                                Tanggal</button>
                                            <button type="button" class="btn btn-outline-primary ml-2"
                                                id="btnResetDefault" onclick="window.location.reload()">Reset</button>
                                        </div>
                                        <div id="containerBKK" class="table-responsive px-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="tableJournalBKK">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No. Journal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table Data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jurnal BKM -->
                    <div id="menuBkm" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex mb-2 mr-3 float-right">
                                            <a class="btn btn-primary" href="{{ route('addjournal') }}?code_type=BKM"
                                                id=""><span class="pr-2"><i
                                                        class="fas fa-plus"></i></span>Buat Journal</a>
                                        </div>
                                        <div class="d-flex mb-4">
                                            <!-- Search -->
                                            <input id="txSearch3" type="text" style="width: 250px; min-width: 250px;"
                                                class="form-control rounded-3" placeholder="Search">
                                            <select class="form-control ml-2" id="filterStatusBkm" style="width: 200px;">
                                                <option value="" selected disabled>Pilih Filter</option>
                                                @foreach ($uniqueStatuses as $status)
                                                    <option value="{{ $status->status }}">{{ $status->status }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter
                                                Tanggal</button>
                                            <button type="button" class="btn btn-outline-primary ml-2"
                                                id="btnResetDefault" onclick="window.location.reload()">Reset</button>
                                        </div>
                                        <div id="containerBKM" class="table-responsive px-3">
                                            <table class="table align-items-center table-flush table-hover"
                                                id="tableJournalBKM">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>No. Journal</th>
                                                        <th>Deskripsi</th>
                                                        <th>Tanggal</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Table Data -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of tab-content -->
            </div>
        </div>
    </div> <!-- End of container-fluid -->

@endsection
@section('script')

    <script>
        $('.nav-links').on('click', function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');

            $('.tab-pane').removeClass('show active');
            $('#' + tab).addClass('show active');

            $('.nav-links').removeClass('active');
            $(this).addClass('active');
        });
    </script>
    <script>
        $(document).ready(function() {
            function initTableJournal(tipeKode, excludeTypes = []) {
                return $('#tableJournal' + (tipeKode || '')).DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('journal.data') }}",
                        method: 'GET',
                        data: function(d) {
                            d.startDate = $('#startDate').val();
                            d.endDate = $('#endDate').val();
                            // Periksa status berdasarkan tab aktif
                            if ($('#jurnalUmum').hasClass('active')) {
                                d.status = $('#filterStatus').val();
                            } else if ($('#menuBkk').hasClass('active')) {
                                d.status = $('#filterStatusBkk').val();
                            } else if ($('#menuBkm').hasClass('active')) {
                                d.status = $('#filterStatusBkm').val();
                            }
                            d.tipe_kode = tipeKode;
                            if (excludeTypes.length > 0) {
                                d.excludeTypes = excludeTypes;
                            }
                        }
                    },
                    columns: [{
                            data: 'no_journal',
                            name: 'no_journal'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'tanggalFormat',
                            name: 'tanggalFormat'
                        },
                        {
                            data: 'totalcredit',
                            name: 'totalcredit'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    order: [],
                    lengthChange: false,
                    language: {
                        processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                        info: "_START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        emptyTable: "No data available in table",
                        loadingRecords: "Loading...",
                        zeroRecords: "No matching records found"
                    }
                });
            }

            function debounce(func, delay) {
                let timer;
                return function(...args) {
                    const context = this;
                    clearTimeout(timer);
                    timer = setTimeout(() => func.apply(context, args), delay);
                };
            }

            let tableGeneral = initTableJournal('', ['BKK', 'BKM']);
            let tableBKK = initTableJournal('BKK');
            let tableBKM = initTableJournal('BKM');

            $('a[data-tab]').on('click', function(e) {
                e.preventDefault();
                let tab = $(this).data('tab');

                $('#jurnalUmum, #menuBkk, #menuBkm').removeClass('show active');
                $('#' + tab).addClass('show active');

                if (tab === 'jurnalUmum') {
                    tableGeneral.draw();
                } else if (tab === 'menuBkk') {
                    tableBKK.draw();
                } else if (tab === 'menuBkm') {
                    tableBKM.draw();
                }
            });

            $(document).ready(function() {
                let lastEditedData = sessionStorage.getItem('lastEditedJournal');
                let lastEditedPage = sessionStorage.getItem('lastEditedPage');
                let lastActiveTab = sessionStorage.getItem('lastActiveTab');

                if (lastActiveTab) {
                    $('a[data-tab="' + lastActiveTab + '"]').trigger('click');
                }

                if (lastEditedData && lastEditedPage) {
                    let [id, type] = lastEditedData.split('-');
                    let targetTab, targetTable;

                    if (type === 'BKM') {
                        targetTab = 'menuBkm';
                        targetTable = tableBKM;
                    } else if (type === 'BKK') {
                        targetTab = 'menuBkk';
                        targetTable = tableBKK;
                    } else {
                        targetTab = 'jurnalUmum';
                        targetTable = tableGeneral;
                    }

                    // Aktifkan tab yang sesuai
                    $('a[data-tab="' + targetTab + '"]').trigger('click');

                    // Tunggu sampai data selesai dimuat sebelum berpindah ke halaman yang benar
                    targetTable.one('xhr.dt', function() {
                        setTimeout(() => {
                            let pageIndex = parseInt(lastEditedPage, 10) || 0;
                            targetTable.page(pageIndex).draw(false);

                            setTimeout(() => {
                                let rowIndex = targetTable
                                    .rows()
                                    .data()
                                    .toArray()
                                    .findIndex(row => row.id == id);

                                if (rowIndex !== -1) {
                                    let rowNode = $(targetTable.row(rowIndex)
                                        .node());
                                    rowNode.addClass(
                                        'highlight');
                                } else {
                                    console.warn(
                                        "Data tidak ditemukan di halaman ini:",
                                        id);
                                }
                            }, 500);

                        }, 500);
                    });

                    // Bersihkan sessionStorage setelah digunakan
                    sessionStorage.removeItem('lastEditedJournal');
                    sessionStorage.removeItem('lastEditedPage');
                }

                // Simpan tab aktif di sessionStorage saat berpindah tab
                $('a[data-tab]').on('click', function(e) {
                    e.preventDefault();
                    let tab = $(this).data('tab');

                    $('#jurnalUmum, #menuBkk, #menuBkm').removeClass('show active');
                    $('#' + tab).addClass('show active');

                    sessionStorage.setItem('lastActiveTab', tab);

                    if (tab === 'jurnalUmum') {
                        tableGeneral.draw();
                    } else if (tab === 'menuBkk') {
                        tableBKK.draw();
                    } else if (tab === 'menuBkm') {
                        tableBKM.draw();
                    }
                });

                // Saat klik update jurnal, simpan informasi halaman dan tab sebelum pindah ke edit
                $(document).on('click', '.btnUpdateJournal', function(e) {
                    e.preventDefault();

                    let id = $(this).data('id');
                    var url = "{{ route('updatejournal', ':id') }}";

                    let currentPage, type, activeTab;
                    if ($('#jurnalUmum').hasClass('show active')) {
                        currentPage = tableGeneral.page.info().page;
                        type = 'General';
                        activeTab = 'jurnalUmum';
                    } else if ($('#menuBkk').hasClass('show active')) {
                        currentPage = tableBKK.page.info().page;
                        type = 'BKK';
                        activeTab = 'menuBkk';
                    } else if ($('#menuBkm').hasClass('show active')) {
                        currentPage = tableBKM.page.info().page;
                        type = 'BKM';
                        activeTab = 'menuBkm';
                    }

                    url = url.replace(':id', id);

                    // Simpan data di sessionStorage
                    sessionStorage.setItem('lastEditedJournal', id + '-' + type);
                    sessionStorage.setItem('lastEditedPage', currentPage);
                    sessionStorage.setItem('lastActiveTab', activeTab);

                    // Pindah ke halaman edit
                    window.location.href = url;
                });
            });


            $('#txSearch').on('input', debounce(function() {
                tableGeneral.search($(this).val()).draw();
            }, 1000));

            $('#txSearch2').on('input', debounce(function() {
                tableBKK.search($(this).val()).draw();
            }, 1000));

            $('#txSearch3').on('input', debounce(function() {
                tableBKM.search($(this).val()).draw();
            }, 1000));

            $('#saveFilterTanggal').on('click', function() {
                const activeTab = $('.tab-pane.active').attr('id');

                if (activeTab === 'jurnalUmum') {
                    tableGeneral.ajax.reload();
                } else if (activeTab === 'menuBkk') {
                    tableBKK.ajax.reload();
                } else if (activeTab === 'menuBkm') {
                    tableBKM.ajax.reload();
                }

                $('#modalFilterTanggal').modal('hide');
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });

            flatpickr("#startDate", {
                dateFormat: "d M Y",
                onChange: function(selectedDates, dateStr, instance) {
                    $("#endDate").flatpickr({
                        dateFormat: "d M Y",
                        minDate: dateStr
                    });
                }
            });

            flatpickr("#endDate", {
                dateFormat: "d MM Y",
                onChange: function(selectedDates, dateStr, instance) {
                    var startDate = new Date($('#startDate').val());
                    var endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showMessage("error",
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });

            $('#filterStatus').change(function() {
                tableGeneral.ajax.reload();
            });
            $('#filterStatusBkk').change(function() {
                tableBKK.ajax.reload();
            });
            $('#filterStatusBkm').change(function() {
                tableBKM.ajax.reload();
            });

            // Event listener untuk tombol yang akan menampilkan modal
            $(document).on('click', '.btnViewJurnal', function() {
                const jurnalId = $(this).data('id'); // Ambil ID dari atribut data-id
                $('#jurnalModal').modal('show'); // Tampilkan modal
                loadJurnalData(jurnalId); // Ambil dan isi data jurnal
            });

            function loadJurnalData(id) {
                $.ajax({
                    url: `/journal/show-detail/${id}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {

                        console.log(data);

                        if (data && data.items && Array.isArray(data.items)) {
                            // Format tanggal ke format "01 January 2025"
                            const formattedDate = new Date(data.tanggal).toLocaleDateString('id-ID', {
                                day: '2-digit',
                                month: 'long',
                                year: 'numeric'
                            });

                            // Format rupiah
                            const formatRupiah = (angka) => {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(angka);
                            };

                            // Isi elemen modal dengan data jurnal
                            $('#modalNoJurnal').text(data.no_journal);
                            $('#modalTanggal').text(formattedDate);
                            $('#modalDeskripsi').text(data.description ?? '-');
                            $('#modalTotalDebit').text(formatRupiah(data.totaldebit));
                            $('#modalTotalCredit').text(formatRupiah(data.totalcredit));

                            let itemsHtml = '';
                            let totalDebit = 0;
                            let totalCredit = 0;
                            data.items.forEach(function(item) {
                                totalDebit += parseFloat(item.debit) || 0;
                                totalCredit += parseFloat(item.credit) || 0;
                                itemsHtml += `
                        <tr>
                            <td>${item.coa.name}</td>
                            <td>${item.description}</td>
                            <td>${formatRupiah(item.debit)}</td>
                            <td>${formatRupiah(item.credit)}</td>
                            <td>${item.memo ?? '-'}</td>
                        </tr>
                    `;
                            });

                            $('#modalItemList').html(itemsHtml);

                            $('#modalTotalDebitFooter').text(formatRupiah(totalDebit));
                            $('#modalTotalCreditFooter').text(formatRupiah(totalCredit));
                        } else {
                            alert('Data jurnal tidak valid!');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Gagal mengambil data jurnal: ' + error);
                    }
                });
            }



            $(document).on('click', '.btnDestroyJournal', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah kamu yakin?',
                    text: "Jurnal ini akan dihapus secara permanen!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#5D87FF',
                    cancelButtonColor: '#49BEFF',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('destroyJurnal', ':id') }}".replace(':id', id),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                showMessage("success", response.message)
                                    .then(
                                        () => {
                                            tableGeneral.ajax.reload(),
                                                tableBKK.ajax.reload(),
                                                tableBKM.ajax.reload();
                                        });
                            },
                            error: function(xhr, status, error) {
                                var errorMessage = xhr.responseJSON.error ||
                                    'Gagal menghapus jurnal.';
                                showMessage("error", errorMessage);
                            }
                        });
                    }
                });

            });

        });




        // if (lastEditedId) {
        //     setTimeout(function() {
        //         let row = $('#tableJournal tbody tr').filter(function() {
        //             return $(this).find('.btnUpdateJournal').data('id') == lastEditedId;
        //         });
        //         if (row.length) {
        //             $('html, body').animate({
        //                 scrollTop: row.offset().top - 100
        //             }, 500);

        //             row.addClass('table-warning');
        //             setTimeout(() => row.removeClass('table-warning'), 3000);
        //         }
        //         sessionStorage.removeItem('lastEditedJournal');
        //     }, 1000);
        // }
    </script>
@endsection
