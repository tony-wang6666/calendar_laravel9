<div id="VisitRecordAddModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="max-w-6xl p-1 mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            data-dismiss="mymodal" data-label="#VisitRecordAddModal">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden block p-2">
            <div class="text-2xl font-bold px-2">
                新增訪談紀錄
            </div>
            <div class="border-t-2 border-gray-300"></div>
            <div class="m-2 p-2 bg-blue-200 ">
                <div class="visit_customer_basic_information grid grid-cols-3 gap-1 p-2 font-bold">
                    <div class="grid grid-cols-5">
                        <label for="vc_id" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">會員編號</label> 
                        <input type="text" id="vc_id" name="vc_id" value="xxxxxx" class="col-span-3 p-2" readonly>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="vc_name_company" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">姓名/公司名</label> 
                        <input type="text" id="vc_name_company" name="vc_name_company" value="xxxxxx" class="col-span-3 px-2" readonly>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="vc_type" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">客戶種類</label>
                        <div class="bg-white col-span-3 flex items-center px-2">
                            <select id="vc_type" name="vc_type" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2" disabled>
                                <option value="">個人</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="vc_sex" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">性別</label>
                        <div class="bg-white col-span-3 flex items-center px-2">
                            <select id="vc_sex" name="vc_sex" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2"disabled>
                                <option value="男">男</option>
                                <option value="女">女</option>
                                <option value="無">無</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="vc_birth_opening_date" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">生日/開業日</label> 
                        <input type="date" id="vc_birth_opening_date" name="vc_birth_opening_date" value="" class="col-span-3 px-2" readonly>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="v_identification_gui_number" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">身分證/統編</label> 
                        <input type="text" id="v_identification_gui_number" name="v_identification_gui_number" value="xxxx" class="col-span-3 px-2" readonly>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="vc_telephone" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">電話</label> 
                        <input type="text" id="vc_telephone" name="vc_telephone" value="***" class="col-span-3 px-2" placeholder="請輸入電話" readonly>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="vc_cellphone" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">手機</label> 
                        <input type="text" id="vc_cellphone" name="vc_cellphone" value="***" class="col-span-3 px-2" placeholder="請輸入手機">
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="v_religion" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">宗教</label> 
                        <div class="bg-white col-span-3 flex items-center px-2">
                            <select id="v_religion" name="v_religion" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2" disabled>
                                <option value="">無</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-5">
                        <label for="v_vip_cyear" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">VIP年度</label> 
                        <input type="text" id="v_vip_cyear" name="v_vip_cyear" value="***" class="col-span-3 px-2" readonly>
                    </div>
                    <div class="col-span-2 grid grid-cols-10">
                        <label for="v_visit_time0" class="col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center">可拜訪時段</label> 
                        <div class="col-span-8 grid grid-cols-4 px-2 flex items-center ">
                            <div class='flex mr-1'>
                                <input type='checkbox' id='v_visit_time0' name='visit_time[]' value='早上' class='px-2 h-6 w-6' onclick="return false">
                                <label for='v_visit_time0' class='text-white bg-blue-500 mx-2 px-2'>早上</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-2 p-2 bg-yellow-200">
                <div id="form_visit_record" class="grid grid-cols-3 gap-1 font-bold">
                    <div class="">
                    <input type="hidden" id="visit_c_id" name="visit_c_id" value="">
                    </div>
                    <div class="col-span-3 grid grid-cols-10">
                        <label for="visit_date" class="col-span-2 text-black bg-yellow-300 mx-2 p-2">拜訪日期</label> 
                        <input type="date" id="visit_date" name="visit_date" value="" class="col-span-3 p-2" required>
                        
                        <label for="visit_type" class="col-span-2 text-black bg-yellow-300 mx-2 p-2">拜訪類別</label>
                        <div class="bg-white col-span-3 flex items-center px-2">
                            <select name="visit_type" id="visit_type" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                                <option value="定期">定期</option>
                                <option value="生日">生日</option>
                                <option value="大額">大額</option>
                            </select>
                        </div>
                    </div>
                    <div class=""></div>
                    <div class="col-span-3 grid grid-cols-10">
                        <label for="visit_title" class="col-span-2 text-black bg-yellow-300 mx-2 p-2">拜訪主旨</label>
                        <textarea id="visit_title" name="visit_title" rows="2" cols="50" placeholder="請輸入拜訪主旨" class="col-span-8 p-2 "></textarea>
                    </div>
                    <div class="col-span-3 grid grid-cols-10">
                        <label for="visit_content" class="col-span-2 text-black bg-yellow-300 mx-2 p-2">拜訪內容</label>
                        <textarea id="visit_content" name="visit_content" rows="4" cols="50" placeholder="請輸入拜訪內容" class="col-span-8 p-2 "></textarea>
                    </div>
                </div>
            </div>
            <div class="border-t-2 border-gray-300"></div>
            <div class="m-2 flex justify-end">
                <button id="btn_visit_record_add" type="button" class="mx-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                    新增儲存
                </button>
                <button type="button" class="mx-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 border border-gray-700 rounded"
                data-dismiss="mymodal" data-label="#VisitRecordAddModal">
                    退出
                </button>
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
        function visit_record_add(){
            var form_data = [];
            $("#form_visit_record :input").each(function(){
                form_data.push($(this).val());
            });
            // console.log(form_data);
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/visit_record_add')!!}',
                data:{'form_data':form_data},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    // console.log('success');
                    alert("新增成功");
                    visit_record(); //更新(客戶基本資料>拜訪紀錄)
                },
                error:function(){
                    console.log('error');
                    alert("新增失敗");
                }
            })
        }
        $(document).on('click','#btn_visit_record_add', function(){
            visit_record_add();
        });
        $(document).on('click','#btn_visit_add', function(){
            var c_id = $(this).data("id")
            $("#visit_c_id").val(c_id)
            // console.log(c_id);
            visit_customer_basic_information_get(c_id); //取得拜訪客戶基本資料
        });
        
    });
</script>