<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // for the doftdeletes
use Illuminate\Http\Request;

class Task extends Model
{
    use SoftDeletes; 

    
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];
}
