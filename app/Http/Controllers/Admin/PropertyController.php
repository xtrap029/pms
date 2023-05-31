<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\PropertyUom;
use App\Models\PropertyLocation;
use App\Models\PropertyCondition;
use App\Models\UserReference;

class PropertyController extends Controller {
    public function index(Request $request) {
        $items = Property::orderBy('entity_name', 'asc');

        if ($request->disposed) {
            $items = $items->where('is_disposed', 1);
        } else {
            $items = $items->where('is_disposed', 0);
        }

        $items = $items->get();

        $categories = PropertyCategory::orderBy('name', 'asc')->get();
        $uoms = PropertyUom::orderBy('name', 'asc')->get();
        $locations = PropertyLocation::orderBy('name', 'asc')->get();
        $conditions = PropertyCondition::orderBy('name', 'asc')->get();
        $users = UserReference::orderBy('employee_no', 'asc')->get();
        
        return view('admin.properties.index')->with([
            'nav' => $request->disposed ? 'properties_disposed' : 'properties',
            'items' => $items,
            'categories' => $categories,
            'uoms' => $uoms,
            'locations' => $locations,
            'conditions' => $conditions,
            'users' => $users,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'entity_name' => ['required'],
            'property_category_id' => ['required', 'exists:property_categories,id'],
            'description' => ['required'],
            'property_uom_id' => ['required', 'exists:property_uoms,id'],
            'unit_value' => ['nullable', 'min:0'],
            'qty_per_card' => ['nullable', 'min:0'],
            'qty_per_count' => ['nullable', 'min:0'],
            'property_location_id' => ['required', 'exists:property_locations,id'],
            'property_condition_id' => ['required', 'exists:property_conditions,id'],
            'remarks' => [],
            'date_added' => ['required', 'date'],
            'status' => ['boolean'],
            'person_accountable_id' => ['required', 'exists:user_references,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);
        
        $data['serial_no'] = str_pad(count(Property::where('description', $data['description'])->get())+1, 4, '0', STR_PAD_LEFT);
        $account_title = PropertyCategory::find($data['property_category_id']);
        $data['property_no'] = date('Y', strtotime($data['date_added'])).'-'.$account_title->group.'-'.$account_title->account.'-'.$data['serial_no'].'-'.config('drops.auto_location');

        if ($request->file('image')) {
            $data['image'] = basename($request->file('image')->store('public/properties/'));
        }

        Property::create($data);

        return redirect()->route('admin.properties')->with('success', __('messages.create_success'));
    }
    
    public function update(Request $request, Property $property) {
        
        $data = $request->validate([
            'entity_name' => ['required'],
            'property_category_id' => ['required', 'exists:property_categories,id'],
            'description' => ['required'],
            'property_uom_id' => ['required', 'exists:property_uoms,id'],
            'unit_value' => [],
            'qty_per_card' => [],
            'qty_per_count' => [],
            'property_location_id' => ['required', 'exists:property_locations,id'],
            'property_condition_id' => ['required', 'exists:property_conditions,id'],
            'remarks' => [],
            'date_added' => ['required', 'date'],
            'status' => ['boolean'],
            'person_accountable_id' => ['required', 'exists:user_references,id'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);

        if ($request->file('image')) {
            Storage::delete('public/properties/'.$property->image);
            $data['image'] = basename($request->file('image')->store('public/properties/'));
        }

        $property->update($data);

        return redirect()->route('admin.properties')->with('success', __('messages.edit_success'));
    }

    public function dispose(Property $property) {
        $property->update([
            'is_disposed' => true,
        ]);

        return redirect()->route('admin.properties')->with('success', __('messages.dispose_success'));
    }

    public function restore(Property $property) {
        $property->update([
            'is_disposed' => false,
        ]);

        return redirect()->route('admin.properties')->with('success', __('messages.restore_success'));
    }

    public function destroy(Property $property) {
        $property->delete();

        return redirect()->route('admin.properties')->with('success', __('messages.delete_success'));
    }

    public function batch(Request $request) {
        $data = $request->validate([
            'type' => ['required', 'in:dispose,delete'],
            'ids' => ['required']
        ]);

        $ids = explode(',', $data['ids']);

        if ($data['type'] == 'dispose') {
            Property::whereIn('id', $ids)->update([
                'is_disposed' => 1
            ]);

            return redirect()->route('admin.properties')->with('success', __('messages.dispose_success'));
        } else {
            Property::whereIn('id', $ids)->delete();

            return redirect()->route('admin.properties')->with('success', __('messages.delete_success'));
        }

    }
}
