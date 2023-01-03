<div id="parameterEditModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="p-1 max-w-3xl mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            data-dismiss="mymodal" data-label="#parameterEditModal">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block">
            <div class="text-lg bg-blue-300 p-3 text-2xl font-bold">
                <i class="material-icons">settings</i>
                <span id="parameter_set_title" class="">{{$title}}></span>
                <span id="parameter_set_title2" class=""></span>
            </div>
            
            <div class="p-2 bg-gray-300 text-2xl">
                <div class="w-full ">
                    <input type="hidden" id='parameter_id' value=''>
                    <div class="flex items-center justify-center mb-6">
                        <div class="">
                            <label for="p_order" class="block text-gray-700 font-bold md:text-right mb-1 md:mb-0 pr-4" >
                                順序
                            </label>
                        </div>
                        <div class="w-64">
                            <input id="p_order"  class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" type="number" value="1">
                        </div>
                    </div>
                    <div class="flex items-center justify-center mb-6">
                        <div class="">
                            <label for="p_item" class="block text-gray-700 font-bold md:text-right mb-1 md:mb-0 pr-4" >
                                參數
                            </label>
                        </div>
                        <div class="w-64">
                            <input id="p_item" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" type="text" placeholder="請輸入參數">
                        </div>
                    </div>
                    <div class="flex items-center justify-center mb-6">
                        <div class="">
                            <label for="p_state" class="block text-gray-700 font-bold md:text-right mb-1 md:mb-0 pr-4" >
                                狀態
                            </label>
                        </div>
                        <div class="w-64">
                            <select id="p_state_option" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                <option>啟用</option>
                                <option>不啟用</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-end">
                        <!-- <div class="md:w-1/3"></div> -->
                        <div class="">
                            <button id="parameter_edit" class="shadow bg-green-500 hover:bg-green-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                儲存
                            </button>
                            <button data-dismiss="mymodal" data-label="#parameterEditModal" class="shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
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
        function parameter_edit_data(id){
            var parameter_type = $("#parameter_type").val()
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/parameter_edit_data')!!}',
                data:{'parameter_type':parameter_type,'id':id},
                dataType:'json',
                success:function(data){
                    $("#parameter_id").val(id)
                    $("#p_order").val(data.p_order)
                    $("#p_item").val(data.p_item)
                    $("#p_state_option").html(data.p_state_option)
                    $("#parameter_set_title2").html(data.parameter_set_title2)
                },
                error:function(){
                    console.log('error');
                }
            })
        }
        $(document).on('click','.parameter_edit_data',function(){ 
            var id = $(this).data('id')
            parameter_edit_data(id)
            
        });
        $(document).on('click','#parameter_edit',function(){
            var parameter_type = $("#parameter_type").val()
            var id = $("#parameter_id").val()
            var p_order = $("#p_order").val()
            var p_item = $("#p_item").val()
            var p_state = $("#p_state_option").val()
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/parameter_edit')!!}',
                data:{'parameter_type':parameter_type,'id':id,'p_order':p_order,'p_item':p_item,'p_state':p_state},
                dataType:'json',
                success:function(data){
                    // console.log(data);
                    parameter_data() //取得所有參數
                    parameter_edit_data(id) //新增參數
                },
                error:function(){
                    console.log('error');
                }
            })
        });
        $('#p_item').keypress(function(e){
            if(e.keyCode==13) $('#parameter_edit').click();
        });
        
    })
</script>