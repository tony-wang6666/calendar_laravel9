@extends('layouts.layout_1')
@section('content')
<div class='bg-gray-100 container mx-auto px-2 w-full'>
    <div class='grid-rows-1'>
        <div class="text-center ">
            <div class='flex flex-wrap border-t border-l font-black text-lg'>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週日</div>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週一</div>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週二</div>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週三</div>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週四</div>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週五</div>
                <div style="width: 14.28%;" class="h-1/5 px-1 pt-1 border-r border-b relative">週六</div>
            </div>
            <div class='flex flex-wrap border-t border-l h-screen'>
                @foreach($month_day_array_json as $v)
                    @if( $v->type == 'last' )
                    <div style="width: 14.28%;" class="a_calendar_day h-1/5 pl-1 pt-1 border-r border-b relative text-gray-500" data-date='{{$last_year_month}}{{str_pad($v->day,2,0,STR_PAD_LEFT)}}'>
                        <div class='inline-flex w-6 h-1/5 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100  hover:bg-blue-200'>
                            {{$v->day}}
                        </div>
                        <div class="">
                            {{$v->lunarDate}}
                        </div>
                        @if($v->data_event)
                        <div class='overflow-auto h-3/5 w-full text-xs lg:text-base'>
                            @foreach($v->data_event as $v2)
                            <div class='truncate'>{{$v2->title}}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @elseif( $v->type == 'this' )
                    <div style="width: 14.28%;" class="a_calendar_day h-1/5 pl-1 pt-1 border-r border-b relative" data-date='{{$this_year_month}}{{str_pad($v->day,2,0,STR_PAD_LEFT)}}'>
                        @if( $v->day == $today_day )
                        <div class='inline-flex w-6 h-1/5 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100 text-black hover:bg-blue-700 bg-blue-500 text-white'>
                        @else
                        <div class='inline-flex w-6 h-1/5 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100 text-black hover:bg-blue-200'>
                        @endif
                            {{$v->day}}
                        </div>
                        <div class="">
                            {{$v->lunarDate}}
                        </div>
                        
                        @if($v->data_event)
                        <div class='overflow-auto h-3/5 w-full text-xs lg:text-base'>
                            @foreach($v->data_event as $v2)
                                @if($v2->type == 0)
                                <div class='truncate border-green-200 text-white bg-green-700 mx-1 rounded-lg pl-1 hover:bg-green-800'>{{$v2->title}}</div>
                                @else
                                <div class='{{UserHelper::dateEventTypeTOcolor($v2->type)}} truncate mx-1 rounded-lg pl-1'>{{$v2->title}}</div>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @elseif( $v->type == 'next' )
                    <div style="width: 14.28%;" class="a_calendar_day h-1/5 pl-1 pt-1 border-r border-b relative text-gray-500" data-date='{{$next_year_month}}{{str_pad($v->day,2,0,STR_PAD_LEFT)}}'>
                        <div class='inline-flex w-6 h-1/5 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100  hover:bg-blue-200'>
                            {{$v->day}}
                        </div>
                        <div class="">
                            {{$v->lunarDate}}
                        </div>
                        @if($v->data_event)
                        <div class='overflow-auto h-3/5 w-full text-xs lg:text-base'>
                            @foreach($v->data_event as $v2)
                            <div class='truncate'>{{$v2->title}}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
@include('modals.modal_add_case')
<!-- /Modal -->
<script>
    $(document).ready(function(){
        $(document).on('click','.a_calendar_day',function(){
            var date = new Date($(this).data('date'));
            window.location.href='calendar_day?year='+date.getFullYear()+'&month='+(date.getMonth()+1)+'&day='+date.getDate();
            
        });
        $(document).on('change','#mydate',function(){
            var date = new Date($(this).val());
            location.replace('calendar_month?year='+date.getFullYear()+'&month='+(date.getMonth()+1));  
        });
    })
</script>
@endsection
