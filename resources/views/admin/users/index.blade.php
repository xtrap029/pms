@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $nav == 'users_deleted' ? 'Deleted' : '' }} Users</div>
                <div class="card-body">
                    <table id="itemTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Emp. #</th>
                                <th class="text-nowrap">School</th>
                                <th class="text-nowrap">Last Name</th>
                                <th class="text-nowrap">First Name</th>
                                <th class="text-nowrap">Email</th>
                                <th class="text-nowrap">Position</th>
                                <th class="text-nowrap">Date Created</th>
                                <th class="text-nowrap">Access Rights</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->employee_no }}</td>
                                    <td>{{ $item->school->tag }}</td>
                                    <td>{{ $item->last_name }}</td>
                                    <td>{{ $item->first_name }}</td>
                                    <td>{{ $item->user_id ? $item->user->email : '-' }}</td>
                                    <td class="text-nowrap">{{ $item->user_position_id ? $item->userPosition->name : '-' }}</td>
                                    <td>{{ Carbon::parse($item->created_at)->format('m-d-Y') }}</td>
                                    <td>{{ $item->is_super ? 'Super ' : '' }}{{ $item->is_admin ? 'Admin' : 'Guest' }}</td>
                                    <td class="text-nowrap">{{ $item->user_id ? 'Registered' : 'Not Registered' }}</td>
                                    <td>
                                        @if ($nav == 'users') 
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal{{ $item->id }}" href="#">Edit</a></li>
                                                    <li><a class="dropdown-item" href="{{ route('admin.users.destroy', $item->id) }}" onclick="return confirm('{{ __('messages.sure') }}');">Delete</a></li>
                                                </ul>
                                            </div>
                                        @else
                                            <a href="{{ route('admin.users.restore', $item->id) }}" class="btn btn-sm btn-secondary" onclick="return confirm('{{ __('messages.sure') }}');">Restore</a>
                                        @endif
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

@if ($nav == 'users')    
    @foreach ($items as $item)
        <form action="{{ route('admin.users.update', $item->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit {{ $item->employee_no }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Last Name*</label>
                                <input type="text" class="form-control" name="last_name" value="{{ $item->last_name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">First Name*</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $item->first_name }}" required>
                            </div>
                            @if ($item->user_id)
                                <div class="mb-3">
                                    <label class="form-label">Email*</label>
                                    <input type="email" class="form-control" name="email" value="{{ $item->user->email }}" required>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Position*</label>
                                <select name="user_position_id" class="form-control" required>
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}" {{ $item->user_position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>    
                                    @endforeach
                                </select>
                            </div>
                            @if (Auth::user()->userReference->is_super)
                                <div class="mb-3">
                                    <label class="form-label">School*</label>
                                    <select name="school_id" class="form-control" required>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}" {{ $item->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>    
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            @if ($item->user_id)
                                <div class="mb-3">
                                    <label class="form-label">Password*</label>
                                    <input type="password" class="form-control" name="password" autocomplete="new-password" value="">
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Access Rights*</label>
                                <select name="is_admin" class="form-control" required>
                                    <option value="0" {{ $item->is_admin == 0 ? 'selected' : '' }}>Guest</option>
                                    <option value="1" {{ $item->is_admin == 1 ? 'selected' : '' }}>Admin</option>
                                    @if (Auth::user()->userReference->is_super)
                                        <option value="2" {{ $item->is_super == 1 ? 'selected' : '' }}>Super Admin</option>
                                    @endif
                                </select>
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

    <form action="{{ route('admin.users.store') }}" method="post">
        @csrf
        <div class="modal fade" id="dropdownMenuButtonAdd" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Employee No*</label>
                            <input type="text" class="form-control" name="employee_no" value="{{ old('employee_no') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name*</label>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">First Name*</label>
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Position*</label>
                            <select name="user_position_id" class="form-control" required>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}" {{ old('user_position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>    
                                @endforeach
                            </select>
                        </div>
                        @if (Auth::user()->userReference->is_super)
                            <div class="mb-3">
                                <label class="form-label">School*</label>
                                <select name="school_id" class="form-control" required>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id }}" {{ Auth::user()->userReference->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>    
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Access Rights*</label>
                            <select name="is_admin" class="form-control" required>
                                <option value="0" {{ $item->is_admin == 0 ? 'selected' : '' }}>Guest</option>
                                <option value="1" {{ $item->is_admin == 1 ? 'selected' : '' }}>Admin</option>
                                @if (Auth::user()->userReference->is_super)
                                    <option value="2" {{ $item->is_super == 1 ? 'selected' : '' }}>Super Admin</option>
                                @endif
                            </select>
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
@endif

@endsection

@section('internal-scripts')
@if ($nav == 'users')
    <script>
        const buttons = [
            {
                text: 'Add New',
                action: function ( e, dt, node, config ) {
                    $('#dropdownMenuButtonAdd').modal('show')
                },
            },
        ]
    </script>
@endif
<script>
    $(document).ready( function () {
        $('#itemTable').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            info: false,
            paging: false,
            columnDefs: [{
                targets: [-1], 
                orderable: false,
            }],
            buttons: buttons,
        });
    } );
</script>
@endsection

