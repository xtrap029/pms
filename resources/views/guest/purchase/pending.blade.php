@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Purchase - Pending</div>
                <div class="card-body">
                    <table id="itemTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Image</th>
                                <th class="text-nowrap">Account Title</th>
                                <th class="text-nowrap">Description</th>
                                <th class="text-nowrap">Serial No.</th>
                                <th class="text-nowrap">Property No.</th>
                                <th class="text-nowrap">UOM</th>
                                <th class="text-nowrap">Unit Value (PhP)</th>
                                <th class="text-nowrap">Requested Qty.</th>
                                <th class="text-nowrap">Total Amount</th>
                                <th class="text-nowrap">Remarks</th>
                                <th class="text-nowrap">Date Requested</th>
                                <th class="text-nowrap">Purpose</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-nowrap">
                                        <img src="{{ '/storage/properties/'.$item->property->image }}" alt="" class="py-2" style="width: 50px">
                                    </td>
                                    <td>{{ $item->property->propertyCategory->name }}</td>
                                    <td>{{ $item->property->description }}</td>
                                    <td>{{ $item->property->serial_no }}</td>
                                    <td class="text-nowrap">{{ $item->property->property_no }}</td>
                                    <td>{{ $item->property->propertyUom->name }}</td>
                                    <td>{{ $item->property->unit_value ?: '-' }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->property->unit_value ? number_format($item->property->unit_value*$item->qty, 2, '.', ',') : '-' }}</td>
                                    <td>{{ $item->property->remarks ?: '-' }}</td>
                                    <td>{{ Carbon::parse($item->created_at)->format('m-d-Y') }}</td>
                                    <td>{{ $item->purpose }}</td>
                                    <td><a class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modal{{ $item->id }}" href="#">Edit</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                    <div class="btn-group float-end">
                        <button class="btn btn-danger batchSelectWithdraw">Withdraw Request</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($items as $item)
    <form action="{{ route('guest.purchase.update', $item->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal fade" id="modal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit {{ $item->purpose }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Requested Qty.</label>
                            <input type="number" min="0" step="0.01" name="qty" value="{{ $item->qty }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purpose</label>
                            <input type="text" name="purpose" value="{{ $item->purpose }}" class="form-control" required>
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

<form action="{{ route('guest.purchase.pending.destroy') }}" method="post" id="batchForm">
    @csrf
    @method('delete')
    <input type="hidden" name="ids">
</form>
@endsection

@section('internal-scripts')
    <script>
        $(document).ready(() => {
            $('#itemTable').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                info: false,
                paging: false,
                buttons: [],
                select: {
                    style: 'multi'
                }
            });

            let batchIds = [];
            $('.batchSelectWithdraw').click(function() {

                if (confirm("{{ __('messages.sure_delete') }}") == true) {
                    if ($('#itemTable tr.selected').length > 0){
                        batchIds = [];
                        
                        $('#itemTable tr.selected').map(function() {
                            batchIds.push($(this).data('id'))
                        })

                        $('#batchForm input[name="ids"]').val(batchIds.join(','))
                        $('#batchForm').trigger('submit')
                    }
                }                
            })
        });
    </script>
@endsection

