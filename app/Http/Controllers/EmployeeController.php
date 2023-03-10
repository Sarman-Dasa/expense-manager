<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ResponseTraits;

    public function list(Request $request)
    {
        $query = Employee::query();
        $searching_Fields = ['name','email','mobile_number','department_name'];
        return $this->sendFilterListData($query, $searching_Fields);
    /*    
        if($request->search){
            $query->where('name','like',$request->search.'%')
            ->orWhere('email','like',$request->search)
            ->orWhere('mobile_number','like',$request->search)
            ->orWhere('mobile_number','like',$request->search)
            ->orWhere('hiredate','like',$request->search)
            ->orWhere('city','like',$request->search)
            ->orWhere('gender','like',$request->search)
            ->orWhere('salary','like',$request->search);
        }
       if($request->sort)
        {
            $sortOrder = $request->sortOrder == 'desc' ? 'desc': 'asc';
            $query->orderBy($request->sort,$sortOrder);
        }

        if($request->salary)
        {
            $query->where('salary','<=',$request->salary);
        }

        $pagination = $request->perPageData ?? 10;
        $employees = $query->paginate($pagination);
        

        return $this->sendSuccessResponse(true,'Employee(s) Data',$employees);*/
    }


    // public function sort($query, $value)
    // {
    //     $employees = Employee::orderBy($query,$value)->paginate(10);
    //     return view('employee',compact('employees'));
    // }
     
    public function webList()
    {
        $employees = Employee::all();
        return view('EmployeeList',compact('employees'));
    }

}
