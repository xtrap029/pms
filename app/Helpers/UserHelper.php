<?php

namespace App\Helpers;

use App\Models\UserReference;

final class UserHelper {
    public static function get_user_school_id() {
        if (auth()->id()) {
            $user_reference = UserReference::where('user_id', auth()->id())->first();
            return  $user_reference->school_id;
        } else {
            return false;   
        }
    }
}
?>