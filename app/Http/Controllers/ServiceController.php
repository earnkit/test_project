<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Carbon\Carbon;

class ServiceController extends Controller
{
    public function index(){
        $services = Service::paginate(4);
        return view('admin.service.index',compact('services'));
    }

    public function edit($id){
        $service = Service::find($id); //ค้นหาข้อมูลในไอดีนั้นๆ
        return view('admin.service.edit',compact('service'));
    }


    public function update(Request $request , $id){
        $request->validate([
            'service_name'=>'required|max:255'
        ],
        [
            'service_name.max'=>"ห้ามป้อนเกิน 255 ตัวอักษร", 
            'service_name.required'=>"กรุณากรอกชื่อบริการ"          
        ]);

        $service_image = $request->file('service_image');
        //อัพเดทภาพเเละชื่อ
        if($service_image){

        $name_gen = hexdec(uniqid()); 
        $img_ext = strtolower($service_image->getClientOriginalExtension());
        $img_name = $name_gen.'.'.$img_ext;

        // อัพโหลดเเละบันทึกข้อมูล
            $upload_location = 'image/service/';
            $full_path = $upload_location.$img_name;

        //update
            Service::find($id)->update([
                'service_name'=>$request->service_name,
                'service_image'=>$full_path,
            ]);

        //ลบภาพเก่าอัพภาพใหม่
            $old_image = $request->old_image;
            unlink($old_image);
           

        //upload ไปยัง โฟลเดอร์
            $service_image->move($upload_location,$img_name);   
            return redirect()->route('service')->with('success',"อัพเดทภาพเรียบร้อย");

        }else{
            //อัพเดทชื่ออย่างเดียว
            Service::find($id)->update([
                'service_name'=>$request->service_name,
            ]);
            return redirect()->route('service')->with('success',"อัพเดทชื่อเรียบร้อย");
        }

   
    }

    public function store(Request $request){
        $request->validate([
            'service_name'=>'required|unique:services|max:255',
            'service_image'=>'required|mimes:jpg,jpeg,png'
        ],
        [
            'service_name.required'=>"กรุณากรอกชื่อบริการ",
            'service_name.max'=>"ห้ามป้อนเกิน 255 ตัวอักษร",
            'service_name.unique'=>"มีชื่อบริการนี้ในฐานข้อมูลเเล้ว",
            'service_image.required'=>"กรุณาใส่ภาพประกอบบริการ",
           
        ]    
    );

        //การเข้ารหัสรูปแบบ  ชื่อภาพใหม่
        $service_image = $request->file('service_image');
        //เจนรหัสชื่อภาพใหม่
        $name_gen = hexdec(uniqid());
        
        //ดึง นามสกุลไฟล์ภาพ
        $img_ext = strtolower($service_image->getClientOriginalExtension());
        
        //เปลี่ยนชื่อภาพใหม่
        $img_name = $name_gen.'.'.$img_ext;
    


        // อัพโหลดเเละบันทึกข้อมูล
            $upload_location = 'image/service/';
            $full_path = $upload_location.$img_name;

            
            Service::insert([
                'service_name'=>$request->service_name,
                'service_image'=>$full_path,
                'created_at'=>Carbon::now()
            ]);
            
            //upload ไปยัง โฟลเดอร์
            $service_image->move($upload_location,$img_name);   
            return redirect()->back()->with('success',"บันทึกข้อมูลเรียบร้อย");
            
    }

    public function delete($id){
        //ลบภาพจาพโฟลเดอร์
        $img = Service::find($id)->service_image;
        unlink($img);

        //ลบภาพจาก DB
        $delete = Service::find($id)->delete();
        return redirect()->back()->with('success',"ลบข้อมูลเรียบร้อย");
    }



}
