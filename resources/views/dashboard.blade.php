<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

        <!-- ดึงชื่อของ user ที่ login มาโชว์ -->
            Hello , {{Auth::user()->name}}
            <b class="float-end">จำนวนผู้ใช้ระบบ {{count($users)}} คน</b>
        </h2>
    </x-slot>

    <div class="py-12">
        <div calss="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                <table class="table   table-hover text-center">
                    <thead class="table-info ">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">date</th>
                            <th scope="col">เริ่มใช้งานระบบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i=1) <!-- ลำดับ -->  
                        @foreach($users as $row)
                        <tr>
                            <th>{{$i++}}</th>
                            <td>{{$row->name}}</td>
                            <td>{{$row->email}}</td>
                            <td>{{$row->created_at}}</td> 
                            <td>{{$row->created_at->diffForHumans()}}</td> 
                            <!-- เเสดงว่า เข้าใช้งานระบบเวลานานเท่าไหร่ ใช้ diffForHumans() -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>

    </div>
</x-app-layout>
