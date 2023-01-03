@extends('layouts.layout_2')
@section('content')
@if($DB_edit_data)
<div id="calendar_detail" class='bg-gray-100 container mx-auto px-2 w-full '>
    <div class="pt-2">
        <div class='col-span-2 lg:col-span-1 mx-auto relative lg:w-96'>
            <div class="grid grid-rows-1 grid-cols-7">
                <div class="rounded-full h-8 w-8 col-span-1 row-span-6 {{UserHelper::dateEventTypeTOcolor($DB_edit_data[0]->case_level)}}"></div>
                <div class="col-span-6 select-none">
                    @if($DB_edit_data[0]->case_title)
                    <div class="text-3xl font-black">
                        {{$DB_edit_data[0]->case_title}}
                        @if($things_ok == 1) 
                            <span class="material-icons text-green-800">task</span>
                        @endif
                    </div>
                    @endif
                    @if($DB_edit_data[0]->informant)
                    <div class="text-xl" id='display_informant' data-informant='{{$DB_edit_data[0]->informant}}'>
                        <span class='font-bold'>通報單位:</span>
                        <span class=''>{{$DB_edit_data[0]->informant}}</span>
                    </div>
                    @endif
                    @if($DB_edit_data[0]->informant_type)
                    <div class="text-xl" id='informant_type_item' data-item='{{$DB_edit_data[0]->informant_type}}'>
                        <span class='font-bold'>通報樣態:</span>
                        <span class=''>{{$DB_edit_data[0]->informant_type}}</span>
                    </div>
                    @endif
                    <div class="pt-1 border-t-4">
                        {{$DB_edit_data[0]->case_time}}-<br>
                        {{$DB_edit_data[0]->case_time2}}
                    </div>
                    @if($DB_edit_data[0]->case_content)
                    <div class="text-lg pt-1 border-t-4">
                        <span class='font-bold'>內容:</span>
                        <span class=''>{{$DB_edit_data[0]->case_content}}</span>
                    </div>
                    @endif
                    @if($DB_edit_data[0]->relevant_members)
                    <div class="text-lg pt-1 border-t-4">
                        <span class='font-bold'>關係人員:</span>
                        @foreach(explode(',',$DB_edit_data[0]->relevant_members) as $v)
                        <div class=''>{{$v}}</div>
                        @endforeach
                    </div>
                    @endif
                    @if($DB_edit_data[0]->case_location)
                    <div class="text-lg pt-1 border-t-4">
                        <span class='font-bold'>地點:</span>
                        <span class=''>{{$DB_edit_data[0]->case_location}}</span>
                    </div>
                    <div class=''>
                        <div class="w-auto">
                            <iframe id="gmap_canvas" src="https://maps.google.com/maps?q={{$DB_edit_data[0]->case_location}}&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>
                    </div>
                    @endif
                    @if($thing_check)
                    <div class="text-lg pt-1 border-t-4 ">
                        <span class="font-bold">處理情形:</span>
                        <!-- @if($DB_edit_data[0]->thing_remark)
                        <div class="">
                            <span class=''>{{$DB_edit_data[0]->thing_remark}}</span>
                        </div>
                        @endif -->
                        @foreach($DB_calendar_thing_records as $v)
                        @if($v->checked)
                        <div class="">
                            <span class=''>{{$v->thing_name}}-</span>
                            <span class=''>{{$v->thing_state_name}}</span>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                    @if($DB_edit_data[0]->case_remarks)
                    <div class="text-lg pt-1 border-t-4">
                        <span class='font-bold'>備註:</span>
                        <span class=''>{{$DB_edit_data[0]->case_remarks}}</span>
                    </div>
                    @endif
                    @if($relevant_member_names)
                    <div class="text-lg pt-1 border-t-4">
                        <span class='font-bold'>邀請對象:</span>
                        <span class=''>
                        @foreach($relevant_member_names as $k=>$v)
                        <div class='flex items-center py-1'>
                            <div class="">
                                <button type="button" id='btn_notifications{{$k}}' data-id='{{$k}}' data-name='{{$v->name}}' data-calendarid='{{$DB_edit_data[0]->id}}' class='btn_notifications bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 hover:border-green-500 rounded shadow-xl'>
                                    <i class="material-icons">notifications</i>
                                </button>
                            </div>
                            <div>({{$k+1}}){{$v->name}} </div>
                        </div>
                        
                        @endforeach
                        </span>
                    </div>
                    @endif
                    @if($DB_calendar_files)
                    <div class="text-lg pt-1 border-t-4">
                        <span class='font-bold'>附件(圖):</span>
                        @foreach($DB_calendar_files as $v)
                            <img src="{{url($v->file_dir)}}" class="" alt="圖">
                            <!-- hover:absolute hover:mx-auto hover:right-0 hover:-bottom-1/4 -->
                        @endforeach
                    </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</div>
