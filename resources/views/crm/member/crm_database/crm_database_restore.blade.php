@extends('layouts.layout_4')
@section('title',$title)
@section('content')


<div class="w-288 mx-auto mt-8 select-none">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl flex items-center justify-between ">
        <div class="text-white">
            <i class="material-icons">storage</i>
            <span class="text-2xl font-bold">{{$title}}</span> 
        </div>
    </div>
    <div class="bg-gray-300 p-2  shadow">
        <div class="flex items-center">
            <div class='mr-auto pl-4 font-bold'>
                <ul class="list-disc text-xl">
                    <li>1.還原資料庫後，原資料庫資料會被完全覆蓋，<span class='text-red-600'>執行前請先備份原資料庫</span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bg-blue-200 text-lg font-bold text-center select-none p-2">
        @if($result)
        <table id='vip_managements_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
            <thead class="bg-gray-600 text-white ">
                <tr>
                    <th class="border p-2 ">備份日期/更新日期</th>
                    <th class="border p-2 ">檔案名稱</th>
                    <th class="border p-2 ">備註</th>
                    <th class="border p-2 "></th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $v)
                <tr class="bg-gray-200 h-16">
                    <td class='border p-2'>{{$v['created_at']}}/<br>{{$v['updated_at']}}</td>
                    <td class="border p-2">{{$v['backup_name']}}</td>
                    <td class="border p-2">{{$v['backup_remark']}}</td>
                    <td class="border p-2">
                        <form action="{{url('crm/database_restore_go')}}" method="post">  
                            @csrf
                            <input type="hidden" name='backup_id' value="{{$v['id']}}">
                            <button type='submit' class="bg-yellow-500 hover:bg-yellow-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-yellow-600 hover:border-yellow-500 rounded shadow-xl">還原</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>


<script>
    $(document).ready( function(){
    });
</script>
@endsection