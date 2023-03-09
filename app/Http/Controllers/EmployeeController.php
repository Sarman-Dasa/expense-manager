<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ResponseTraits;

    public function list()
    {
        $employees = Employee::paginate(10);
        //return $this->sendSuccessResponse(true,'Employee(s) Data',$employees);
        return view('employee',compact('employees'));
    }

    public function get($query, $value)
    {
        if($query=='search')
            $employees = Employee::where('name','like',$value .'%')->paginate(10);
        else if($query=='sortBy')
            $employees = Employee::orderBy($value,'DESC')->paginate(10);
        return $this->sendSuccessResponse(true,'Employee(s) Data',$employees);
    }

}
