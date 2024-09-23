@extends('layout.main')

@section('title', 'Content | Contact')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Whatsapp</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Whatsapp</li>
        </ol>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div id="containerWhatsapp" class="table-responsive px-3"></div>
                    <div class="mt-3">
                        <label for="nomorWa" class="form-label fw-bold">Number Whatsapp</label>
                        <div class="input-group" style="width:auto;">
                            <span class="input-group-text" id="nomor">+62</span>
                            <input type="text" class="form-control" id="nomorWa"
                                value="{{ isset($waData->No_wa) ? ltrim($waData->No_wa, '62') : '' }}"
                                placeholder="Masukkan Nomor Whatsapp">
                        </div>
                        <div id="noWaError" class="text-danger mt-1 d-none">Please fill in the Number
                        </div>
                        <div class="mt-3">
                            <label for="pesanWa" class="form-label fw-bold">Message WhatsApp</label>
                            <input type="text" class="form-control" id="pesanWa"
                                value="{{ isset($waData->pesan_wa) ? $waData->pesan_wa : '' }}"
                                placeholder="Masukkan Pesan WhatsApp">
                            <div id="pesanWaError" class="text-danger mt-1 d-none">Please fill in the Message </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="saveWa">
                            <span class="pr-3"><i class="fas fa-save"></i></span> Save
                        </button>
                        <button type="button" class="btn btn-danger mt-3" id="destroyWa"
                            data-id="{{ isset($waData->id) ? $waData->id : '' }}">
                            <span class="pr-3"><i class="fas fa-trash"></i></span> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $(document).on('click', '#saveWa', function (e) {
            e.preventDefault();

            var nomorWa = $('#nomorWa').val().trim();
            var pesanWa = $('#pesanWa').val().trim();
            var formattedNomorWa = '62' + nomorWa;  
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            var isValid = true;

            if (nomorWa === '') {
                $('#noWaError').removeClass('d-none');
                isValid = false;
            } else {
                $('#noWaError').addClass('d-none');
            }
            if (pesanWa === '') {
                $('#pesanWaError').removeClass('d-none');
                isValid = false;
            } else {
                $('#pesanWaError').addClass('d-none');
            }


            if (isValid) {
                var formData = new FormData();
                formData.append('nomorWa', formattedNomorWa);
                formData.append('pesanWa', pesanWa);
                formData.append('_token', csrfToken);

                Swal.fire({
                    title: 'Loading...',
                    text: 'Please wait while we process your WhatsApp.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route("addWa") }}',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.close();

                        if (response.status === 'success') {
                            

                            $('#destroyWa').data('id', response.data.id);

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

        $(document).on('click', '#destroyWa', function (e) {
            e.preventDefault();

            let id = $(this).data('id');

            // Check if there is an ID to delete
            if (!id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'there is no data.'
                });
                return;
            }

            Swal.fire({
                title: "Are you sure you want to delete this?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#5D87FF',
                cancelButtonColor: '#49BEFF',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while we process the deletion.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('destroyWa') }}",
                        data: {
                            id: id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.close();

                            if (response.status === 'success') {
                                // Clear the WhatsApp number input
                                $('#nomorWa').val('');
                                $('#pesanWa').val('');
                                // Remove from localStorage

                                // Clear the delete button ID
                                $('#destroyWa').data('id', null);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Data berhasil dihapus'
                                });
                            } else if (response.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.error
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Fail',
                                text: 'Successfully deleted'
                            });
                        }
                    });
                }
            });
        });

        $('#nomorWa').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

</script>
@endsection