<div id="calendar_work" class='bg-gray-100 container mx-auto px-2 w-192 hidden'>
    <div class="pt-2">
        <div class='mx-auto relative px-4 bg-blue-300 text-2xl'>
            <div class="text-center text-3xl font-black mb-4">{{$DB_edit_data[0]->case_title}}工作單</div>
            <div class="grid grid-cols-3 justify-items-stretch">
                @if($DB_edit_data[0]->case_title)
                <div class="col-span-1 font-black">行程:</div>
                <div class="col-span-2 ">{{$DB_edit_data[0]->case_title}}</div>
                @endif
                @if($DB_edit_data[0]->member_name)
                <div class="col-span-1 font-black">行程人員:</div>
                <div class="col-span-2">{{$DB_edit_data[0]->member_name}}</div>
                @endif
                @if($DB_edit_data[0]->informant)
                <div class='col-span-1 font-black'>通報單位:</div>
                <div class='col-span-2'>{{$DB_edit_data[0]->informant}}</div>
                @endif
                @if($DB_edit_data[0]->informant_type)
                <div class='col-span-1 font-black'>通報樣態:</div>
                <div class='col-span-2'>{{$DB_edit_data[0]->informant_type}}</div>
                @endif
                <div class="pt-1 border-t-4 col-span-3"></div>
                <div class='col-span-1 font-black '>時間:</div>
                <div class='col-span-2'>
                    {{$DB_edit_data[0]->case_time}}-<br>{{$DB_edit_data[0]->case_time2}}
                </div>
                @if($DB_edit_data[0]->case_content)
                <div class="pt-1 border-t-4 col-span-3"></div>
                <div class='col-span-1 font-black'>內容:</div>
                <div class='col-span-2'>{{$DB_edit_data[0]->case_content}}</div>
                @endif
                @if($DB_edit_data[0]->relevant_phone)
                <div class="pt-1 border-t-4 col-span-3"></div>
                <div class='col-span-1 font-black'>關係人員電話:</div>
                <div class='col-span-2'>{{$DB_edit_data[0]->relevant_phone}}</div>
                @endif
                @if($DB_edit_data[0]->relevant_members)
                <div class='col-span-1 font-black'>關係人員:</div>
                <div class='col-span-2'>{{$DB_edit_data[0]->relevant_members}}</div>
                @endif
                @if($DB_edit_data[0]->case_location)
                <div class="pt-1 border-t-4 col-span-3"></div>
                <div class='col-span-1 font-black'>地點:</div>
                <div class='col-span-2'>
                    <div class="">{{$DB_edit_data[0]->case_location}}</div>
                    <iframe id="gmap_canvas" class="w-full h-56"  src="https://maps.google.com/maps?q={{$DB_edit_data[0]->case_location}}&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
                @endif
                @if($thing_check)
                <div class="mt-4 pt-2 border-t-4 col-span-3"></div>
                <div class="col-span-1 font-bold">物品處理情形:</div>
                <div class="col-span-2">
                @foreach($DB_calendar_thing_records as $v)
                    @if($v->checked)
                        <span class='text-black bg-red-200 rounded-full px-2 m-2'>{{$v->thing_name}}</span>-
                        <span class=''>{{$v->thing_state_name}}</span><br>
                    @endif
                @endforeach
                </div>
                @endif
                @if($DB_edit_data[0]->case_remarks)
                    <div class="mt-4 pt-2 border-t-4 col-span-3"></div>
                    <div class='col-span-1 font-bold'>備註:</div>
                    <div class='col-span-2'>{{$DB_edit_data[0]->case_remarks}}</div>
                @endif
                @if($relevant_member_names)
                    <div class="mt-4 pt-2 border-t-4 col-span-3"></div>
                    <div class='col-span-1 font-bold'>邀請對象:</div>
                    <div class='col-span-2'>
                        @foreach($relevant_member_names as $k=>$v)
                            ({{$k+1}}){{$v->name}}
                        @endforeach
                    </div>
                @endif
                @if($DB_calendar_files)
                    <div class="mt-4 pt-2 border-t-4 col-span-3"></div>
                    <div class='col-span-1 font-bold'>附件(圖):</div>
                    <div class="col-span-2">
                    @foreach($DB_calendar_files as $v)
                        <img src="{{url($v->file_dir)}}" class="" alt="圖">
                    @endforeach
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
<div id="calendar_print2" class='bg-gray-100 container mx-auto px-2 w-full h-screen pt-8 grid justify-items-stretch hidden'>
    <div class="pt-8 ">
        <div class='col-span-2 lg:col-span-1 mx-auto relative lg:w-96'>
            <div class="grid grid-rows-1 grid-cols-7">
                <div class="rounded-full h-8 w-8 col-span-1 row-span-6 {{UserHelper::dateEventTypeTOcolor($DB_edit_data[0]->case_level)}}"></div>
                <div class="col-span-6 select-none">
                    @if($DB_edit_data[0]->case_title)
                    <div class="text-4xl font-black">{{$DB_edit_data[0]->case_title}}</div>
                    @endif
                    <div class="text-2xl pt-1 border-t-4">
                        {{$DB_edit_data[0]->case_time}}-<br>
                        {{$DB_edit_data[0]->case_time2}}
                    </div>
                    @if($DB_edit_data[0]->relevant_members)
                    <div class="text-2xl pt-1 border-t-4">
                        <span class='font-bold'>關係人員:</span>
                        @foreach(explode(',',$DB_edit_data[0]->relevant_members) as $v)
                        <div class=''>{{$v}}</div>
                        @endforeach
                    </div>
                    @endif
                    @if($DB_edit_data[0]->case_location)
                    <div class="text-2xl pt-1 border-t-4">
                        <span class='font-bold'>地點:</span>
                        <span class=''>{{$DB_edit_data[0]->case_location}}</span>
                    </div>
                    <div class=''>
                        <div class="">
                            <iframe id="gmap_canvas" class="w-96 h-56" src="https://maps.google.com/maps?q={{$DB_edit_data[0]->case_location}}&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('modals.modal_edit_case')
