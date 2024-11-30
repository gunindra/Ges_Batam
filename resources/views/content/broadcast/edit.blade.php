@extends('layout.main')

@section('title', 'Content | Edit Broadcast')

@section('main')

<div class="container-fluid" id="container-wrapper">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Broadcast Whatsapp</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active" aria-current="page">Edit Broadcast Whatsapp</li>
        </ol>
    </div>
    <div class="d-flex justify-content-between">
        <a class="btn btn-primary mb-3" href="{{ route('wa.broadcast') }}">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="row mb-3 d-flex">
        <div class="col-xl-12">
            <form action="{{ route('wa.broadcast.update', $broadcast->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="mt-3">
                            @if ($broadcast->media_path)
                                <p class="mt-2">
                                    Current Media: 
                                    <a href="{{ asset($broadcast->media_path) }}" target="_blank">View Media</a>
                                </p>
                            @endif
                        </div>
                        <div class="mt-3">
                            <label for="message" class="form-label fw-bold">Pesan Broadcast</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required readonly>{{ $broadcast->message }}</textarea>
                        </div>
                        <div class="mt-3">
                            <div>Recipients</div>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    @foreach ($broadcast->recipients as $recipient)
                                        <tr>
                                            <td>{{ $recipient->recipient }}</td>
                                            <td>{{ $recipient->phone }}</td>
                                            <td>{{ $recipient->status }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary btn-resend mt-1" data-id="{{$recipient->id}}">Resend</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).on('click', '.btn-resend', function () {
        let broadcastId = $(this).data('id');

        if (confirm('Are you sure you want to resend this broadcast?')) {
            $.ajax({
                url: '{{ route('wa.broadcast.resend') }}',
                type: 'POST',
                data: {
                    id: broadcastId,
                    type: 'detail',
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
