@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $nav == 'properties_disposed' ? 'Disposed' : '' }} Properties</div>
                <div class="card-body">
                    <table id="itemTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Image</th>
                                <th class="text-nowrap">School</th>
                                <th class="text-nowrap">Account Title</th>
                                <th class="text-nowrap">Description</th>
                                <th class="text-nowrap">Serial No.</th>
                                <th class="text-nowrap">Property No.</th>
                                <th class="text-nowrap">UOM</th>
                                <th class="text-nowrap">Unit Value (PhP)</th>
                                <th class="text-nowrap">Qty. per Property Card</th>
                                <th class="text-nowrap">Qty. per Physical Count</th>
                                <th class="text-nowrap">Location/Whereabouts</th>
                                <th class="text-nowrap">Condition</th>
                                <th class="text-nowrap">Remarks</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Date Added</th>
                                <th class="text-nowrap">Published</th>
                                <th class="text-nowrap">Person Accountable</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-nowrap">
                                        <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="py-2" style="width: 50px">
                                    </td>
                                    <td class="text-nowrap">{{ $item->school->tag }}</td>
                                    <td>{{ $item->propertyCategory->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->serial_no }}</td>
                                    <td class="text-nowrap">{{ $item->property_no }}</td>
                                    <td>{{ $item->propertyUom->name }}</td>
                                    <td>{{ $item->unit_value ?: '-' }}</td>
                                    <td>{{ $item->qty_per_card ?: '-' }}</td>
                                    <td>{{ $item->qty_per_count ?: '-' }}</td>
                                    <td>{{ $item->propertyLocation->name }}</td>
                                    <td>{{ $item->propertyCondition->name }}</td>
                                    <td>{{ $item->remarks ?: '-' }}</td>
                                    <td>{{ $item->is_available ? 'Available' : 'Borrowed' }}</td>
                                    <td>{{ $item->date_added }}</td>
                                    <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $item->personAccountable->last_name.', '.$item->personAccountable->first_name }}</td>
                                    <td>
                                        @if ($nav == 'properties') 
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                    <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal{{ $item->id }}" href="#">Edit</a></li>
                                                    @if ($item->is_available)
                                                        <li><a class="dropdown-item" href="{{ route('admin.properties.dispose', $item->id) }}" onclick="return confirm('{{ __('messages.sure_dispose') }}');">Dispose</a></li>
                                                        <li><a class="dropdown-item" href="{{ route('admin.properties.destroy', $item->id) }}" onclick="return confirm('{{ __('messages.sure_delete') }}');">Delete</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @else
                                            <a href="{{ route('admin.properties.restore', $item->id) }}" class="btn btn-sm btn-secondary" onclick="return confirm('{{ __('messages.sure_restore') }}');">Restore</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($nav == 'properties') 
                        <div class="btn-group mt-2 float-end">
                            <button class="btn btn-warning batchSelect batchSelectDispose">Dispose</button>
                            <button class="btn btn-danger batchSelect batchSelectDelete">Delete</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if ($nav == 'properties')
    @foreach ($items as $item)
        <form action="{{ route('admin.properties.update', $item->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit {{ $item->code }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">School*</label>
                                    <input type="text" class="form-control" value="{{ $item->school->name }}" readonly required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Account Title*</label>
                                    <select name="property_category_id" class="form-control" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ $item->property_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Description*</label>
                                    <textarea name="description" class="form-control" rows="3" required>{{ $item->description }}</textarea>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Unit of Measurement*</label>
                                    <select name="property_uom_id" class="form-control" required>
                                        @foreach ($uoms as $uom)
                                            <option value="{{ $uom->id }}" {{ $item->property_uom_id == $uom->id ? 'selected' : '' }}>{{ $uom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Unit Value</label>
                                    <input type="number" class="form-control" name="unit_value" value="{{ $item->unit_value }}" min="0" step="0.01">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Qty. per Property Card</label>
                                    <input type="number" class="form-control" name="qty_per_card" value="{{ $item->qty_per_card }}" min="0" step="1">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Qty. per Physical Count</label>
                                    <input type="number" class="form-control" name="qty_per_count" value="{{ $item->qty_per_count }}" min="0" step="1">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Location/Whereabouts*</label>
                                    <select name="property_location_id" class="form-control" required>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" {{ $item->property_location_id == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Condition*</label>
                                    <select name="property_condition_id" class="form-control" required>
                                        @foreach ($conditions as $condition)
                                            <option value="{{ $condition->id }}" {{ $item->property_condition_id == $condition->id ? 'selected' : '' }}>{{ $condition->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="3">{{ $item->remarks }}</textarea>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Date Added*</label>
                                    <input type="date" class="form-control" name="date_added" value="{{ $item->date_added }}" required>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Published*</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Person Accountable*</label>
                                    <select name="person_accountable_id" class="form-control" required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ $item->person_accountable_id == $user->id ? 'selected' : '' }}>{{ $user->employee_no.' - '.$user->last_name.', '.$user->first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Replace Image</label>
                                    <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="py-2" style="width: 50px">
                                    <input type="file" class="form-control" name="image" accept="image/png, image/jpeg">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('{{ __('messages.sure_edit') }}');">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endforeach
    <form action="{{ route('admin.properties.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="dropdownMenuButtonAdd" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Property</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">School*</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->userReference->school->name }}" readonly required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Account Title*</label>
                                <select name="property_category_id" class="form-control" required>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}" {{ old('property_category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Description*</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Unit of Measurement*</label>
                                <select name="property_uom_id" class="form-control" required>
                                    @foreach ($uoms as $item)
                                        <option value="{{ $item->id }}" {{ old('property_uom_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Unit Value</label>
                                <input type="number" class="form-control" name="unit_value" value="{{ old('unit_value') }}" min="0" step="0.01">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Qty. per Property Card</label>
                                <input type="number" class="form-control" name="qty_per_card" value="{{ old('qty_per_card') }}" min="0" step="1">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Qty. per Physical Count</label>
                                <input type="number" class="form-control" name="qty_per_count" value="{{ old('qty_per_count') }}" min="0" step="1">
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Location/Whereabouts*</label>
                                <select name="property_location_id" class="form-control" required>
                                    @foreach ($locations as $item)
                                        <option value="{{ $item->id }}" {{ old('property_location_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Condition*</label>
                                <select name="property_condition_id" class="form-control" required>
                                    @foreach ($conditions as $item)
                                        <option value="{{ $item->id }}" {{ old('property_condition_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Date Added*</label>
                                <input type="date" class="form-control" name="date_added" value="{{ old('date_added') }}" required>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Published*</label>
                                <select name="status" class="form-control" required>
                                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Person Accountable*</label>
                                <select name="person_accountable_id" class="form-control" required>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->id }}" {{ old('person_accountable_id') == $item->id ? 'selected' : '' }}>{{ $item->employee_no.' - '.$item->last_name.', '.$item->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" name="image" accept="image/png, image/jpeg"  required>
                            </div>
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

    <form action="{{ route('admin.properties.batch') }}" method="post" id="batchForm">
        @csrf
        <input type="hidden" name="type">
        <input type="hidden" name="ids">
    </form>
@endif


@endsection

@section('internal-scripts')
    @if ($nav == 'properties')
        <script>
            const buttons = [
                {
                    text: 'Add New',
                    action: function ( e, dt, node, config ) {
                        $('#dropdownMenuButtonAdd').modal('show')
                    },
                },
                'excel',
            ]
        </script>
    @else
        <script>
            const buttons = [
                'excel',
            ]
        </script>
    @endif

    <script>
        $(document).ready(() => {
            $('#itemTable').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                info: false,
                paging: false,
                buttons: buttons,
                select: {
                    style: 'multi'
                }
            });

            let batchIds = [];
            let message = '';
            $('.batchSelect').click(function() {

                if ($(this).hasClass('batchSelectDispose')) {
                    message = "{{ __('messages.sure_dispose') }}"
                } else {
                    message = "{{ __('messages.sure_delete') }}"
                }

                if (confirm(message) == true) {
                    if ($('#itemTable tr.selected').length > 0){
                        batchIds = [];
        
                        $('#itemTable tr.selected').map(function() {
                            batchIds.push($(this).data('id'))
                        })

                        $('#batchForm input[name="ids"]').val(batchIds.join(','))
                        if ($(this).hasClass('batchSelectDispose')) {
                            $('#batchForm input[name="type"]').val('dispose')
                        } else {
                            $('#batchForm input[name="type"]').val('delete')
                        }

                        $('#batchForm').trigger('submit')
                    }
                }
            })
        });
    </script>
@endsection

