<div id="Modal24" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="p-1 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-3">
            
            <h2 class="font-bold text-2xl mb-6 text-gray-800 border-b pb-2 text-center">編輯</h2>
            <form method='POST' action="{{url('member/detail_edit')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name='case_id' value='{{$DB_edit_data[0]->id}}'>
                @if($edit_case_type == 1)
                <div class="mb-2 flex">
                    <label class="text-gray-800 mb-1 font-bold text-xl tracking-wide w-2/6 text-center">通報單位</label>
                    <div class="relative w-4/6">
                        <select id='informant_unit' name='informant_unit' class="w-full border-gray-200 hover:border-gray-500 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500"  placeholder="請選擇單位" required>
                            <option value="">Lorem, ipsum.</option>
                            <option value="0">dolor</option>
                        </select>
                        <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div> -->
                    </div>
                </div>
                <div class="mb-2 flex">
                    <label class="text-gray-800 mb-1 font-bold text-xl tracking-wide w-2/6 text-center">通報樣態</label>
                    <div class="relative w-4/6">
                        <select class="w-full bg-gray-200 appearance-none border-2 border-gray-200 hover:border-gray-500 py-1 px-4 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" id='select_informant_type'>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div>
                <div class="mb-4 flex" id='informant_type'>
                </div>
                @endif
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">標題</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" x-model="event_title" name='case_title' value='@if($DB_edit_data[0]->case_title){{$DB_edit_data[0]->case_title}}@endif'>
                </div>
                <div class="mb-4 grid grid-cols-7 grid-rows-3 gap-1">
                    <label class="col-span-1 row-span-1 lg:row-span-4 text-gray-800 block mb-1 font-bold text-xl tracking-wide">時間</label>
                    <div class='col-span-3 row-span-1 text-right'>全天</div>
                    <div class='col-span-3 row-span-1'>
                        <div class="flex items-center justify-center w-full">
                            <label for="toogleA" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input id="toogleA" type="checkbox" class="hidden" name='case_all_day' @if($DB_edit_data[0]->case_all_day) checked @endif/>
                                    <div class="toggle__line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                    <div class="toggle__dot absolute w-6 h-6 bg-white rounded-full shadow inset-y-0 left-0"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class='col-span-4 row-span-1 lg:col-span-3'>
                        <input class="date1 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="date" name='date1' id='date1' value="@if($DB_edit_data[0]->case_begin_date){{$DB_edit_data[0]->case_begin_date}}@endif" required>
                    </div>
                    <div class='col-span-3 row-span-1'>
                        <input class="time1 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="time" name='time1' id='time1' value="@if($DB_edit_data[0]->case_begin_time){{$DB_edit_data[0]->case_begin_time}}@endif" required>
                    </div>
                    <div class='col-span-4 row-span-1 lg:col-span-3'>
                        <input class="date2 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="date" name='date2' id='date2' value="@if($DB_edit_data[0]->case_end_date){{$DB_edit_data[0]->case_end_date}}@endif" required>
                    </div>
                    <div class='col-span-3 row-span-1'>
                        <input class="time2 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="time" name='time2' id='time2' value="@if($DB_edit_data[0]->case_end_time){{$DB_edit_data[0]->case_end_time}}@endif" required>
                    </div>
                    <div class="col-span-4 lg:col-span-3 ">
                        <div class="relative">
                            <select id='repeat_type' name='repeat_type' class="repeat_type block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                                {!!$option_repeat_type!!}
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <span class="material-icons">expand_more</span>
                            </div>
                            <input id='hidden_repeat_type' type="hidden" value="{{$selected_repeat_type}}"> <!-- 重複行程修改方式(javascrip用) -->
                        </div>
                    </div>
                    <div class="repeat_number col-span-3 hidden ">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">
                                    重複
                                </span>
                            </div>
                            <input id='repeat_number' type="number" name="repeat_number" value="{{$repeat_group_count}}" min="2" max="100" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full pl-12 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <div class="absolute inset-y-0 left-24 flex items-center">
                                <span class="text-gray-500 font-bold">
                                    次
                                </span>
                            </div>
                            <input id='hidden_repeat_number' type="hidden" value="{{$repeat_group_count}}"> <!-- 重複行程修改方式(javascrip用) -->
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">內容</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_content' value='@if($DB_edit_data[0]->case_content){{$DB_edit_data[0]->case_content}}@endif'>
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">關係人員</label>
                    <div class="relative items-center justify-items-center">
                        <input class="relevant_phone bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-2/5 py-2 pl-1 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='relevant_phone' placeholder="電話">
                        <input class="relevant_customer bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-2/5 py-2 pl-1 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='relevant_members' placeholder="客戶">
                        <button type="button" data-btnid="2" class="btn_add_relevant_customer bg-green-500 hover:bg-green-700 text-white font-bold w-10 h-10 rounded-lg">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                </div>
                <div id="add_relevant_customer2" class="mb-4">
                    @if($DB_edit_data[0]->relevant_members)
                    @foreach(explode(',',$DB_edit_data[0]->relevant_members) as $v)
                        <div class='flex mb-1'>
                            <input type='text' name='relevant_customer[]' value='{{$v}}' class='w-4/5 py-2 pr-8 mr-1 text-center text-base text-gray-700 outline-none rounded-lg text-lg' readonly>
                            <button type='button' class='btn_delete_relevant_customer bg-red-500 hover:bg-red-700 text-white font-bold w-11 h-11 rounded-lg'>
                                <i class='material-icons'>clear</i>
                            </button>
                        </div>
                    @endforeach
                    @endif
                </div>

                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">地點</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_location' value='@if($DB_edit_data[0]->case_location){{$DB_edit_data[0]->case_location}}@endif'>
                </div>

                <!-- <div class="inline-block w-64 mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">狀態</label>
                    <div class="relative">
                        <select name="case_state" class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                            <option value="未處理" >未處理</option>
                            <option value="處理中">處理中</option>
                            <option value="完成" >完成</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div> -->
                @if($edit_case_type == 1)
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">處理情形</label>
                    <!-- <div class="">
                        <div class="my-1">
                            {!!$select_option_thing_remark!!}
                        </div>
                    </div> -->

                    <div class="block pt-1">
                    @foreach($DB_calendar_thing_records as $v)
                        <label class="inline-flex items-center mr-3">
                            <input type="checkbox" class="form-checkbox h-6 w-6" value='{{$v->thing_id}}' name="thing_id[]" 
                            @if( $v->checked )
                                checked
                            @endif
                            >
                            <span class="ml-2 text-lg">{{$v->thing_name}}</span>
                            @if($v->thing_name == '禮金' && !$v->checked)
                            <div id='thing_state' class='hidden'>
                                <input type='text' name='giftMoney[1]' value='' placeholder='請輸入禮金' class=' bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500'>
                            </div>
                            @endif
                            @if( $v->checked )
                                @if($v->thing_name == '禮金')
                            <input type="hidden" name="giftMoney[]" value="{{$v->calendar_thing_record_id}}" class="input_change_state">
                            <input type="text" name="giftMoney[]" value="{{$v->thing_state_name}}" class="input_change_state w-24 bg-gray-200 appearance-none border-2 border-gray-200 hover:border-gray-500 py-1 px-2 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                @else
                            <select name="thing_state[]" id="" class="bg-gray-200 appearance-none border-2 border-gray-200 hover:border-gray-500 py-1 px-2 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                                    @foreach($v->schedule_array as $k2=>$v2)
                                <option value="{{$v->calendar_thing_record_id}},{{$v2}}" @if($v->thing_state_name == $v2) selected @endif >{{$v2}}</option>
                                    @endforeach
                            </select>
                                @endif
                            @endif
                        </label>
                    @endforeach
                    </div>
                </div>
                @endif
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">備註</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_remarks' value="@if($DB_edit_data[0]->case_remarks){{$DB_edit_data[0]->case_remarks}}@endif">
                </div>
                <div class="inline-block w-64 mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">活動分類</label>
                    <div class="relative">
                        <select class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" name='case_level'>
                            <option value='1' @if($DB_edit_data[0]->case_level==1)selected @endif>紅色</option>
                            <option value='2' @if($DB_edit_data[0]->case_level==2)selected @endif>橙色</option>
                            <option value='3' @if($DB_edit_data[0]->case_level==3)selected @endif>黃色</option>
                            <option value='4' @if($DB_edit_data[0]->case_level==4)selected @endif>綠色</option>
                            <option value='5' @if($DB_edit_data[0]->case_level==5)selected @endif>藍色</option>
                            <option value='6' @if($DB_edit_data[0]->case_level==6)selected @endif>靛色</option>
                            <option value='7' @if($DB_edit_data[0]->case_level==7)selected @endif>紫色</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 mr-1 font-bold text-xl tracking-wide py-2">新增邀請對象</label>
                    <div class="flex">
                        <select class="option_calendar_members block w-2/5 bg-gray-200 border-2 border-gray-200 hover:border-gray-500 py-2 pr-8 mr-1 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                        </select>
                        <button type="button" data-btnid="2" class="btn_add_relevant_member bg-green-500 hover:bg-green-700 text-white font-bold w-11 h-11 rounded-lg">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                </div>
                <div id="add_relevant_member2" class="mb-4">
                </div>
                <div class="mb-4 text-xl">
                    @if($relevant_member_names)
                    <table class="min-w-max w-full table-auto ">
                        <thead>
                            <tr>
                                <th class="w-2/3">邀請對象</th>
                                <th class="w-1/3">刪除</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_relevant_member">
                            @foreach($relevant_member_names as $k=>$v)
                            <tr>
                                <td class="text-center">{{$v->name}}</td>
                                <td class="text-center"><input type="checkbox" value='{{$v->name}}' name='delete_relevant_member[]' class="w-8 h-8"/></td>
                                <input type='hidden' name='relevant_member[]' value='{{$v->name}}' readonly>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">附件(圖)</label>
                    <label for="img_file1" class="cursor-pointer"><i class="material-icons border-2 border-black md-48">add</i></label>
                    <input class="hidden visible" type="file" id='img_file1' name='img_file[]' multiple>
                    <div id='upload_img_preview1' class=""></div>
                    @foreach($DB_calendar_files as $v)
                    <div class="flex justify-end mt-2 my-auto items-center">
                        <!-- <a href="#" class='file_delete my-auto border-2 border-red-600 bg-red-500 hover:bg-red-600' data-id='{{$v->id}}'><i class="material-icons">clear</i></a> -->
                        <input id="img{{$v->id}}" type="checkbox" value='{{$v->id}}' name='delete_file[]' class="w-8 h-8"/>
                        <label for="img{{$v->id}}" class="ml-2 text-xl text-red-700" >刪除</label>
                        <input type="hidden" value='{{$v->file_name.".".$v->file_type}}' name='file_name[]' class="w-8 h-8"/>
                    </div>
                    <img src="{{url($v->file_dir)}}" alt="圖">
                    @endforeach
                </div>
                @if($repeat_group)
                <div class="inline-block w-64 mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">重複行程修改方式</label>
                    <div class="relative">
                        <select id='change_repeat_type' name='change_repeat_type' class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                            <option value="1">修改此行程</option>
                            <option value="2">修改此行程和後續行程</option>
                            <option value="3">修改全部行程</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="mt-8 text-right">
                    <button type="button" class="close-modal bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2">
                        取消
                    </button>	
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm">
                        修改
                    </button>	
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // const modal = document.querySelector('#Modal24');
        // const showModal = document.querySelectorAll('.show-modal');
        // const closeModal = document.querySelectorAll('.close-modal');
        // showModal.forEach(show => {
        //     show.addEventListener('click', function (){
        //         modal.classList.remove('hidden')
        //     });
        // });
        // closeModal.forEach(close => {
        //     close.addEventListener('click', function (){
        //         modal.classList.add('hidden')
        //     });
        // });
        $(document).on('click','.show-modal',function(){
            $('#Modal24').removeClass("hidden");
        });
        $(document).on('click','.close-modal',function(){
            $('#Modal24').addClass("hidden");
        });
        

        if($('#toogleA').is(":checked")){
            $("#time1,#time2").addClass("hidden");
            $('#time1,#time2').prop('required',false);
        }else{
            $("#time1,#time2").removeClass("hidden");
            $('#time1,#time2').prop('required',true);
        }
        $(document).on('click','#toogleA',function(){
            if($(this).is(":checked")){
                $("#time1,#time2").addClass("hidden");
                $('#time1,#time2').prop('required',false);
            }else{
                $("#time1,#time2").removeClass("hidden");
                $('#time1,#time2').prop('required',true);
            }
        });
        
        $(document).on('change','.date1',function(){ //同步日期 (日期1 日期2)
            $(".date1 ,.date2").val($(this).val());
        });
        $(document).on('change','.time1',function(){ //同步時間 (時間1)(時間2 +30分)
            var time1 = $(this).val();
            var dt = new Date('2021-01-01T'+time1);
            dt.setTime(dt.getTime() + (30*60*1000));
            var time2 = pad(dt.getHours(),2) + ":"+pad(dt.getMinutes(),2)+":00";
            $(".time2").val(time2);
        });
        $(document).on('change','.date1, .date2, .time1, .time2, #repeat_type, #repeat_number',function(){ //如果修改了1日期2時間3重複類型，那只能夠編輯'修改此行程和後續行程'
            var repeat_type = $("#repeat_type").val();
            var repeat_number = $("#repeat_number").val();
            var hidden_repeat_type = $("#hidden_repeat_type").val();
            var hidden_repeat_number = $("#hidden_repeat_number").val();
            if(repeat_number != hidden_repeat_number){
                console.log(1)
                var option = "<option value='3'>修改全部行程</option>"
            }else if(repeat_type == hidden_repeat_type){
                console.log(2)
                var option = "<option value='1'>修改此行程</option><option value='2'>修改此行程和後續行程</option><option value='3'>修改全部行程</option>"
            }else if(repeat_type == "不重複"){
                console.log(3)
                var option = "<option value='1'>修改此行程</option>"
            }else{
                console.log(4)
                var option = "<option value='1'>修改此行程</option><option value='2'>修改此行程和後續行程</option>"
            }
            $("#change_repeat_type").html(option);
        });
        function repeat_number_select(){
            var repeat_type = $(".repeat_type").val();
            if(repeat_type == '不重複'){ //不重複
                $(".repeat_number").addClass("hidden");
                $('.repeat_number').prop('required',false);
            }else{ //每日 每週 每月 每年
                $(".repeat_number").removeClass("hidden");
                $('.repeat_number').prop('required',true);
            }
        }
        repeat_number_select()
        $(document).on('change','.repeat_type',function(){ //重複類型
            repeat_number_select()
        });

        function get_informant_types() { //取得所有通報類型 與 選項
            var item = $('#informant_type_item').data('item');
            var informant = $('#display_informant').data('informant');
            var table_length = $('#tbody_relevant_member tr').length;
            var relevant_member = [];
            for(var i=0; i<table_length ; i++){
                relevant_member[i] = $("#tbody_relevant_member tr:eq("+i+") td:eq(0)").text();
            }
            $.ajax({
                type:'get',
                url:'{!!URL::to('member/get_informant_types')!!}',
                data:{'item':item,'informant':informant,'relevant_member':relevant_member},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    try { //錯誤就跳過
                        document.getElementById('informant_unit').innerHTML = data.informants_option;
                        $('#informant_unit').selectize({
                            create: true,
                            sortField: 'text'
                        });
                        document.getElementById('select_informant_type').innerHTML = data.type_option;
                        document.getElementById('informant_type').innerHTML = data.informat_type_checkbox;
                        checkbox_informant_items(); //顯示所選的類型   隱藏未選的類型
                        // console.log('success');
                    } catch (e) {
                        // console.log('error');
                    }
                    $(".option_calendar_members").html(data.option_calendar_members); //邀請對象
                    
                },
                error:function(){
                    console.log('error');
                }
            });
        }
        get_informant_types(); //取得所有通報類型 與 選項
        function checkbox_informant_items() { //顯示所選的類型   隱藏未選的類型
            var type = $('#select_informant_type').val(); //選擇的
            var optionValues = []; //所有項目
            $('#select_informant_type option').each(function() {
                optionValues.push($(this).val());
            });
            optionValues = jQuery.grep(optionValues, function(value) { //所有項目中，移除(選擇的項目)
                return value != type;
            });
            $.each(optionValues, function( index, value ) {
                $("#"+value).addClass("hidden");
            });
            $("#"+type).removeClass("hidden");
        }
        
        $(document).on('change','#select_informant_type',function(){
            checkbox_informant_items();
        });
        $(document).on('change','#img_file1',function(){ 
            const file = this.files[0];
            if(file){
                var previews ="";
                for(var i=0;i<this.files.length;i++){
                    const file = this.files[i];
                    var src = URL.createObjectURL(file);
                    previews += "<img src='"+src+"' alt='預覽'>"
                }
                $("#upload_img_preview1").html(previews);
            }else{
                $("#upload_img_preview1").html("");
            }
        });
        $(document).on('click','.btn_add_relevant_member',function(){
            var btn_id = $(this).data("btnid");
            const select_val = this.previousSibling.previousElementSibling.value;
            if(select_val){
                var check_repeat = 1;
                var members = $("#add_relevant_member"+btn_id).children().children("input").each( //檢查重複
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
                        "<div class='flex mb-1'>"+
                            "<input type='text' name='relevant_member[]' value='"+select_val+"' class='w-2/5 py-2 pr-8 mr-1 text-center text-base text-gray-700 outline-none rounded-lg text-lg' readonly>"+
                            "<button type='button' class='btn_delete_relevant_member bg-red-500 hover:bg-red-700 text-white font-bold w-11 h-11 rounded-lg'>"+
                                "<i class='material-icons'>clear</i>"+
                            "</button>"+
                        "</div>";
                    $("#add_relevant_member"+btn_id).append(add_div);
                }
                
            }
        })
        $(document).on('click','.btn_delete_relevant_member',function(){
            this.parentNode.remove();
        })
        $(document).on('change','input[name="thing_id[]"]',function(){
            var thing_id = $(this).val();
            if(thing_id == '5'){
                // console.log('tt禮金')
                if($(this).is(":checked")){
                    $("#thing_state").removeClass("hidden");
                }else{
                    $("#thing_state").addClass("hidden");
                }
            }
        })
        $(document).on('change','.relevant_phone',function(){
            var relevant_phone = $(this).val();
            $(".relevant_phone").val(relevant_phone);//改變所有這個欄位的

            var case_type = $("#select_add_case_type").val();
            informant_type = $("#select_informant_type :selected").text();
            console.log(informant_type)

            $.ajax({
                type:'get',
                url:'{!!URL::to('member/phoneToMember')!!}',
                data:{'relevant_phone':relevant_phone,'informant_type':informant_type},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    var relevant_customer = data.c_name
                    $(".relevant_customer").val(relevant_customer);
                },
                error:function(){
                    console.log('error');
                    $(".relevant_customer").val("");
                }
            });
        })
        $(document).on('click','.btn_add_relevant_customer',function(){
            var btn_id = $(this).data("btnid");
            const val1 = this.previousSibling.previousElementSibling.value;
            const customer_name = this.previousSibling.previousElementSibling.value;
            const customer_phone = this.previousSibling.previousElementSibling.previousElementSibling.value;
            var customer = customer_name + " " + customer_phone
            if(customer){
                var check_repeat = 1;
                var members = $("#add_relevant_customer"+btn_id).children().children("input").each( //檢查重複
                    function(index){
                        var input_val = $(this).val();
                        if(input_val == customer){
                            check_repeat = 0;
                            return;
                        }
                    }
                );
                if(check_repeat){ //無重複就添加
                    var add_div = 
                        "<div class='flex mb-1'>"+
                            "<input type='text' name='relevant_customer[]' value='"+customer+"' class='w-4/5 py-2 pr-8 mr-1 text-center text-base text-gray-700 outline-none rounded-lg text-lg' readonly>"+
                            "<button type='button' class='btn_delete_relevant_customer bg-red-500 hover:bg-red-700 text-white font-bold w-11 h-11 rounded-lg'>"+
                                "<i class='material-icons'>clear</i>"+
                            "</button>"+
                        "</div>";
                    $("#add_relevant_customer"+btn_id).append(add_div);
                }
                
            }
        })
        $(document).on('click','.btn_delete_relevant_customer',function(){
            this.parentNode.remove();
        })

    });

</script>