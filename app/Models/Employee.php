<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'surname', 'name', 'patronymic', 'gender_id', 'salary'
    ];
    /**
     * The departments that belong to the employee.
     */
    public function departments()
    {
        return $this->belongsToMany('App\Models\Department');
    }
    public function gender()
    {
        return $this->belongsTo('App\Models\Gender');
    }
}
