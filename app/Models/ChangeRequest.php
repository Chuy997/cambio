<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    protected $fillable = ['status'];

    public function items()
    {
        return $this->hasMany(ChangeRequestItem::class);
    }
}
