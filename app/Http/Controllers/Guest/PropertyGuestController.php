<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Property;
use App\Models\PropertyBorrow;
use App\Models\PropertyPurchase;
use App\Models\UserReference;

use App\Helpers\UserHelper;

class PropertyGuestController extends Controller {
    public function index(Request $request) {
        $items = Property::where('school_id', UserHelper::get_user_school_id())->where('status', 1)->where('is_disposed', 0)->orderBy('entity_name', 'asc');

        if (!$request->borrowed) {
            $items = $items->where('is_available', 1);
        }

        $items = $items->get();
        
        return view('guest.properties.index')->with([
            'nav' => 'properties',
            'items' => $items,
        ]);
    }

    public function borrow(Request $request) {
        $data = $request->validate([
            'id.*' => ['required', 'exists:properties,id'],
            'date.*' => ['required', 'date']
        ]);

        foreach ($data['id'] as $key => $value) {
            $validate_property = Property::where('id', $value)->where('status', 1)->where('is_disposed', 0)->where('is_available', 1)->first();
            
            if (!$this->validate_command($validate_property)) {
                return abort(401);
            }

            $user_reference = UserReference::where('user_id', auth()->id())->first();

            if ($validate_property) {
                PropertyBorrow::create([
                    'property_id' => $value,
                    'requestor_id' => $user_reference->id,
                    'return_date' => $data['date'][$key],
                ]);
            }
        }

        return redirect()->route('guest.properties')->with('success', __('messages.request_success'));
    }

    public function borrow_pending(Request $request) {
        $user_reference = UserReference::where('user_id', auth()->id())->first();
        $items = PropertyBorrow::where('requestor_id', $user_reference->id)->whereNull('status')->orderBy('created_at', 'desc')->get();
        
        return view('guest.borrow.pending')->with([
            'nav' => 'borrow_pending',
            'items' => $items,
        ]);
    }

    public function borrow_pending_destroy(Request $request) {
        $data = $request->validate([
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);
        $user_reference = UserReference::where('user_id', auth()->id())->first();

        foreach ($ids as $key => $value) {
            PropertyBorrow::where('id', $value)->where('requestor_id', $user_reference->id)->whereNull('status')->delete();
        }

        return redirect()->route('guest.borrow.pending')->with('success', __('messages.withdraw_success'));   
    }

    public function borrow_history(Request $request) {
        $user_reference = UserReference::where('user_id', auth()->id())->first();
        $items = PropertyBorrow::where('requestor_id', $user_reference->id)->whereNotNull('status')->orderBy('created_at', 'desc')->get();
        
        return view('guest.borrow.history')->with([
            'nav' => 'borrow_history',
            'items' => $items,
        ]);
    }

    public function purchase(Request $request) {
        $data = $request->validate([
            'id.*' => ['required', 'exists:properties,id'],
            'qty.*' => ['required', 'min:0'],
            'purpose.*' => ['required'],
        ]);

        foreach ($data['id'] as $key => $value) {
            $validate_property = Property::where('id', $value)->where('status', 1)->where('is_disposed', 0)->first();

            if (!$this->validate_command($validate_property)) {
                return abort(401);
            }

            $user_reference = UserReference::where('user_id', auth()->id())->first();

            if ($validate_property) {
                PropertyPurchase::create([
                    'property_id' => $value,
                    'requestor_id' => $user_reference->id,
                    'qty' => $data['qty'][$key],
                    'purpose' => $data['purpose'][$key],
                ]);
            }
        }

        return redirect()->route('guest.properties')->with('success', __('messages.request_success'));
    }
    
    public function purchase_pending(Request $request) {
        $user_reference = UserReference::where('user_id', auth()->id())->first();
        $items = PropertyPurchase::where('requestor_id', $user_reference->id)->whereNull('status')->orderBy('created_at', 'desc')->get();
        
        return view('guest.purchase.pending')->with([
            'nav' => 'purchase_pending',
            'items' => $items,
        ]);
    }

    public function purchase_pending_destroy(Request $request) {
        $data = $request->validate([
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);
        $user_reference = UserReference::where('user_id', auth()->id())->first();

        foreach ($ids as $key => $value) {
            PropertyPurchase::where('id', $value)->where('requestor_id', $user_reference->id)->whereNull('status')->delete();
        }

        return redirect()->route('guest.purchase.pending')->with('success', __('messages.withdraw_success'));   
    }

    public function purchase_update(Request $request, PropertyPurchase $property_purchase) {
        $data = $request->validate([
            'qty' => ['required', 'min:0'],
            'purpose' => ['required'],
        ]);

        $validate_property = Property::where('id', $property_purchase->property_id)->first();
        if (!$this->validate_command($validate_property)) {
            return abort(401);
        }

        $property_purchase->update($data);

        return redirect()->route('guest.purchase.pending')->with('success', __('messages.edit_success'));
    }

    public function purchase_history(Request $request) {
        $user_reference = UserReference::where('user_id', auth()->id())->first();
        $items = PropertyPurchase::where('requestor_id', $user_reference->id)->whereNotNull('status')->orderBy('created_at', 'desc')->get();
        
        return view('guest.purchase.history')->with([
            'nav' => 'purchase_history',
            'items' => $items,
        ]);
    }

    private function validate_command($property) {
        if ($property->school_id != UserHelper::get_user_school_id()) {
            return false;
        }

        return true;
    }
}
