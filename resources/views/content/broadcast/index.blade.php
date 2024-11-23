@extends('layout.main')

@section('title', ' Content | Information')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-2 mr-3 float-right">
                    <a href="{{ route('wa.broadcast.new') }}" class="btn btn-primary">
                        <span class="pr-2"><i class="fas fa-plus"></i></span> Tambah Broadcast
                    </a>
                </div>
                <div class="row" style="clear: both">
                    <div class="col-12">
                        <table id="brodcastTable" class="table table-striped table-bordered" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Created On</th>
                                    <th>Message</th>
                                    <th>Recipients</th>
                                    <th>Status</th>
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
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        var table = $('#brodcastTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/content/whatsapp/broadcast',
                type: 'GET',
                data: function(d) {}
            },
            columns: [
                { data: 'created_at' },
                { data: 'message' },
                { data: 'recipients', className: "text-center" },
                { data: 'status', className: "text-center" },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

    });

    $(document).on('click', '.btn-resend', function () {
        let broadcastId = $(this).data('id');

        if (confirm('Are you sure you want to resend this broadcast?')) {
            $.ajax({
                url: '{{ route('wa.broadcast.resend') }}',
                type: 'POST',
                data: {
                    id: broadcastId,
                    type: 'broadcast',
                    _token: $('meta[name="csrf-token"]').attr('content'), // Ensure CSRF token is included
                },
                success: function (response) {
                    alert(response.message || 'Broadcast resent successfully.');
                    // Optionally reload the DataTable
                    $('#exampleTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    alert(xhr.responseJSON.message || 'Failed to resend broadcast.');
                },
            });
        }
    });
</script>
@endsection