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
                                <th class="text-nowrap">Borrower's Name</th>
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
                                    <td>{{ $item->userReference->last_name.', '.$item->userReference->first_name }}</td>
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
                    <div class="btn-group mt-2 float-end">
                        <button class="btn btn-success batchSelectApprove">Approve</button>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalDecline">Decline</button>
                        <button class="btn btn-danger batchSelectDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.borrow.pending.destroy') }}" method="post" id="batchFormDelete">
    @csrf
    @method('delete')
    <input type="hidden" name="ids">
</form>

<form action="{{ route('admin.borrow.pending.approve') }}" method="post" id="batchFormApprove">
    @csrf
    <input type="hidden" name="ids">
</form>

<div class="modal fade" id="modalDecline" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reason for Declining</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.borrow.pending.decline') }}" method="post" id="batchFormDecline">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <textarea name="status_reason" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="ids">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary batchSelectDecline">Save changes</button>
            </div>
        </div>
    </div>
</div>
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

            $('.batchSelectDelete').click(function() {

                if (confirm("{{ __('messages.sure_delete') }}") == true) {
                    if ($('#itemTable tr.selected').length > 0){
                        batchIds = [];
                        
                        $('#itemTable tr.selected').map(function() {
                            batchIds.push($(this).data('id'))
                        })

                        $('#batchFormDelete input[name="ids"]').val(batchIds.join(','))
                        $('#batchFormDelete').trigger('submit')
                    }
                }
            })

            $('.batchSelectApprove').click(function() {

                if (confirm("{{ __('messages.sure_approve_2') }}") == true) {
                    if ($('#itemTable tr.selected').length > 0){
                        batchIds = [];
                        
                        $('#itemTable tr.selected').map(function() {
                            batchIds.push($(this).data('id'))
                        })

                        $('#batchFormApprove input[name="ids"]').val(batchIds.join(','))
                        $('#batchFormApprove').trigger('submit')
                    }
                }
            })

            $('.batchSelectDecline').click(function() {

                if (confirm("{{ __('messages.sure_decline') }}") == true) {
                    if ($('#itemTable tr.selected').length > 0){
                        batchIds = [];
                        
                        $('#itemTable tr.selected').map(function() {
                            batchIds.push($(this).data('id'))
                        })

                        $('#batchFormDecline input[name="ids"]').val(batchIds.join(','))
                        $('#batchFormDecline').trigger('submit')
                    }
                }
            })
        });
    </script>
@endsection

