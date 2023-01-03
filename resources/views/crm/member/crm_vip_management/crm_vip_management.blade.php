@extends('layouts.layout_4')
@section('title','vip管理')
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
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl font-bold flex items-center text-white text-2xl">
        <i class="material-icons">grade</i>
        <span class="text-2xl font-bold">vip管理</span> 
    </div>
    <div class="bg-gray-300 p-2 flex items-center shadow">
        <div class="select-none">
            <!-- <form action="{{url('crm/search_vip_management')}}" method="post"> -->
                <!-- @csrf -->
                <label for="" class=" text-2xl">民國年度</label>
                <input id='interface' type="hidden" name="interface" value="search_list">
                <input id='search_val' required name="search_val" value="{{$search_val}}" type="number" placeholder='例如:{{$now_cyear}}' class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                <a href="{{url('crm/vip_management_export?cyear=')}}" id='a_vip_management_export' type="button" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">匯出</a>
                
            <!-- </form> -->
            
        </div>
        <div class='mr-auto'></div>
        <div class="">
            <a href="{{url('crm/add_vip_management')}}" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">新增</a>
            <a href="#" id='delete_vip_management' class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-red-600 hover:border-red-500 rounded shadow-xl">刪除</a>
        </div>
        
    </div>
    <div class="">
        <table id='vip_managements_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
            <thead class="bg-blue-600 text-white ">
                <tr>
                    <th class='border p-2 w-16'><input type='checkbox' class='checked_all w-6 h-6'></th>
                    <th class="border p-2 w-20">年度</th>
                    <th class="border p-2 w-32">編號</th>
                    <th class="border p-2 ">姓名</th>
                    <th class="border p-2 w-32">等級</th>
                    <th class="border p-2 w-32">電話</th>
                    <th class="border p-2 w-32">手機</th>
                    <th class="border p-2 w-32">AO</th>
                    <th class="border p-2 w-24"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-200 h-16">
                    <td class='border p-2'>*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                    <td class="border p-2">*****</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function search_vip_management(){
        var interface = $('#interface').val();
        var search_val = $('#search_val').val();

        var url = $('#a_vip_management_export').attr('href'); // 取得原連結
        var change_search_val = url.substring(url.indexOf('=')); // 抓取 = 以後的資料
            url = url.substr(0,url.length-change_search_val.length) +"=" +search_val; //扣掉 = 以後的資料，再加上=和年度
            $('#a_vip_management_export').attr('href', url); //修改匯出 超連結

        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/search_vip_management')!!}',
            data:{'interface':interface,'search_val':search_val},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#vip_managements_table").html(data.vip_managements_table)
                // visit_record();
            },
            error:function(){
                console.log('error');
            }
        })
    }
    var checked_name = 'checked_vip_management';
    function delete_vip_management(){
        var vals = [];
        $('.'+checked_name+':checkbox:checked').each(function (index, item) {
            vals.push($(this).data('id'));
        });
        if(vals.length){
            if (confirm('確定要刪除嗎?')){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/delete_vip_management')!!}',
                    data:{'vals':vals},
                    dataType:'json',
                    success:function(data){
                        // console.log(data);
                        search_vip_management()
                    },
                    error:function(){
                        console.log('error');
                    }
                })
            }
        }else{
            alert('請勾選要刪除的VIP客戶');
        }
        // console.log(vals);
    }
    $(document).ready(function(){
        search_vip_management()
        $(document).on('change','#search_val',function(){
            search_vip_management();//查詢vip客戶
        });
        $(document).on('click','.checked_all',function(){
            var a = $(this).prop("checked");
            if($(this).prop("checked")){
                $('.'+checked_name).prop("checked", true);
            }else{
                $('.'+checked_name).prop("checked", false);
            }
        });
        $(document).on('click','#delete_vip_management',function(){
            delete_vip_management()
        });
        
    })
</script>

@endsection