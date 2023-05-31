@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Borrow - Pending</div>
                <div class="card-body">
                    <table id="itemTable" class="table table-bordered table-hover display">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Image</th>
                                <th class="text-nowrap">Account Title</th>
                                <th class="text-nowrap">Description</th>
                                <th class="text-nowrap">Serial No.</th>
                                <th class="text-nowrap">Property No.</th>
                                <th class="text-nowrap">Location/Whereabouts</th>
                                <th class="text-nowrap">Condition</th>
                                <th class="text-nowrap">Remarks</th>
                                <th class="text-nowrap">Date Requested</th>
                                <th class="text-nowrap">Due Date</th>
                                <th class="text-nowrap">Person Accountable</th>
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
                                    <td>{{ $item->property->propertyLocation->name }}</td>
                                    <td>{{ $item->property->propertyCondition->name }}</td>
                                    <td>{{ $item->property->remarks ?: '-' }}</td>
                                    <td>{{ Carbon::parse($item->created_at)->format('m-d-Y') }}</td>
                                    <td>{{ $item->return_date }}</td>
                                    <td>{{ $item->property->personAccountable->last_name.', '.$item->property->personAccountable->first_name }}</td>
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

<form action="{{ route('guest.borrow.pending.destroy') }}" method="post" id="batchForm">
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

