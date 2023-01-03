@extends('layouts.layout_1')
@section('content')

<!-- <div class='bg-gray-100 container mx-auto px-2 w-full '>
    <button id='btn_add_case' class="page_change bg-blue-800 hover:bg-blue-900 fixed z-30 right-8 bottom-24 w-10 h-10 rounded-full flex items-center justify-center focus:outline-none opacity-90">
        <i class="material-icons text-white">receipt_long</i>
    </button>
</div> -->

<div id='calendar_today' class='bg-gray-100 container mx-auto px-2 w-full '>
    @if($date_event_json)
    <div class="flex flex-wrap justify-end">
        <button class="add_case_type_check bg-blue-500 hover:bg-blue-600 rounded-lg p-2 my-1 mx-1 text-white text-lg flex" data-addcasetype='general'>
            <i class='material-icons'>check</i>一般行程
        </button>
        <button class="add_case_type_check bg-blue-500 hover:bg-blue-600 rounded-lg p-2 my-1 mx-1 text-white text-lg flex" data-addcasetype='ungeneral'>
            <i class='material-icons'>check</i>婚喪喜慶
        </button>
    </div>
    <div class='w-full border-b-2 border-gray-400'></div>
    <div class='grid-rows-1 text-lg'>
        @foreach($date_event_json as $k=>$v)
            @if($v->type == 0 )
            <div class='{{$v->add_case_type}} h-auto pl-auto truncate border-green-200 text-white bg-green-700 mx-1 my-1 px-2 rounded-lg hover:bg-green-800' data-id='{{$v->id}}' >
            @else
            <div class='{{$v->add_case_type}} a_detail_edit {{UserHelper::dateEventTypeTOcolor($v->type)}} h-auto pl-auto truncate mx-1 my-1 px-2 rounded-lg' data-id='{{$v->id}}' >
            @endif
                <div class="flex items-center">
                    <span class='truncate'>{{$v->title}}</span>
                @if($v->things_ok == 1) 
                    <span class="material-icons text-green-800">task</span>
                @endif
                @if(!empty($v->which_day)) 
                    <div class=''>{{$v->which_day}}</div>
                @endif
                </div>
                <div class="">{{$v->time}}</div>
                @if(!empty($v->things))
                    <!-- @if($v->thing_remark)
                    <div class="">{{$v->thing_remark}}</div>
                    @endif -->
                    @foreach($v->things as $k2=>$v2)
                    <div class="">
                        <span class="">{{$v2->name}}</span>
                        <span class="">-{{$v2->schedule}}</span>
                    </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
    @else
    <div class='w-full text-2xl text-center py-8 '>
        <div class='bg-gray-200 mx-auto flex text-center'>
            <div class="mx-auto flex">
                <i class="material-icons md-36 ">free_breakfast</i>
                <span class="">今日無行程</span>
            </div>
        </div>
    </div>
    @endif

    <!-- <div class='
    border-green-200 text-white bg-green-700 hover:bg-green-800
    border-red-200 text-red-800 bg-red-300 hover:bg-red-400
    border-yellow-400 text-yellow-900 bg-yellow-500 hover:bg-yellow-600
    border-yellow-200 text-yellow-800 bg-yellow-300 hover:bg-yellow-400
    border-green-200 text-green-800 bg-green-300 hover:bg-green-400
    border-blue-200 text-blue-800 bg-blue-300 hover:bg-blue-400
    border-indigo-200 text-indigo-800 bg-indigo-400 hover:bg-indigo-500
    border-purple-200 text-purple-800 bg-purple-400 hover:bg-purple-500'></div> -->
    
