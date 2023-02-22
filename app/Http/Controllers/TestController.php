<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeesManagers;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function Test(){


        $employee = EmployeesManagers::find(6);

       dd($employee->subordinates);

    }
}
