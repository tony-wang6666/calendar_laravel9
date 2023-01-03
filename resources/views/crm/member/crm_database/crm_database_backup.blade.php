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
            <div class='mr-auto pl-4'>
                <ul class="list-disc text-xl">
                    <li>1.備份檔名，可使用預設日期為檔名(預設日期為今天)</li>
                    <li>2.檔名相同將會被覆蓋</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bg-blue-200 p-2">
        <!-- <a href="{{url('crm/database_backup_go')}}" class="bg-green-500 hover:bg-green-400 text-white text-base font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">資料庫備份</a> -->
        <form action="{{url('crm/database_backup_add')}}" method="post" class="text-xl">
            @csrf
            <div class="p-2 flex items-center justify-center">
                <div class="bg-blue-400 mx-2 p-2">
                    <label for="backup_name" class="font-bold text-center">備份檔檔名</label>
                </div>
                <input type="text" id="backup_name" name="backup_name" value="{{$now_date}}" class="p-2" placeholder="請輸入備份檔檔名" required="">
            </div>
            <div class="p-2 flex items-center justify-center">
                <div class="bg-blue-400 mx-2 p-2">
                    <label for="backup_remark" class="font-bold text-center">備份檔備註</label>
                </div>
                <input type="text" id="backup_remark" name="backup_remark" value="" class="p-2" placeholder="請輸入備份檔備註" >
            </div>
            <div class="flex justify-center">
                <button type='submit' class="bg-blue-500 hover:bg-blue-400 text-white font-bold m-2 py-2 px-4 hover:border-blue-500 rounded shadow-xl">備份</button>
            </div>
        </form>
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
                    <td class="border p-2"><a href="{{url('file/f1f458be0cc3a/'.md5($v['backup_name'].'cld_crm').'.sql')}}" download="{{$v['backup_name']}}.sql" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">下載</a></td>
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