<!-- /Modal -->

@else
    <div class="mx-auto px-2 w-full text-center ">
        <a href="javascript:history.go(-1)" class="underline text-4xl">錯誤，請回上一頁</a>
    </div>
@endif

<script>
$(document).ready(function(){

    // change_view
    $(document).on('click','#btn_change_view',function(){
        var t_data = $("#btn_change_view i").text();
        if(t_data == 'receipt_long'){
            $("#calendar_detail").addClass("hidden");
            $("#calendar_work").removeClass("hidden");
            $("#btn_print2").addClass("hidden");
            $("#btn_change_view").html("<i class='material-icons'>event_note</i>行程內容");
        }else{
            $("#calendar_detail").removeClass("hidden");
            $("#calendar_work").addClass("hidden");
            $("#btn_print2").removeClass("hidden");
            $("#btn_change_view").html("<i class='material-icons'>receipt_long</i>工作單");
        }
    });
    
    $(document).on('click','.btn_notifications',function(){
        var id = $(this).data("id");
        document.getElementById("btn_notifications"+id).innerHTML = "<i class='material-icons'>notifications_active</i>";
        
        var name = $(this).data("name");
        var calendarid = $(this).data("calendarid");
        // console.log(calendarid)
        $.ajax({
            type:'get',
            url:'{!!URL::to('member/notificationDetailEdit')!!}',
            data:{'name':name,'calendarid':calendarid,},
            dataType:'json',
            success:function(data){
                console.log(data);
                // var relevant_customer = data.c_name
                // $(".relevant_customer").val(relevant_customer);
            },
            error:function(){
                console.log('error');
            }
        });
    });
    
})
</script>

@endsection
