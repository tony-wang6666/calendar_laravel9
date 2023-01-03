<div id="Modal24" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="p-1 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-3">
            
            <div class="font-bold text-2xl mb-6 text-gray-800 border-b pb-2 text-center mx-auto">
                <span class=''>新增行程</span>
                <span class="relative text-xl">
                    <select id='select_add_case_type' class="appearance-none bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-1 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                        <option value='1'>一般行程</option>
                        <option value='2'>婚喪喜慶</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <span class="material-icons">expand_more</span>
                    </div>
                </span>
            </div>
            <!--婚喪喜慶-->
            <form id='case_add1' method='POST' action="{{url('member/add_case')}}" class='hidden' enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">行程人員</label>
                    <div class="relative">
                        <select name='calendar_datetime_record_member' class="option_calendar_members_id block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                            <option value="TTT">TTT</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div>
                <div class="mb-2 flex">
                    <label class="text-gray-800 mb-1 font-bold text-xl tracking-wide w-2/6 text-center">通報單位</label>
                    <div class="relative w-4/6">
                        <select id='informant_unit' name='informant_unit' class="w-full border-gray-200 hover:border-gray-500 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500"  placeholder="請選擇單位" required>
                            <option value="">Lorem, ipsum.</option>
                            <option value="0">dolor</option>
                        </select>
                    </div>
                </div>
                <div class="mb-2 flex">
                    <label class="text-gray-800 mb-1 font-bold text-xl tracking-wide w-2/6 text-center">通報樣態</label>
                    <div class="relative w-4/6">
                        <select id='select_informant_type' class="w-full bg-gray-200 appearance-none border-2 border-gray-200 hover:border-gray-500 py-1 px-4 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div>
                
                <div id='informant_type' class="mb-4 flex" >
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl  tracking-wide">標題</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_title' required>
                </div>

                <div class="mb-4 grid grid-cols-7 grid-rows-3 gap-1">
                    <label class="col-span-1 row-span-1 lg:row-span-4 text-gray-800 block mb-1 font-bold text-xl tracking-wide">時間</label>
                    <div class='col-span-3 row-span-1 text-right'>全天</div>
                    <div class='col-span-3 row-span-1'>
                        <div class="flex items-center justify-center w-full">
                            <label for="toogleA" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input id="toogleA" type="checkbox" class="toogle_class hidden" name='case_all_day' data-allday='2'/>
                                    <div class="toggle__line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                    <div class="toggle__dot absolute w-6 h-6 bg-white rounded-full shadow inset-y-0 left-0"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class='col-span-4 row-span-1 lg:col-span-3'>
                        <input class="date1 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="date" name='date1' required>
                    </div>
                    <div class='col-span-3 row-span-1'>
                        <input class="time1 hiddentime2 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="time" name='time1' required>
                    </div>
                    <div class='col-span-4 row-span-1 lg:col-span-3'>
                        <input class="date2 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="date" name='date2' required>
                    </div>
                    <div class='col-span-3 row-span-1'>
                        <input class="time2 hiddentime2 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="time" name='time2' required>
                    </div>
                    <div class="col-span-4 lg:col-span-3 ">
                        <div class="relative">
                            <select name='repeat_type' class="repeat_type block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                                <option value="">不重複</option>
                                <option value="每日">每日</option>
                                <option value="每週">每週</option>
                                <option value="每月">每月</option>
                                <option value="每年">每年</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <span class="material-icons">expand_more</span>
                            </div>
                        </div>
                    </div>
                    <div class="repeat_number col-span-3 hidden ">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">
                                    重複
                                </span>
                            </div>
                            <input type="number" name="repeat_number" value="5" min="2" max="100" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full pl-12 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <div class="absolute inset-y-0 left-24 flex items-center">
                                <span class="text-gray-500 font-bold">
                                    次
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">內容</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_content'>
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">關係人員</label>
                    <div class="relative items-center justify-items-center">
                        <input class="relevant_phone bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-2/5 py-2 pl-1 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='relevant_phone' placeholder="電話">
                        <input class="relevant_customer bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-2/5 py-2 pl-1 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='relevant_members' placeholder="客戶">
                    
                        <button type="button" data-btnid="1" class="btn_add_relevant_customer bg-green-500 hover:bg-green-700 text-white font-bold w-10 h-10 rounded-lg">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                </div>
                <div id="add_relevant_customer1" class="mb-4">
                </div>

                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">地點</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_location'>
                </div>

                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">處理情形</label>
                    <div id='checkbox_things' class="block">
                        <label class="inline-flex items-center mr-3">
                            <input type="checkbox" class="form-checkbox h-6 w-6" value='' name="thing_id[]">
                            <span class="ml-2 text-lg">花圈</span>
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">備註</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_remarks'>
                </div>

                <div class=" inline-block w-64 mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">活動分類</label>
                    <div class="relative">
                        <select class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" name='case_level'>
                            <option value="5">藍色</option>
                            <option value="7">紫色</option>
                            <option value="2">橙色</option>
                            <option value="1">紅色</option>
                            <option value="3">黃色</option>
                            <option value="4">綠色</option>
                            <option value="6">靛色</option>
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
                        <button type="button" data-btnid="1" class="btn_add_relevant_member bg-green-500 hover:bg-green-700 text-white font-bold w-11 h-11 rounded-lg">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                </div>
                <div id="add_relevant_member1" class="mb-4">
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">附件(圖)</label>
                    <label for="img_file1" class="cursor-pointer"><i class="material-icons border-2 border-black md-48">add</i></label>
                    <input class="hidden visible" type="file" id='img_file1' name='img_file[]' multiple>
                    <div id='upload_img_preview1' class=""></div>
                </div>

                <div class="mt-8 text-right">
                    <button type="button" class="close-modal bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2">
                        取消
                    </button>	
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm">
                        新增
                    </button>	
                </div>
            </form>
            <!--一般行程-->
            <form id='case_add2' method='POST' action="{{url('member/add_case')}}" class='hidden' enctype="multipart/form-data">
                @csrf

                <div class="mb-2">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">行程人員</label>
                    <div class="relative">
                        <select name='calendar_datetime_record_member' class="option_calendar_members_id block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                            
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <span class="material-icons">expand_more</span>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl  tracking-wide">標題</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_title' required>
                </div>
                <div class="mb-4 grid grid-cols-7 grid-rows-3 gap-1">
                    <label class="col-span-1 row-span-1 lg:row-span-4 text-gray-800 block mb-1 font-bold text-xl tracking-wide">時間</label>
                    <div class='col-span-3 row-span-1 text-right'>全天</div>
                    <div class='col-span-3 row-span-1'>
                        <div class="flex items-center justify-center w-full">
                            <label for="toogleB" class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input id="toogleB" type="checkbox" class="hidden toogle_class" name='case_all_day' data-allday='1'/>
                                    <div class="toggle__line w-10 h-4 bg-gray-400 rounded-full shadow-inner"></div>
                                    <div class="toggle__dot absolute w-6 h-6 bg-white rounded-full shadow inset-y-0 left-0"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class='col-span-4 row-span-1 lg:col-span-3'>
                        <input class="date1 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="date" name='date1' required>
                    </div>
                    <div class='col-span-3 row-span-1'>
                        <input class="time1 hiddentime1 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="time" name='time1' required>
                    </div>
                    <div class='col-span-4 row-span-1 lg:col-span-3'>
                        <input class="date2 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="date" name='date2' required>
                    </div>
                    <div class='col-span-3 row-span-1'>
                        <input class="time2 hiddentime1 bg-gray-200 appearance-none border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="time" name='time2' required>
                    </div>
                    <div class="col-span-4 lg:col-span-3 ">
                        <div class="relative">
                            <select name='repeat_type' class="repeat_type block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" >
                                <option value="">不重複</option>
                                <option value="每日">每日</option>
                                <option value="每週">每週</option>
                                <option value="每月">每月</option>
                                <option value="每年">每年</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <span class="material-icons">expand_more</span>
                            </div>
                        </div>
                    </div>
                    <div class="repeat_number col-span-3 hidden ">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">
                                    重複
                                </span>
                            </div>
                            <input type="number" name="repeat_number" value="5" min="2" max="100" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full pl-12 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <div class="absolute inset-y-0 left-24 flex items-center">
                                <span class="text-gray-500 font-bold">
                                    次
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">內容</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_content'>
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
                </div>
                
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">地點</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_location'>
                </div>
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">備註</label>
                    <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_remarks'>
                </div>
                <div class="inline-block w-64 mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">活動分類</label>
                    <div class="relative">
                        <select class="block appearance-none w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" name='case_level'>
                            <option value="5">藍色</option>
                            <option value="7">紫色</option>
                            <option value="2">橙色</option>
                            <option value="1">紅色</option>
                            <option value="3">黃色</option>
                            <option value="4">綠色</option>
                            <option value="6">靛色</option>
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
                <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">附件(圖)</label>
                    <label for="img_file2" class="cursor-pointer"><i class="material-icons border-2 border-black md-48">add</i></label>
                    <input class="hidden visible" type="file" id='img_file2' name='img_file[]' multiple>
                    <div id='upload_img_preview2' class=""></div>
                </div>

                <div class="mt-8 text-right">
                    <button type="button" class="close-modal bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2">
                        取消
                    </button>	
                    <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm">
                        新增
                    </button>	
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        const modal = document.querySelector('#Modal24');
        const showModal = document.querySelectorAll('.show-modal');
        const closeModal = document.querySelectorAll('.close-modal');
        // console.log(showModal);
        // showModal.addEventListener('click', function (){
        //     modal.classList.remove('hidden')
        // });
        showModal.forEach(show => {
            show.addEventListener('click', function (){
                modal.classList.remove('hidden')
            });
        });
        closeModal.forEach(close => {
            close.addEventListener('click', function (){
                modal.classList.add('hidden')
            });
        });

        if($('.toogle_class').is(":checked")){
            $(".hiddentime1,.hiddentime2").addClass("hidden");
            $('.hiddentime1,.hiddentime2').prop('required',false);
        }else{
            $(".hiddentime1,.hiddentime2").removeClass("hidden");
            $('.hiddentime1,.hiddentime2').prop('required',true);
        }
        function case_all_day_change(case_all_day_checkbox ){
            var allday = $(case_all_day_checkbox).data('allday');
            if($(case_all_day_checkbox).is(":checked")){
                $(".hiddentime"+allday).addClass("hidden");
                $(".hiddentime"+allday).prop('required',false);
            }else{
                $(".hiddentime"+allday).removeClass("hidden");
                $(".hiddentime"+allday).prop('required',true);
            }
        }
        $(document).on('click','.toogle_class',function(){
            case_all_day_change(this);
        });
        $(document).on('change','.date1',function(){ //同步日期 (日期1 日期2)
            $(".date1 ,.date2").val($(this).val());
        });
        $(document).on('change','.date2',function(){ //同步日期 (日期2)
            $(".date2").val($(this).val());
        });
        $(document).on('change','.time1',function(){ //同步時間 (時間1)(時間2 +30分)
            var time1 = $(this).val();
            var dt = new Date('1970-01-01T'+time1);
            dt.setTime(dt.getTime() + (30*60*1000));
            var time2 = pad(dt.getHours(),2) + ":"+pad(dt.getMinutes(),2)+":00";

            $(".time1").val($(this).val());
            $(".time2").val(time2);
        });
        $(document).on('change','.time2',function(){ //同步時間 (時間2)
            $(".time2").val($(this).val());
        });
        $(document).on('change','.repeat_type',function(){ //重複類型
            var repeat_type = $(this).val();
            if(repeat_type){
                // console.log(repeat_type);
                $(".repeat_number").removeClass("hidden");
                $('.repeat_number').prop('required',true);
            }else{
                // console.log("無");
                $(".repeat_number").addClass("hidden");
                $('.repeat_number').prop('required',false);
            }
        });

        function get_informant_types() { //取得所有通報類型 與 選項
            $.ajax({
                type:'get',
                url:'{!!URL::to('member/get_informant_types')!!}',
                dataType:'json',
                success:function(data){
                    document.getElementById('informant_type').innerHTML = data.informat_type_checkbox;
                    document.getElementById('select_informant_type').innerHTML = data.type_option;
                    document.getElementById('informant_unit').innerHTML = data.informants_option;
                    document.getElementById('checkbox_things').innerHTML = data.checkbox_things;
                    $(".option_calendar_members").html(data.option_calendar_members);
                    $(".option_calendar_members_id").html(data.option_calendar_members_id);
                    $("#add_relevant_member1").html(data.add_div_relevant_member1);
                    $("#add_relevant_member2").html(data.add_div_relevant_member2);
                    $('#informant_unit').selectize({
                        create: true,
                        sortField: 'text'
                    });
                    checkbox_informant_items(); //顯示所選的類型   隱藏未選的類型
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
        $(document).on('change','#informant_unit',function(){
            // console.log('123978'); //測試新增20210308
        })
        function change_add_case_type(case_type){
            if(case_type == '1'){
                $("#case_add1").addClass("hidden");
                $("#case_add2").removeClass("hidden");
            }else{
                $("#case_add2").addClass("hidden");
                $("#case_add1").removeClass("hidden");
            }
        }
        change_add_case_type($('#select_add_case_type').val());
        $(document).on('change','#select_add_case_type',function(){
            change_add_case_type($(this).val());
        })

        $(document).on('change','#img_file1',function(){
            const file = this.files[0];
            // console.log(this.files);
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
        })
        $(document).on('change','#img_file2',function(){
            const file = this.files[0];
            // console.log(this.files);
            if(file){
                var previews ="";
                for(var i=0;i<this.files.length;i++){
                    const file = this.files[i];
                    var src = URL.createObjectURL(file);
                    previews += "<img src='"+src+"' alt='預覽'>"
                }
                $("#upload_img_preview2").html(previews);
            }else{
                $("#upload_img_preview2").html("");
            }
        })
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
            $(".relevant_phone").val(relevant_phone); //改變所有這個欄位的
            var case_type = $("#select_add_case_type").val();
            var informant_type = "";
            if(case_type == "2") informant_type = $("#select_informant_type :selected").text();
            // console.log(informant_type);

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
            // console.log(btn_id)
            const val1 = this.previousSibling.previousElementSibling.value;
            const customer_name = this.previousSibling.previousElementSibling.value;
            const customer_phone = this.previousSibling.previousElementSibling.previousElementSibling.value;
            // console.log(customer_name)
            // console.log(customer_phone)
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