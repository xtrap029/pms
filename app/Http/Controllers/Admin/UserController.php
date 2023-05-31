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

class UserController extends Controller {
    public function index(Request $request) {
        $items = UserReference::orderBy('employee_no', 'asc');

        if ($request->deleted) {
            $items = $items->whereNotNull('deleted_at')->withTrashed();
        }

        $items = $items->get();

        $positions = UserPosition::orderBy('name', 'asc')->get();
        
        return view('admin.users.index')->with([
            'nav' => $request->deleted ? 'users_deleted' : 'users',
            'items' => $items,
            'positions' => $positions,
        ]);
    }

    public function update(Request $request, UserReference $user_reference) {
        
        $data = $request->validate([
            'last_name' => ['required'],
            'first_name' => ['required'],
            'user_position_id' => ['required', 'exists:user_positions,id'],
            'is_admin' => ['boolean'],
        ]);
        
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
        User::where('id', $user_reference->user_id)->delete();
        $user_reference->delete();

        return redirect()->route('admin.users')->with('success', __('messages.delete_success'));
    }

    public function restore($id) {
        UserReference::withTrashed()->find($id)->restore();

        return redirect()->route('admin.users')->with('success', __('messages.restore_success'));
    }
}
