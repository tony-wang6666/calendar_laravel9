<div id="account_balance" class="bg-blue-200 text-lg font-bold text-center select-none hidden ">
    <div id="" class="">
        <!-- <div class="p-2 text-2xl font-black">帳戶餘額</div> -->
        <div class="">
            <table id="account_balance_table" class="shadow-lg bg-whtie mx-auto text-center text-lg w-full ">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="border p-2">日期</th>
                        <th class="border p-2">行別</th>
                        <th class="border p-2">科目</th>
                        <th class="border p-2">帳號</th>
                        <th class="border p-2">餘額</th>
                        <th class="border p-2">存摺戶<br>前六月均額</th>
                        <th class="border p-2">定期戶<br>去年度均額</th>
                        <th class="border p-2">放款戶<br>初貸額</th>
                        <th class="border p-2">去年度利息<br>回收總額</th>
                        <th class="border p-2">存款總額</th>
                        <th class="border p-2">放款總額</th>
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
                        <td class="border p-2">xxxxx</td>
                        <td class="border p-2">xxxxx</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function account_balance(){
        var basic_c_id = $("#c_id").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/account_balance')!!}',
            data:{'basic_c_id':basic_c_id,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#account_balance_table").html(data.account_balance_table);
            },
            error:function(){
                console.log('error');
            }
        })
    };
    $(document).ready( function(){
        account_balance()
    });
</script>