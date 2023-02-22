<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeesManagers;
use App\Models\Position;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
class EmployeeController extends Controller
{

    private $response = ['success' => true];


    public function show(Request $request){

       return DataTables::of(Employee::query())->addColumn("action",
           function ($employee){
             return '<button>
                 <img src="'.asset("icons/admin/edit.svg").'" value="'.$employee->id.'" action = "edit">
                    </button> <button uk-toggle="target: #modal-del">
                <img src="'.asset("icons/admin/slash.svg").'" action = "delete" value="'.$employee->id.'" full-name = "'.$employee->full_name.'">
                    </button> ';
           }
       )
           ->editColumn("position_id",function ($employee){
             return Position::find($employee->position_id)->name;
       })->editColumn("photo",function ($employee){
                        return '<img src="'.asset($employee->photo).'" alt="" width="30" height="30">';
           })->rawColumns(['photo','action'])
           ->toJson();

    }



    public function edit($id){

        $employee = Employee::find($id);
        $positions = Position::all();
        $current_position =Position::find($employee->position_id);

        $manager = EmployeesManagers::find($id);
        if($manager){
            $manager = $manager->manager;
        }

        return view("admin.edit",['employee'=>$employee,'positions'=>$positions,'current_position'=>$current_position,"manager" => $manager]);
    }
    public function create(){


        $positions = Position::all();


        return view("admin.edit",['positions'=>$positions]);
    }

    public function getNames(Request $request){

        if(isset($request->name)){
            $employees = Employee::where("full_name","like",$request->name."%")->limit(10)->get();
            $this->response['names'] = $employees;

        }

        return response()->json($this->response);

    }

    public function editEmployee(Request $request){
        $rules = [
            'date' => 'required|date_format:d.m.Y',
            'name' => 'required|min:2|max:256',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^\+380 \([0-9]{2}\) [0-9]{3} [0-9]{2} [0-9]{2}$/'],
            'position' => 'required'
        ];
        if($request->id) $redirect = "/admin/panel/edit/".$request->id;
        else $redirect = "/admin/panel/create";
        $validator = Validator::make($request->all(),$rules);

        if (!empty($validator->fails())) {
            return redirect($redirect)
                ->withErrors($validator)
                ->withInput();
        }else{


          if($request->id) {
              $employee = Employee::find($request->id);
              if(!$employee) return abort(422);
          } else {
              $employee = new Employee();
          }
            $employee->full_name = $request->name;
            $employee->telephone_number = $request->phone;
            $employee->email=$request->email;
            $employee->salary = $request->salary;
            $employee->entry_date = Carbon::parse($request->date)->format('Y-m-d');
            $employee->position_id = $request->position;
            $employee->photo = (!empty($request->img)) ? $request->img : asset("img/default_photo.png");
            $employee->admin_created_id = Auth::user()->id;
            $employee->admin_updated_id = Auth::user()->id;
            $employee->save();


            if(EmployeesManagers::find($employee->id)){
                $menager = EmployeesManagers::find($employee->id);
                $menager->manager_id =  (!empty($request->head_id))? $request->head_id:Employee::where('full_name',$request->head)->first()->id;
                $menager->save();
            }else if(Employee::where('full_name',$request->head)->first() || $request->head_id){
                $menager = new EmployeesManagers();
                $menager->manager_id = (!empty($request->head_id))? $request->head_id:Employee::where('full_name',$request->head)->first()->id;
                $menager->employee_id = $request->id;
                $menager->save();
            }

           return redirect($redirect)->withErrors(['success' => "Success edit employee"]);


        }
    }


    public function loadTempImage(Request $request){
        $rules = [
            'img' => 'mimes:jpg,png|dimensions:min_width=300,min_height=300|max:5000',
        ];

        $validator = Validator::make($request->all(),$rules);


        if(!empty($validator->fails())){

            $this->response['errors'] = $validator->errors();

        }else {

            if($request->parameter == "temp"){
                $path = $request->file('img')->store("temp_img", "public");
                $this->response['path'] = asset("/storage/".$path);
            }

        }

        return response()->json($this->response);
    }

    public function delete(Request $request){



        if($request->id){

            $status = $this->deleteManager($request->id);

            if($status) {
                Employee::find($request->id)->delete();
                if(!Employee::find($request->id)){
                    $this->response['delete'] = true;
                } else {
                    $this->response['delete'] = false;
                }
            }
        }

        return response()->json($this->response);
    }

    private function deleteManager($id){

        $subordinates = Employee::find($id)->subordinates;

        if(count($subordinates)>0){

            $newManager = $subordinates[rand(0,count($subordinates))];
            foreach ($subordinates as $subordinate){
                $setManage = EmployeesManagers::find($subordinate->id);
                if($subordinate->id != $newManager->id) {
                    $setManage->manager_id = $newManager->id;
                    $setManage->save();
                }
            }

            $this->deleteManager($newManager->id);
        }

      return true;
    }

}