</div>
<div id='receipt_long' class='bg-gray-100 container mx-auto px-2 w-full hidden'>
    
    <div class="flex flex-wrap justify-end">
        <div id='a_check_list' class="pr-3 text-white mt-4">
            <!-- <a href="https://wuchi.azurewebsites.net" target="_blank" class="bg-blue-500 hover:bg-blue-600 rounded-lg px-2 py-3 my-1 mx-1"><i class="material-icons">receipt_long</i></a> -->
        </div>
    </div>
    <div class='w-full border-b-2 border-gray-400 mt-4'></div>
    <div id='a_today_leave_member' class="flex flex-wrap justify-center">
        <div class="text-center text-2xl">資料載入中...</div>
        <!-- <div class="col-span-1 flex items-center justify-center text-2xl font-black m-3 lg:w-2/5 w-full">
            <div class="text-center w-full border-blue-400 border-4">
                <div class="bg-blue-500 text-white w-full py-4">今日請假(1)</div>
                <div class="">
                    <div class="bg-yellow-200 text-yellow-800 py-2">審核中</div>
                    <div class="bg-green-300 text-green-900 py-2">已通過</div>
                    <div class="py-2">今日沒有請假人員</div>
                </div>
            </div>
        </div>
        <div class="col-span-1 flex items-start justify-center text-2xl font-black m-3 lg:w-2/5 w-full">
            <div class="text-center w-full border-blue-400 border-4">
                <div data-toggle='mycollapse' data-target="#list_data" class="bg-blue-500 text-white w-full py-4">1</div>
                <div id='list_data' class="max-h-0 overflow-hidden">
                    <div class="bg-yellow-200 text-yellow-800 py-2 border-b-2 border-gray-400">
                        <div class='text-xl'>2</div>
                        <div class='text-base text-right pr-3'>3</div>
                    </div>
                    <div class="bg-green-300 text-green-900 py-2 border-b-2 border-gray-400">
                        <div class='text-xl'>4</div>
                        <div class='text-base text-right pr-3'>5</div>
                    </div>
                </div>
                <div id='list_data' class="max-h-0 overflow-hidden">
                    <div class="py-2">6</div>
                </div>
            </div>
        </div> -->
    </div>
</div>

<!-- Modal -->
@include('modals.modal_add_case')
<!-- /Modal -->
<script>
    $(document).ready(function(){
        $(document).on('change','#mydate',function(){
            var date = new Date($(this).val());
            location.replace('first_page?year='+date.getFullYear()+'&month='+(date.getMonth()+1)+'&day='+date.getDate());  
        });
        $(document).on('click','.a_detail_edit',function(){
            var id = $(this).data('id');
            window.location.href='detail_edit?id='+id;
        });
        $(document).on('click','.page_change',function(){
            var page = $(".page_change i").text();
            if(page == 'receipt_long'){
                $("#calendar_today").addClass("hidden");
                $("#receipt_long").removeClass("hidden");
                $(".page_change i").text("calendar_today");
                $(".page_change").removeClass("bg-blue-800 hover:bg-blue-900");
                $(".page_change").addClass("bg-green-800 hover:bg-green-900");
            }else if(page == 'calendar_today'){
                $("#receipt_long").addClass("hidden");
                $("#calendar_today").removeClass("hidden");
                $(".page_change i").text("receipt_long");
                $(".page_change").removeClass("bg-green-800 hover:bg-green-900");
                $(".page_change").addClass("bg-blue-800 hover:bg-blue-900");
            }
        });
        $(document).on('click','.add_case_type_check',function(){
            var addcasetype = $(this).data('addcasetype');
            var check = $(this).children("i").text();
            if(check == 'check'){
                $(this).children("i").text("clear");
                $("."+addcasetype).addClass("hidden");
            }else{
                $(this).children("i").text("check");
                $("."+addcasetype).removeClass("hidden");
            }
            // console.log(addcasetype);
            // console.log(check);
            // var page = $(this).text();
            // if(page == 'receipt_long'){
            //     $("#calendar_today").addClass("hidden");
            //     $("#receipt_long").removeClass("hidden");
            //     $(".page_change i").text("calendar_today");
            //     $(".page_change").removeClass("bg-blue-800 hover:bg-blue-900");
            //     $(".page_change").addClass("bg-green-800 hover:bg-green-900");
            // }else if(page == 'calendar_today'){
            //     $("#receipt_long").addClass("hidden");
            //     $("#calendar_today").removeClass("hidden");
            //     $(".page_change i").text("receipt_long");
            //     $(".page_change").removeClass("bg-green-800 hover:bg-green-900");
            //     $(".page_change").addClass("bg-blue-800 hover:bg-blue-900");
            // }
        });
        
        function leave_first_page_data_json(){
            $.ajax({
                type:'get',
                url:'{!!URL::to('leave_data/leave_first_page_data_json')!!}',
                dataType:'json',
                success:function(data){
                    document.getElementById('a_check_list').innerHTML = data.a_check_list;
                    document.getElementById('a_today_leave_member').innerHTML = data.a_today_leave_member;
                },
                error:function(){
                    console.log('error');
                }
            });
        }
        // leave_first_page_data_json()
    })
</script>
@endsection
