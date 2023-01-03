<div id="visit_record" class="bg-blue-200 text-lg font-bold text-center select-none p-2 hidden">
    <div class="flex">
        <div class="mr-auto"></div>
        <button id="btn_visit_add" class="mx-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded"
            data-id="{{$DB_crm__customer_basic_informations[0]->id}}" data-toggle="mymodal" data-target="#VisitRecordAddModal">
            新增訪談
        </button>
        <button onclick='visit_record_delete()' class="mx-2 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded">
            刪除
        </button>
    </div>
    <div class="mt-2">
        <div class="overflow-y-auto h-96 block"> 
            <table id="visit_record_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="border p-2">拜訪日期</th>
                        <th class="border p-2">目的</th>
                        <th class="border p-2">分析報告</th>
                        <th class="border p-2">AO人員</th>
                        <th class="border p-2"></th>
                    </tr>
                </thead>
                <tbody >
                    <tr class="bg-blue-300 h-16">
                        <td class="border p-2 w-36">xxxxx</td>
                        <td class="border p-2 w-48">xxxxx</td>
                        <td class="border p-2">xxxxx</td>
                        <td class="border p-2 w-36">xxxxx</td>
                        <td class="border p-2 w-24">
                            <button type='button' data-id='' class='bg-yellow-500 text-yellow-900 p-2 hover:bg-yellow-600'>編輯</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@include("crm.member.crm_basic_customer_list.visit_record_add")
@include("crm.member.crm_basic_customer_list.visit_record_edit")

<script>
    function visit_record(){
        var basic_c_id = $("#basic_c_id").text();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/visit_record')!!}',
            data:{'basic_c_id':basic_c_id,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#visit_record_table").html(data.visit_record_table);
            },
            error:function(){
                console.log('error');
            }
        })
    }
    function visit_record_delete(){
        var vals = [];
        $('.checked_visit_record_delete:checkbox:checked').each(function (index, item) {
            vals.push($(this).data('id'));
        });
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/visit_record_delete')!!}',
            data:{'vals':vals},
            dataType:'json',
            success:function(data){
                // console.log(data);
                visit_record();
            },
            error:function(){
                console.log('error');
            }
        })
        // console.log(vals);
    }
    $(document).ready(function(){
        visit_record();
        $(document).on('click','.checked_all_visit_records_delete',function(){
            var a = $(this).prop("checked");
            if($(this).prop("checked")){
                $('.checked_visit_record_delete').prop("checked", true);
            }else{
                $('.checked_visit_record_delete').prop("checked", false);
            }
        });
    });
</script>