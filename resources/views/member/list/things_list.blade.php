@extends('layouts.layout_3')
@section('title','處理清單')
@section('content')
<div class='bg-gray-100 container mx-auto px-1 w-full'>
    <div class='grid grid-cols-1 items-center m-auto mt-4 mx-auto'>
        <div class="bg-white shadow-md rounded px-1 pt-6 pb-8 mb-4 flex flex-col">
            <div class="text-center pb-4 font-black inline-block align-middle">
                <span class='text-3xl'>處理清單</span>
                <span class='hover:bg-gray-100 rounded-t relative' >
                    <i class='material-icons md-36 hover:bg-gray-500 hover:text-white select-none' data-toggle='mycollapse' data-target="#CollapseThingListSearch">search</i>
                    <div class="max-h-0 overflow-hidden bg-white shadow rounded flex-1 rounded-b right-0 w-auto" id='CollapseThingListSearch'>
                        <form method="POST"action="{{ url('member/things_list') }}" class="bg-white shadow-md rounded px-2 text-xl py-2 ">
                            @csrf
                            <div class='lg:w-96 mx-auto'>
                                <div class="mb-4 flex items-center">
                                    <label class="block text-gray-700 font-bold mb-2 text-2xl pr-2" for="name">
                                        起
                                    </label>
                                    <input
                                        class="shadow bg-gray-200 appearance-none border rounded-lg w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:bg-white focus:border-blue-500"
                                        name="date1" id="date1" type="date" placeholder="Ingresa tu Fecha de Nacimiento" required value='{{$start_date}}' required>
                                </div>
                                <div class="mb-4 flex items-center">
                                    <label class="block text-gray-700 font-bold mb-2 text-2xl pr-2" for="name">
                                        訖
                                    </label>
                                    <input
                                        class="shadow bg-gray-200 appearance-none border rounded-lg w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:bg-white focus:border-blue-500"
                                        name="date2" id="date2" type="date" placeholder="Ingresa tu Fecha de Nacimiento" required value='{{$end_date}}' required>
                                </div>
                                <!-- bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500 -->
                                <!-- <div class="mb-4">
                                    <label class="block text-gray-700 font-bold mb-2" for="name">
                                        狀態
                                    </label>
                                    <div class="relative">
                                        <select class="block font-bold appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" name='case_level'>
                                            <option value='0'>任何狀態</option>
                                            <option value='1'>處理中</option>
                                            <option value='2'>已送達</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                            <span class="material-icons">expand_more</span>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="flex justify-center">
                                <button
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    type="submit">
                                    查詢
                                </button>
                            </div>
                
                        </form>
                    </div>
                </span>
            </div>
            <div class="bg-white mx-auto mb-1 ">
                <nav class="flex flex-row ">
                    <button id='btn_tab_complete' class="btn_tabs text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none text-blue-500 border-b-2 font-black border-blue-500" data-state='state_make'>
                        處理中
                    </button>
                    <button class="btn_tabs text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none" data-state='state_complete'>
                        已送達
                    </button>
                </nav>
            </div>
            <table class="table-auto">
                <thead>
                    <tr>
                        <th class='border-2 border-gray-500'>行程</th>
                        <th class='border-2 border-gray-500'>日期</th>
                        <th class='border-2 border-gray-500'>處理狀態</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($DB_calendar_datetime_records as $v)
                    @if($v->complete)
                    <tr class='state_complete'>
                    @else
                    <tr class='state_make'>
                    @endif
                        <td class='a_detail_edit text-center border-2 border-gray-500 hover:bg-gray-300' data-id='{{$v->id}}'>
                            <div class="">{{$v->case_title}}</div>
                        </td>
                        <td class='text-center border-2 border-gray-500'>
                            <div class="">{{$v->case_begin_date}}</div>
                            <div class="">{{$v->case_begin_time}}</div>
                        </td>
                        <td class='text-center border-2 border-gray-500'>
                            @foreach($v->things as $v2)
                            <div class='pb-1'>
                                <span class="">{{$v2['name']}}</span>
                                @if($v2['name'] == '禮金')
                                <input type="hidden" name="giftMoney[]" value="{{$v2['id']}}" class="input_change_state">
                                <input type="text" name="giftMoney[]" value="{{$v2['schedule']}}" class="input_change_state w-24 bg-gray-200 appearance-none border-2 border-gray-200 hover:border-gray-500 py-1 px-2 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                @else
                                <select id="" class="select_change_state bg-gray-200 appearance-none border-2 border-gray-200 hover:border-gray-500 py-1 px-2 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                    @foreach($v2['schedule_array'] as $k3=>$v3)
                                    <option value="{{$v2['id']}},{{$v3}}" @if($v3 == $v2['schedule'] )selected @endif >{{$v3}}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            @endforeach
                            <!-- <div class="">花圈-製作</div> -->
                        </td>
                    </tr>
                    @endforeach
                    <!-- <tr class='state_complete'>
                        <td class='text-center border-2 border-gray-500'>
                            <div class="">標題標題標題標題標題標題</div>
                        </td>
                        <td class='text-center border-2 border-gray-500'>
                            <div class="">2021/3/3</div>
                            <div class="">11:30</div>
                        </td>
                        <td class='text-center border-2 border-gray-500'>
                            <div class="">花籃-送達</div>
                            <div class="">花圈-送達</div>
                        </td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        function tabs_change(tab){
            //tabs 變化
            $(".btn_tabs").removeClass("text-blue-500 border-b-2 font-black border-blue-500");
            $(tab).addClass("text-blue-500 border-b-2 font-black border-blue-500");
            var state = $(tab).data('state');
            //list 變化
            if(state == 'state_complete'){
                $('.state_make').addClass('hidden');
                $('.state_complete').removeClass("hidden");
            }else{
                $('.state_complete').addClass('hidden');
                $('.state_make').removeClass("hidden");
            }
        }
        
        tabs_change($('#btn_tab_complete'));
        $(document).on("click",".btn_tabs",function(){
            tabs_change(this);
        });

        $(document).on("change",".select_change_state", function(){
            var data = $(this).val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('member/thing_state_change')!!}',
                data:{'data':data},
                dataType:'json',
                success:function(){
                    // console.log('success');
                },
                error:function(){
                    console.log('error');
                }
            });
        });
        
        $(document).on("change",".input_change_state", function(){
            var d1 = $(this).val()
            var d2 = $(this).prev().val()
            var data = d2 + "," + d1
            $.ajax({
                type:'get',
                url:'{!!URL::to('member/thing_state_change')!!}',
                data:{'data':data},
                dataType:'json',
                success:function(){
                    // console.log('success');
                },
                error:function(){
                    console.log('error');
                }
            });
        });
        
        $(document).on('click','.a_detail_edit',function(){
            var id = $(this).data('id');
            window.location.href='detail_edit?id='+id;
            
        });
    })
</script>
@endsection
