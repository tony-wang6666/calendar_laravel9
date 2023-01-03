@extends('layouts.layout_4')
@section('title',$title)
@section('content')

<div class="w-288 mx-auto pt-8">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl font-bold flex items-center text-white text-2xl">
        <i class="material-icons">settings</i>
        <span class="text-2xl font-bold">{{$title}}</span> 
    </div>
    <div class="bg-gray-300 p-2  shadow">
        <div class="flex items-center">
            <div class='mr-auto pl-4'>
                <ul class="list-disc text-xl">
                    <li>主管可以簽核AO人員的拜訪紀錄單，加入建議事項</li>
                    <li>非AO人員僅提供客戶之[勸募員工]欄位參考用，不用給任何權限</li>
                </ul>
            </div>
        </div>
        <div class="flex items-center">
            <div class="text-2xl mr-auto">
                <select id="search_val" class="focus:outline-none focus:ring-2 focus:ring-blue-600">
                    {!!$select_search_option!!}
                </select>
            </div>
            <div class="">
                <input id='parameter_type' type="hidden" name="" value="123">
                <button type='button' data-toggle='mymodal' data-target='#calendar_memberEditModal' class="calendar_member_edit_data bg-green-500 hover:bg-green-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">新增</a>
                <button type='button' id="manage_account_delete" data-type='delete' class="bg-red-500 hover:bg-red-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-red-600 hover:border-red-500 rounded shadow-xl">刪除</a>
            </div>
        </div>
        
    </div>
    <div class="">
        <table id='calendar_member_set_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
            <thead class="bg-blue-600 text-white ">
                <tr>
                    <th class='border p-2 w-16'><input type='checkbox' class='checked_all w-6 h-6'></th>
                    <th class='border p-2 w-48'>姓名</th>
                    <th class='border p-2 w-48'>帳號</th>
                    <th class='border p-2 w-24'>AO</th>
                    <th class='border p-2 w-24'>主管</th>
                    <th class='border p-2'>權限</th>
                    <th class='border p-2 w-24'>狀態</th>
                    <th class="border p-2 w-24"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-200 h-16">
                    <td class='border p-2'><input type='checkbox' class='calendar_member_checkbox w-6 h-6'></td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">
                        <button type='button' class='calendar_member_edit_data bg-yellow-500 text-white p-2 hover:bg-yellow-600'>編輯</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
@include('crm.member.crm_manage_accounts.modal_manage_account_set')
<!-- /Modal -->
<script>
    function search_manage_accounts(){
        var search_val = $("#search_val").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/search_manage_accounts')!!}',
            data:{'search_val':search_val},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#calendar_member_set_table").html(data.calendar_member_set_table)
                
                // visit_record();
            },
            error:function(){
                $("#calendar_member_set_table").html("<p class='text-xl'>ERROR</p>")
                console.log('error');
            }
        })
    }
    search_manage_accounts()
    // var checked_name = 'parameter_checkbox';
    function manage_account_delete(){
        // var parameter_type = $("#parameter_type").val()
        var vals = [];
        $('.calendar_member_checkbox:checkbox:checked').each(function (index, item) {
            vals.push($(this).data('id'));
        });
        if(vals.length){
            if (confirm('確定要刪除嗎?')){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/manage_account_edit')!!}',
                    data:{'vals':vals},
                    dataType:'json',
                    success:function(data){
                        // console.log(data);
                        search_manage_accounts();
                        if(data.message) alert(data.message)
                    },
                    error:function(){
                        console.log('error');
                    }
                })
            }
        }else{
            alert('請勾選要刪除的使用者');
        }
    }
    $(document).ready(function(){
        $(document).on('change','#search_val',function(){
            search_manage_accounts()
        });
        $(document).on('click','.checked_all',function(){
            var a = $(this).prop("checked");
            if($(this).prop("checked")){
                $('.calendar_member_checkbox').prop("checked", true);
            }else{
                $('.calendar_member_checkbox').prop("checked", false);
            }
        });
        $(document).on('click','#manage_account_delete',function(){
            manage_account_delete()
        });
    })
</script>

@endsection