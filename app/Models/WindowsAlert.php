<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowsAlert extends Model
{
    protected $fillable = ['message', 'delivered'];
}
