@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Properties</div>
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
                                <th class="text-nowrap">Status</th>
                                <th class="text-nowrap">Qty. per Property Card</th>
                                <th class="text-nowrap">Qty. per Physical Count</th>
                                <th class="text-nowrap">Location/Whereabouts</th>
                                <th class="text-nowrap">Condition</th>
                                <th class="text-nowrap">Remarks</th>
                                <th class="text-nowrap">Date Added</th>
                                <th class="text-nowrap">Published</th>
                                <th class="text-nowrap">Person Accountable</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-nowrap">
                                        <img src="{{ '/storage/properties/'.$item->image }}" alt="" class="py-2" style="width: 50px">
                                    </td>
                                    <td>{{ $item->school->tag }}</td>
                                    <td class="batchSelectAttrTitle">{{ $item->propertyCategory->name }}</td>
                                    <td class="batchSelectAttrDescription">{{ $item->description }}</td>
                                    <td>{{ $item->serial_no }}</td>
                                    <td class="text-nowrap batchSelectAttrProperty">{{ $item->property_no }}</td>
                                    <td>{{ $item->propertyUom->name }}</td>
                                    <td class="batchSelectAttrUnitValue">{{ $item->unit_value ?: '-' }}</td>
                                    <td class="batchSelectAttrStatus">{{ $item->is_available ? 'Available' : 'Borrowed' }}</td>
                                    <td>{{ $item->qty_per_card ?: '-' }}</td>
                                    <td>{{ $item->qty_per_count ?: '-' }}</td>
                                    <td>{{ $item->propertyLocation->name }}</td>
                                    <td>{{ $item->propertyCondition->name }}</td>
                                    <td>{{ $item->remarks ?: '-' }}</td>
                                    <td>{{ $item->date_added }}</td>
                                    <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                    <td>{{ $item->personAccountable->last_name.', '.$item->personAccountable->first_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($nav == 'properties') 
                        <div class="alert alert-primary" role="alert">
                            We constantly update our product catalog, so if you can't find the specific item you're searching for, please contact our administrator to request its addition.
                        </div>
                        <div class="btn-group float-end">
                            <button class="btn btn-success batchSelectPuchase">Purchase Request</button>
                            <button class="btn btn-primary batchSelectBorrow">Borrow Request</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('guest.properties.borrow') }}" method="post">
    @csrf
    <div class="modal modal-lg fade" id="dropdownMenuButtonBorrow" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request to Borrow Property(ies) Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th class="text-nowrap">Account Title</th>
                                <th class="text-nowrap">Description</th>
                                <th class="text-nowrap">Property No.</th>
                                <th class="text-nowrap">Return Date</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel Selection</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('{{ __('messages.sure_borrow') }}');">Submit Request</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('guest.properties.purchase') }}" method="post">
    @csrf
    <div class="modal modal-xl fade" id="dropdownMenuButtonPurchase" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request to Purchase Property(ies) Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th class="text-nowrap">Account Title</th>
                                <th class="text-nowrap">Description</th>
                                <th class="text-nowrap">Property No.</th>
                                <th class="text-nowrap">Unit Value (PhP)</th>
                                <th class="text-nowrap" style="width: 100px;">Req. Qty.</th>
                                <th class="text-nowrap">Total Amount</th>
                                <th class="text-nowrap" style="min-width: 200px;">Purpose</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel Selection</button>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('{{ __('messages.sure_borrow') }}');">Submit Request</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('internal-scripts')
    @if (isset($_GET['borrowed']))
        <script>
            const buttons = [
                {
                    text: 'Hide Borrowed Properties',
                    action: function ( e, dt, node, config ) {
                        window.location.replace("{{ route('guest.properties') }}");
                    },
                },
            ]
        </script>
        @else
        <script>
            const buttons = [
                {
                    text: 'Show Borrowed Properties',
                    action: function ( e, dt, node, config ) {
                        window.location.replace("{{ route('guest.properties') }}?borrowed=1");
                    },
                },
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

            $('.batchSelectBorrow').click(function() {

                if ($('#itemTable tr.selected').length > 0){
                    const modal = $('#dropdownMenuButtonBorrow')
                    modal.modal('show')

                    let tbody = modal.find('.modal-body table > tbody')
                    tbody.html('')
    
                    $('#itemTable tr.selected').map(function() {

                        if ($(this).find('.batchSelectAttrStatus').text() === 'Available') {    
                            tbody.append('<tr>'
                                    +'<td>'+$(this).find('.batchSelectAttrTitle').html()+'</td>'
                                    +'<td>'+$(this).find('.batchSelectAttrDescription').html()+'</td>'
                                    +'<td>'+$(this).find('.batchSelectAttrProperty').html()+'</td>'
                                    +'<td><input type="hidden" name="id[]" value="'+$(this).data('id')+'" required><input type="date" name="date[]" class="form-control" required>'
                                +'</tr>')
                        }
                    })
                }
            })

            $('.batchSelectPuchase').click(function() {

                if ($('#itemTable tr.selected').length > 0){
                    const modal = $('#dropdownMenuButtonPurchase')
                    modal.modal('show')

                    let tbody = modal.find('.modal-body table > tbody')
                    tbody.html('')
    
                    $('#itemTable tr.selected').map(function() {

                        // if ($(this).find('.batchSelectAttrStatus').text() === 'Available') {    
                            tbody.append('<tr>'
                                    +'<td>'+$(this).find('.batchSelectAttrTitle').html()+'</td>'
                                    +'<td>'+$(this).find('.batchSelectAttrDescription').html()+'</td>'
                                    +'<td>'+$(this).find('.batchSelectAttrProperty').html()+'</td>'
                                    +'<td>'+$(this).find('.batchSelectAttrUnitValue').html()+'</td>'
                                    +'<td><input type="hidden" name="id[]" value="'+$(this).data('id')+'" required><input type="number" min="0" step="0.01" name="qty[]" class="form-control batchQty" required>'
                                    +'<td></td>'
                                    +'<td><input type="text" name="purpose[]" class="form-control" required></td>'
                                +'</tr>')
                        // }
                    })
                }
            })

            $(document).on('change', '.batchQty', function() {
                let inputVal = parseFloat($(this).val()) * parseFloat($(this).parent().prev().text())
                $(this).parent().next().text(isNaN(inputVal) ? '-' : inputVal)
            })
        });
    </script>
@endsection

