<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        <!-- ดึงชื่อของ user ที่ login มาโชว์ -->
            Hello , {{Auth::user()->name}}
           
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                
                <div class="row">
                    <div class="col-md-8">
                        @if(session("success"))
                        <!-- นำ session มาเเสดงเวลาที่บันทึกข้อมูลเรียบร้อยเเล้ว ที่อยู๋ใน with() -->
                            <div class="alert alert-success">{{session("success")}}</div> 
                        @endif
                        <div class="card">
                            <div class="card-header">ตารางข้อมูลแผนก</div>
                           
                                    <table class="table   text-center">
                                        <thead class=" ">
                                             <tr>
                                                <th scope="col">ลำดับ</th>
                                                <th scope="col">ชื่อแผนก</th>
                                                <th scope="col">พนักงาน</th>
                                                <th scope="col">created_at</th>  
                                                <th scope="col">Edit</th>
                                                <th scope="col">Del</th>          
                                            </tr>
                                        </thead>
                                         <tbody>
                                            @foreach($departments as $row)
                                                <tr>
                                                    <th>{{$departments->firstItem()+$loop->index}}</th> <!-- แก้ปัญหาเลือกหน้าไม่ต้อง โดยอ้างอิงจำนวนข้อมูลที่อยู๋ใน DB --> 
                                                    <td>{{$row->department_name}}</td>
                                                    <td>{{$row->user->name}}</td>  
                                                    <td>
                                                        @if($row->created_at == NULL )
                                                            ไม่ถูกนิยาม
                                                        @else
                                                          
                                                            {{Carbon\Carbon::parse($row->created_at)->diffForHumans()}} <!--การดึงข้อมูลจาก DB โดยใช้วิธี query Builder --> 
                                                        @endif
                                                    </td>
                                                    <td><a href="{{url('/department/edit/'.$row->id)}}" class="btn btn-warning">แก้ไข</a></td>
                                                    <td><a href="{{url('/department/softdelete/'.$row->id)}}" class="btn btn-danger">ลบข้อมูล</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$departments->links()}} <!-- กำหนดลิ้งค์ให้ดูข้อมูลหน้าต่อไป --> 
                               
                        </div>
                        <br>

                    @if(count($trashDepartments)>0)
                        <div class="card">
                                <div class="card-header">ถังขยะ</div>
                                        <table class="table   text-center">
                                            <thead class=" ">
                                                <tr>
                                                    <th scope="col">ลำดับ</th>
                                                    <th scope="col">ชื่อแผนก</th>
                                                    <th scope="col">พนักงาน</th>
                                                    <th scope="col">created_at</th>  
                                                    <th scope="col">กู้คืนข้อมูล</th>
                                                    <th scope="col">ลบข้อมูลถาวร</th>          
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($trashDepartments as $row)
                                                    <tr>
                                                        <th>{{$departments->firstItem()+$loop->index}}</th> <!-- แก้ปัญหาเลือกหน้าไม่ต้อง โดยอ้างอิงจำนวนข้อมูลที่อยู๋ใน DB --> 
                                                        <td>{{$row->department_name}}</td>
                                                        <td>{{$row->user->name}}</td>  
                                                        <td>
                                                            @if($row->created_at == NULL )
                                                                ไม่ถูกนิยาม
                                                            @else
                                                            
                                                                {{Carbon\Carbon::parse($row->created_at)->diffForHumans()}} <!--การดึงข้อมูลจาก DB โดยใช้วิธี query Builder --> 
                                                            @endif
                                                        </td>
                                                        <td><a href="{{url('/department/restore/'.$row->id)}}" class="btn btn-info">กู้คืนข้อมูล</a></td>
                                                        <td><a href="{{url('/department/delete/'.$row->id)}}" class="btn btn-danger">ลบข้อมูลถาวร</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{$trashDepartments->links()}} <!-- กำหนดลิ้งค์ให้ดูข้อมูลหน้าต่อไป --> 
                            </div>
                    @endif    
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                                <div class="card-header">แบบฟอร์ม</div>
                                <div class="card-body">
                                    <form action="{{route('addDepartment')}}" method="post">
                                        <!-- ป้องกันการแฮกข้อมูล -->
                                        @csrf
                                        <div class="form-group">
                                            <label for="department_name">ชื่อแผนก</label>
                                            <input type="text" class="form-control" name="department_name">
                                        </div>
                                        @error('department_name')
                                           <div class="my-2"> <span class="text-danger">{{$message}}</span></div>
                                        @enderror

                                        <br>
                                        <input type="submit" value="บันทึก" class="btn btn-success">
                                    </form>
                                </div>
                        </div>
                    </div>
                </div>
                
            </div>
             <div class="col-md-2"></div>
        </div>

    </div>
</x-app-layout>
