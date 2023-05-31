@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Borrow - History</div>
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
                                <th class="text-nowrap">Date Returned</th>
                                <th class="text-nowrap">Date Approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
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
                                    <td>{{ $item->return_actual_date }}</td>
                                    <td>{{ $item->status_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            });
        });
    </script>
@endsection

