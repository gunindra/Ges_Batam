@extends('layout.main')

@section('title', 'Report | Statement of Account')

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

    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Statement of Account</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Report</li>
                <li class="breadcrumb-item active" aria-current="page">Statement of Account</li>
            </ol>
        </div>
        @if ($errors->has('error'))
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
        @endif
        <div class="modal fade" id="modalFilterTanggal" tabindex="-1" role="dialog"
            aria-labelledby="modalFilterTanggalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFilterTanggalTitle">Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold">Customer:</label>
                                    <select class="form-control" id="customer">
                                        <option value="" selected disabled>Pilih Costumer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->marking }} -
                                                {{ $customer->nama_pembeli }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="customer" class="form-label fw-bold">Payment method:</label>
                                    <select class="form-control" id="paymentMethod" multiple>
                                        @foreach ($listpayment as $payment)
                                            <option value="{{ $payment->tipe_pembayaran }}">{{ $payment->tipe_pembayaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="Tanggal" class="form-label fw-bold">Pilih Tanggal: ( Kosongkan jika ingin
                                        munculkan semua )</label>
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


        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-secondary mr-2" id="closingSoa" style="display: none;">Closing
                                SOA</button> --}}
                            <button class="btn btn-primary mr-2" id="exportBtn">Export Excel</button>
                            <a class="btn btn-success mr-1" style="color:white;" id="sendWA"><span class="pr-2"><i
                                        class="fab fa-whatsapp"></i></span>Send Whatsapp</a>
                        </div>
                        <div class="d-flex mb-2 mr-3">
                            <button class="btn btn-primary ml-2" id="filterTanggal">Filter</button>
                            <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                                onclick="window.location.reload()">
                                Reset
                            </button>
                        </div>
                        <div id="containerSoa" class="table-responsive px-3">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                        <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
                    </div> `;

            const getSOA = () => {
                const txtSearch = $('#txSearch').val();
                const paymentMethod = $('#paymentMethod').val();
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const customer = $('#customer').val();

                $.ajax({
                    url: "{{ route('getSoa') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch,
                        paymentMethod: paymentMethod,
                        startDate: startDate,
                        endDate: endDate,
                        customer: customer,
                    },
                    beforeSend: () => {
                        $('#containerSoa').html(loadSpin);
                    }
                }).done(res => {
                    $('#containerSoa').html(res.html);
                    // window.invoiceIds = res.invoiceIds;
                    // if (res.invoiceIds.length > 0) {
                    //     $('#closingSoa').fadeIn();
                    // } else {
                    //     $('#closingSoa').fadeOut();
                    // }
                });
            }

            getSOA();

            // const startOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1);
            // const endOfMonth = new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0);

            const startOfYear = new Date(new Date().getFullYear(), 0, 1);
            const today = new Date();

            flatpickr("#startDate", {
                dateFormat: "d M Y",
                defaultDate: startOfYear,
                onChange: function(selectedDates, dateStr, instance) {
                    $("#endDate").flatpickr({
                        dateFormat: "d M Y",
                        minDate: dateStr,
                        defaultDate: endOfMonth
                    });
                }
            });

            flatpickr("#endDate", {
                dateFormat: "d M Y",
                defaultDate: today,
                onChange: function(selectedDates, dateStr, instance) {
                    var startDate = new Date($('#startDate').val());
                    var endDate = new Date(dateStr);
                    if (endDate < startDate) {
                        showwMassage('error',
                            "Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.");
                        $('#endDate').val('');
                    }
                }
            });

            $(document).on('click', '#filterTanggal', function(e) {
                $('#modalFilterTanggal').modal('show');
            });

            $('#customer').select2({
                placeholder: "Pilih Customer",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownParent: $('#modalFilterTanggal')
            });

            $('#paymentMethod').select2({
                placeholder: "Pilih payment",
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 0,
                dropdownParent: $('#modalFilterTanggal')
            });

            $('#saveFilterTanggal').click(function() {
                getSOA();
                $('#modalFilterTanggal').modal('hide');
            });
            $('#sendWA').hide();

            let filterApplied = false;

            $('#saveFilterTanggal').on('click', function() {
                filterApplied = true;
                checkFilters();
            });

            function checkFilters() {
                const customer = $('#customer').val();
                const dataAvailable = $('#containerSoa tbody tr').length > 0;

                if (customer && filterApplied && dataAvailable) {
                    $('#sendWA').show();
                } else {
                    $('#sendWA').hide();
                }
            }

            $('#customer').on('input change', function() {
                filterApplied = false;
            });

            $(document).ajaxComplete(function() {
                checkFilters();
            });


            $('#sendWA').on('click', function(e) {
                e.preventDefault();

                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();
                const customer = $('#customer').val();

                if (!customer) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Mohon pilih customer terlebih dahulu!'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Mengirim WhatsApp...',
                    text: 'Mohon tunggu beberapa saat',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('soaWA') }}",
                    type: "GET",
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        customer: customer,
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.success,
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengirim WhatsApp.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                        });
                    }
                });
            });

            $('#exportBtn').on('click', function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var customer = $('#customer').val();
                const paymentMethod = $('#paymentMethod').val();

                var now = new Date();
                var day = String(now.getDate()).padStart(2, '0');
                var month = now.toLocaleString('default', {
                    month: 'long'
                });
                var year = now.getFullYear();
                var hours = String(now.getHours()).padStart(2, '0');
                var minutes = String(now.getMinutes()).padStart(2, '0');
                var seconds = String(now.getSeconds()).padStart(2, '0');

                var filename = `Soa Customer_${day} ${month} ${year} ${hours}:${minutes}:${seconds}.xlsx`;

                $.ajax({
                    url: "{{ route('exportSoaCustomer') }}",
                    type: 'GET',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        nama_pembeli: customer,
                        paymentMethod: paymentMethod
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(data) {
                        var blob = new Blob([data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = filename;
                        link.click();
                    },
                    error: function() {
                        Swal.fire({
                            title: "Export failed!",
                            icon: "error"
                        });
                    }
                });
            });



            $('#closingSoa').on('click', function() {
                if (window.invoiceIds && window.invoiceIds.length > 0) {
                    Swal.fire({
                        title: 'Apakah kamu yakin?',
                        text: "SOA yang di-closing tidak bisa dibuka kembali!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#5D87FF',
                        cancelButtonColor: '#49BEFF',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('closingSoa') }}",
                                method: "GET",
                                data: {
                                    invoiceIds: window.invoiceIds,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response.message
                                    });

                                    getSOA(); // Refresh data setelah closing
                                },
                                error: function(xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops!',
                                        text: 'Gagal melakukan closing SOA!'
                                    });
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Tidak ada data yang bisa di-closing.'
                    });
                }
            });


        });
    </script>
@endsection
