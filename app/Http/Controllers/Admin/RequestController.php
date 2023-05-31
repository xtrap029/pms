<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Property;
use App\Models\PropertyBorrow;
use App\Models\PropertyPurchase;
use App\Models\UserReference;

class RequestController extends Controller {
    public function borrow_pending(Request $request) {
        $items = PropertyBorrow::whereNull('status')->orderBy('created_at', 'desc')->get();
        
        return view('admin.borrow.pending')->with([
            'nav' => 'borrow_pending',
            'items' => $items,
        ]);
    }

    public function borrow_pending_destroy(Request $request) {
        $data = $request->validate([
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        foreach ($ids as $key => $value) {
            PropertyBorrow::where('id', $value)->whereNull('status')->delete();
        }

        return redirect()->route('admin.borrow.pending')->with('success', __('messages.delete_success'));   
    }

    public function borrow_pending_approve(Request $request) {
        $data = $request->validate([
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        foreach ($ids as $key => $value) {
            $property_borrow = PropertyBorrow::find($value);
            $property = Property::find($property_borrow->property_id);

            // check if not borrowed
            if ($property_borrow && $property_borrow->status == NULL && $property && $property->is_available) {

                // cancel other pending borrows
                PropertyBorrow::where('id', '!=', $value)->where('property_id', $property_borrow->property_id)->whereNull('status')->update([
                    'status' => 0,
                    'status_date' => NOW(),
                    'status_reason' => __('messages.unavailable'),
                ]);

                // approve
                PropertyBorrow::where('id', $value)->whereNull('status')->update([
                    'status' => 1,
                    'status_date' => NOW(),
                ]);

                // add borrow count and set to unavailable
                $property->update([
                    'count_borrow' => $property->count_borrow + 1,
                    'is_available' => 0
                ]);
            }
        }

        return redirect()->route('admin.borrow.pending')->with('success', __('messages.approve_success'));   
    }

    public function borrow_pending_decline(Request $request) {
        $data = $request->validate([
            'ids' => ['required'],
            'status_reason' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        foreach ($ids as $key => $value) {
            PropertyBorrow::where('id', $value)->whereNull('status')->update([
                'status' => 0,
                'status_date' => NOW(),
                'status_reason' => $data['status_reason'],
            ]);
        }

        return redirect()->route('admin.borrow.pending')->with('success', __('messages.decline_success')); 
    }

    public function borrow_borrowed(Request $request) {
        $items = PropertyBorrow::where('status', 1)->whereNull('return_actual_date')->orderBy('return_date', 'asc')->get();
        
        return view('admin.borrow.borrowed')->with([
            'nav' => 'borrow_borrowed',
            'items' => $items,
        ]);
    }
    
    public function borrow_borrowed_return(Request $request) {
        $data = $request->validate([
            'ids' => ['required'],
        ]);
        
        $ids = explode(',', $data['ids']);
        
        foreach ($ids as $key => $value) {
            $property_borrow = PropertyBorrow::where('id', $value)->where('status', 1)->whereNull('return_actual_date')->first();

            $property_borrow->update([
                'return_actual_date' => NOW(),
            ]);

            // make property available
            Property::withTrashed()->where('id', $property_borrow->property_id)->first()->update([
                'is_available' => 1
            ]);
        }
        
        return redirect()->route('admin.borrow.borrowed')->with('success', __('messages.return_success')); 
    }

    public function borrow_history(Request $request) {
        $items = PropertyBorrow::where('status', 1)->whereNotNull('return_actual_date')->orderBy('created_at', 'desc')->get();
        
        return view('admin.borrow.history')->with([
            'nav' => 'borrow_history',
            'items' => $items,
        ]);
    }

    public function borrow_rejected(Request $request) {
        $items = PropertyBorrow::where('status', 0)->orderBy('created_at', 'desc')->get();
        
        return view('admin.borrow.rejected')->with([
            'nav' => 'borrow_rejected',
            'items' => $items,
        ]);
    }

    public function borrow_restore(Request $request) {
        $data = $request->validate([
            'ids' => ['required'],
        ]);

        $ids = explode(',', $data['ids']);
        
        foreach ($ids as $key => $value) {
            $property_borrow = PropertyBorrow::where('id', $value)->where('status', 0)->first();

            $property = Property::withTrashed()->where('id', $property_borrow->property_id)->first();

            if ($property->deleted_at == NULL && $property->is_available == 1 && $property->status == 1 && $property->is_disposed == 0) {
                $property_borrow->update([
                    'status' => NULL,
                    'status_date' => NULL,
                    'status_reason' => NULL,
                ]);
            }
        }
        
        return redirect()->route('admin.borrow.rejected')->with('success', __('messages.restore_success')); 
    }

    public function purchase_pending(Request $request) {
        $items = PropertyPurchase::whereNull('status')->orderBy('created_at', 'desc')->get();
        
        return view('admin.purchase.pending')->with([
            'nav' => 'purchase_pending',
            'items' => $items,
        ]);
    }

    public function purchase_pending_destroy(Request $request) {
        $data = $request->validate([
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        foreach ($ids as $key => $value) {
            PropertyPurchase::where('id', $value)->whereNull('status')->delete();
        }

        return redirect()->route('admin.purchase.pending')->with('success', __('messages.delete_success'));   
    }

    public function purchase_pending_approve(Request $request) {
        $data = $request->validate([
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        foreach ($ids as $key => $value) {
            $property_purchase = PropertyPurchase::find($value);
            $property = Property::find($property_purchase->property_id);

            // check if existing
            if ($property_purchase && $property_purchase->status == NULL && $property) {

                // approve
                PropertyPurchase::where('id', $value)->whereNull('status')->update([
                    'status' => 1,
                    'status_date' => NOW(),
                ]);

                // add purchase count
                $property->update([
                    'count_purchase' => $property->count_purchase + 1
                ]);
            }
        }

        return redirect()->route('admin.purchase.pending')->with('success', __('messages.approve_success'));   
    }

    public function purchase_pending_decline(Request $request) {
        $data = $request->validate([
            'ids' => ['required'],
            'status_reason' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        foreach ($ids as $key => $value) {
            PropertyPurchase::where('id', $value)->whereNull('status')->update([
                'status' => 0,
                'status_date' => NOW(),
                'status_reason' => $data['status_reason'],
            ]);
        }

        return redirect()->route('admin.purchase.pending')->with('success', __('messages.decline_success')); 
    }

    public function purchase_history(Request $request) {
        $items = PropertyPurchase::where('status', 1)->orderBy('created_at', 'desc')->get();
        
        return view('admin.purchase.history')->with([
            'nav' => 'purchase_history',
            'items' => $items,
        ]);
    }

    public function purchase_rejected(Request $request) {
        $items = PropertyPurchase::where('status', 0)->orderBy('created_at', 'desc')->get();
        
        return view('admin.purchase.rejected')->with([
            'nav' => 'purchase_rejected',
            'items' => $items,
        ]);
    }

    public function purchase_restore(Request $request) {
        $data = $request->validate([
            'ids' => ['required'],
        ]);

        $ids = explode(',', $data['ids']);
        
        foreach ($ids as $key => $value) {
            $property_purchase = PropertyPurchase::where('id', $value)->where('status', 0)->first();
            $property = Property::withTrashed()->where('id', $property_purchase->property_id)->first();

            if ($property->deleted_at == NULL) {
                $property_purchase->update([
                    'status' => NULL,
                    'status_date' => NULL,
                    'status_reason' => NULL,
                ]);
            }
        }
        
        return redirect()->route('admin.purchase.rejected')->with('success', __('messages.restore_success')); 
    }
}
