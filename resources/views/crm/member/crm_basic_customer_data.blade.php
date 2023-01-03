@extends('layouts.layout_4')
@section('title','基本資料')
@section('content')

<!-- <div class="w-screen flex">
    <div class="">
        <div class="flex justify-center">
            <div class='mt-8 bg-green-300 w-auto h-60 rounded-3xl shadow-lg'>
                <div class="w-96 px-4 py-2">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veniam laborum voluptatibus amet et assumenda fugit!
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="w-288 mx-auto pt-8">
    <!-- <div class="bg-blue-400 p-2 rounded-t-xl text-center text-xl font-bold">
        客戶管理
    </div> -->
    <div class="bg-gray-300 mb-2 p-2 flex items-center shadow">
        <div class="select-none">
            <form action="{{url('crm/search_customer_data')}}" method="post">
                @csrf
                <label for="" class=" text-2xl">條件查詢</label>
                <input type="hidden" name="interface" value="search_list">
                <select required name="search_type" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <!-- <option value="c_name_company">客戶姓名</option>
                    <option value="identification_gui_number">身分證號</option>
                    <option value="phone">電話手機</option>
                    <option value="id">客戶編號</option> -->
                    {!!$option_search_type!!}
                </select>
                <input required name="search_val" value="{{$search_val}}" type="text" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                <button type="submit" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">查詢</button>
            </form>
        </div>
        <div class='mr-auto'></div>
        <div class="">
            <a href="{{url('crm/create_customer_data')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">新增</a>
            <!-- <a href="#" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">回列表</a> -->
        </div>
        
    </div>
    @if($interface == 'search_list')
    <div class="mx-2">
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
                    <th class="border p-2"></th>
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
                    <td class="border p-2 w-24">
                        <form action="{{url('crm/search_customer_data')}}" method="post">
                            @csrf
                            <input type="hidden" name="interface" value="basic_customer_data">
                            <input type="hidden" name="search_id" value="{{$v->id}}">
                            <button type="submit" class="bg-blue-500 text-white p-2 hover:bg-blue-600">選取</button>
                        </form>
                    </td>
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
    @elseif($interface == 'basic_customer_data')
    <div class="">
        <ul class="flex border-b-4 select-none">
            <li id="basic_customer_data_tab" class="change_customer_tab mr-1 -mb-1 bg-blue-200 text-blue-700" data-tab="basic_customer_data">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">基本資料</a>
            </li>
            <li id="customer_family_tab"  class="change_customer_tab mr-1 text-blue-500 hover:text-blue-800 hover:bg-blue-200" data-tab="customer_family">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">同戶親屬</a>
            </li>
            <li id="visit_record_tab" class="change_customer_tab mr-1 text-blue-500 hover:text-blue-800 hover:bg-blue-200" data-tab="visit_record">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">拜訪紀錄</a>
            </li>
            <li id="account_balance_tab" class="change_customer_tab mr-1 text-blue-500 hover:text-blue-800 hover:bg-blue-200" data-tab="account_balance">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">帳戶餘額</a>
            </li>
            <li id="change_customer_tab"  class="change_customer_tab mr-1 text-blue-500 hover:text-blue-800 hover:bg-blue-200" data-tab="change_customer">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">大額異動</a>
            </li>
            <li id="contribution_tab" class="change_customer_tab mr-1 text-blue-500 hover:text-blue-800 hover:bg-blue-200" data-tab="contribution">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">貢獻度</a>
            </li>
            <li id="insurance_information_tab" class="change_customer_tab mr-1 text-blue-500 hover:text-blue-800 hover:bg-blue-200" data-tab="insurance_information">
                <a class="inline-block py-2 px-4 border-l border-t border-r border-gray-300 font-semibold" href="#">保險資訊</a>
            </li>
        </ul>
        <div class="">
            @include("crm.member.crm_basic_customer_list.basic_customer_data")
            @include("crm.member.crm_basic_customer_list.customer_family")
            @include("crm.member.crm_basic_customer_list.visit_record")
            @include("crm.member.crm_basic_customer_list.account_balance")
            @include("crm.member.crm_basic_customer_list.change_customer")
            @include("crm.member.crm_basic_customer_list.contribution")
            @include("crm.member.crm_basic_customer_list.insurance_information")
        </div>
    </div>
    @endif
</div>

<script>
    $(document).ready(function(){
        function change_customer_tab(data_tab){
            // var data_tab = $(this).data("tab");
            $("#"+data_tab).siblings().addClass("hidden");
            $("#"+data_tab).removeClass("hidden");
            var li_tab = data_tab+"_tab";
            $("#"+li_tab).siblings().removeClass("-mb-1 bg-blue-200 text-blue-700");
            $("#"+li_tab).siblings().addClass("text-blue-500 hover:text-blue-800 hover:bg-blue-200");
            $("#"+li_tab).addClass("-mb-1 bg-blue-200 text-blue-700");
        }
        // if(getCookieByName('customer_tab')){
        //     change_customer_tab(getCookieByName('customer_tab')); //呼叫cookie //20210508暫時用不到
        // }else{
        //     change_customer_tab("basic_customer_data");
        // }
        
        $(document).on("click",".change_customer_tab",function(){
            var data_tab = $(this).data("tab");
            change_customer_tab(data_tab);
            switch (data_tab){
                case 'customer_family': customer_family(); break;
                case 'visit_record': visit_record(); break;
                case 'account_balance': account_balance(); break;
                case 'change_customer': change_customer(); break;
                case 'contribution': contribution(); break;
                case 'insurance_information': insurance_information(); break;
            }
            
            // document.cookie = 'customer_tab='+data_tab; //儲存tab //20210508暫時用不到
        })
        // document.cookie = 'customer_tab='; //刪除cookie 清空 //20210508暫時用不到
    })
</script>

@endsection