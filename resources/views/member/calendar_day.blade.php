@extends('layouts.layout_1')
@section('content')
<div class='bg-gray-100 container mx-auto px-2 w-full'>
    <div class="grid grid-cols-12 grid-rows-1">
        <div class='col-span-2 lg:col-span-1 ml-auto relative w-full'>
            <div id="getDate" class="ml-1 relative flex justify-center items-center select-none grid grid-rows-2" data-date='{{$this_date}}'>
                <label class="text-center @if($today) text-sm text-blue-700 @endif">{{$title_week}}</label>
                <label class="text-center @if($today) text-lg rounded-full bg-blue-400 text-white @endif">{{$title_day}}</label>
            </div>
        </div>
        <div id='calendar_all_day' class="col-span-10 lg:col-span-11">
            <!-- <div class='border-2 border-blue-300 text-white bg-blue-500 rounded-lg w-full truncate outline-none border-l-2 ' tabindex='0' aria-hidden='true'>
                <label class='font-bold'></label>:
                <span class=''></span>
            </div> -->
        </div>
    </div>
    
    <div class="grid grid-cols-12 grid-rows-1 container overflow-auto h-screen ">
        <div class='col-span-2 lg:col-span-1 ml-auto relative w-full text-xs lg:text-sm font-bold'>
                <div class="ml-1 h-10 relative flex justify-center items-center border-r-2 ">
                </div>
            @foreach($title_time as $v)
                <div class="ml-1 h-10 relative flex justify-center items-center border-r-2 ">
                    <span class='absolute bottom-7 '>{{$v}}</span>
                </div>
            @endforeach
        </div>
        <div class="col-span-10 lg:col-span-11">
            <div class='' aria-hidden="true">
                @foreach($title_time as $v)
                    <div class='lines border-b-2'></div>
                @endforeach
            </div>
            <div id='calendar_day' class='relative text-xs' style='top: -920px; width: calc((100% - 0px) * 1);'>
                <!-- <div class='absolute border-2 border-green-300 text-green-800 bg-green-100 rounded-lg ' style='top: 100px; height: 38px; left: calc((100% - 0px) * 0 + 0px); width: calc((100% - 0px) * 0.34);' tabindex="0" >
                    12345red
                </div>
                <div class='absolute border-2 border-blue-300 text-blue-800 bg-blue-100 rounded-lg ' style='top: 100px; height: 38px; left: calc((100% - 0px) * 0.2 + 0px); width: calc((100% - 0px) * 0.34);' aria-hidden="true">
                    12blue
                </div>
                <div class='absolute border-2 border-yellow-300 text-yellow-800 bg-yellow-100 rounded-lg ' style='top: 120px; height: 38px; left: calc((100% - 0px) * 0.2 + 0px); width: calc((100% - 0px) * 0.34);' tabindex="0" aria-hidden="true">
                    yellow
                </div> -->
            </div>
        </div>
        <!-- <div class='flex flex-wrap border-t border-l '>
            1
        </div> -->
    </div>
</div>


<!-- Modal -->
@include('modals.modal_add_case')
<!-- /Modal -->
<script>
    $(document).ready(function(){
        function calendar_day_dataget(date){
            $.ajax({
                type:'get',
                url:'{!!URL::to('member/calendar_day_dataget')!!}',
                data:{'date':date},
                dataType:'json',
                success:function(data){
                    // console.log('success');
                    // console.log(data);
                    document.getElementById('calendar_day').innerHTML = data.calendar_day; 
                    document.getElementById('calendar_all_day').innerHTML = data.calendar_all_day; 

                },
                error:function(){
                    console.log('error');
                }
            });
        };
        var date = $('#getDate').data('date');
        calendar_day_dataget(date)
        $(document).on('change','#mydate',function(){
            var date = new Date($(this).val());
            location.replace('calendar_day?year='+date.getFullYear()+'&month='+(date.getMonth()+1)+'&day='+date.getDate());  
        });
        // 
        
        $(document).on('click','.a_detail_edit',function(){
            var id = $(this).data('id');
            window.location.href='detail_edit?id='+id;
            
        });
    })
</script>
@endsection
