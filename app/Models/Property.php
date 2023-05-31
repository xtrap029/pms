<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model {
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function propertyCategory() {
        return $this->belongsTo(PropertyCategory::class)->withTrashed();
    }

    public function personAccountable() {
        return $this->belongsTo(UserReference::class, 'person_accountable_id')->withTrashed();
    }

    public function propertyUom() {
        return $this->belongsTo(PropertyUom::class)->withTrashed();
    }

    public function propertyLocation() {
        return $this->belongsTo(PropertyLocation::class)->withTrashed();
    }

    public function propertyCondition() {
        return $this->belongsTo(PropertyCondition::class)->withTrashed();
    }
}