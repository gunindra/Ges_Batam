@extends('layout.main')

@section('title', 'About')

@section('main')

<div class="modal fade" id="modalGambar" tabindex="-1" role="dialog"
        aria-labelledby="modalGambarTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGambarTitle">Gambar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mt-3">
                        <img src="/img/Aboutus.jpg" width="400px">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!---Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">About</h1>
        </div>
        <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="m-0 font-weight-bold text-primary">About</h6>
                    <label for="namaAboutUs" class="form-label fw-bold">Preview</label>
                    <div id="containerAboutUs" class="table-responsive px-3">
                        <div style="border:2px solid; min-height:150px; border-radius:20px;">
                            <p id="textPreview" class="p-3"></p>
                        </div>
                        
                        
                            <!-- <table class="table align-items-center table-flush table-hover" id="tableAboutUs">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No.</th>
                                        <th>Paraf About</th>
                                        <th>Image About</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Distinctio natus aspernatur eligendi, aperiam voluptatibus quia! Facere eveniet consequuntur nostrum molestias, asperiores cupiditate quibusdam dolore molestiae quod modi? Assumenda, tenetur repudiandae?</td>
                                        <td><img src="/img/Aboutus.jpg" width="50px"></td>
                                        <td>
                                        <a href="#" class="btn btn-sm btn-secondary"><i
                                                    class="fas fa-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                        <a href="#" class="btn btn-sm btn-primary btnGambar"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table> -->
                            
                       </div>
                    <div class="input-group pt-3">
                        <textarea id="aboutUsText" class="form-control" aria-label="With textarea"></textarea>
                    </div>
                    <button id="saveChanges" class="btn btn-primary mt-2">Save</button>
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
            const loadSpin = `<div class="d-flex justify-content-center align-items-center mt-5">
                <div class="spinner-border d-flex justify-content-center align-items-center text-primary" role="status"></div>
            </div> `;
            
    $('#saveChanges').click(function() {
        const paraf = $('#aboutUsText').val();
        console.log('ini isi paraf', paraf);
        
        $.ajax({
            url: "{{ route('insertAboutUs') }}", 
            method: "POST",
            data: {
                paraf: paraf,
                _token: "{{ csrf_token() }}" 
            },
            beforeSend: () => {
                $('#containerAboutUs').html(loadSpin);
            }
        })
        .done(response => {
            if (response.success) {
                getlistAbout(); 
            }
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            console.error('Error:', textStatus, errorThrown);
        });
    });


                const getlistAbout = () => {

                $.ajax({
                        url: "{{route('getlistAbout') }}", 
                        method: "GET",
                        beforeSend: () => {
                            $('#containerAboutUs').html(loadSpin)
                        }
                    })
                    .done(res => {
                        $('#containerAboutUs').html(res)
                        
                    })
                    
            }

            getlistAbout();
        });

//             $(document).on('click', '.btnDestroyAboutUs', function(e) {
//                 let id = $(this).data('id');

//                 Swal.fire({
//                     title: "Apakah Kamu Yakin Ingin Hapus Ini?",
//                     icon: 'question',
//                     showCancelButton: true,
//                     confirmButtonColor: '#5D87FF',
//                     cancelButtonColor: '#49BEFF',
//                     confirmButtonText: 'Ya',
//                     cancelButtonText: 'Tidak',
//                     reverseButtons: true
//                 }).then((result) => {
//                     if (result.isConfirmed) {
//                         $.ajax({
//                             type: "GET",
//                             url: "{{ route('destroyAboutUs') }}",
//                             data: {
//                                 id: id,
//                             },
//                             success: function(response) {
//                                 if (response.status === 'success') {
//                                     showMessage("success",
//                                         "Berhasil menghapus");
//                                     getListDriver();
//                                 } else {
//                                     showMessage("error", "Gagal menghapus");
//                                 }
//                             }
//                         });
//                     }
//                 })
//             });
//         });

//  $(document).on('click', '.btnGambar', function(e) {
//     $('#modalGambar').modal('show');
//  })

</script>
@endsection


<!-- // $.ajax({
        //     url: "{{ route('getlistAbout') }}", 
        //     method: "POST",
        //     data: {
        //         _token: "{{ csrf_token() }}",
        //         paraf: paraf
        //     },
        //     success: function(response) {
        //         if (response.success) {
        //             $('#textPreview').text(paraf);
        //             alert('Data updated successfully!');
        //         } else {
        //             alert('Failed to update data.');
        //         }
        //     },
        //     error: function(xhr) {
        //         // alert('An error occurred: ' + xhr.statusText);
        //         showMessage("error","Ini error bosku")
        //     }
        // }); -->