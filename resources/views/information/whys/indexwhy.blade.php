@extends('layout.main')

@section('title', 'Why')

@section('main')

<!---Container Fluid-->
<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Why</h1>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary">Why</h6>
                    <div id="containerAbout" class="table-responsive px-3">
                    </div>
                    <div class="mt-3">
                        <label for="imageWhy" class="form-label fw-bold p-1">Image</label>
                        <input type="file" class="form-control" id="imageWhy" value="">
                        <div id="imageWhyError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                    </div>
                    <div class="input-group pt-2 mt-3">
                        <label for="parafWhy" class="form-label fw-bold p-3">Content</label>
                        <textarea id="parafWhy" class="form-control" aria-label="With textarea">{{ $whyData->Paraf_WhyUs ?? '' }}</textarea>
                    </div>
                    <div id="parafWhyError" class="text-danger mt-1 d-none">Silahkan isi Content</div>
                    <button type="button" class="btn btn-primary mt-3" id="saveWhy">
                        <span class="pr-3"><i class="fas fa-save"></i></span> Save
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary p-2">Preview</h6>
                    <div class="preview" id="previewContainer" style="border:1px solid black; height: auto; border-radius:10px;">
                    @if($whyData)
                            @if($whyData->Image_WhyUs)
                                <img src="{{ asset('storage/images/' . $whyData->Image_WhyUs) }}" width="600px" style="padding:5px 30px;">
                            @endif
                            <p style="padding-left:30px;">{{ $whyData->Paraf_WhyUs }}</p>
                        @else
                            <p class="p-3">No content available</p>
                        @endif
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
    $(document).on('click', '#saveWhy', function(e) {
        e.preventDefault(); 

        // Ambil nilai input
        var parafWhy = $('#parafWhy').val().trim();
        var imageWhy = $('#imageWhy')[0].files[0];

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        var isValid = true;

        // Validasi input
        if (parafWhy === '') {
            $('#parafWhyError').removeClass('d-none');
            isValid = false;
        } else {
            $('#parafWhyError').addClass('d-none');
        }
        if (!imageWhy) {
            $('#imageWhyError').removeClass('d-none');
            isValid = false;
        } else {
            $('#imageWhyError').addClass('d-none');
        }

        // Jika semua input valid, lanjutkan aksi simpan
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
                    formData.append('parafWhy', parafWhy);
                    formData.append('imageWhy', imageWhy);
                    formData.append('_token', csrfToken);

                    $.ajax({
                        type: "POST",
                        url: "{{ route('addWhy') }}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    // Update preview with new data
                                    var previewContainer = $('#previewContainer');

                                    // Clear previous content
                                    previewContainer.html('');

                                    // Append new image and text without looping
                                    if (response.data.imageWhy) {
                                        previewContainer.append('<img src="{{ asset("storage/images/") }}/' + response.data.imageWhy + '" width="600px" style="padding:5px 30px;">');
                                    }

                                    if (response.data.parafWhy) {
                                        previewContainer.append('<p style="margin-left:30px;">' + response.data.parafWhy + '</p>');
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal Menambahkan Data",
                                    text: response.message,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: "Error",
                                text: "Terjadi kesalahan: " + error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                title: "Periksa Input",
                text: "Tolong periksa input yang kosong",
                icon: "warning"
            });
        }
    });
});
</script>
@endsection