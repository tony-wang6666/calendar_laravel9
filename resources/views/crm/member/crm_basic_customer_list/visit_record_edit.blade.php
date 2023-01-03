<div id="VisitRecordEditModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="max-w-6xl p-1 mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            data-dismiss="mymodal" data-label="#VisitRecordEditModal">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden block p-2">
            <div class="text-2xl font-bold px-2">
                編輯訪談紀錄
            </div>
            <div class="border-t-2 border-gray-300"></div>
            
            <div class="m-2 p-2 bg-blue-200 ">
                <div class="visit_customer_basic_information grid grid-cols-3 gap-1 p-2 font-bold">
                </div>
            </div>
            <div class="m-2 p-2 bg-yellow-200">
                <div id="form_visit_record_edit" class="grid grid-cols-3 gap-1 font-bold">
                    <div class="">
                    <input type="hidden" id="edit_visit_record_id" name="edit_visit_record_id" value="" class='input_visit_record_edit'>
                    </div>
                    <div class="col-span-3 grid grid-cols-10">
                        <label for="edit_visit_date" class="col-span-2 text-black bg-yellow-300 mx-2 p-2 flex items-center">拜訪日期</label> 
                        <input type="date" id="edit_visit_date" name="edit_visit_date" value="" class="input_visit_record_edit col-span-3 p-2" required>
                        
                        <label for="edit_visit_type" class="col-span-2 text-black bg-yellow-300 mx-2 p-2 flex items-center">拜訪類別</label>
                        <div class="bg-white col-span-3 flex items-center px-2">
                            <select name="edit_visit_type" id="edit_visit_type" class="input_visit_record_edit w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                                <option value="定期">定期</option>
                                <option value="生日">生日</option>
                                <option value="大額">大額</option>
                            </select>
                        </div>
                    </div>
                    <div class=""></div>
                    <div class="col-span-3 grid grid-cols-10">
                        <label for="edit_visit_title" class="col-span-2 text-black bg-yellow-300 mx-2 p-2 flex items-center">拜訪主旨</label>
                        <textarea id="edit_visit_title" name="edit_visit_title" rows="2" cols="50" placeholder="請輸入拜訪主旨" class="input_visit_record_edit col-span-8 p-2 "></textarea>
                    </div>
                    <div class="col-span-3 grid grid-cols-10">
                        <label for="edit_visit_content" class="col-span-2 text-black bg-yellow-300 mx-2 p-2 flex items-center">拜訪內容</label>
                        <textarea id="edit_visit_content" name="edit_visit_content" rows="4" cols="50" placeholder="請輸入拜訪內容" class="input_visit_record_edit col-span-8 p-2 "></textarea>
                    </div>
                    <div class="col-span-3">
                        <div class="m-2 flex justify-center">
                            <button id="btn_visit_record_edit" type="button" data-type='edit' class="mx-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                                編輯儲存
                            </button>
                            <button type="button" class="mx-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 border border-gray-700 rounded"
                                data-dismiss="mymodal" data-label="#VisitRecordEditModal">
                                退出
                            </button>
                        </div>
                    </div>
                    <div class="col-span-3"><div class="border-t-2 border-gray-300"></div></div>
                    <div class="col-span-3 grid grid-cols-10">
                        <div class="col-span-2 text-black bg-yellow-300 mx-2 p-2 relative">
                            <label for="edit_visit_follow" class="flex items-center">後續追蹤</label>
                            <button id="btn_visit_record_edit" type="button" data-type='follow' class="absolute bottom-0 mx-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                                儲存追蹤
                            </button>
                        </div>
                        <div class="col-span-8">
                            <div class="my-2">
                                <label for="edit_option_visit_follow_phrase" class='text-black bg-yellow-300 mx-2 p-2'>追蹤片語</label>
                                <select name="edit_option_visit_follow_phrase" id="edit_option_visit_follow_phrase" class='input_visit_record_edit appearance-none w-auto bg-white-200 border border-gray-600 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2'>
                                    <option value="無">無</option>
                                </select>
                            </div>
                            <textarea id="edit_visit_follow" name="edit_visit_follow" rows="4" cols="50" placeholder="後續追蹤" class="input_visit_record_edit w-full p-2"></textarea>
                        </div>
                    </div>
                    <div class="col-span-3"><div class="border-t-2 border-gray-300"></div></div>
                    <div class="col-span-3 grid grid-cols-10">
                        <div class="col-span-2 text-black bg-yellow-300 mx-2 p-2 relative">
                            <label for="edit_customer_analysis" class="flex items-center">客管分析</label>
                            <button id="btn_visit_record_edit" type="button" data-type='analysis' class="absolute bottom-0 mx-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                                儲存分析
                            </button>
                        </div>
                        <!-- <label for="edit_customer_analysis" class="col-span-2 text-black bg-yellow-300 mx-2 p-2 flex items-center">客管分析</label> -->
                        <textarea id="edit_customer_analysis" name="edit_customer_analysis" rows="4" cols="50" placeholder="客管分析" class="input_visit_record_edit col-span-8 p-2 "></textarea>
                    </div>
                    <div class="col-span-3"><div class="border-t-2 border-gray-300"></div></div>
                    <div class="col-span-3 grid grid-cols-10">
                        <div class="col-span-2 text-black bg-yellow-300 mx-2 p-2 relative">
                            <label for="edit_supervisor_suggest" class="flex items-center">主管建議</label>
                            <button id="btn_visit_record_edit" type="button" data-type='suggest' class="absolute bottom-0 mx-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                                儲存建議
                            </button>
                        </div>
                        <!-- <label for="edit_supervisor_suggest" class="col-span-2 text-black bg-yellow-300 mx-2 p-2 flex items-center">主管建議</label> -->
                        <div class="col-span-8">
                            <div class="my-2">
                                <label for="edit_option_supervisor_suggest_phrase" class='text-black bg-yellow-300 mx-2 p-2'>簽核片語</label>
                                <select id="edit_option_supervisor_suggest_phrase" name="edit_option_supervisor_suggest_phrase" class='input_visit_record_edit appearance-none w-auto bg-white-200 border border-gray-600 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2'>
                                </select>
                            </div>
                            <textarea id="edit_supervisor_suggest" name="edit_supervisor_suggest" rows="4" cols="50" placeholder="主管建議" class="input_visit_record_edit w-full p-2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        function visit_customer_basic_information_get(c_id){
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/visit_customer_basic_information_get')!!}',
                data:{'c_id':c_id},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    $(".visit_customer_basic_information").html(data.visit_customer_basic_information)
                    // console.log('success');
                },
                error:function(){
                    console.log('error');
                }
            })
        }
        function visit_record_edit_get(visit_record_id){
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/visit_record_edit_get')!!}',
                data:{'visit_record_id':visit_record_id},
                dataType:'json',
                success:function(data){
                    $("#edit_visit_record_id").val(data.visit_record_id);
                    $("#edit_visit_date").val(data.visit_date);
                    $("#edit_visit_type").html(data.option_visit_type);
                    $("#edit_visit_title").val(data.visit_title);
                    $("#edit_visit_content").val(data.visit_content);
                    $("#edit_visit_follow").val(data.visit_follow);
                    $("#edit_option_visit_follow_phrase").html(data.option_visit_follow_phrase);
                    $("#edit_customer_analysis").val(data.customer_analysis);
                    $("#edit_supervisor_suggest").val(data.supervisor_suggest);
                    $("#edit_option_supervisor_suggest_phrase").html(data.option_supervisor_suggest_phrase);
                    // console.log(data);
                    // console.log('success');
                },
                error:function(){
                    console.log('error');
                }
            })
        }
        $(document).on('click','.visit_record_edit_get', function(){
            var visit_record_id = $(this).data('id');
            visit_record_edit_get(visit_record_id);
            var c_id = $(this).data('c_id');
            visit_customer_basic_information_get(c_id)
        });
        function visit_record_edit(btn_type){
            var form_data = [];
            $(".input_visit_record_edit").each(function(){
                form_data.push($(this).val());
            });
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/visit_record_edit')!!}',
                data:{'btn_type':btn_type,'form_data':form_data},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    alert("儲存成功");
                    try {
                        visit_record();
                    }
                    catch(err) {
                        search_visit_records_manage()
                    }
                },
                error:function(){
                    console.log('error');
                    alert("失敗");
                }
            })
        }
        $(document).on('click','#btn_visit_record_edit', function(){
            var btn_type = $(this).data("type");
            visit_record_edit(btn_type);
        });
        
    });
</script>