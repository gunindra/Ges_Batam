@extends('layout.main')

@section('title', 'Content | Contact')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Broadcast Whatsapp</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Broadcast Whatsapp</li>
        </ol>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-12">
            <form action="{{route('wa.broadcast.store')}}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            Note: Check "Send to all customer" to broadcast message to all current active customer.
                        </div>
                        <div class="mt-3">
                            <label for="messageWa" name="media" class="form-label fw-bold">Media (Optional, Max 4 MB)</label>
                            <input type="file" class="form-control"/>
                        </div>
                        <div class="mt-3">
                            <label for="messageWa" class="form-label fw-bold">Pesan Broadcast</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required
                                    placeholder="Masukkan Pesan WhatsApp">{{ isset($waData->Message_wa) ? $waData->Message_wa : '' }}</textarea>
                        </div>
                        <div class="mt-3">
                            <div>
                                Recipients
                                <button type="button" class="btn btn-sm btn-secondary float-right ml-3" data-toggle="modal" data-target="#mdlSearchCustomer">Search From Customer</button>
                                <button type="button" onclick="addRow()" class="btn btn-sm btn-info float-right">Add Recipient</button>
                            </div>
                            <input type="checkbox" name="send_to_all_customer"/>Send to all customer
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                </tbody>
                            </table>

                            
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">
                            <span class="pr-3"><i class="fas fa-save"></i></span> Send Broadcast
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Find Customer Modal-->
<div class="modal fade" id="mdlSearchCustomer">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
    
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Find Customer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
    
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <table id="customerTable" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Marking</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
    
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
    
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    addRow();
    function addRow(recipient='', phone='62'){
        var newRow = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="name[]" placeholder="Recipient Name" value="${recipient}" required>
                </td>
                <td>
                    <input type="tel" class="form-control" name="phone[]" value="${phone}" placeholder="62" required>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger removeItemButton mt-1">Remove</button>
                </td>
            </tr>
            `;
        $('#items-container').append(newRow);
    }
    $(document).on('click', '.removeItemButton', function () {
        $(this).closest('tr').remove();
    });

    $(document).ready(function() {
        var table = $('#customerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/masterdata/costumer/listbyname',
                type: 'GET',
                data: function(d) {
                    
                }
            },
            columns: [
                { data: 'marking' },
                { data: 'nama_pembeli' },
                { data: 'no_wa' },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return '<button class="btn btn-success" onclick="addRow(\'' + row.nama_pembeli + '\', \'' + row.no_wa + '\')">Select</button>';
                    }
                }
            ]
        });

        $('#searchCustomerBtn').on('click', function() {
            table.draw(); 
        });
    });

</script>
@endsection