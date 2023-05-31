<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PropertyCategory;

class PropertyCategoryController extends Controller {
    public function index(Request $request) {
        $items = PropertyCategory::orderBy('id', 'asc')->get();
        
        return view('admin.propertyCategories.index')->with([
            'nav' => 'property_categories',
            'items' => $items,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required'],
            'group' => ['required'],
            'account' => ['required'],
        ]);

        PropertyCategory::create($data);

        return redirect()->route('admin.property_categories')->with('success', __('messages.create_success'));
    }
    
    public function update(Request $request, PropertyCategory $property_category) {
        
        $data = $request->validate([
            'name' => ['required'],
            'group' => ['required'],
            'account' => ['required'],
        ]);

        $property_category->update($data);

        return redirect()->route('admin.property_categories')->with('success', __('messages.edit_success'));
    }

    public function destroy(PropertyCategory $property_category) {
        $property_category->delete();

        return redirect()->route('admin.property_categories')->with('success', __('messages.delete_success'));
    }
}
