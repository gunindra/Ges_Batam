@extends('layout.main')

@section('title', 'Master Data | Rekening')

@section('main')

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">

        <!-- Modal Center -->
        <div class="modal fade" id="modalTambahRekening" tabindex="-1" role="dialog" aria-labelledby="modalTambahRekeningTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahRekeningTitle">Modal Tambah Rekening</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-3">
                            <label for="namaRekening" class="form-label fw-bold">Nama Rekening</label>
                            <input type="text" class="form-control" id="namaRekening" value="">
                        </div>
                        <div class="mt-3">
                            <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                            <input type="text" class="form-control numericInput" id="noTelpon" value="">
                        </div>
                        <div class="mt-3">
                            <label for="alamat" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatRekening" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Rekening</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Master Data</li>
                <li class="breadcrumb-item active" aria-current="page">Rekening</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex mb-2 mr-3 float-right">
                            {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalTambahRekening" id="#modalCenter"><span class="pr-2"><i
                                        class="fas fa-plus"></i></span>Tambah Rekening</button>
                        </div>
                        <div id="containerRekening" class="table-responsive px-3 ">
                            {{-- <table class="table align-items-center table-flush table-hover" id="tableRekening">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Pemilik</th>
                                        <th>No. Rekening</th>
                                        <th>Bank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ilham</td>
                                        <td>291037292</td>
                                        <td>BCA</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tio</td>
                                        <td>1192837432</td>
                                        <td>Mandiri</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table> --}}
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

        const getListRekening = () => {
            const txtSearch = $('#txSearch').val();

            $.ajax({
                    url: "{{ route('getlistRekening') }}",
                    method: "GET",
                    data: {
                        txSearch: txtSearch
                    },
                    beforeSend: () => {
                        $('#containerRekening').html(loadSpin)
                    }
                })
                .done(res => {
                    $('#containerRekening').html(res)
                    $('#tableRekening').DataTable({
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

        getListRekening();
    </script>

@endsection
