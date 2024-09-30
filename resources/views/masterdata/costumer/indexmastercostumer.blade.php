@extends('layout.main')

@section('title', 'Master Data | Costumer')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">

    <!-- Modal Tambah Customer -->
    <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog"
        aria-labelledby="modalTambahCustomerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahCustomerTitle">Tambah Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <label for="markingCustomer" class="form-label fw-bold">Marking</label>
                        <input type="text" class="form-control" id="markingCustomer" value="">
                        <div id="markingCostumerError" class="text-danger mt-1 d-none">Silahkan isi marking</div>
                    </div>
                    <div class="mt-3">
                        <label for="namaCustomer" class="form-label fw-bold">Nama Customer</label>
                        <input type="text" class="form-control" id="namaCustomer" value=""
                            placeholder="Masukkan nama customer">
                        <div id="namaCustomerError" class="text-danger mt-1 d-none">Silahkan isi nama customer</div>
                    </div>
                    <div class="mt-3">
                        <label for="metodePengiriman" class="form-label fw-bold">Metode Pengiriman</label>
                        <select class="form-control" id="metodePengiriman">
                            <option value="" selected disabled>Pilih Metode Pengiriman</option>
                            <option value="Pickup">Pickup</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                        <div id="metodePengirimanError" class="text-danger mt-1 d-none">Silahkan pilih metode pengiriman
                        </div>
                    </div>

                    <div id="alamatSection">
                        <div id="alamatContainer">
                            <div class="mt-3 alamat-item">
                                <label for="alamatCustomer" class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" name="alamatCustomer[]" placeholder="Masukkan alamat"
                                    rows="3"></textarea>
                                <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"
                                    style="display: none;"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <button type="button" id="addAlamatButton" class="btn btn-secondary mt-3" style="display:none;"><i
                                class="fas fa-plus" ></i></button>
                    </div>
                    <div class="mt-3">
                        <label for="noTelpon" class="form-label fw-bold">No. Telpon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="nomor">+62</span>
                            <input type="text" placeholder="8**********" class="form-control" id="noTelpon" value="">
                        </div>
                        <div id="notelponCustomerError" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                            customer</div>
                    </div>
                    <div class="mt-3">
                        <label for="categoryCustomer" class="form-label fw-bold">Category</label>
                        <select class="form-control" id="categoryCustomer">
                            <option value="" selected disabled>Pilih Category Customer</option>
                            @foreach ($listCategory as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="categoryCustomerError" class="text-danger mt-1 d-none">Silahkan pilih category customer
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveCostumer" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Tambah -->

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog" aria-labelledby="modalEditCustomerTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditCustomerTitle">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="customerIdEdit">
                    <div class="mt-3">
                        <label for="namaCustomerEdit" class="form-label fw-bold">Nama Customer</label>
                        <input type="text" class="form-control" id="namaCustomerEdit" value=""
                            placeholder="Masukkan nama customer">
                        <div id="namaCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi nama customer
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="metodePengirimanEdit" class="form-label fw-bold">Metode Pengiriman</label>
                        <select class="form-control" id="metodePengirimanEdit">
                            <option value="" selected disabled>Pilih Metode Pengiriman</option>
                            <option value="Pickup">Pickup</option>
                            <option value="Delivery">Delivery</option>
                        </select>
                        <div id="metodePengirimanErrorEdit" class="text-danger mt-1 d-none">Silahkan pilih metode
                            pengiriman
                        </div>
                    </div>

                    <div id="alamatSectionEdit">
                        <div id="alamatContainerEdit">
                            <div class="mt-3 alamat-item">
                                <label for="alamatCustomerEdit" class="form-label fw-bold">Alamat</label>
                                <textarea class="form-control" name="alamatCustomerEdit[]" placeholder="Masukkan alamat"
                                    rows="3"></textarea>
                                <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"
                                    style="display: none;"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <button type="button" id="addAlamatButtonEdit" class="btn btn-secondary mt-3"><i
                                class="fas fa-plus"></i></button>
                    </div>

                    <div class="mt-3">
                        <label for="noTelponEdit" class="form-label fw-bold">No. Telpon</label>
                        <div class="input-group">
                            <span class="input-group-text" id="nomorEdit">+62</span>
                            <input type="text" placeholder="8**********" class="form-control" id="noTelponEdit"
                                value="">
                        </div>
                        <div id="notelponCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan isi no. telepon
                            customer
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="categoryCustomerEdit" class="form-label fw-bold">Category</label>
                        <select class="form-control" id="categoryCustomerEdit">
                            <option value="" selected disabled>Pilih Category Customer</option>
                            @foreach ($listCategory as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="categoryCustomerErrorEdit" class="text-danger mt-1 d-none">Silahkan pilih category
                            customer
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveEditCostumer" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Edit -->


    <!-- Modal Detail -->
    <div class="modal fade" id="modalPointCostumer" tabindex="-1" role="dialog"
        aria-labelledby="modalPointCostumerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg rounded-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPointCostumerTitle">Detail Costumer</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <div class="mb-4">
                            <h1 id="pointValue" class="display-3 font-weight-bold text-primary" value="0">0</h1>
                            <p class="text-muted">Poin</p>
                        </div>
                        <!-- <div>
                            <p id="statusValue" class="h5"></p>
                        </div> -->
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-primary btn-lg px-4"
                        data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Modal Detail -->

    <!-- Modal untuk Menampilkan Alamat -->
    <div class="modal fade" id="modalAlamatCustomer" tabindex="-1" role="dialog"
        aria-labelledby="modalAlamatCustomerTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAlamatCustomerTitle">Daftar Alamat Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="alamatList" class="list-group">
                        <!-- Alamat akan ditambahkan di sini -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">Customer</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex mb-2 mr-3 float-right">
                        {{-- <button class="btn btn-primary" id="btnModalTambahCostumer">Tambah</button> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTambahCustomer" id="modalTambahCost"><span class="pr-2"><i
                                    class="fas fa-plus"></i></span>Tambah Customer</button>
                    </div>
                    <div class="float-left">
                        <input id="txSearch" type="text" style="width: 250px; min-width: 250px;"
                            class="form-control rounded-3" placeholder="Search">
                    </div>
                    <div class="float-left ps-4">
                        <select class="form-control ml-2" id="filterStatus" name="status" style="width: 200px;">
                            <option value="" selected disabled>Pilih Status</option>
                            <option value="1">Active</option>
                            <option value="0">Non Active</option>
                        </select>
                    </div>
                    <div class="float-left ps-4">
                        <button type="button" class="btn btn-outline-primary ml-2" id="btnResetDefault"
                            onclick="window.location.reload()">
                            Reset
                        </button>
                    </div>
                    <div id="containerCustomer" class="table-responsive px-3">
                        {{-- <table class="table align-items-center table-flush table-hover" id="tableCostumer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ilham</td>
                                    <td>Jl.Central Legenda poin blok j No. 13 </td>
                                    <td>0893483478283</td>
                                    <td><span class="badge badge-primary">VIP</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Show point</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tio</td>
                                    <td>Jl.Central Legenda poin blok j No. 12 </td>
                                    <td>08123476282221</td>
                                    <td>Normal</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary">Show point</a>
                                        <a href="#" class="btn btn-sm btn-secondary"><i class="fas fa-edit"></i></a>
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
    $(document).ready(function () {
        const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;

        const getListCustomer = () => {
            const txtSearch = $('#txSearch').val();
            const filterStatus = $('#filterStatus').val();

            $.ajax({
                url: "{{ route('getlistCostumer') }}",
                method: "GET",
                data: {
                    txSearch: txtSearch,
                    status: filterStatus
                },
                beforeSend: () => {
                    $('#containerCustomer').html(loadSpin)
                }
            })
                .done(res => {
                    $('#containerCustomer').html(res)
                    $('#tableCostumer').DataTable({
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

        getListCustomer();

        $('#txSearch').keyup(function (e) {
            var inputText = $(this).val();
            if (inputText.length >= 1 || inputText.length == 0) {
                getListCustomer();
            }
        });
        $('#filterStatus').change(function () {
            getListCustomer();
        });


        $(document).on('click', '#modalTambahCost', function (e) {
            $.ajax({
                type: "GET",
                url: "{{ route('generateMarking') }}",
                success: function (response) {
                    let valuemarking = response.new_marking
                    $('#markingCustomer').val(valuemarking);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $('#noTelpon, #noTelponEdit').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function toggleRemoveButton() {
            const alamatItems = $('#alamatContainer').children('.alamat-item');
            if (alamatItems.length > 1) {
                $('.remove-alamat-btn').show();
            } else {
                $('.remove-alamat-btn').hide();
            }
        }

        $('#metodePengiriman').change(function () {
        var metodePengiriman = $(this).val();
        if (metodePengiriman === 'Delivery') {
            $('#alamatSection').show();
            $('#addAlamatButton').show();
        } else if (metodePengiriman === 'Pickup') {
            $('#alamatSection').show();
            $('#addAlamatButton').hide();
            $('#alamatContainer').find('textarea').val('');
            $('#alamatContainer').children('.alamat-item:gt(0)').remove();
        } else {
            $('#alamatSection').hide();
            $('#alamatContainer').find('textarea').val('');
            $('#alamatContainer').children('.alamat-item:gt(0)').remove();
            toggleRemoveButton();
        }
        toggleRemoveButton();
    });

        $('#addAlamatButton').click(function () {
            let alamatContainer = $('#alamatContainer');

            let newAlamat = `<div class="mt-3 alamat-item">
                            <label for="alamatCustomer" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" name="alamatCustomer[]" placeholder="Masukkan alamat" rows="3"></textarea>
                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"><i class="fas fa-trash-alt"></i></button>
                        </div>`;
            alamatContainer.append(newAlamat);
            toggleRemoveButton();
        });

        $(document).on('click', '.remove-alamat-btn', function () {
            $(this).closest('.alamat-item').remove();
            toggleRemoveButton();
        });

        $('#saveCostumer').click(function () {
            var markingCostmer = $('#markingCustomer').val();
            var namaCustomer = $('#namaCustomer').val();
            var nomorTelpon = $('#noTelpon').val().trim();
            var categoryCustomer = $('#categoryCustomer').val();
            var metodePengiriman = $('#metodePengiriman').val();
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            let nomors = $('#nomor').text();
            let clearplus = nomors.replace('+', '')
            let valueNotlep = clearplus + nomorTelpon;

            var isValid = true;

            $('.text-danger').addClass('d-none');

            if (markingCostmer === '') {
                $('#markingCostumerError').removeClass('d-none');
                isValid = false;
            }

            if (namaCustomer === '') {
                $('#namaCustomerError').removeClass('d-none');
                isValid = false;
            }

            if (nomorTelpon === '') {
                $('#notelponCustomerError').removeClass('d-none');
                isValid = false;
            }

            if (categoryCustomer === '' || categoryCustomer === null) {
                $('#categoryCustomerError').removeClass('d-none');
                isValid = false;
            }

            if (metodePengiriman === '' || metodePengiriman === null) {
                $('#metodePengirimanError').removeClass('d-none');
                isValid = false;
            }

            var alamatCustomer = [];
        if (metodePengiriman === 'Delivery') {
            $('textarea[name="alamatCustomer[]"]').each(function () {
                var alamat = $(this).val().trim();
                if (alamat === '') {
                    $(this).siblings('.alamat-error').removeClass('d-none');
                    isValid = false;
                } else {
                    alamatCustomer.push(alamat);
                }
            });
        } else if (metodePengiriman === 'Pickup') {
            var alamat = $('textarea[name="alamatCustomer[]"]').first().val().trim();
            if (alamat === '') {
                $('textarea[name="alamatCustomer[]"]').siblings('.alamat-error').removeClass('d-none');
                isValid = false;
            } else {
                alamatCustomer.push(alamat);
            }
        }

            if (isValid) {
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
                        var formData = new FormData();
                        formData.append('markingCostmer', markingCostmer);
                        formData.append('namaCustomer', namaCustomer);
                        formData.append('noTelpon', valueNotlep);
                        formData.append('categoryCustomer', categoryCustomer);
                        formData.append('metodePengiriman', metodePengiriman);
                        formData.append('_token', csrfToken);

                        if (metodePengiriman === 'Delivery' || metodePengiriman === 'Pickup') {
                            alamatCustomer.forEach((alamat, index) => {
                                formData.append('alamatCustomer[]', alamat);
                            });
                        }

                        Swal.fire({
                            title: 'Checking...',
                            html: 'Please wait while we process your data customer.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "{{ route('addCostumer') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
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

                                if (response.status === 'success') {
                                    showMessage("success", "Data Berhasil Disimpan");
                                    getListCustomer();
                                    $('#modalTambahCustomer').modal('hide');
                                } else {
                                    Swal.fire({
                                        title: "Gagal Menambahkan Data",
                                        text: response.message,
                                        icon: "error",
                                    });
                                }
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    title: "Gagal Menambahkan Data",
                                    // text: xhr.responseJSON.message,
                                    icon: "error",
                                });
                            }
                        });
                    }
                });
            } else {
                showMessage("error", "Mohon periksa input yang kosong");
            }
        });

        $('#modalTambahCustomer').on('hidden.bs.modal', function () {
            $('#namaCustomer, #noTelpon, #categoryCustomer, #markingCustomer').val('');
            $('#alamatContainer').children('.alamat-item:gt(0)').remove();
            $('#alamatContainer').children('.alamat-item').first().find('textarea').val('');
            $('#alamatSection').hide();
            $('#metodePengiriman').val('').trigger('change');
            $('.text-danger').addClass('d-none');
        });


        $(document).on('click', '.btnPointCostumer', function (e) {
            e.preventDefault();
            let poinValue = $(this).data('poin') || 0;
            let category = $(this).data('category');
            let transaksi = $(this).data('transaksi');
            let status = $(this).data('status');

            $('#pointValue').text(poinValue).show();

            if (!transaksi) {
                $('#transaksiDate').text('Tanggal belum tersedia');
            } else {
                let transaksiDate = new Date(transaksi);
                let currentDate = new Date();
                let timeDifference = currentDate - transaksiDate;

                if (isNaN(transaksiDate.getTime())) {
                    $('#transaksiDate').text('Tanggal transaksi tidak valid');
                } else {
                    let seconds = Math.floor(timeDifference / 1000);
                    let minutes = Math.floor(seconds / 60);
                    let hours = Math.floor(minutes / 60);
                    let days = Math.floor(hours / 24);
                    let months = Math.floor(days / 30);
                    let years = Math.floor(months / 12);

                    let result;
                    if (seconds < 60) {
                        result = seconds === 1 ? "Sedetik yang lalu" : `${seconds} detik yang lalu`;
                    } else if (minutes < 60) {
                        result = minutes === 1 ? "Semenit yang lalu" : `${minutes} menit yang lalu`;
                    } else if (hours < 24) {
                        result = hours === 1 ? "Sejam yang lalu" : `${hours} jam yang lalu`;
                    } else if (days < 30) {
                        result = days === 1 ? "Sehari yang lalu" : `${days} hari yang lalu`;
                    } else if (months < 12) {
                        result = months === 1 ? "Sebulan yang lalu" : `${months} bulan yang lalu`;
                    } else {
                        result = years === 1 ? "Setahun yang lalu" : `${years} tahun yang lalu`;
                    }

                    $('#transaksiDate').text(result);
                }
            }

            if (category === "VIP") {
                $('#pointValue').text(point).show();
            } else if (category === "Normal") {
                $('#pointValue').hide();
            }

            // $('#transaksiDate').text(result);
            $('#statusValue').html(
                `<span class="badge ${status === 1 ? 'badge-success' : 'badge-danger'}">${status === 1 ? 'Aktif' : 'Non Aktif'}</span>`
            );

            // Show modal
            $('#modalPointCostumer').modal('show');
        });


        $(document).on('click', '.btnUpdateCustomer', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let noTelp = $(this).data('notelp');
            noTelp = String(noTelp);
            let noTelpWithoutCode = noTelp.slice(2);
            let alamat = $(this).data('alamat');
            let category = $(this).data('category');
            let pengiriman = $(this).data('metode_pengiriman');

            // Memisahkan string alamat menjadi array
            let alamatArray = alamat.split(', ');

            // Kosongkan container alamat dan tambahkan textarea sesuai jumlah alamat
            $('#alamatContainerEdit').empty();
            alamatArray.forEach((alamatItem, index) => {
                let newAlamat = `<div class="mt-3 alamat-item">
                            <label for="alamatCustomerEdit${index}" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" id="alamatCustomerEdit${index}" name="alamatCustomerEdit[]" placeholder="Masukkan alamat" rows="3">${alamatItem}</textarea>
                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"><i class="fas fa-trash-alt"></i></button>
                        </div>`;
                $('#alamatContainerEdit').append(newAlamat);
            });

            // Logika untuk menampilkan dan menyembunyikan tombol hapus alamat
            function toggleRemoveButtonEdit() {
                const alamatItems = $('#alamatContainerEdit').children('.alamat-item');
                if (alamatItems.length > 1) {
                    $('.remove-alamat-btn').show();
                } else {
                    $('.remove-alamat-btn').hide();
                }
            }

            // Menangani perubahan metode pengiriman
            $('#metodePengirimanEdit').change(function () {
                var metodePengiriman = $(this).val();
                if (metodePengiriman === 'Delivery' || metodePengiriman === 'Pickup') {
                    $('#alamatSectionEdit').show(); // Tampilkan bagian alamat
                } else {
                    $('#alamatSectionEdit').hide(); // Sembunyikan bagian alamat
                    $('#alamatContainerEdit').find('textarea').val('');
                    $('#alamatContainerEdit').children('.alamat-item:gt(0)').remove();
                    toggleRemoveButtonEdit();
                }
            });

            // Menambahkan alamat baru ketika tombol 'Tambah Alamat' ditekan pada modal edit
            $('#addAlamatButtonEdit').off('click').on('click', function () {
                let alamatContainer = $('#alamatContainerEdit');
                let newAlamat = `<div class="mt-3 alamat-item">
                            <label for="alamatCustomerEditNew" class="form-label fw-bold">Alamat</label>
                            <textarea class="form-control" name="alamatCustomerEdit[]" placeholder="Masukkan alamat" rows="3"></textarea>
                            <div class="text-danger mt-1 d-none alamat-error">Silahkan isi alamat customer</div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-alamat-btn"><i class="fas fa-trash-alt"></i></button>
                        </div>`;
                alamatContainer.append(newAlamat);
                toggleRemoveButtonEdit();
            });

            // Hapus alamat saat tombol 'Hapus Alamat' ditekan pada modal edit
            $(document).on('click', '.remove-alamat-btn', function () {
                $(this).closest('.alamat-item').remove();
                toggleRemoveButtonEdit();
            });

            // Isi form edit dengan data yang diterima
            $('#namaCustomerEdit').val(nama);
            $('#noTelponEdit').val(noTelpWithoutCode);
            $('#categoryCustomerEdit').val(category);
            $('#metodePengirimanEdit').val(pengiriman);
            $('#customerIdEdit').val(id);

            // Trigger event change untuk memastikan tampilan sesuai dengan metode pengiriman yang dipilih
            $('#metodePengirimanEdit').trigger('change');

            toggleRemoveButtonEdit();

            $('#modalEditCustomer').modal('show');
        });



        $(document).on('click', '#saveEditCostumer', function (e) {
    e.preventDefault();

    let id = $('#customerIdEdit').val();
    let namaCustomerEdit = $('#namaCustomerEdit').val();
    let noTelponInput = $('#noTelponEdit').val().trim();
    let noTelponCustomer = '62' + noTelponInput;
    let metodePengiriman = $('#metodePengirimanEdit').val();
    let categoryCustomer = $('#categoryCustomerEdit').val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    let isValid = true;

    // Validasi Nama Customer
    if (namaCustomerEdit === '') {
        $('#namaCustomerErrorEdit').removeClass('d-none');
        isValid = false;
    } else {
        $('#namaCustomerErrorEdit').addClass('d-none');
    }

    // Validasi Nomor Telepon
    if (noTelponInput === '') {
        $('#notelponCustomerErrorEdit').removeClass('d-none');
        isValid = false;
    } else {
        $('#notelponCustomerErrorEdit').addClass('d-none');
    }

    // Validasi Kategori Customer
    if (categoryCustomer === '' || categoryCustomer === null) {
        $('#CategoryCustomerErrorEdit').removeClass('d-none');
        isValid = false;
    } else {
        $('#CategoryCustomerErrorEdit').addClass('d-none');
    }

    // Validasi dan kumpulkan semua alamat
    let alamatCustomer = [];
    if (metodePengiriman === 'Delivery' || metodePengiriman === 'Pickup') {
        $('#alamatContainerEdit').find('textarea').each(function () {
            let alamat = $(this).val().trim();
            if (alamat === '') {
                $(this).siblings('.alamat-error').removeClass('d-none');
                isValid = false;
            } else {
                $(this).siblings('.alamat-error').addClass('d-none');
                alamatCustomer.push(alamat);
            }
        });
    }

    if (isValid) {
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
                let formData = new FormData();
                formData.append('id', id);
                formData.append('namaCustomer', namaCustomerEdit);
                formData.append('noTelpon', noTelponCustomer);
                formData.append('metodePengiriman', metodePengiriman);
                formData.append('categoryCustomer', categoryCustomer);
                formData.append('_token', csrfToken);

                alamatCustomer.forEach((alamat, index) => {
                    formData.append('alamatCustomer[]', alamat);
                });

                Swal.fire({
                    title: 'Checking...',
                    html: 'Please wait while we process update your data customer.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('updateCostumer') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
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

                        if (response.status === 'success') {
                            showMessage("success", "Data Berhasil Diubah");
                            getListCustomer();
                            $('#modalEditCustomer').modal('hide');
                        } else {
                            Swal.fire({
                                title: "Gagal Menambahkan",
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: "Gagal Menambahkan Data",
                            text: xhr.responseJSON.message,
                            icon: "error",
                        });
                    }
                });
            }
        });
    } else {
        showMessage("error", "Mohon periksa input yang kosong");
    }
});


        // $('#modalEditCustomer').on('hidden.bs.modal', function() {
        //     $('#namaCustomerEdit, #alamatCustomerEdit, #noTelponEdit, #categoryCustomerEdit').val('');
        //     if (!$('#namaCustomerErrorEdit').hasClass('d-none')) {
        //         $('#namaCustomerErrorEdit').addClass('d-none');

        //     }
        //     if (!$('#alamatCustomerErrorEdit').hasClass('d-none')) {
        //         $('#alamatCustomerErrorEdit').addClass('d-none');

        //     }
        //     if (!$('#notelponCustomerErrorEdit').hasClass('d-none')) {
        //         $('#notelponCustomerErrorEdit').addClass('d-none');

        //     }
        // });

        $('#modalEditCustomer').on('hidden.bs.modal', function () {
            $('#namaCustomerEdit, #noTelponEdit, #categoryCustomerEdit, #markingCustomerEdit')
                .val('');
            $('#alamatContainerEdit').children('.alamat-item:gt(0)').remove();
            $('#alamatContainerEdit').children('.alamat-item').first().find('textarea').val(
                '');
            $('.text-danger').addClass('d-none');
            $('#alamatSectionEdit').hide();
            $('#metodePengirimanEdit').val('').trigger('change');
            toggleRemoveButtonEdit();
        });

        $(document).on('click', '.show-address-modal', function () {
            let alamat = $(this).data('alamat');
            let alamatArray = alamat.split(', ');

            let alamatList = $('#alamatList');
            alamatList.empty(); // Kosongkan daftar alamat

            // Tambahkan setiap alamat sebagai item dalam daftar
            alamatArray.forEach(function (item) {
                alamatList.append('<li class="list-group-item">' + item + '</li>');
            });

            // Tampilkan modal
            $('#modalAlamatCustomer').modal('show');
        });
    });
</script>
@endsection
