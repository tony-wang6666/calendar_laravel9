<div id="customer_family" class="bg-blue-200 text-lg font-bold text-center select-none p-2 hidden">
<!-- bg-yellow-600  hidden -->
    
    <div class="">
        <div class="flex m-2">
            <div class="p-2">
                <span class="bg-blue-400 p-2">戶號</span>
                <span id="basic_c_number" class="bg-white p-2">{{$DB_crm__customer_basic_informations[0]->c_number}}</span>
            </div>
            <div class="p-2">
                <span class="bg-blue-400 p-2">編號</span>
                <span id="basic_c_id" class="bg-white p-2">{{$DB_crm__customer_basic_informations[0]->id}}</span>
            </div>
            <div class="p-2">
                <span class="bg-blue-400 p-2">名字</span>
                <span class="bg-white p-2">{{$DB_crm__customer_basic_informations[0]->c_name_company}}</span>
            </div>
            <div class="p-2">
                <span class="bg-blue-400 p-2">性別</span>
                <span class="bg-white p-2">{{$DB_crm__customer_basic_informations[0]->c_sex}}</span>
            </div>
            <div class="p-2">
                <span class="bg-blue-400 p-2">親屬關係</span>
                <span class="bg-white p-2">{{$DB_crm__customer_basic_informations[0]->c_family}}</span>
            </div>
        </div>
        <div id="c_number_family_list" class="h-80">
            <div class="p-2 text-2xl font-black">同戶親屬</div>
            <div class="overflow-y-auto h-64 block">
                <table id="customer_family_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full ">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border p-2">戶號</th>
                            <th class="border p-2">會號</th>
                            <th class="border p-2">姓名</th>
                            <th class="border p-2">身分</th>
                            <th class="border p-2">性別</th>
                            <th class="border p-2">生日</th>
                            <th class="border p-2">電話</th>
                            <th class="border p-2">手機</th>
                            <th class="border p-2"></th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr class="bg-blue-300 h-16">
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2 w-24"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- <button id='btn_add_case' class="show-modal fixed z-30 bg-green-800 bottom-8 right-8 w-10 h-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-green-900">
        <i class="material-icons text-white">add</i>
    </button> -->
    
    <div id="search_customer" class="bg-gray-400" >
        <div class="">
            <div class="p-2 text-2xl font-black">添加同戶親屬</div>
            <label for="" class="select-none text-2xl">條件查詢</label>
            <select id="search_type" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                <option value="c_name_company">客戶姓名</option>
                <option value="identification_gui_number">身分證號</option>
                <option value="phone">電話手機</option>
                <option value="id">客戶編號</option>
            </select>
            <input id="search_val" value="" type="text" placeholder="請輸入查詢值" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
        </div>
        <div id="add_c_number_family_list" class="mt-2">
            <div class="overflow-y-auto h-64 block"> 
                <table id="search_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
                    <thead id='search_thead' class='bg-gray-600 text-white'>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('crm.modals.modal_search_customer')
</div>

<script>
    function customer_family(){
        var basic_c_number = $("#basic_c_number").text();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/customer_family')!!}',
            data:{'basic_c_number':basic_c_number,},
            dataType:'json',
            success:function(data){
                $("#customer_family_table").html(data.customer_family_table);
            },
            error:function(){
                console.log('error');
            }
        })
    }
    $(document).ready(function(){
        customer_family()
        function crm_search_customer(){
            var basic_c_number = $("#basic_c_number").text();
            var search_type = $("#search_type").val();
            var search_val = $("#search_val").val();
            if(search_type && search_val){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/crm_search_customer')!!}',
                    data:{'basic_c_number':basic_c_number,'search_type':search_type,'search_val':search_val},
                    dataType:'json',
                    success:function(data){
                        $("#search_table").html(data.search_table);
                    },
                    error:function(){
                        console.log('error');
                    }
                })
            }
        }
        $(document).on("change","#search_val , #search_type", function(){
            crm_search_customer()
        });
        $(document).on("click",".check_c_id", function(){
            var type = $(this).data("type");
            var c_id = $(this).data("id");
            var basic_c_number = $("#basic_c_number").text();
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/change_c_number_family')!!}',
                data:{'type':type,'c_id':c_id,'basic_c_number':basic_c_number,},
                dataType:'json',
                success:function(data){
                    crm_search_customer()
                    customer_family()
                },
                error:function(){
                    console.log('error');
                }
            })
        });
        $(document).on("change",".change_c_number_family_edit", function(){
            var type = $(this).data("type");
            var c_id = $(this).data("id");
            var c_family = $(this).val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/change_c_number_family')!!}',
                data:{'type':type,'c_id':c_id,'c_family':c_family,},
                dataType:'json',
                success:function(data){
                    console.log("success");
                    // crm_search_customer()
                    // customer_family()
                },
                error:function(){
                    console.log('error');
                }
            })
        });
        
    });
</script>

