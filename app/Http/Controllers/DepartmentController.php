<?php

namespace App\Http\Controllers;
// imporrt
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\Auth; //เข้าถึงคนที่ล็อคอินได้ ใช้ Auth
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index(){
        // $departments = Department::all();  //  การดึงข้อมูลจาก DB โดยใช้วิธี Eloquent ใช้ผ่าน model departments
        // $departments = DB::table('departments')->get(); //การดึงข้อมูลจาก DB โดยใช้วิธี query Builder 

         $departments = Department::paginate(4); //กำหนดให้แสดงข้อมูลตามจำนวน โดยใช้วิธี Eloquent
        // $departments = DB::table('departments')->paginate(5);  //กำหนดให้แสดงข้อมูลตามจำนวน โดยใช้วิธี query Builder

        $trashDepartments = Department::onlyTrashed()->paginate(4);

        //การ join ตาราง โดยใช้วิธี query Builder ไม่ใช่ model
        // $departments = DB::table('departments') 
        // ->join('users','departments.user_id','users.id')
        // ->select('departments.*','users.name')->paginate(5);

        return view('admin.department.index',compact('departments','trashDepartments'));
    }


    public function store(Request $request){
        // ตรวจสอบข้อมูล
        $request->validate([
            // unique = ไม่ซ้ำ
            'department_name'=>'required|unique:departments|max:255'
        ],
        [
            // ทำให้ error message เป็นภาษาไทย
            'department_name.required'=>"กรุณากรอกชื่อแผนก",
            'department_name.max'=>"ห้ามป้อนเกิน 255 ตัวอักษร",
            'department_name.unique'=>"มีชื่อแผนกนี้ในฐานข้อมูลเเล้ว"
        ]);

       // บันทึกข้อมูล
       $department = new Department;
       //ตัวแปร->ชื่อในคอลัม = ตัวแปร->ช่องinput จาก form
       $department->department_name = $request->department_name;
       $department->user_id = Auth::user()->id; //เข้าถึงคนที่ล็อคอินเข้ามาโดยใช้ id (id มาจากชื่อคอลัมใน DB)
       $department->save(); //บันทึกข้อมูล
       return redirect()->back()->with('success',"บันทึกข้อมูลเรียบร้อย");
    
    }

    public function edit($id){
        // dd(); เช็คดีบัค
        $department = Department::find($id); //ค้นหาข้อมูลในไอดีนั้นๆ
        // dd($department->department_name); //เข้าถึงข้อมูล department_name ของไอดีนั้นๆ

        return view('admin.department.edit',compact('department'));


    }

    public function update(Request $request , $id){
        //ตรวจสอบข้อมูล   
        $request->validate([
            // unique = ไม่ซ้ำ
            'department_name'=>'required|unique:departments|max:255'
        ],
        [
            // ทำให้ error message เป็นภาษาไทย
            'department_name.required'=>"กรุณากรอกชื่อแผนก",
            'department_name.max'=>"ห้ามป้อนเกิน 255 ตัวอักษร",
            'department_name.unique'=>"มีชื่อแผนกนี้ในฐานข้อมูลเเล้ว"
        ]);

        //update data
        $update = Department::find($id)->update([
            'department_name' => $request->department_name,
            'user_id'=>Auth::user()->id
        ]);

        return redirect()->route('department')->with('success',"อัพเดทข้อมูลเรียบร้อย");
    }

    public function softdelete($id){
       $delete = Department::find($id)->delete();
       return redirect()->back()->with('success',"ลบข้อมูลเรียบร้อย");
    }

    public function restore($id){
      $restore =  Department::withTrashed()->find($id)->restore();
      return redirect()->back()->with('success',"กู้คืนข้อมูลเรียบร้อย");
    }

    public function delete($id){
        $delete = Department::onlyTrashed()->find($id)->forceDelete();
        return redirect()->back()->with('success',"ลบข้อมูลถาวรเรียบร้อย");
    }
}
