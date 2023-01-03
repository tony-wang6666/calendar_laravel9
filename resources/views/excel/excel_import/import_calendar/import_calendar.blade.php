@extends('layouts.layout_3')
@section('title','匯入')
@section('content')
<!-- <div class="flex items-center justify-center">
    <div class="w-96">
        <form id="form" class="bg-white shadow-md rounded px-4 pt-6 pb-8 mb-4 mt-4" method='POST' action="{{url('member/calendar_import_ics')}}" enctype="multipart/form-data">
            @csrf
            <h1 class="block text-gray-700 font-bold mb-2 text-3xl text-center ">GOOGLE 行程匯入(ics)</h1>
            <div class="mb-4 pt-2">

            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                GOOGLE(ics) 匯入  (請選擇上傳的ics)
            </label>

            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="import_file" type="file" >
            </div>
            <div class="flex items-center justify-between">
                <button id="submit"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    上傳
                </button>
            </div>
        </form>
    </div>
</div> -->
<div class=" flex items-center justify-center ">
    <div class="bg-white shadow-md rounded px-4 pt-6 pb-8 mb-4 mt-4 w-4/5">
        <h1 class="block text-gray-700 font-bold mb-2 text-3xl text-center ">行程匯出EXCEL</h1>
        <div class=" flex items-center justify-center">
            <a href="{{url('member/calendar_export')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                匯出歷史行程
            </a>
        </div>
        <div class='my-2'>
            <hr class='border-2 border-black'>
        </div>
        <div class="mb-4">
            <form class="bg-white shadow-md rounded px-4 pt-6 pb-8 mb-4 mt-4" method='POST' action="{{url('member/calendar_delete_recored')}}" onsubmit="return confirm('確定要刪除行程嗎？');" >
                @csrf
                <label class="block text-red-700 text-lg font-bold mb-2" for="name">
                    刪除歷史行程年份
                </label>
                <select name="record_year" required class="block appearance-none w-full border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700">
                    <option value="">請選擇年</option>
                    {!!$option_year!!}
                </select>
                
                <button id="submit"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    刪除
                </button>
            </form>
        </div>
    </div>
</div>
<div class=" flex items-center justify-center">
    <form id="form" class="bg-white shadow-md rounded px-4 pt-6 pb-8 mb-4 mt-4" method='POST' action="{{url('member/calendar_import')}}" enctype="multipart/form-data">
        <h1 class="block text-gray-700 font-bold mb-2 text-3xl text-center ">行程匯入EXCEL</h1>
        <br>
        @csrf
        <!-- <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Nombre
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="name" id="name" type="text" placeholder="Ingresa tu nombre" required>
        </div>
         -->
        <div class="mb-4">
            <div class="text-center text-2xl py-2">
                <span>範例</span>
                <span><a href="{{url('example/excel/行程匯入範例.xlsx')}}" class="text-blue-600 hover:text-blue-800 hover:underline">下載</a></span>
            </div>
            <table class="table-auto">
                <thead>
                    <tr>
                        <th class='border-2 border-gray-500' style="white-space: nowrap; text-overflow:ellipsis; overflow: hidden; max-width:1px;">通報單位</th>
                        <th class='border-2 border-gray-500'>通報樣態</th>
                        <th class='border-2 border-gray-500'>標題</th>
                        <th class='border-2 border-gray-500'>開始日期</th> 
                        <th class='border-2 border-gray-500'>開始時間</th> 
                        <th class='border-2 border-gray-500'>結束日期</th> 
                        <th class='border-2 border-gray-500'>結束時間</th> 
                        <th class='border-2 border-gray-500'>是否整天</th> 
                        <th class='border-2 border-gray-500'>內容</th> 
                        <th class='border-2 border-gray-500'>地點</th> 
                        <th class='border-2 border-gray-500'>處理情形</th> 
                        <th class='border-2 border-gray-500'>備註</th> 
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-center border-2 border-gray-500'>範例單位1</td>	
                        <td class='text-center border-2 border-gray-500'>訂婚</td>
                        <td class='text-center border-2 border-gray-500'>XX訂婚</td>
                        <td class='text-center border-2 border-gray-500'>2021/3/3</td>	
                        <td class='text-center border-2 border-gray-500'>11:30</td>
                        <td class='text-center border-2 border-gray-500'>2021/3/3</td>	
                        <td class='text-center border-2 border-gray-500'>12:00</td>
                        <td class='text-center border-2 border-gray-500'>否</td>
                        <td class='text-center border-2 border-gray-500'>流程…</td>
                        <td class='text-center border-2 border-gray-500'>台灣</td>
                        <td class='text-center border-2 border-gray-500'>花籃,未訂</td>	
                        <td class='text-center border-2 border-gray-500'>備註1</td>	
                    </tr>
                    <tr class="bg-emerald-200">
                        <td class='text-center border-2 border-gray-500'>範例單位2</td>	
                        <td class='text-center border-2 border-gray-500'>康復</td>
                        <td class='text-center border-2 border-gray-500'>XX流感康復</td>	
                        <td class='text-center border-2 border-gray-500'>2021/3/4</td>	
                        <td class='text-center border-2 border-gray-500'>15:00</td>
                        <td class='text-center border-2 border-gray-500'>2021/3/4</td>	
                        <td class='text-center border-2 border-gray-500'>17:00</td>
                        <td class='text-center border-2 border-gray-500'>否</td>
                        <td class='text-center border-2 border-gray-500'>祝福…</td>
                        <td class='text-center border-2 border-gray-500'>台灣</td>
                        <td class='text-center border-2 border-gray-500'>花籃,叫送</td>	
                        <td class='text-center border-2 border-gray-500'>備註2</td>	
                    </tr>
                    <tr>
                        <td class='text-center border-2 border-gray-500'>範例單位3</td>
                        <td class='text-center border-2 border-gray-500'>紀念</td>
                        <td class='text-center border-2 border-gray-500'>XX滿60周年</td>
                        <td class='text-center border-2 border-gray-500'>2021/3/5</td>
                        <td class='text-center border-2 border-gray-500'>07:00</td>
                        <td class='text-center border-2 border-gray-500'>2021/3/6</td>
                        <td class='text-center border-2 border-gray-500'>08:00</td>
                        <td class='text-center border-2 border-gray-500'>是</td>
                        <td class='text-center border-2 border-gray-500'>慶祝活動…</td>
                        <td class='text-center border-2 border-gray-500'>台灣</td>
                        <td class='text-center border-2 border-gray-500'>花籃,送達</td>
                        <td class='text-center border-2 border-gray-500'>備註3</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class='my-2'>
            <hr class='border-2 border-black'>
        </div>
        <div class="mb-4 pt-2">

            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                EXCEL 匯入  (請選擇上傳的EXCEL)
            </label>
            
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="import_file" type="file" >

        </div>

        
        <div class="flex items-center justify-between">
            <button id="submit"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                上傳
            </button>
        </div>

        <div class="mb-4">


    </form>
        
</div>

<!-- Modal -->
<!-- /Modal -->

@endsection