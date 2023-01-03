@extends('layouts.layout_4')
@section('title','每月餘額轉入')
@section('content')

<div class="w-288 mx-auto mt-4 select-none">
    <div class="bg-blue-400 p-2 rounded-t-lg flex items-center justify-center ">
        <i class="material-icons">post_add</i>
        <span class="text-2xl font-bold ">每月餘額轉入</span> 
    </div>


    <div class="bg-blue-200 text-lg font-bold text-center select-none">
        <div class="flex ">
            <div class="text-left text-2xl m-2">
                <span class="">查詢日期：</span>
                <input id='input_year' type="number" class="w-24 text-center" value="{{$now_year}}">
                <label for='input_year' class="">年</label>
                <select id='select_month' name="" id="" class="">
                    @for($i=1;$i<=12;$i++)
                    <option value="{{str_pad($i,2,'0',STR_PAD_LEFT)}}" @if($i == $now_month) selected @endif >{{str_pad($i,2,'0',STR_PAD_LEFT)}}</option>
                    @endfor
                </select>
                <label for='select_month' class="">月</label>
            </div>
            <div class='mr-auto'></div>
            <div class="m-2">
                <a href="{{url('crm/account_balance_import')}}" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">轉入資料</a>
                <a href="{{url('crm/account_balance_export')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">匯出資料</a>
            </div>
        </div>
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
    function account_balance_check(year,month){
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/account_balance_check')!!}',
            data:{'year':year,'month':month,},
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
        var year = $("#input_year").val()
        var month = $("#select_month").val()
        account_balance_check(year,month)
        $(document).on("change","#input_year , #select_month", function(){
            var year = $("#input_year").val()
            var month = $("#select_month").val()
            account_balance_check(year,month)
        });
    });
</script>
@endsection