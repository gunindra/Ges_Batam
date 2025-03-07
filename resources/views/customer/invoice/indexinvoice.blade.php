@extends('layout.main')

@section('title', 'Invoice')

@section('main')
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 1px solid #d1d3e2;
            border-radius: 0.25rem;
            padding: 6px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 27px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
    </style>
    <div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalFilterLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilterLabel">Filter Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Existing filters -->
                    <div class="form-group">
                        <label for="filterStatus">Pilih Status</label>
                        <select class="form-control" id="filterStatus">
                            <option value="" selected>Pilih Status</option>
                            @foreach ($listStatus as $status)
                                <option value="{{ $status->status_name }}">{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterNoDO">Pilih No DO</label>
                        <select class="form-control select2singgle" id="filterNoDO">
                            <option value="" selected disabled>Pilih No DO</option>
                            @foreach ($listDo as $NoDo)
                                <option value="{{ $NoDo->no_do }}">{{ $NoDo->no_do }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filterMarking">Pilih Marking</label>
                        <select class="form-control select2singgle" id="filterMarking">
                            <option value="" selected disabled>Pilih Marking</option>
                            @foreach ($listMarking as $marking)
                                <option value="{{ $marking->marking }}">{{ $marking->marking }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- New filter for payment status -->
                    <div class="form-group">
                        <label for="filterPaymentStatus">Status Pembayaran</label>
                        <select class="form-control" id="filterPaymentStatus">
                            <option value="" selected disabled>Pilih Status Pembayaran</option>
                            <option value="Lunas">Lunas</option>
                            <option value="Belum lunas">Belum Lunas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveFilter">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalConfirmasiPembayaran" tabindex="-1" role="dialog"
        aria-labelledby="modalConfirmasiPembayaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmasiPembayaranTitle">Confirmasi Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pembayaranStatus" class="form-label fw-bold">Masukkan Bukti Transfer</label>
                        <input type="file" class="form-control" id="pembayaranStatus" value="">
                        <div id="err-pembayaranStatus" class="text-danger mt-1">Silakan masukkan file</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveFileTransfer" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBuktiPembayaran" tabindex="-1" role="dialog"
        aria-labelledby="modalBuktiPembayaranTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBuktiPembayaranTitle">Bukti Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="pembayaranStatus" class="form-label fw-bold">Bukti Pembayaran :</label>
                        <div class="containerFoto">
                            {{-- <img src="storage/app/bukti_pembayaran/1.jpg" alt=""> --}}
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    {{-- <button type="button" id="saveFileTransfer" class="btn btn-primary">Save</button> --}}
                </div>
            </div>
        </div>
    </div>

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
                                <label for="pembayaranStatus" class="form-label fw-bold">Pilih Tanggal:</label>
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
    <div class="modal fade" id="modalResi" tabindex="-1" role="dialog" aria-labelledby="modalResiTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResiTitle">Daftar Resi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="resiList" class="list-group">
                        <!-- Alamat akan ditambahkan di sini -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Invoice</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Costumer</li>
                <li class="breadcrumb-item active" aria-current="page">Invoice</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 justify-content-between align-items-center">
                            <div class="d-flex">
                                {{-- Search --}}
                                <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                                    class="form-control rounded-3" placeholder="Search">
                                <button class="btn btn-primary ml-2" id="filterModalButton">Filter</button>
                                <button class="btn btn-primary ml-2" id="filterTanggal">Filter Tanggal</button>
                                <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                    onclick="window.location.reload()">
                                    Reset
                                </button>
                            </div>
                            <div class="d-flex mb-2 mr-3 float-right">


                                @if (Auth::user()->role === 'supervisor')
                                    <a class="btn btn-secondary mr-1" style="color:white;" id="kirimNot"><span
                                            class="pr-2"><i class="fas fa-paper-plane"
                                                style="color: #ffffff;"></i></span>Kirim
                                        Notifikasi</a>
                                    <a class="btn btn-success mr-1" style="color:white;" id="kirimInvoice"><span
                                            class="pr-2"><i class="fab fa-whatsapp"
                                                style="color: #ffffff;"></i></span>Kirim Invoice</a>
                                @endif

                                <!-- <button class="btn btn-success mr-1" id="isNotif"><span class="pr-2"><i
                                                                                                                        class="fas fa-bell"></i></span>Notifikasi</button> -->
                                <a class="btn btn-primary" href="{{ route('addinvoice') }}" id=""><span
                                        class="pr-2"><i class="fas fa-plus"></i></span>Buat Invoice</a>

                            </div>
                        </div>
                        {{-- <div id="containerInvoice" class="table-responsive px-3"> --}}
                        {{-- <table class="table align-items-center table-flush table-hover" id="tableInvoice">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No Resi</th>
                                        <th>Tanggal</th>
                                        <th>Costumer</th>
                                        <th>Pengiriman</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>B0230123</td>
                                        <td>24 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>Delivery</td>
                                        <td>Rp. 10.000</td>
                                        <td><span class="badge badge-warning">Paid</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>B0234043</td>
                                        <td>28 Juli 2024</td>
                                        <td>Tandrio</td>
                                        <td>PickUp</td>
                                        <td>Rp. 12.000</td>
                                        <td><span class="badge badge-warning">Paid</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-print"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
                        {{-- </div> --}}

                        <div id="containerInvoice">
                            <div class="table-responsive">
                                <table id="tableInvoice" class="table align-items-center table-flush table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            @if (Auth::user()->role === 'supervisor')
                                                <th><input type="checkbox" class="selectAll" id="selectAll"></th>
                                            @endif
                                            <th>No Invoice</th>
                                            <th>Tanggal</th>
                                            <th>No Do</th>
                                            <th>Marking</th>
                                            <th>No Resi</th>
                                            <th>Customer</th>
                                            <th>Pengiriman</th>
                                            <th>Alamat</th>
                                            <th>Status Pembayaran</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Created by</th>
                                            <th>Updated by</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---Container Fluid-->

@endsection



@section('script')
    <script>
        $(document).ready(function() {
            let tableColumns = [];
            if ($('#selectAll').length) {
                tableColumns.push({
                    data: 'checkbox',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<input type="checkbox" class="selectItem" data-id="' + row.id +
                            '" value="' + row.id + '">';
                    }
                });
            }
            tableColumns = tableColumns.concat([{
                    data: 'no_invoice',
                    name: 'no_invoice',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                }, {
                    data: 'tanggal_bayar',
                    name: 'tanggal_bayar',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'no_do',
                    name: 'no_do',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'marking',
                    name: 'marking',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'resi_cell',
                    name: 'resi_cell',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },

                {
                    data: 'pembeli',
                    name: 'pembeli',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'metode_pengiriman',
                    name: 'metode_pengiriman',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'status_bayar',
                    name: 'status_bayar',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'converted_harga',
                    name: 'converted_harga',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    }
                },
                {
                    data: 'status_badge',
                    name: 'status_badge',
                    render: function(data, type, row) {
                        return data ? data : '-';
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        if (data) {
                            return row.user ?
                                `${data} (${row.user})` :
                                `${data}`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    render: function(data, type, row) {
                        if (data) {
                            return row.user_update ?
                                `${data} (${row.user_update})` :
                                `${data}`;
                        }
                        return '-';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]);

            let table = $('#tableInvoice').DataTable({
                serverSide: true,
                processing: true,
                searching: false,
                ajax: {
                    url: "{{ route('getlistInvoice') }}",
                    method: 'GET',
                    data: function(d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                        d.status = $('#filterStatus').val();
                        d.txSearch = $('#txSearch').val();
                        d.no_do = $('#filterNoDO').val();
                        d.payment_status = $('#filterPaymentStatus').val();
                        d.marking = $('#filterMarking').val();
                    },
                    error: function(xhr, error, thrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load payment data. Please try again!',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                columns: tableColumns,
                order: [],
                lengthChange: false,
                pageLength: 7,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    info: "_START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    emptyTable: "No data available in table",
                    loadingRecords: "Loading...",
                    zeroRecords: "No matching records found"
                }
            });



            const handleSelectItems = () => {
                $('#tableInvoice').on('change', '.selectItem', function() {
                    const allChecked = $('.selectItem').length === $('.selectItem:checked').length;
                    $('#selectAll').prop('checked', allChecked);
                });

                $('#tableInvoice').on('change', '#selectAll', function() {
                    $('.selectItem').prop('checked', this.checked);
                });
            };

            table.on('draw', function() {
                handleSelectItems();
            });

            $('.select2singgle').select2({
                dropdownAutoWidth: true,
                width: '100%',
                dropdownParent: $('#modalFilter')
            });

            $(document).ready(function() {
                handleSelectItems();

                $(document).on('click', '#kirimNot', function(e) {
                    e.preventDefault();

                    let selectedItems = [];
                    $('.selectItem:checked').each(function() {
                        selectedItems.push($(this).data('id'));
                    });

                    if (selectedItems.length === 0) {
                        showMessage("error", "Tidak ada invoice yang dipilih");
                        return;
                    }

                    Swal.fire({
                        title: "Apakah Kamu Yakin?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Mengirim notifikasi...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "GET",
                                url: "{{ route('kirimPesanWaPembeli') }}",
                                data: {
                                    id: selectedItems,
                                    type: 'listbarang',
                                },
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success",
                                            "Berhasil mengirim notifikasi");
                                        // $('#kirimNot').hide();
                                        var currentPage = table.page();
                                        table.ajax.reload(null, false);
                                        table.one('draw', function() {
                                            table.page(currentPage)
                                                .draw(false);
                                        });
                                    } else {
                                        showMessage("error", response.message ||
                                            "Gagal mengirim notifikasi");
                                    }
                                },
                                error: function() {
                                    Swal.close();
                                    showMessage("error",
                                        "Terjadi kesalahan saat mengirim notifikasi"
                                    );

                                }
                            });
                        }
                    });
                });


                $(document).on('click', '#kirimInvoice', function(e) {
                    e.preventDefault();

                    let selectedItems = [];
                    $('.selectItem:checked').each(function() {
                        selectedItems.push($(this).data('id'));
                    });

                    if (selectedItems.length === 0) {
                        showMessage("error", "Tidak ada invoice yang dipilih");
                        return;
                    }


                    Swal.fire({
                        title: "Apakah Kamu Yakin?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Mengirim notifikasi...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                type: "GET",
                                url: "{{ route('kirimPesanWaPembeli') }}",
                                data: {
                                    id: selectedItems,
                                    type: 'invoice',
                                },
                                success: function(response) {
                                    Swal.close();
                                    if (response.success) {
                                        showMessage("success",
                                            "Berhasil mengirim notifikasi");
                                        var currentPage = table.page();
                                        table.ajax.reload(null, false);
                                        table.one('draw', function() {
                                            table.page(currentPage)
                                                .draw(false);
                                        });
                                    } else {
                                        showMessage("error", response.message ||
                                            "Gagal mengirim notifikasi");
                                    }
                                },
                                error: function() {
                                    Swal.close();
                                    showMessage("error",
                                        "Terjadi kesalahan saat mengirim notifikasi"
                                    );

                                }
                            });
                        }
                    });

                });



            });


            $('#txSearch').keyup(function() {
                table.draw();
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
                    let startDate = new Date($('#startDate').val());
                    let endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showMessage(error,
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });
            $(document).on('click', '#filterModalButton', function() {
                $('#modalFilter').modal('show');
            });
            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });
            $('#saveFilter').click(function() {
                table.ajax.reload();
                $('#modalFilter').modal('hide');
            });
            $('#saveFilterTanggal').click(function() {
                table.ajax.reload();
                $('#modalFilterTanggal').modal('hide');
            });




            $(document).on('click', '.btnExportInvoice', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "{{ route('exportPdf') }}",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        Swal.close();

                        if (response.url) {
                            window.open(response.url, '_blank');
                        } else if (response.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();

                        let errorMessage = 'Gagal Export Invoice';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    }
                });
            });

            $(document).on('click', '.btnDetailPembayaran', function(e) {
                e.preventDefault();
                let namafoto = $(this).data('bukti');
                $.ajax({
                    url: "{{ route('detailBuktiPembayaran') }}",
                    method: 'GET',
                    data: {
                        namafoto: namafoto,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            let imageUrl = response.url;

                            $('#modalBuktiPembayaran').find('.containerFoto').html(
                                '<img src="' + imageUrl + '" class="img-fluid">');
                        } else {
                            showMessage("error", "Gagal memuat bukti pembayaran");
                        }
                        $('#modalBuktiPembayaran').modal('show');
                    },
                    error: function(xhr) {
                        showMessage("error", "Terjadi kesalahan saat memuat bukti pembayaran");
                        $('#modalBuktiPembayaran').modal('show');
                    }
                });
            });


            $(document).on('click', '.btnPembayaran', function(e) {
                let id = $(this).data('id');
                let tipePembayaran = $(this).data('tipe');

                if (tipePembayaran === 'Transfer') {
                    function validatePembayaran() {
                        let isValid = true;
                        const fileInput = $('#pembayaranStatus');
                        const file = fileInput[0].files[0];

                        if (!file) {
                            $('#err-pembayaranStatus').show();
                            isValid = false;
                        } else {
                            $('#err-pembayaranStatus').hide();
                        }

                        return isValid;
                    }

                    $('#pembayaranStatus').on('input change', function() {
                        validatePembayaran();
                    });

                    $(document).on('click', '#saveFileTransfer', function(e) {
                        if (validatePembayaran()) {
                            Swal.fire({
                                title: "Apakah Pembayaran Invoice ini Sudah di Selesaikan?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#5D87FF',
                                cancelButtonColor: '#49BEFF',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    const fileInput = $('#pembayaranStatus')[0].files[0];
                                    const formData = new FormData();
                                    formData.append('id', id);
                                    formData.append('file', fileInput);
                                    formData.append('_token', $('meta[name="csrf-token"]')
                                        .attr('content'));

                                    $.ajax({
                                        type: "POST",
                                        url: "{{ route('completePayment') }}",
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(response) {
                                            if (response.error) {
                                                showMessage("error", response
                                                    .message);
                                            } else {
                                                showMessage("success",
                                                    "Berhasil");
                                                getlistInvoice();
                                                $('#modalConfirmasiPembayaran')
                                                    .modal('hide');
                                            }
                                        },
                                        error: function() {
                                            showMessage("error",
                                                "Terjadi kesalahan pada server."
                                            );
                                        }
                                    });
                                }
                            });
                        } else {
                            showMessage("error", "Mohon periksa input yang kosong.");
                        }
                    });

                    $('#modalConfirmasiPembayaran').modal('show');
                } else {
                    Swal.fire({
                        title: "Apakah Pembayaran Invoice ini Sudah di Selesaikan?",
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
                                type: "POST",
                                url: "{{ route('completePayment') }}",
                                data: {
                                    id: id,
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(response) {
                                    if (response.error) {
                                        showMessage("error", response.message);
                                    } else {
                                        showMessage("success", "Berhasil");
                                        getlistInvoice();
                                    }
                                },
                                error: function() {
                                    showMessage("error",
                                        "Terjadi kesalahan pada server.");
                                }
                            });
                        }
                    });
                }
            });

            $('#modalConfirmasiPembayaran').on('hidden.bs.modal', function() {
                $('#pembayaranStatus').val('');
                validatePembayaran('modalConfirmasiPembayaran');
            });



            $(document).on('click', '.btnDeleteInvoice', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Kamu Yakin Ingin Hapus Invoice Ini?",
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
                            type: "GET",
                            url: "{{ route('deleteInvoice') }}",
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Berhasil menghapus Invoice");
                                    getlistInvoice();
                                } else {
                                    showMessage("error", "Gagal menghapus Invoice");
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.btnEditInvoice', function(e) {
                let id = $(this).data('id');
                let url = "{{ route('deleteoreditinvoice', ':id') }}";
                url = url.replace(':id', id);
                window.location.href = url;
            });

            $(document).on('click', '.btnCicilan', function(e) {
                let id = $(this).data('id');
                let url = "{{ route('cicilanInvoice', ':id') }}";
                url = url.replace(':id', id);
                window.location.href = url;
            });
            $(document).on('click', '.btnChangeMethod', function(e) {
                let id = $(this).data('id');
                let method = $(this).data('method');

                Swal.fire({
                    title: "Apakah Anda ingin mengubah pengiriman invoice ini menjadi Delivery?",
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
                            type: "GET",
                            url: "{{ route('changeMethod') }}",
                            data: {
                                id: id,
                                method: method,
                            },
                            success: function(response) {
                                if (response
                                    .success) {
                                    showMessage("success", response
                                        .message);
                                    table.ajax.reload();
                                } else {
                                    showMessage("error", response.message ||
                                        "Gagal mengubah invoice");
                                }
                            },
                            error: function() {
                                showMessage("error",
                                    "Terjadi kesalahan dalam mengubah invoice");
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.show-address-modal', function() {
                let noresi = $(this).data('no_resi');
                let noresiArray = noresi.split(';');

                let resiList = $('#resiList');
                resiList.empty(); // Kosongkan daftar alamat

                // Tambahkan setiap alamat sebagai item dalam daftar
                noresiArray.forEach(function(item) {
                    resiList.append('<li class="list-group-item">' + item + '</li>');
                });

                // Tampilkan modal
                $('#modalResi').modal('show');
            });

        });
    </script>
@endsection
