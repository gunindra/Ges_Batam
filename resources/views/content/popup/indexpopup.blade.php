@extends('layout.main')

@section('title', ' Content | Popup')

@section('main')

<!-- Container Fluid -->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Popup</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Popup</li>
        </ol>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary">Popup</h6>
                    <div id="containerPopup" class="table-responsive px-3"></div>
                    <div class="mt-3">
                        <label for="imagePopup" class="form-label fw-bold p-1">Image</label>
                        <input type="file" class="form-control" id="imagePopup">
                        <div id="imagePopupError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                        <p>Nama Gambar= <span id="imageName">{{ $popupData->Image_Popup ?? ' -' }}</span></p>
                    </div>
                    <div class="mt-3">
                        <label for="judulPopup" class="form-label fw-bold">Judul</label>
                        <input type="text" class="form-control" id="judulPopup"
                            value="{{ isset($popupData->Judul_Popup) ? $popupData->Judul_Popup : '' }}"
                            placeholder="Masukkan judul Popup">
                        <div id="judulPopupError" class="text-danger mt-1 d-none">Silahkan isi Judul</div>
                    </div>
                    <div class="input-group pt-2 mt-3">
                        <label for="parafPopup" class="form-label fw-bold p-3">Content</label>
                        <textarea id="parafPopup" class="form-control" aria-label="With textarea"
                            placeholder="Masukkan content">{{ isset($popupData->Paraf_Popup) ? $popupData->Paraf_Popup : '' }}</textarea>
                    </div>
                    <div id="parafPopupError" class="text-danger mt-1 d-none">Silahkan isi Content</div>
                    <div class="mt-3">
                        <label for="linkPopup" class="form-label fw-bold">Link</label>
                        <input type="text" class="form-control" id="linkPopup"
                            value="{{ isset($popupData->Link_Popup) ? $popupData->Link_Popup : '' }}"
                            placeholder="Masukkan link">
                        <div id="linkPopupError" class="text-danger mt-1 d-none">Silahkan isi Link</div>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" id="savePopup">
                        <span class="pr-3"><i class="fas fa-save"></i></span> Save
                    </button>
                    <button type="button" class="btn btn-danger mt-3" id="destroyPopup"
                        data-id="{{ isset($popupData->id) ? $popupData->id : '' }}">
                        <span class="pr-3"><i class="fas fa-trash"></i></span> Delete
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary p-2">Preview</h6>
                    <div class="preview" id="previewContainer"
                        style="border:1px solid black; height: auto; border-radius:10px;">
                        @if($popupData)
                            @if($popupData->Image_Popup)
                                <img src="{{ asset('storage/images/' . $popupData->Image_Popup) }}" width="600px"
                                    style="padding:5px 30px;">
                                <p style="padding-left:30px;">{{ $popupData->Judul_Popup }}</p>
                            @endif
                            <p style="padding-left:30px;">{{ $popupData->Paraf_Popup }}</p>
                            <p class="text-primary" style="padding-left:30px;">{{ $popupData->Link_Popup }}</p>
                        @else
                            <p class="p-3">No content available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Container Fluid -->
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $(document).on('click', '#savePopup', function (e) {
            e.preventDefault();

            var judulPopup = $('#judulPopup').val().trim();
            var parafPopup = $('#parafPopup').val().trim();
            var linkPopup = $('#linkPopup').val().trim();
            var imagePopup = $('#imagePopup')[0].files[0];
            var existingImage = $('#textNamaEdit').text();  // Gambar yang sudah ada

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (judulPopup === '') {
                $('#judulPopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#judulPopupError').addClass('d-none');
            }
            if (parafPopup === '') {
                $('#parafPopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#parafPopupError').addClass('d-none');
            }
            if (linkPopup === '') {
                $('#linkPopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#linkPopupError').addClass('d-none');
            }
            if (imagePopup) {
                var validExtensions = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validExtensions.includes(imagePopup.type)) {
                    $('#imagePopupError').text('Hanya file JPG , JPEG atau PNG yang diperbolehkan atau input tidak boleh kosong').removeClass('d-none');
                    isValid = false;
                } else {
                    $('#imagePopupError').addClass('d-none');
                }
            } else if (!$('#previewContainer img').length) {
                $('#imagePopupError').removeClass('d-none');
                isValid = false;
            } else {
                $('#imagePopupError').addClass('d-none');
            }

            if (isValid) {
                var formData = new FormData();
                formData.append('judulPopup', judulPopup);
                formData.append('parafPopup', parafPopup);
                formData.append('linkPopup', linkPopup);
                formData.append('imagePopup', imagePopup ? imagePopup : existingImage);  // Kirim gambar yang ada jika tidak diubah
                formData.append('_token', csrfToken);
                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your data popup.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.ajax({
                    url: '{{ route("addPopup") }}',
                    method: 'POST',
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
                            $('#textNamaEdit').text(response.data.imagePopup);
                            $('#previewContainer').html(`
                    <img src="{{ asset('storage/images/') }}/${response.data.imagePopup}" width="600px" style="padding:5px 30px;">
                    <p style="padding-left:30px;">${response.data.judulPopup}</p>
                    <p style="padding-left:30px;">${response.data.parafPopup}</p>
                    <p class="text-primary" style="padding-left:30px;">${response.data.linkPopup}</p>
                `);

                            $('#destroyPopup').data('id', response.data.id);
                            Swal.fire({
                                icon: 'success',
                                title: 'Data Saved',
                                text: response.message
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to save data'
                        });
                    }
                });
            }
        });


        $(document).on('click', '#destroyPopup', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            if (!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: ' Tidak ada data.'
                });
                return;
            }

            Swal.fire({
                title: "Apakah Kamu Yakin Ingin Hapus Ini?",
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
                        title: 'Loading...',
                        text: 'Please wait while we process delete your data popup.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('destroyPopup') }}",
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
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
                                $('#judulPopup').val('');
                                $('#parafPopup').val('');
                                $('#linkPopup').val('');
                                $('#imagePopup').val('');
                                $('#previewContainer').html('<p class="p-3">No content available</p>');
                                $('#textNamaEdit').text('Belum ada gambar');
                                $('#destroyPopup').data('id', '');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data berhasil dihapus'
                                });
                            } else if (response.status === 'info') {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Info',
                                    text: response.message
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menghapus data'
                            });
                        }
                    });
                }
            });
        });
    });


</script>
@endsection