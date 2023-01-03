@extends('layouts.layout_4')
@section('title','匯入客戶資料')
@section('content')

<div class="w-288 mx-auto mt-4 select-none">
    <div class="bg-blue-400 p-2 rounded-t-lg flex items-center justify-center ">
        <i class="material-icons">person_add</i>
        <span class="text-2xl font-bold ">匯入客戶資料</span> 
    </div>


    <div class="bg-blue-200 text-lg font-bold text-center select-none">
        <div class="flex ">
            <div class="text-left text-2xl m-2">
            @if($DB_crm__customer_basic_informations)
                最新建立日期：{{$new_date}}  筆數：{{count($DB_crm__customer_basic_informations)}}
            @endif

            </div>
            <div class='mr-auto'></div>
            <div class="m-2">
                <a href="{{url('crm/basic_customer_data_import')}}" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">轉入資料</a>
                <a href="{{url('crm/basic_customer_data_export')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">匯出資料</a>
            </div>
        </div>
        <div class="">
            @if($DB_crm__customer_basic_informations)
            <table class="shadow-lg bg-whtie mx-auto text-center text-lg">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="border p-2 items-center">編號</th>
                        <th class="border p-2">姓名</th>
                        <th class="border p-2">電話</th>
                        <th class="border p-2">手機</th>
                        <th class="border p-2">區碼</th>
                        <th class="border p-2">縣市</th>
                        <th class="border p-2">鄉鎮</th>
                        <th class="border p-2">地址</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($DB_crm__customer_basic_informations as $k=> $v)
                    @if($k%2 == 1)
                    <tr class="bg-blue-200 h-16">
                    @else
                    <tr class="bg-blue-300 h-16">
                    @endif
                        <td class="border p-2">{{$v->id}}</td>
                        <td class="border p-2">{{$v->c_name_company}}</td>
                        <td class="border p-2">{{$v->c_telephone}}</td>
                        <td class="border p-2">{{$v->c_cellphone}}</td>
                        <td class="border p-2">{{$v->postcode}}</td>
                        <td class="border p-2">{{$v->city}}</td>
                        <td class="border p-2">{{$v->city_area}}</td>
                        <td class="border p-2 w-80 "><div class="overflow-x-auto h-16">{{$v->c_address}}</div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
            <div class="mt-8 mx-auto flex justify-center">
                <span class="text-3xl">查無資料</span>
            </div>
            @endif
        </div>
    </div>

</div>

<script>
    $(document).ready( function(){
    });
</script>
@endsection