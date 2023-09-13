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
    public function index(Request $request) {
        $school_id = $request->user()->userReference->school_id;

        if ($request->user()->userReference->is_admin) {
            $properties = Property::where('school_id', $request->user()
                ->userReference->school_id)
                ->orderBy('created_at', 'desc')->limit(5)->get();
            $properties_borrow = Property::where('school_id', $school_id)
                ->orderBy('count_borrow', 'desc')->limit(5)->get();
            $properties_purchase = Property::where('school_id', $school_id)
                ->orderBy('count_purchase', 'desc')->limit(5)->get();
            $properties_to_return = PropertyBorrow::where('status', 1)
                ->whereNull('return_actual_date')
                ->whereHas('property', function($q) use($school_id){
                    $q->where('school_id', $school_id);
                })
                ->orderBy('return_date', 'asc')->limit(5)->get();
            $users = UserReference::where('school_id', $school_id)
                ->orderBy('employee_no', 'asc')->get();

            $counts = [
                'borrow_pending' => PropertyBorrow::whereNull('status')
                    ->whereHas('property', function($q) use($school_id){
                        $q->where('school_id', $school_id);
                    })
                    ->count(),
                'purchase_pending' => PropertyPurchase::whereNull('status')
                    ->whereHas('property', function($q) use($school_id){
                        $q->where('school_id', $school_id);
                    })
                    ->count(),
                'borrow_past' => PropertyBorrow::where('status', 1)
                    ->whereHas('property', function($q) use($school_id){
                        $q->where('school_id', $school_id);
                    })
                    ->whereNotNull('return_actual_date')->count(),
                'purchase_past' => PropertyPurchase::where('status', 1)
                    ->whereHas('property', function($q) use($school_id){
                        $q->where('school_id', $school_id);
                    })    
                    ->count(),
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
            $properties = Property::where('school_id', $school_id)->orderBy('created_at', 'desc')->limit(5)->get();
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
