@extends('layouts.layout_4')
@section('title',$title)
@section('content')

<div class="w-288 mx-auto pt-8">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl font-bold flex items-center text-white text-2xl">
        <i class="material-icons">feed</i>
        <span class="text-2xl font-bold mr-auto">{{$title}}</span>
        <a href="{{url('crm/visit_records_manage_add')}}" class="bg-green-500 hover:bg-green-400 text-white text-base font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">新增拜訪紀錄</a>
        
    </div>
    <div class="bg-gray-300 p-2 px-8 shadow">
        <div class="flex items-center">
            <div class="text-xl mr-auto flex items-center">
                <div class="flex items-center ">
                    <label for="supervisor_suggest_phrase" class="font-bold">簽核狀態：</label>
                    <select id="supervisor_suggest_phrase" class="focus:outline-none focus:ring-2 focus:ring-blue-600">
                        {!!$option_supervisor_suggest_phrase!!}
                    </select>
                    <label for="" class="font-bold">日期：</label>
                    <input type="date" name="" id="date1" value="{{$date1}}" class="w-44">~
                    <input type="date" name="" id="date2" value="{{$date2}}" class="w-44">
                    <label for="visit_type" class="font-bold">種類：</label>
                    <select id="visit_type" class="focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">不限</option>
                        {!!$option_visit_type!!}
                    </select>
                    <label for="ao_staff" class="font-bold">AO：</label>
                    <select id="ao_staff" class="focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">不限</option>
                        {!!$option_ao_staff!!}
                    </select>
                </div>
            </div>

            <div class="">
                <a href="{{url('crm/search_visit_records_manage_export')}}" class="bg-green-500 hover:bg-green-400 text-white text-base font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">匯出</a>
            </div>
        </div>
        
    </div>
    <div class="mb-8">
        <form action="{{url('crm/visit_records_manage_delete')}}" class="">
        @csrf
        <div class="bg-gray-300 flex items-center font-bold text-lg px-2">
            <span class="">符合條件筆數：</span>
            <span id='visit_records_count' class="text-red-500"></span>
            <button type="submit" onclick="return confirm('確定要刪除嗎?')" class="bg-red-500 hover:bg-red-400 text-white text-base font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-red-600 hover:border-red-500 rounded shadow-xl">刪除</button>
        </div>
        <table id='visit_record_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full ">
            <thead class="bg-blue-600 text-white ">
                <tr>
                    <th class='border p-2'><input type='checkbox' class='checked_all w-4 h-4'></th>
                    <th class='border p-2 w-20'>訪談日期</th>
                    <th class='border p-2 w-20'>報告日期</th>
                    <th class='border p-2 w-28'>種類</th>
                    <th class='border p-2 w-24'>姓名</th>
                    <th class='border p-2 w-28'>內容</th>
                    <th class='border p-2 w-24'>建立者</th>
                    <th class='border p-2 w-20'>AO</th>
                    <th class='border p-2 w-20'>客管</th>
                    <th class='border p-2 w-20'>主管</th>
                    <th class='border p-2 w-24'></th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-200 h-16">
                    <td class='border p-2'><input type='checkbox' class='checks w-4 h-4'></td>
                    <td class='border p-2'></td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">
                        <button type='button' data-id='' class='bg-yellow-500 text-yellow-900 p-2 hover:bg-yellow-600'>編輯</button>
                    </td>
                </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>
<!-- Modal -->
@include("crm.member.crm_basic_customer_list.visit_record_edit")
<!-- /Modal -->
<script>
    function search_visit_records_manage(){
        var supervisor_suggest_phrase = $("#supervisor_suggest_phrase").val();
        var date1 = $("#date1").val();
        var date2 = $("#date2").val();
        var visit_type = $("#visit_type").val();
        var ao_staff = $("#ao_staff").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/search_visit_records_manage')!!}',
            data:{'supervisor_suggest_phrase':supervisor_suggest_phrase,'date1':date1,'date2':date2,
                'visit_type':visit_type,'ao_staff':ao_staff,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#visit_record_table").html(data.visit_record_table)
                $("#visit_records_count").html(data.DB_crm__visit_records_count)
                
                // visit_record();
            },
            error:function(){
                $("#visit_record_table").html("<p class='text-xl'>ERROR</p>")
                console.log('error');
            }
        })
    }
    search_visit_records_manage()
    $(document).ready(function(){    
        $(document).on('change','#supervisor_suggest_phrase, #date1, #date2, #visit_type, #ao_staff',function(){
            search_visit_records_manage()
        });
        $(document).on('click','.checked_all',function(){
            if($(this).prop("checked")){
                $('.checks').prop("checked", true);
            }else{
                $('.checks').prop("checked", false);
            }
        });

    })
</script>

@endsection