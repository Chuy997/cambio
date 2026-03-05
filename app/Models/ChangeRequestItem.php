<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeRequestItem extends Model
{
    protected $fillable = ['change_request_id', 'box_name', 'indirecto_code', 'status'];

    public function changeRequest()
    {
        return $this->belongsTo(ChangeRequest::class);
    }
}
