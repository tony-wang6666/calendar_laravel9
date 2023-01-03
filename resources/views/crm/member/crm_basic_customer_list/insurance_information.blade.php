<div id="insurance_information" class="bg-blue-200 text-lg font-bold text-center select-none hidden ">
    <div class="">
        <table id="insurance_information_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full ">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="border p-2">日期</th>
                    <th class="border p-2">被保人</th>
                    <th class="border p-2">要保人</th>
                    <th class="border p-2">保險公司</th>
                    <th class="border p-2">保單日期</th>
                    <th class="border p-2">險別(小)</th>
                    <th class="border p-2">保費</th>
                    <th class="border p-2">農漁會佣金</th>
                    <th class="border p-2">車號</th>
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
                    <td class="border p-2">xxxxx</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
        

<script>
    function insurance_information(){
        var basic_c_id = $("#c_id").val();
        // console.log(basic_c_id);
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/insurance_information')!!}',
            data:{'basic_c_id':basic_c_id,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#insurance_information_table").html(data.insurance_information_table);
            },
            error:function(){
                console.log('error');
            }
        });
    };
    $(document).ready( function(){
        insurance_information()
    });
</script>