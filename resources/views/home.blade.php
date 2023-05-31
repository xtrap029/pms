@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 mb-3">
                <div class="card-body">
                    <h6 class="p-3 bg-body rounded mb-3">
                        <strong>Properties - Latest</strong>
                        <a href="{{ route('guest.properties') }}" class="float-end text-decoration-none" target="_blank">More</a>
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
                        <strong>Borrows - Pending</strong>
                        <a href="{{ route('guest.borrow.pending') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($pending_borrow as $item)
                                <tr>
                                    <td>
                                        <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="rounded" style="width: 30px">
                                    </td>
                                    <td>
                                        {{ $item->property->description }}<br>
                                        <span class="text-black-50">{{ $item->property->propertyCategory->name }}</span>                                        
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
                        <strong>Puchases - Pending</strong>
                        <a href="{{ route('guest.purchase.pending') }}" class="float-end text-decoration-none" target="_blank">More</a>
                    </h6>
                    <table class="table table-sm">
                        <tbody>
                            @foreach ($pending_purchase as $item)
                                <tr>
                                    <td>
                                        <img src="{{ '/storage/properties/'.$item->property->image }}" alt="" class="rounded" style="width: 30px">
                                    </td>
                                    <td>
                                        {{ $item->property->description }}<br>
                                        <span class="text-black-50">{{ $item->property->propertyCategory->name }}</span>                                        
                                    </td>
                                    <td class="text-end">
                                        {{ Carbon::parse($item->created_at)->format('m-d-Y') }}<br>
                                        <span class="text-black-50">Requested Date</span>    
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
@endsection
