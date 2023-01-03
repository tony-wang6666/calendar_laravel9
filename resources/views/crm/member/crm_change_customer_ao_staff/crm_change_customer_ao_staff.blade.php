@extends('layouts.layout_4')
@section('title',$title)
@section('content')

<div class="w-288 mx-auto pt-8">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl font-bold flex items-center text-white text-2xl">
        <i class="material-icons">settings</i>
        <span class="text-2xl font-bold mr-auto">{{$title}}>AO異動作業</span> 
        <a href="{{url('crm/change_customer_ao_staff_record')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">AO異動紀錄</a>
        <a href="{{url('crm/change_customer_ao_staff')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">AO異動作業</a>
    </div>
    <form action="{{url('crm/change_customer_ao_staff_post')}}" method="post" >
    @csrf
    <div class="bg-gray-300 p-2 shadow">
        <div class="flex items-center">
            <div class='mr-auto pl-4'>
                <!-- <ul class="list-disc text-xl">
                    <li>1.查詢原AO的客戶群，全選或勾選客戶，移交給新AO人員。</li>
                    <li>2.客戶檔及拜訪紀錄檔-AOId欄位以新AO取代。</li>
                    <li>3.拜訪記錄檔-createId欄位紀錄原選稿AO人員，AOId為目前管理人員</li>
                    <li>4.寫入AO人員異動檔(memHisAO)</li>
                </ul> -->
            </div>
        </div>
        <div class="flex items-center">
            <div class="text-2xl mr-auto flex items-center">
                <label for="search_val" class="">AO：</label>
                <select name="old_ao_staff" id="search_val" class="mr-8">
                    {!!$ao_staffs_option!!}
                </select>
            </div>

            <div class="flex items-center">
                <select name="new_ao_staff" id="" class="mr-4 text-2xl" required>
                    <option value="" selected >--請選擇新AO人員--</option>
                    {!!$ao_staffs_new_option!!}
                </select>
                <button type='submit' class="bg-yellow-500 hover:bg-yellow-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-yellow-600 hover:border-yellow-500 rounded shadow-xl">客戶移交</button>
            </div>
        </div>
        
    </div>
    <div class="">
        <table id='customer_basic_information_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
            <thead class="bg-blue-600 text-white ">
                <tr>
                    <th class='border p-2 w-12'><input type='checkbox' class='checked_all w-6 h-6'></th>
                    <th class='border p-2 w-24'>編號</th>
                    <th class='border p-2 '>姓名</th>
                    <th class='border p-2 w-28'>存-放等級</th>
                    <th class='border p-2 w-28'>電話</th>
                    <th class='border p-2 w-28'>手機</th>
                    <th class='border p-2 w-16'>區碼</th>
                    <th class='border p-2 w-20'>縣市</th>
                    <th class='border p-2 w-20'>鄉鎮</th>
                    <th class='border p-2 w-28'>AO</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-200 h-16">
                    <td class='border p-2'><input type='checkbox' class='w-6 h-6'></td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                </tr>
            </tbody>
        </table>
    </div>
    </form>
</div>
<!-- Modal -->
<!-- /Modal -->
<script>
    function search_change_customer_ao_staff(){
        var search_val = $("#search_val").val();
        // console.log(search_val);
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/search_change_customer_ao_staff')!!}',
            data:{'search_val':search_val},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#customer_basic_information_table").html(data.customer_basic_information_table)
                
                // visit_record();
            },
            error:function(){
                $("#customer_basic_information_table").html("<p class='text-xl'>ERROR</p>")
                console.log('error');
            }
        })
    }
    setTimeout( //等頁面跑出來，再抓查詢值，比較準確
        function() {
            search_change_customer_ao_staff()
        },300
    );
    $(document).ready(function(){
        $(document).on('change','#search_val',function(){
            search_change_customer_ao_staff()
        });
        // $(document).on('change','#search_type',function(){
        //     search_change_customer_ao_staff_record()
        // });
        $(document).on('click','.checked_all',function(){
            var a = $(this).prop("checked");
            if($(this).prop("checked")){
                $('.ao_staff_checkbox').prop("checked", true);
            }else{
                $('.ao_staff_checkbox').prop("checked", false);
            }
        });
    })
</script>

@endsection