@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Purchase - Rejected</div>
                <div class="card-body">
                    <table id="itemTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Image</th>
                                <th class="text-nowrap">Requestor's Name</th>
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
                                <th class="text-nowrap">Date Rejected</th>
                                <th class="text-nowrap">Reason for Rejecting</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-nowrap">
                                        <img src="{{ '/storage/properties/'.$item->property->image }}" alt="" class="py-2" style="width: 50px">
                                    </td>
                                    <td>{{ $item->userReference->last_name.', '.$item->userReference->first_name }}</td>
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
                                    <td>{{ $item->status_date }}</td>
                                    <td>{{ $item->status_reason }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="btn-group mt-2 float-end">
                        <button class="btn btn-primary batchSelectRevert">Revert</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.purchase.rejected.restore') }}" method="post" id="batchFormRevert">
    @csrf
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
                buttons: ['excel'],
                select: {
                    style: 'multi'
                }
            });

            let batchIds = [];

            $('.batchSelectRevert').click(function() {

                if (confirm("{{ __('messages.sure_restore') }}") == true) {
                    if ($('#itemTable tr.selected').length > 0){
                        batchIds = [];
                        
                        $('#itemTable tr.selected').map(function() {
                            batchIds.push($(this).data('id'))
                        })

                        $('#batchFormRevert input[name="ids"]').val(batchIds.join(','))
                        $('#batchFormRevert').trigger('submit')
                    }
                }
            })
        });
    </script>
@endsection
