@extends('layouts.layout_4')
@section('title',$title)
@section('content')

<div class="w-288 mx-auto pt-8">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl font-bold flex items-center text-white text-2xl">
        <i class="material-icons">settings</i>
        <span class="text-2xl font-bold mr-auto">{{$title}}>AO異動紀錄 (最多顯示20筆)</span>
        <a href="{{url('crm/change_customer_ao_staff_record')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">AO異動紀錄</a>
        <a href="{{url('crm/change_customer_ao_staff')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">AO異動作業</a>
    </div>
    <div class="bg-gray-300 p-2  shadow">
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
                <select name="" id="search_type" class="mr-8">
                    <option value="AO人員查詢">AO人員查詢</option>
                    <option value="客戶查詢">客戶查詢</option>
                </select>
                <div id="ao_search" class="flex items-center ">
                    <label for="search_old_ao" class="">原AO：</label>
                    <select id="search_old_ao" class="focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">不限</option>
                        {!!$ao_staff_option!!}
                    </select>
                    <label for="search_new_ao" class="">新AO：</label>
                    <select id="search_new_ao" class="focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">不限</option>
                        {!!$ao_staff_option!!}
                    </select>
                </div>
                <div id="customer_search" class="flex items-center hidden">
                    <!-- <div class="p-2 text-2xl font-black">選擇會員</div> -->
                    <!-- <label for="" class="select-none text-2xl">條件查詢</label> -->
                    <select id="search_customer_type" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        <option value="c_name_company">客戶姓名</option>
                        <option value="identification_gui_number">身分證號</option>
                        <option value="phone">電話手機</option>
                        <option value="c_id">客戶編號</option>
                    </select>
                    <input id="search_customer_val" value="" type="text" placeholder="請輸入查詢值" class="border border-transparent h-8 w-40 mx-2 px-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                    </div>
            </div>

            <div class="">
                <a href="{{url('crm/change_customer_ao_staff_export')}}" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">匯出</a>
            </div>
        </div>
        
    </div>
    <div class="mb-8">
        <table id='change_customer_ao_staff_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
            <thead class="bg-blue-600 text-white ">
                <tr>
                    <th class='border p-2 w-28'>異動日期</th>
                    <th class='border p-2 w-24'>原AO</th>
                    <th class='border p-2 w-28'>原AO姓名</th>
                    <th class='border p-2 w-24'>新AO</th>
                    <th class='border p-2 w-28'>新AO姓名</th>
                    <th class='border p-2 w-24'>編號</th>
                    <th class='border p-2 w-20'>姓名</th>
                    <th class="border p-2 w-20">電話</th>
                    <th class="border p-2 w-20">手機</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-200 h-16">
                    <td class='border p-2'></td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<!-- /Modal -->
<script>
    function search_change_customer_ao_staff_record(){
        var search_type = $("#search_type").val();
        var search_old_ao = $("#search_old_ao").val();
        var search_new_ao = $("#search_new_ao").val();
        var search_customer_type = $("#search_customer_type").val();
        var search_customer_val = $("#search_customer_val").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/search_change_customer_ao_staff_record')!!}',
            data:{'search_type':search_type,'search_old_ao':search_old_ao,'search_new_ao':search_new_ao,
                'search_customer_type':search_customer_type,'search_customer_val':search_customer_val,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#change_customer_ao_staff_table").html(data.change_customer_ao_staff_table)
                
                // visit_record();
            },
            error:function(){
                $("#change_customer_ao_staff_table").html("<p class='text-xl'>ERROR</p>")
                console.log('error');
            }
        })
    }
    search_change_customer_ao_staff_record()
    $(document).ready(function(){
        $(document).on('change','#search_type, #search_old_ao, #search_new_ao, #search_customer_type, #search_customer_val',function(){
            var search_type = $(this).val();
            if(search_type == "AO人員查詢"){
                $("#ao_search").removeClass( "hidden" );
                $("#customer_search").addClass( "hidden" );
                
            }else if(search_type == "客戶查詢"){
                $("#ao_search").addClass( "hidden" );
                $("#customer_search").removeClass( "hidden" );
                
            }
            search_change_customer_ao_staff_record()
        });
    })
</script>

@endsection