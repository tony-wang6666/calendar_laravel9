@extends('layouts.layout_4')
@section('title','每月保險資訊轉入')
@section('content')

<div class="w-288 mx-auto mt-4 select-none">
    <div class="bg-blue-400 p-2 rounded-t-lg flex items-center justify-between ">
        <div class="w-16"></div>
        <div class="">
            <i class="material-icons">post_add</i>
            <span class="text-2xl font-bold">每月保險資訊轉入</span> 
        </div>
        
        <!-- <div class="mr-auto"></div> -->
        <a href="{{url('crm/insurance_information_import_check')}}" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 border-gray-600 hover:border-gray-500 rounded shadow-xl">回列表</a>
    </div>
    
    <div class="bg-blue-200 text-lg font-bold text-center select-none">
        <form action="{{url('crm/insurance_information_import')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-center">
                <div class="m-2">
                    每月保險資訊EXCEL檔案
                </div>
                <div class="m-2">
                    <input type="file" name="excel_file" id="">
                </div>
            </div>
            <div class="m-2">
                <input type="submit" value='轉入資料' class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-4 border-b-4 border-r-4 border-green-600 hover:border-green-500 rounded shadow-xl" >
            </div>
        </form>
    </div>

</div>

<script>
    $(document).ready( function(){
    });
</script>
@endsection