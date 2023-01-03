<div id="calendar_memberEditModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="p-1 w-288 mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            data-dismiss="mymodal" data-label="#calendar_memberEditModal">
            <span class="material-icons">clear</span>
        </div>
        
        <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block">
            <div class="text-lg bg-blue-300 p-3 text-2xl font-bold">
                <i class="material-icons">settings</i>
                <span id="parameter_set_title" class="">{{$title}}></span>
                <span class="manage_account_edit_title2"></span>
            </div>
            
            <div class="p-2 bg-gray-300 text-xl">
                <div class="flex items-center">
                    <div class='mr-auto pl-8 font-black'>
                        <ul class="list-disc text-xl">
                            <li>主管可以簽核AO人員的拜訪紀錄單，加入建議事項</li>
                            <li>非AO人員僅提供客戶之[勸募員工]欄位參考用，不用給任何權限</li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-center font-bold">
                    <input type="hidden" id='cm_id' value=''>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="cm_account" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">帳號</label> 
                            <input type="text" id="cm_account" name="cm_account" value="" class="col-span-3 px-2" placeholder="請輸入帳號" required>
                        </div>
                    </div>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="cm_password" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">密碼</label> 
                            <input type="password" id="cm_password" name="cm_password" value="" class="col-span-3 px-2" placeholder="請輸入密碼" required>
                        </div>
                    </div>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="cm_name" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">姓名</label> 
                            <input type="text" id="cm_name" name="cm_name" value="" class="col-span-3 px-2" placeholder="請輸入姓名" required>
                        </div>
                    </div>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="cm_ao_staff" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">是否為AO</label> 
                            <select name="cm_ao_staff" id="cm_ao_staff" class="col-span-3 px-2 block w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">是</option>
                                <option value="">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="cm_manager" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">是否為主管</label> 
                            <select name="cm_manager" id="cm_manager" class="col-span-3 px-2 block w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">是</option>
                                <option value="">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="cm_state" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">狀態</label> 
                            <select name="cm_state" id="cm_state" class="col-span-3 px-2 block w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">啟用</option>
                                <option value="">不啟用</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-92">
                        <div class="grid grid-cols-5 py-1">
                            <label for="notification_token" class="col-span-2 bg-blue-400 mx-2 p-2 text-center">line權杖</label> 
                            <input type="text" id="notification_token" name="notification_token" value="" class="col-span-3 px-2" placeholder="請輸入line權杖">
                        </div>
                    </div>
                    <div class="w-full px-2">
                        <div class="grid grid-cols-7 py-1">
                            <label class="col-span-1 bg-blue-400 mx-2 p-2 text-center flex items-center justify-center">權限</label> 
                            <div id='checkbox_cm_authoritys' class="col-span-6 px-2 grid grid-cols-5 flex items-center select-none">
                                <input type='checkbox' id='cm_authority1' name='cm_authority[]' value='客戶管理' class='px-2 h-6 w-6'>
                                <label for='cm_authority1' class='text-white bg-blue-500 mx-2 px-2'>客戶管理</label>
                                <input type='checkbox' id='cm_authority2' name='cm_authority[]' value='拜訪紀錄' class='px-2 h-6 w-6'>
                                <label for='cm_authority2' class='text-white bg-blue-500 mx-2 px-2'>拜訪紀錄</label>
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center justify-end">
                        <!-- <div class="md:w-1/3"></div> -->
                        <div class="">
                            <button id="manage_account_edit" class="shadow bg-green-500 hover:bg-green-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                <span class="manage_account_edit_title2">編輯</span>
                            </button>
                            <button data-dismiss="mymodal" data-label="#calendar_memberEditModal" class="shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                回列表
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        function manage_account_edit_data(id){
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/manage_account_edit_data')!!}',
                data:{'id':id},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    $("#cm_id").val(id)
                    $("#cm_account").val(data.cm_account)
                    $("#cm_password").val("")
                    $("#cm_name").val(data.cm_name)
                    $(".manage_account_edit_title2").html(data.manage_account_edit_title2)
                    $("#checkbox_cm_authoritys").html(data.checkbox_cm_authoritys)
                    $("#cm_ao_staff").html(data.cm_ao_staff_option)
                    $("#cm_manager").html(data.cm_manager_option)
                    $("#cm_state").html(data.cm_state_option)
                    $("#cm_account").prop("disabled", data.cm_account_disabled);
                    $("#notification_token").val(data.notification_token)
                },
                error:function(){
                    console.log('error');
                }
            })
        }
        $(document).on('click','.calendar_member_edit_data',function(){ 
            var id = $(this).data('id')
            manage_account_edit_data(id)
            
        });
        $(document).on('click','#manage_account_edit',function(){ //編輯
            var cm_id = $("#cm_id").val()
            var cm_account = $("#cm_account").val()
            var cm_password = $("#cm_password").val()
            var cm_name = $("#cm_name").val()
            var cm_ao_staff = $("#cm_ao_staff").val()
            var cm_manager = $("#cm_manager").val()
            var cm_state = $("#cm_state").val()
            var notification_token = $("#notification_token").val()
            var cm_authority = [];
            $('input[name=cm_authority]:checked').each(function(i){
                cm_authority[i] = $(this).val();
            });
            var data = {'cm_id':cm_id,
                    'cm_account':cm_account,
                    'cm_password':cm_password,
                    'cm_name':cm_name,
                    'cm_ao_staff':cm_ao_staff,
                    'cm_manager':cm_manager,
                    'cm_state':cm_state,
                    'cm_authority':cm_authority,
                    'notification_token':notification_token}
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/manage_account_edit')!!}',
                data:data,
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    search_manage_accounts();
                    if(data.message) alert(data.message)
                    // parameter_edit_data(id) //新增參數
                },
                error:function(){
                    alert("錯誤");
                    console.log('error');
                }
            })
        });
        
    })
    
</script>