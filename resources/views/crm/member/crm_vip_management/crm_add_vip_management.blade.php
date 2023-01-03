@extends('layouts.layout_4')
@section('title','vip管理')
@section('content')


<div class="w-288 mx-auto mt-8 select-none">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl flex items-center justify-between ">
        <!-- <div class="w-16"></div> -->
        <div class="text-white">
            <i class="material-icons">grade</i>
            <span class="text-2xl font-bold">vip管理</span> 
        </div>
    </div>
    <div class="bg-blue-200 p-2">
        <span class="text-xl">選擇會員：</span> 
        <button data-toggle='mycollapse' data-target="#search_customer" class='bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl'>搜尋</button>
        <div id="search_customer" class="max-h-0 overflow-hidden bg-gray-400" >
            <div class="">
                <div class="p-2 text-2xl font-black">選擇會員</div>
                <label for="" class="select-none text-2xl">條件查詢</label>
                <select id="search_type" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    <option value="c_name_company">客戶姓名</option>
                    <option value="identification_gui_number">身分證號</option>
                    <option value="phone">電話手機</option>
                    <option value="id">客戶編號</option>
                </select>
                <input id="search_val" value="" type="text" placeholder="請輸入查詢值" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                
            </div>
            <div id="add_c_number_family_list" class="mt-2">
                <div class="overflow-y-auto h-64 block"> 
                    <table id="search_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
                        <thead id='search_thead' class='bg-gray-600 text-white'>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-blue-200 text-lg font-bold text-center select-none">
        <!-- <form action="{{url('crm/add_vip_management_post')}}" method="post"> -->
            <!-- @csrf -->
            <div class="grid grid-cols-3 gap-4 p-2 ">
                <div class="grid grid-cols-5">
                    <label for="cyear" class="col-span-2 bg-blue-400 mx-2 p-2">年度</label> 
                    <input type="text" id="cyear" name="cyear" class="col-span-3 p-2" value='' placeholder='請輸入年度，例:110'>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_id" class="col-span-2 bg-blue-400 mx-2 p-2">編號</label> 
                    <input type="text" id="c_id" name="c_id" class="col-span-3 px-2" value='' readonly placeholder='請點選搜尋'>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_name" class="col-span-2 bg-blue-400 mx-2 p-2">會員姓名</label> 
                    <input type="text" id="c_name" name="c_name" class="col-span-3 px-2" value='' readonly placeholder='請點選搜尋'>
                </div>
            </div>
            <div class="m-2">
                <button id='btn_add_vip_management_post' type='button' class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl" >添加儲存</button> 
                <a href="{{url('crm/vip_management')}}" >
                    <button type='button' class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">回列表</button> 
                </a>
            </div>
        <!-- </form> -->
    </div>

</div>

<script>
    $(document).ready( function(){
        function crm_search_customer(){
            var basic_c_number = $("#basic_c_number").text();
            var search_type = $("#search_type").val();
            var search_val = $("#search_val").val();
            if(search_type && search_val){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/crm_search_customer')!!}',
                    data:{'basic_c_number':basic_c_number,'search_type':search_type,'search_val':search_val},
                    dataType:'json',
                    success:function(data){
                        $("#search_table").html(data.search_table);
                    },
                    error:function(){
                        console.log('error');
                    }
                })
            }
        }
        $(document).on("change","#search_val , #search_type", function(){
            crm_search_customer()
        });
        $(document).on("click",".check_c_id", function(){
            var type = $(this).data("type");
            var c_id = $(this).data("id");
            $("#c_id").val('獲取資料中...');
            $("#c_name").val('獲取資料中...');
            $('#btn_add_vip_management_post').attr("disabled", true);
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/get_customer_data')!!}',
                data:{'type':type,'c_id':c_id},
                dataType:'json',
                success:function(data){
                    $("#c_id").val(data.crm__customer_basic_information.id);
                    $("#c_name").val(data.crm__customer_basic_information.c_name_company);
                    $("#btn_add_vip_management_post").attr("disabled", false);
                },
                error:function(){
                    console.log('error');
                }
            })
        });
        function add_vip_management_post(){
            var cyear = $("#cyear").val();
            var c_id = $("#c_id").val();
            if(cyear && c_id){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/add_vip_management_post')!!}',
                    data:{'cyear':cyear,'c_id':c_id,},
                    dataType:'json',
                    success:function(data){
                        // console.log(data);
                        alert(data.message);
                    },
                    error:function(){
                        console.log('error');
                    }
                })
            }else{
                alert("1.請輸入年份\n2.搜尋會員並選取");
            }
        }
        $(document).on("click","#btn_add_vip_management_post", function(){
            add_vip_management_post()
        });
        

    });
</script>
@endsection