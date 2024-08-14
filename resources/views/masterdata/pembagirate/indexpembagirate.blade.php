@extends('layout.main')

@section('title', 'Pembagi Dan Rate')

@section('main')


    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pembagi Dan Rate</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Pembagi dan Rate</li>
            </ol>
        </div>
        <div class="row mb-3">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-0 font-weight-bold text-primary">Pembagi</h6>
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahPembagi" id="modalTambahPembagi"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah</button>
                        </div>
                        <div id="containerPembagiRate" class="table-responsive px-3">
                            <table class="table align-items-center table-flush table-hover" id="tablePembagi">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Nilai</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>1.000.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>6.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="m-0 font-weight-bold text-primary">Rate Harga</h6>
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahRate" id="modalTambahRate"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah</button>
                        </div>
                        <div id="containerRate" class="table-responsive px-3">
                            <table class="table align-items-center table-flush table-hover" id="tableRate">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Rate Harga</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Rp. 200.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Rp. 300.000</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
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
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getListPembagi = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistPembagi') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch
                    },
                    beforeSend: () => {
                        $('#containerPembagi').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerPembagi').html(res)
                    $('#tablePembagi').DataTable({
                        searching: false,
                        lengthChange: false,
                        "bSort": true,
                        "aaSorting": [],
                        pageLength: 7,
                        "lengthChange": false,
                        responsive: true,
                        language: {
                            search: ""
                        }
                    });
                })
        }

        getListPembagi();

        $('#savePembagi').click(function() {
            $('#noPembagi,#nilaiPembagi').data('touched', true);

            let noPembagi = $('#noPembagi').val();
            let nilaiPembagi = $('#nilaiPembagi').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (('modalTambahPembagi')) {
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
                        $.ajax({
                            type: "POST",
                            url: "{{ route('addPembagi') }}",
                            data: {
                                noPembagi: noPembagi,
                                nilaiPembagi: nilaiPembagi,
                                _token: csrfToken
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getlistPembagi();
                                    $('#modalTambahPembagi').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan",
                                        icon: "error"
                                    });
                                }
                            }
                        });
                    }
                });
            } else {
                showMessage("error", "Mohon periksa input yang kosong");
            }
        });
        $('#modalTambahPembagi').on('hidden.bs.modal', function() {
            $('#noPembagi, #nilaiPembagi').val('');
            validatePembagiInput('modalTambahPembagi');
        });

        $(document).on('click', '.btnUpdatePembagi', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let no_pembagi = $(this).data('no_pembagi');
                let nilai_pembagi = $(this).data('nilai_pembagi');

                $('#noPembagiEdit').val(no_pembagi);
                $('#nilaiPembagiEdit').val(nilai_pembagi);
                $('#PembagiIdEdit').val(id);

                validatePembagiInput('modalEditPembagi');
                $('#modalEditPembagi').modal('show');
            });

            $(document).on('click', '.btnDestroyPembagi', function(e) {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Kamu Yakin Ingin Hapus Driver Ini?",
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
                            url: "{{ route('destroyPembagi') }}",
                            data: {
                                id: id,
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    showMessage("success",
                                        "Berhasil menghapus");
                                    getListPembagi();
                                } else {
                                    showMessage("error", "Gagal menghapus");
                                }
                            }
                        });
                    }
                })
            });
    </script>


@endsection

