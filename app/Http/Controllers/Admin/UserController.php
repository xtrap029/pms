<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\UserReference;
use App\Models\UserPosition;
use App\Models\User;
use App\Models\School;

class UserController extends Controller {
    public function index(Request $request) {
        $user_reference = UserReference::where('user_id', auth()->id())->first();
        $items = UserReference::where('school_id', $user_reference->school_id)->orderBy('employee_no', 'asc');

        if ($request->deleted) {
            $items = $items->whereNotNull('deleted_at')->withTrashed();
        }
        
        if (!$user_reference->is_super) {
            $items = $items->where('is_super', 0);
        }

        $items = $items->get();

        $positions = UserPosition::orderBy('name', 'asc')->get();

        $schools = [];
        if ($user_reference->is_super) {
            $schools = School::orderBy('name', 'asc')->get();
        }
        
        return view('admin.users.index')->with([
            'nav' => $request->deleted ? 'users_deleted' : 'users',
            'items' => $items,
            'positions' => $positions,
            'schools' => $schools,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'employee_no' => ['required', Rule::unique('user_references')->whereNull('deleted_at')],
            'last_name' => ['required'],
            'first_name' => ['required'],
            'user_position_id' => ['required', 'exists:user_positions,id'],
            'is_admin' => ['in:0,1,2'],
        ]);

        $logged_in = UserReference::where('user_id', auth()->id())->first();

        if ($data['is_admin'] == 2) {
            $data['is_admin'] = 1;
            $data['is_super'] = 1;
        } else {
            $data['is_super'] = 0;
        }


        if ($logged_in->is_super) {
            $school = $request->validate([
                'school_id' => ['required', 'exists:schools,id'],
            ]);

            $data['school_id'] = $school['school_id'];

            if ($data['is_admin'] == 2) {
                $data['is_admin'] = 1;
                $data['is_super'] = 1;
            }
        } else {
            $data['school_id'] = $logged_in->school_id;
        }

        UserReference::create($data);

        return redirect()->route('admin.users')->with('success', __('messages.create_success'));
    }

    public function update(Request $request, UserReference $user_reference) {
        if (!$this->validate_command($user_reference)) {
            return abort(401);
        }
        
        $data = $request->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'user_position_id' => ['required', 'exists:user_positions,id'],
            'is_admin' => ['in:0,1,2'],
        ]);
        
        if ($data['is_admin'] == 2) {
            $data['is_admin'] = 1;
            $data['is_super'] = 1;
        } else {
            $data['is_super'] = 0;
        }

        $logged_in = UserReference::where('user_id', auth()->id())->first();

        if ($logged_in->is_super) {
            $school = $request->validate([
                'school_id' => ['required', 'exists:schools,id'],
            ]);

            $data['school_id'] = $school['school_id'];
        }
        
        if ($user_reference->user_id) {
            $user_data = $request->validate([
                'email' => ['required', Rule::unique('users')->ignore($user_reference->user_id)],
                'password' => ['nullable']
            ]);

            $user = User::find($user_reference->user_id);

            if ($user_data['password']) {
                $user->forceFill([
                    'password' => Hash::make($user_data['password'])
                ])->setRememberToken(Str::random(60));
     
                $user->save();
            }

            $user->update([
                'email' => $user_data['email']
            ]);
        }

        $user_reference->update($data);

        return redirect()->route('admin.users')->with('success', __('messages.edit_success'));
    }

    public function destroy(UserReference $user_reference) {
        if (!$this->validate_command($user_reference)) {
            return abort(401);
        }

        User::where('id', $user_reference->user_id)->delete();
        $user_reference->delete();

        return redirect()->route('admin.users')->with('success', __('messages.delete_success'));
    }

    public function restore($id) {
        $user_reference = UserReference::withTrashed()->find($id)->first();
        if (!$this->validate_command($user_reference)) {
            return abort(401);
        }

        UserReference::withTrashed()->find($id)->restore();

        return redirect()->route('admin.users')->with('success', __('messages.restore_success'));
    }

    private function validate_command($user_reference) {
        $logged_in = UserReference::where('user_id', auth()->id())->first();

        if (!$logged_in->is_super) {
            // disable non super on managing super
            // disable non super on managing other schools
            if ($user_reference->is_super || $user_reference->school_id != $logged_in->school_id) {
                return false;
            }
        }

        return true;
    }
}
