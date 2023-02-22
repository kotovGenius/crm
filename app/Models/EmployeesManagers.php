<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesManagers extends Model
{
    protected $primaryKey = "employee_id";
    public function manager(){
        return $this->belongsTo(Employee::class,'manager_id');
    }
    use HasFactory;
}
