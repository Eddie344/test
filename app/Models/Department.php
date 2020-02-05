<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'label'
    ];
    /**
     * The employees that belong to the departments.
     */
    public function employees()
    {
        return $this->belongsToMany('App\Models\Employee');
    }
}
