@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Account Titles</div>
                <div class="card-body">
                    <table id="itemTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Account Title</th>
                                <th class="text-nowrap">Su-Major Account Group</th>
                                <th class="text-nowrap">GL Account</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-nowrap">{{ $item->name }}</td>
                                    <td class="text-nowrap">{{ $item->group }}</td>
                                    <td class="text-nowrap">{{ $item->account }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal{{ $item->id }}" href="#">Edit</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.property_categories.destroy', $item->id) }}" onclick="return confirm('{{ __('messages.sure') }}');">Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($items as $item)
    <form action="{{ route('admin.property_categories.update', $item->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit {{ $item->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Account Title*</label>
                            <input type="text" class="form-control" name="name" value="{{ $item->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Su-Major Account Group*</label>
                            <input type="text" class="form-control" name="group" value="{{ $item->group }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">GL Account*</label>
                            <input type="text" class="form-control" name="account" value="{{ $item->account }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endforeach

<form action="{{ route('admin.property_categories.store') }}" method="post">
    @csrf
    <div class="modal fade" id="dropdownMenuButtonAdd" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Account Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Account Title*</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Su-Major Account Group*</label>
                        <input type="text" class="form-control" name="group" value="{{ old('group') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">GL Account*</label>
                        <input type="text" class="form-control" name="account" value="{{ old('account') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('internal-scripts')
<script>
    $(document).ready( function () {
        $('#itemTable').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            info: false,
            paging: false,
            buttons: [
                {
                    text: 'Add New',
                    action: function ( e, dt, node, config ) {
                        $('#dropdownMenuButtonAdd').modal('show')
                    },
                },
                'excel',
            ],
            columnDefs: [{
                targets: [-1], 
                orderable: false,
            }],
        });
    } );
</script>
@endsection

