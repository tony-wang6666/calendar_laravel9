@extends('layouts.layout_1')
@section('content')
<div class='bg-gray-100 container mx-auto px-2 w-full'>
<!-- <div class='bg-gray-100 px-2 w-full'> -->
    <div class='grid-rows-1'>
        @foreach($schedule_array_json as $v)
        <a class="block relative -top-20 invisible" id="mydate_a_link{{str_pad($v->day,2,'0',STR_PAD_LEFT)}}"></a>
        <div id='' class="border-b-2 text-lg">
            <div class="grid grid-rows-1 grid-cols-12 ">
                <div class="col-span-2 pl-auto grid-rows-2 pt-1">
                    @if($today == $v->date)
                    <div id='today' class="text-center text-sm text-blue-700">{{$v->week}}</div>
                    <div class="text-center text-lg rounded-full bg-blue-400 text-white">{{$v->day}}</div>
                    <div class="text-center text-sm text-blue-700">{{$v->lunarDate }}</div>
                    @else
                    <div class="text-center">{{$v->week}}</div>
                    <div class="text-center">{{$v->day}}</div>
                    <div class="text-center text-sm font-bold">{{$v->lunarDate }}</div>
                    @endif
                </div>
                <div class="col-span-10">
                @foreach($v->data_event as $k2=>$v2)
                    @if($v2->type == 0 )
                    <div class='h-auto pl-auto truncate border-green-200 text-white bg-green-700 mx-1 my-1 px-2 rounded-lg hover:bg-green-800' data-id='{{$v2->id}}' >
                    @else
                    <div class='a_detail_edit {{UserHelper::dateEventTypeTOcolor($v2->type)}} h-auto pl-auto truncate mx-1 my-1 px-2 rounded-lg' data-id='{{$v2->id}}' >
                    @endif
                        <div class="flex">
                            <span class='truncate'>{{$v2->title}}</span>
                        @if($v2->things_ok == 1) 
                            <span class="material-icons text-green-800">task</span>
                        @endif
                        @if(!empty($v2->which_day)) 
                            <div class=''>{{$v2->which_day}}</div>
                        @endif
                        </div>
                        <div class="">{{$v2->time}}</div>
                        @if(!empty($v2->things))
                            <!-- @if($v2->thing_remark)
                            <div class="">{{$v2->thing_remark}}</div>
                            @endif -->
                            @foreach($v2->things as $k3=>$v3)
                            <div class="">
                                <span class="">{{$v3->name}}</span>
                                <span class="">-{{$v3->schedule}}</span>
                            </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
@include('modals.modal_add_case')
<!-- /Modal -->

<script>
    $(document).ready(function(){
        
        var url_string = window.location.href;
        var url = new URL(url_string);
        var day = url.searchParams.get("day");
        if(day){
            var mydate_a_link = "mydate_a_link"+day;
            window.location.hash = mydate_a_link;
        }else{
            window.location.hash = "#today";
        }
        
        $(document).on('change','#mydate',function(){
            var date = new Date($(this).val());
            year = date.getFullYear()
            month = date.getMonth()+1
            day = date.getDate().toString().padStart(2, '0'); //取得日，轉字串，左邊補0
            // day = day.padStart(2, '0')
            // console.log(day.padStart(2, '0'))
            location.replace('home?year='+year+'&month='+month+'&day='+day);
            
        });
        $(document).on('click','.a_detail_edit',function(){
            var id = $(this).data('id');
            window.location.href='detail_edit?id='+id;
        });
    })
</script>
@endsection
