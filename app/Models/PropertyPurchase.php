<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyPurchase extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    public function userReference() {
        return $this->belongsTo(UserReference::class, 'requestor_id');
    }
}