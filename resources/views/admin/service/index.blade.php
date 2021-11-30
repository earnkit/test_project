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
                            <div class="card-header">ตารางข้อมูลบริการ</div>
                           
                                    <table class="table   text-center">
                                        <thead class=" ">
                                             <tr>
                                                <th scope="col">ลำดับ</th>
                                                <th scope="col">ชื่อบริการ</th>
                                                <th scope="col">ภาพประกอบ</th>
                                                <th scope="col">created_at</th>  
                                                <th scope="col">Edit</th>
                                                <th scope="col">Del</th>          
                                            </tr>
                                        </thead>
                                         <tbody>
                                            @foreach($services as $row)
                                                <tr>
                                                    <th>{{$services->firstItem()+$loop->index}}</th> <!-- แก้ปัญหาเลือกหน้าไม่ต้อง โดยอ้างอิงจำนวนข้อมูลที่อยู๋ใน DB --> 
                                                    <td>{{$row->service_name}}</td>
                                                    <td>
                                                        <img src="{{asset($row->service_image)}}" alt="" width="150px" height="150px">
                            
                                                    </td>  
                                                    <td>
                                                        @if($row->created_at == NULL )
                                                            ไม่ถูกนิยาม
                                                        @else
                                                          
                                                            {{Carbon\Carbon::parse($row->created_at)->diffForHumans()}} <!--การดึงข้อมูลจาก DB โดยใช้วิธี query Builder --> 
                                                        @endif
                                                    </td>
                                                    <td><a href="{{url('/service/edit/'.$row->id)}}" class="btn btn-warning">แก้ไข</a></td>
                                                    <td><a href="{{url('/service/delete/'.$row->id)}}" class="btn btn-danger" onclick="return confirm('ต้องการลบข้อมูลหรือไม่')">ลบข้อมูล</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{$services->links()}} <!-- กำหนดลิ้งค์ให้ดูข้อมูลหน้าต่อไป --> 
                               
                        </div>
                        <br>

                 
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                                <div class="card-header">แบบฟอร์มบริการ</div>
                                <div class="card-body">
                                    <form action="{{route('addService')}}" method="post" enctype="multipart/form-data">
                                        <!-- ป้องกันการแฮกข้อมูล -->
                                        @csrf
                                        <div class="form-group">
                                            <label for="service_name">ชื่อบริการ</label>
                                            <input type="text" class="form-control" name="service_name">
                                        </div>
                                        @error('service_name')
                                           <div class="my-2"> <span class="text-danger">{{$message}}</span></div>
                                        @enderror

                                        <div class="form-group">
                                            <label for="service_image">ภาพประกอบ</label>
                                            <input type="file" class="form-control" name="service_image" enctype="multipart/form-data">
                                        </div>
                                        @error('service_image')
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
