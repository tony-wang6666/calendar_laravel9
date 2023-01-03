@extends('layouts.layout_3')
@section('title','批次邀請行程對象')
@section('content')
<div class='bg-gray-100 container mx-auto px-1 w-full'>
    <div class='grid grid-cols-1 items-center m-auto mt-4 mx-auto'>
        <div class="bg-white shadow-md rounded px-1 pt-6 pb-8 mb-4 flex flex-col">
            <div class="text-center pb-4 font-black inline-block align-middle">
                <span class='text-3xl'>行程時間範圍</span>
                <span class='hover:bg-gray-100 rounded-t relative' >
                    <i class='material-icons md-36 hover:bg-gray-500 hover:text-white select-none' data-toggle='mycollapse' data-target="#CollapseThingListSearch">search</i>
                    <div class="max-h-0 overflow-hidden bg-white shadow rounded flex-1 rounded-b right-0 w-auto" id='CollapseThingListSearch'>
                        <form method="POST"action="{{ url('member/calendarBatchGroupSearch') }}" class="bg-white shadow-md rounded px-2 text-xl py-2 ">
                            @csrf
                            <div class='lg:w-96 mx-auto'>
                                <div class="mb-4 flex items-center">
                                    <label class="block text-gray-700 font-bold mb-2 text-2xl pr-2" for="name">
                                        起
                                    </label>
                                    <input
                                        class="shadow bg-gray-200 appearance-none border rounded-lg w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:bg-white focus:border-blue-500"
                                        name="date1" id="date1" type="date" placeholder="Ingresa tu Fecha de Nacimiento" required value='{{$date1}}' required>
                                </div>
                                <div class="mb-4 flex items-center">
                                    <label class="block text-gray-700 font-bold mb-2 text-2xl pr-2" for="name">
                                        訖
                                    </label>
                                    <input
                                        class="shadow bg-gray-200 appearance-none border rounded-lg w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:bg-white focus:border-blue-500"
                                        name="date2" id="date2" type="date" placeholder="Ingresa tu Fecha de Nacimiento" required value='{{$date2}}' required>
                                </div>
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
            @if($tbody)
            <form action="{{url('member/calendarBatchGroupPost')}}" method="post">
                @csrf
                <div class="flex justify-center mb-4">
                    <div class="">
                        <div class="text-center mb-4">
                            <label class="text-gray-800 block mb-1 mr-1 font-bold text-xl tracking-wide py-2">新增邀請對象</label>
                            <div class="flex justify-center ">
                                <select class="option_calendar_members block w-auto bg-gray-200 border-2 border-gray-200 hover:border-gray-500 py-2 pr-8 mr-1 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                    {!!$option!!}
                                </select>
                                <button type="button" data-btnid="2" class="btn_add_relevant_member_batch bg-green-500 hover:bg-green-700 text-white font-bold w-11 h-11 rounded-lg">
                                    <i class="material-icons">add</i>
                                </button>
                            </div>
                        </div>
                        <div id="add_relevant_member_batch" class=" overflow-auto h-56 bg-white dark:bg-slate-800 dark:highlight-white/5 shadow-lg ring-1 ring-black/5 rounded-xl flex flex-col divide-y dark:divide-slate-200/5">
                            
                        </div>
                    </div>
                </div>
                <table id="parameter_set_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border p-2"><input type="checkbox" class="checked_all w-6 h-6"></th>
                            <th class="border p-2">行程</th>
                        </tr>
                    </thead>
                    <tbody class="bg-blue-300">
                        {!!$tbody!!}
                    </tbody>
                </table>
                <div class="mt-2 flex justify-evenly">
                    <button
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit" name='btn_val' value='add'>
                        批次新增邀請對象
                    </button>
                    <button
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit" name='btn_val' value='delete'>
                        批次刪除邀請對象
                    </button>
                </div>
            </form>
            @else
            <div class="text-center text-3xl">{{$date1}}~{{$date2}}查無行程</div>
            @endif
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var checked_name = 'calendar_checkbox';
        $(document).on('click','.checked_all',function(){
            var a = $(this).prop("checked");
            if($(this).prop("checked")){
                $('.'+checked_name).prop("checked", true);
            }else{
                $('.'+checked_name).prop("checked", false);
            }
        });
        $(document).on('click','.btn_add_relevant_member_batch',function(){
            // var btn_id = $(this).data("btnid");
            const select_val = this.previousSibling.previousElementSibling.value;
            if(select_val){
                var check_repeat = 1;
                var members = $("#add_relevant_member_batch").children().children("input").each( //檢查重複
                    function(index){
                        var input_val = $(this).val();
                        if(input_val == select_val){
                            check_repeat = 0;
                            return;
                        }
                    }
                );
                if(check_repeat){ //無重複就添加
                    var add_div = 
                        "<div class='flex justify-center mb-1'>"+
                            "<input type='text' name='relevant_member[]' value='"+select_val+"' class='w-2/5 py-2 pr-8 mr-1 text-center text-base text-gray-700 outline-none rounded-lg text-lg' readonly>"+
                            "<button type='button' class='btn_delete_relevant_member_batch bg-red-500 hover:bg-red-700 text-white font-bold w-11 h-11 rounded-lg'>"+
                                "<i class='material-icons'>clear</i>"+
                            "</button>"+
                        "</div>";
                    $("#add_relevant_member_batch").prepend(add_div);
                }
                
            }
        })
        $(document).on('click','.btn_delete_relevant_member_batch',function(){
            this.parentNode.remove();
        })

        // function tabs_change(tab){
        //     //tabs 變化
        //     $(".btn_tabs").removeClass("text-blue-500 border-b-2 font-black border-blue-500");
        //     $(tab).addClass("text-blue-500 border-b-2 font-black border-blue-500");
        //     var state = $(tab).data('state');
        //     //list 變化
        //     if(state == 'state_complete'){
        //         $('.state_make').addClass('hidden');
        //         $('.state_complete').removeClass("hidden");
        //     }else{
        //         $('.state_complete').addClass('hidden');
        //         $('.state_make').removeClass("hidden");
        //     }
        // }
        
        // tabs_change($('#btn_tab_complete'));
        // $(document).on("click",".btn_tabs",function(){
        //     tabs_change(this);
        // });

        // $(document).on("change",".select_change_state", function(){
        //     var data = $(this).val();
        //     $.ajax({
        //         type:'get',
        //         url:'{!!URL::to('member/thing_state_change')!!}',
        //         data:{'data':data},
        //         dataType:'json',
        //         success:function(){
        //             // console.log('success');
        //         },
        //         error:function(){
        //             console.log('error');
        //         }
        //     });
        // });
        
        // $(document).on("change",".input_change_state", function(){
        //     var d1 = $(this).val()
        //     var d2 = $(this).prev().val()
        //     var data = d2 + "," + d1
        //     $.ajax({
        //         type:'get',
        //         url:'{!!URL::to('member/thing_state_change')!!}',
        //         data:{'data':data},
        //         dataType:'json',
        //         success:function(){
        //             // console.log('success');
        //         },
        //         error:function(){
        //             console.log('error');
        //         }
        //     });
        // });
        
        // $(document).on('click','.a_detail_edit',function(){
        //     var id = $(this).data('id');
        //     window.location.href='detail_edit?id='+id;
            
        // });
    })
</script>
@endsection
