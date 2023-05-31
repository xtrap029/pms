<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserReference extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function userPosition() {
        return $this->belongsTo(UserPosition::class);
    }
}