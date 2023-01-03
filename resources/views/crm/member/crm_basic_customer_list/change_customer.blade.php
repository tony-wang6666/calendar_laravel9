<div id="change_customer" class="bg-blue-200 text-lg font-bold text-center select-none hidden ">
    <div id="" class="">
        <!-- <div class="p-2 text-2xl font-black">大額異動</div> -->
        <div class="">
            <table id="change_customers_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full ">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="border p-2">姓名</th>
                        <th class="border p-2">日期</th>
                        <th class="border p-2">分會id</th>
                        <th class="border p-2">科目</th>
                        <th class="border p-2">帳號</th>
                        <th class="border p-2">本日餘額</th>
                        <th class="border p-2">前日餘額</th>
                        <th class="border p-2">正負成長</th>
                        <th class="border p-2">增減金額</th>
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
</div>

<script>
    function change_customer(){
        var basic_c_id = $("#c_id").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/change_customer')!!}',
            data:{'basic_c_id':basic_c_id,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#change_customers_table").html(data.change_customers_table);
            },
            error:function(){
                console.log('error');
            }
        })
    };
    $(document).ready( function(){
        
        $(document).ready( function(){
            change_customer()
        });
    });
</script>