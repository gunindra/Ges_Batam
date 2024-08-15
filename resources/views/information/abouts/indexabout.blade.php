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
                    <div id="containerAbout" class="table-responsive px-3">
                        <!-- <div style="border:2px solid; min-height:150px; border-radius:20px;">
                            <p id="textPreview" class="p-3"></p>
                        </div>
                         -->
                        
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
                    <div class="mt-3">
                            <label for="imageAbout" class="form-label fw-bold p-1">Image</label>
                            <input type="file" class="form-control" id="imageAbout" value="">
                            <div id="imageAboutError" class="text-danger mt-1 d-none">Silahkan isi Gambar</div>
                    </div>
                    <div class="input-group pt-2 mt-3">
                        <label for="contentAbout" class="form-label fw-bold p-3">Content</label>
                        <textarea id="aboutText" class="form-control" aria-label="With textarea"></textarea>
                    </div>
                    <button type="button" class="btn btn-primary mt-3" data-toggle="modal"
                    data-target="#modalTambahAbout" id="#modalCenter"><span class="pr-3"><i class="fas fa-save"></i></span>Save</button>
                    <button type="button" class="btn btn-secondary mt-3" data-toggle="modal"
                    data-target="#modalPreview" id="#modalCenter"><span class=""><i class="fas fa-eye"></i></span></button>
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
            
    
        });


</script>
@endsection

