@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <a href="{{ route('admin.borrow.pending') }}" target="_blank" class="btn bg-white w-100 mb-3 text-start">
                <h6 class="py-3 m-0"><strong>Pending Borrow Requests</strong> <span class="badge bg-danger float-end">{{ $counts['borrow_pending'] }}</span></h6> 
            </a>
        </div> 
        <div class="col-md-3">
            <a href="{{ route('admin.purchase.pending') }}" target="_blank" class="btn bg-white w-100 mb-3 text-start">
                <h6 class="py-3 m-0"><strong>Pending Purchase Requests</strong> <span class="badge bg-danger float-end">{{ $counts['purchase_pending'] }}</span></h6> 
            </a>
        </div> 
        <div class="col-md-3">
            <a href="{{ route('admin.borrow.history') }}" target="_blank" class="btn bg-white w-100 mb-3 text-start">
                <h6 class="py-3 m-0"><strong>Past Borrows</strong> <span class="badge bg-secondary float-end">{{ $counts['borrow_past'] }}</span></h6> 
            </a>
        </div> 
        <div class="col-md-3">
            <a href="{{ route('admin.purchase.history') }}" target="_blank" class="btn bg-white w-100 mb-3 text-start">
                <h6 class="py-3 m-0"><strong>Past Purchases</strong> <span class="badge bg-secondary float-end">{{ $counts['purchase_past'] }}</span></h6> 
            </a>
        </div>        
        <div class="col-md-6">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="p-3 bg-body rounded mb-3">
                        <strong>Properties - Latest</strong>
                        <a href="{{ route('admin.properties') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($properties as $item)
                                <tr>
                                    <td>
                                        <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="rounded" style="width: 30px">
                                    </td>
                                    <td>
                                        {{ $item->description }}<br>
                                        <span class="text-black-50">{{ $item->propertyCategory->name }}</span>                                        
                                    </td>
                                    <td class="text-end">
                                        {{ $item->date_added }}<br>
                                        <span class="text-black-50">Date Added</span>    
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="p-3 bg-body rounded mb-3">
                        <strong>Properties - To Return</strong>
                        <a href="{{ route('admin.borrow.borrowed') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($properties_to_return as $item)
                                <tr>
                                    <td>
                                        <img src="{{ '/storage/properties/'.$item->property->image }}" alt="" class="rounded" style="width: 30px">
                                    </td>
                                    <td>
                                        {{ $item->property->description }}<br>
                                        <span class="text-black-50">{{ $item->property->propertyCategory->name }}</span>                                        
                                    </td>
                                    <td class="text-end">
                                        {{ $item->return_date }}<br>
                                        <span class="text-black-50">Due Date</span>                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="p-3 bg-body rounded mb-3">
                        <strong>Most Borrowed</strong>
                        <a href="{{ route('admin.properties') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($properties_borrow as $item)
                                <tr>
                                    <td>
                                        <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="rounded" style="width: 30px">
                                    </td>
                                    <td>
                                        {{ $item->description }}<br>
                                        <span class="text-black-50">{{ $item->propertyCategory->name }}</span>     
                                    </td>
                                    <td>{{ $item->count_borrow }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="p-3 bg-body rounded mb-3">
                        <strong>Most Purchased</strong>
                        <a href="{{ route('admin.properties') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($properties_purchase as $item)
                                <tr>
                                    <td>
                                        <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="rounded" style="width: 30px">
                                    </td>
                                    <td>
                                        {{ $item->description }}<br>
                                        <span class="text-black-50">{{ $item->propertyCategory->name }}</span>     
                                    </td>
                                    <td>{{ $item->count_purchase }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="p-3 bg-body rounded mb-3">
                        <strong>Users</strong>
                        <a href="{{ route('admin.users') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($users as $item)
                                <tr>
                                    <td>
                                        {{ $item->employee_no }} <strong>{{ $item->last_name.', '.$item->first_name }}</strong><br>
                                        <span class="text-black-50">{{ $item->user_position_id ? $item->userPosition->name : '-' }}</span>                                        
                                    </td>
                                    <td>{{ $item->user_id ? $item->user->email : '-' }}</td>
                                    <td>{{ $item->user_id ? 'Registered' : 'Not Registered' }}</td>
                                    <td class="text-end"><strong>{{ $item->is_admin ? 'Admin' : 'Guest' }}</strong></td>
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
