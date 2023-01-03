@extends('layouts.layout_3')
@section('title','設定')
@section('content')
<div class=" flex items-center justify-center">
    <div class="bg-white shadow-md rounded px-4 pt-6 pb-8 mb-4 mt-4 w-96">
        <h1 class="block text-gray-700 font-bold mb-2 text-3xl text-center ">設定</h1>
        <br>
        <div class="mb-4">
            <div class="text-center text-2xl py-2">
            <button id='btn_add_case' class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full" 
                data-toggle="mymodal" data-target="#informantSetModal">
                通報單位設定
            </button>
            </div>
        </div>
        <div class='my-2'>
            <hr class='border-2 border-black'>
        </div>
    </div>
</div>

<!-- Modal -->
@include('modals.modal_set_informant')
<!-- /Modal -->

@endsection