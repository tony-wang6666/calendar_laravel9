@extends('layouts.layout_4')
@section('title',$title)
@section('content')


<div class="w-288 mx-auto mt-8 select-none">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl flex items-center justify-between ">
        <!-- <div class="w-16"></div> -->
        <div class="text-white">
            <i class="material-icons">note_add</i>
            <span class="text-2xl font-bold">{{$title}}</span> 
        </div>
        <a href="{{url('crm/visit_records_manage')}}" class="bg-gray-500 hover:bg-gray-400 text-white text-base font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">回拜訪管理</a>
    </div>
    <div class="bg-blue-200 p-2">
        <div id="search_customer" class="bg-gray-300" >
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
                <div class="overflow-y-auto h-96 block"> 
                    <table id="search_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
                        <thead id='search_thead' class='bg-gray-600 text-white'>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-blue-200 text-lg font-bold text-center select-none">
    </div>

</div>

@include("crm.member.crm_basic_customer_list.visit_record_add")

<script>
    $(document).ready( function(){
        function crm_search_customer(){
            var use_type = "visit_records_manage_add";
            var basic_c_number = $("#basic_c_number").text();
            var search_type = $("#search_type").val();
            var search_val = $("#search_val").val();
            if(search_type && search_val){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/crm_search_customer')!!}',
                    data:{'use_type':use_type,'search_type':search_type,'search_val':search_val},
                    dataType:'json',
                    success:function(data){
                        $("#search_table").html(data.search_table);
                        // console.log(data)
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

    });
</script>
@endsection