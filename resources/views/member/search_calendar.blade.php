@extends('layouts.layout_5')
@section('title','查詢')
@section('content')
<!-- 搜尋用 -->
<div class='bg-gray-100 container px-2 w-full'>
    <div class='grid-rows-1'>
    @if($DB_calendar_datetime_records)
        @foreach($DB_calendar_datetime_records as $v)
        <div id='' class="border-b-2 text-lg">
            <div class="grid grid-rows-1 grid-cols-12 ">
                <div class="col-span-2 pl-auto grid-rows-2 pt-1">
                    @if($v->case_today)
                    <div id='today' class="text-center text-sm text-blue-700">{{$v->case_weekday}}</div>
                    <div class="text-center text-lg rounded-full bg-blue-400 text-white">{{$v->case_day}}</div>
                    @else
                    <div class="text-center">{{$v->case_weekday}}</div>
                    <div class="text-center">{{$v->case_day}}</div>
                    @endif
                </div>
                <div class="col-span-10">
                <div class="text-center">{{$v->case_date}}</div>
                @if($v->case_event)
                @foreach($v->case_event as $k2=>$v2)
                    @if($v2->case_level == 0 )
                    <div class='h-auto pl-auto truncate border-green-200 text-white bg-green-700 mx-1 my-1 px-2 rounded-lg hover:bg-green-800' data-id='{{$v2->case_id}}' >
                    @else
                    <div class='a_detail_edit {{UserHelper::dateEventTypeTOcolor($v2->case_level)}} h-auto pl-auto truncate mx-1 my-1 px-2 rounded-lg' data-id='{{$v2->case_id}}' >
                    @endif
                        <div class="flex">
                            <span class='truncate'>{{$v2->case_title}}</span>
                        </div>
                        <div class="">{{$v2->case_time}}</div>
                        @if(!empty($v2->things))
                        @foreach($v2->things as $k3=>$v3)
                            <div class="">
                                <span class="">{{$v3->name}}</span>
                                <span class="">-{{$v3->schedule}}</span>
                            </div>
                        @endforeach
                        @endif
                    </div>
                @endforeach
                @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
    </div>
</div>


<script>
    $(document).ready(function(){
        $(document).on('click','.a_detail_edit',function(){
            var id = $(this).data('id');
            window.location.href='detail_edit?id='+id;
            
        });
    })
</script>
@endsection
