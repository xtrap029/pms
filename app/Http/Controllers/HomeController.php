<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyBorrow;
use App\Models\PropertyPurchase;
use App\Models\UserReference;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->user()->userReference->is_admin) {
            $properties = Property::orderBy('created_at', 'desc')->limit(5)->get();
            $properties_borrow = Property::orderBy('count_borrow', 'desc')->limit(5)->get();
            $properties_purchase = Property::orderBy('count_purchase', 'desc')->limit(5)->get();
            $properties_to_return = PropertyBorrow::where('status', 1)->whereNull('return_actual_date')->orderBy('return_date', 'asc')->limit(5)->get();
            $users = UserReference::orderBy('employee_no', 'asc')->get();

            $counts = [
                'borrow_pending' => PropertyBorrow::whereNull('status')->count(),
                'purchase_pending' => PropertyPurchase::whereNull('status')->count(),
                'borrow_past' => PropertyBorrow::where('status', 1)->whereNotNull('return_actual_date')->count(),
                'purchase_past' => PropertyPurchase::where('status', 1)->count(),
            ];

            return view('homeAdmin')->with([
                'properties' => $properties,
                'properties_borrow' => $properties_borrow,
                'properties_purchase' => $properties_purchase,
                'properties_to_return' => $properties_to_return,
                'users' => $users,
                'counts' => $counts,
            ]);
        } else {
            $properties = Property::orderBy('created_at', 'desc')->limit(5)->get();
            $pending_borrow = PropertyBorrow::where('requestor_id', $request->user()->userReference->id)->whereNull('status')->orderBy('created_at', 'desc')->limit(5)->get();
            $pending_purchase = PropertyPurchase::where('requestor_id', $request->user()->userReference->id)->whereNull('status')->orderBy('created_at', 'desc')->limit(5)->get();
            $properties_to_return = PropertyBorrow::where('status', 1)->where('requestor_id', $request->user()->userReference->id)->whereNull('return_actual_date')->orderBy('return_date', 'asc')->limit(5)->get();

            return view('home')->with([
                'properties' => $properties,
                'pending_borrow' => $pending_borrow,
                'pending_purchase' => $pending_purchase,
                'properties_to_return' => $properties_to_return,
            ]);
        }

    }
}
