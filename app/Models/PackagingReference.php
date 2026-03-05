<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagingReference extends Model
{
    protected $fillable = ['code', 'dimensions', 'type'];
}
