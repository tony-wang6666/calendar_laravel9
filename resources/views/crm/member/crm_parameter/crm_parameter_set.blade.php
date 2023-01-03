@extends('layouts.layout_4')
@section('title',$title)
@section('content')

<div class="w-288 mx-auto pt-8">
    <div class="bg-blue-400 p-2 px-8 rounded-t-xl font-bold flex items-center text-white text-2xl">
        <i class="material-icons">settings</i>
        <span class="text-2xl font-bold">{{$title}}</span> 
    </div>
    <div class="bg-gray-300 p-2 flex items-center shadow">
        <div class='mr-auto'>
            <span id="parameter_len" class="text-xl"></span>
        </div>
        <div class="">
            <input id='parameter_type' type="hidden" name="" value="{{$select}}">
            <button type='button' data-toggle='mymodal' data-target='#parameterEditModal' class="parameter_edit_data bg-green-500 hover:bg-green-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl">新增</a>
            <button type='button' id="parameter_delete" data-type='delete' class="bg-red-500 hover:bg-red-400 text-white font-bold m-2 py-2 px-4 border-b-4 border-r-4 border-red-600 hover:border-red-500 rounded shadow-xl">刪除</a>
        </div>
    </div>
    <div class="">
        <table id='parameter_set_table' class="shadow-lg bg-whtie mx-auto text-center text-lg w-full">
            <thead class="bg-blue-600 text-white ">
                <tr>
                    <th class='border p-2 w-16'><input type='checkbox' class='checked_all w-6 h-6'></th>
                    <th class="border p-2 w-20">順序</th>
                    <th class="border p-2 ">項目</th>
                    <th class="border p-2 w-32">狀態</th>
                    <th class="border p-2 w-24"></th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-blue-200 h-16">
                    <td class='border p-2'><input type='checkbox' class='checked_all w-6 h-6'></td>
                    <td class="border p-2">...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">讀取中...</td>
                    <td class="border p-2">
                        <button type='button' class='parameter_edit bg-yellow-500 text-white p-2 hover:bg-yellow-600'>編輯</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
@include('crm.member.crm_parameter.modal_parameter_set')
<!-- /Modal -->
<script>
    function parameter_data(){
        var select = $("#parameter_type").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/parameter_data')!!}',
            data:{'select':select},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#parameter_set_table").html(data.parameter_set_table)
                $("#parameter_len").html(data.parameter_len)
                
                // visit_record();
            },
            error:function(){
                $("#parameter_set_table").html("<p class='text-xl'>ERROR</p>")
                console.log('error');
            }
        })
    }
    parameter_data()
    var checked_name = 'parameter_checkbox';
    function parameter_delete(){
        var parameter_type = $("#parameter_type").val()
        var vals = [];
        $('.'+checked_name+':checkbox:checked').each(function (index, item) {
            vals.push($(this).data('id'));
        });
        if(vals.length){
            if (confirm('確定要刪除嗎?')){
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('crm/parameter_edit')!!}',
                    data:{'parameter_type':parameter_type,'vals':vals},
                    dataType:'json',
                    success:function(data){
                        // console.log(data);
                        parameter_data();
                    },
                    error:function(){
                        console.log('error');
                    }
                })
            }
        }else{
            alert('請勾選要刪除的參數');
        }
    }
    $(document).ready(function(){
        $(document).on('click','#parameter_delete',function(){
            parameter_delete()
            // parameter_data()
        });
        $(document).on('click','.checked_all',function(){
            var a = $(this).prop("checked");
            if($(this).prop("checked")){
                $('.'+checked_name).prop("checked", true);
            }else{
                $('.'+checked_name).prop("checked", false);
            }
        });
    })
</script>

@endsection