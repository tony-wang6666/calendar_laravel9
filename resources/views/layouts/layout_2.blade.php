<!doctype html>
<head>
    <!-- ... --->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- JS -->
    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- 下拉清單 查詢 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <!-- Style --->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- tailwind CSS --->
    <link href="{{ asset('css/mycss.css') }}" rel="stylesheet"> <!-- mycss --->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- icon --->

    <title>編輯</title>
</head>
            
<body class='min-h-screen bg-gray-100'>
    <nav class='flex relative z-40 bg-white flex-wrap px-5 py-2 w-full top-0 opacity-90 '>
        @if(Session::get('member_id'))
            <a href='@if(!empty($a_back_url)){{$a_back_url}}@else javascript:history.go(-1)@endif' class="p-2 hover:bg-gray-100 mr-auto">
                <i class="material-icons">clear</i>
            </a>
            <button class="p-2 hover:bg-gray-100 show-modal">
                <i class="material-icons">edit</i>
            </button>
            <div class="md:inline-block flex rounded-t relative">
                <button class='p-2 ml-auto inline-flex items-center hover:bg-gray-100' data-toggle='mycollapse' data-target="#CollapseMoreTool">
                    <i class="material-icons">more_vert</i>
                </button>
                <div class="max-h-0 overflow-hidden bg-white shadow rounded flex-1 rounded-b w-32 absolute top-10 right-0 " id='CollapseMoreTool'>
                    <button id='btn_change_view' class='text-black p-2 hover:bg-gray-300 rounded-tr flex w-full' ><i class='material-icons'>receipt_long</i>工作單</button>
                    <button id='btn_print' class='text-black p-2 hover:bg-gray-300 rounded-tr flex w-full' data-toggle='mycollapse' data-target="#CollapseMoreTool"><i class='material-icons'>print</i>列印</button>
                    <button id='btn_print2' class='text-black p-2 hover:bg-gray-300 rounded-tr flex w-full' data-toggle='mycollapse' data-target="#CollapseMoreTool"><i class='material-icons'>print</i>列印(簡易)</button>
                    <button href="#" onclick="javascript:window.location.reload()" class="text-black p-2 hover:bg-gray-300 rounded-tr flex w-full"><i class="material-icons">refresh</i> 重新整理</button>
                    <!-- <a href="@if(!empty($a_delete)) {{$a_delete}} @endif" class="block text-black p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">delete</i> 刪除行程</a> -->
                    <button type='button' data-toggle='mymodal' data-target='#parameterEditModal' class="text-black p-2 hover:bg-gray-300 rounded-tr flex w-full"><i class="material-icons">delete</i> 刪除行程</button>
                        
                </div>
            </div>
        @else
            <div class='top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto' id='navigation'>
                
            </div>
            <div href="#" class='p-2 mr-auto inline-flex items-center'>
                <span class='select-none'>登入</span>
            </div>
        @endif
    </nav>
    <!-- modal delete calendar -->
    <div id="parameterEditModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
        <div class="p-1 max-w-sm mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
            <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
                data-dismiss="mymodal" data-label="#parameterEditModal">
                <span class="material-icons">clear</span>
            </div>

            <div class="shadow w-full rounded-lg bg-white overflow-hidden block">
                <h2 class="font-bold text-2xl mb-6 text-gray-800 border-b py-2 text-center">刪除行程</h2>
                <div class="p-2 text-2xl">
                    <div class="w-full ">
                        <form action="{{url('member/detail_edit_delete')}}" method="post">
                            @csrf
                            <input type="hidden" name="id" value='{{$DB_edit_data[0]->id}}'>
                            @if($repeat_group)
                            <div class="flex items-center justify-start mb-6">
                                <input type="radio" id="radio1" name="delete_type" value="1" class="px-2 h-6 w-6" checked>
                                <label for="radio1" class="mx-2 px-2 block text-gray-700 font-bold md:text-right pr-4">刪除此行程</label>
                            </div>
                            <div class="flex items-center justify-start mb-6">
                                <input type="radio" id="radio2" name="delete_type" value="2" class="px-2 h-6 w-6" >
                                <label for="radio2" class="mx-2 px-2 block text-gray-700 font-bold md:text-right pr-4">刪除此行程和後續行程</label>
                            </div>
                            <div class="flex items-center justify-start mb-6">
                                <input type="radio" id="radio3" name="delete_type" value="3" class="px-2 h-6 w-6" >
                                <label for="radio3" class="mx-2 px-2 block text-gray-700 font-bold md:text-right pr-4">刪除全部行程</label>
                            </div>
                            @else
                            <div class="flex items-center justify-center mb-6">
                                <label class="mx-2 px-2 block text-gray-700 font-bold md:text-right pr-4">確定要刪除嗎？</label>
                            </div>
                            @endif
                            <div class="flex items-center justify-end">
                                <!-- <div class="md:w-1/3"></div> -->
                                <div class="">
                                    <button type="submit" class="shadow bg-red-500 hover:bg-red-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                        刪除
                                    </button>
                                    <button data-dismiss="mymodal" data-label="#parameterEditModal" class="shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="button">
                                        取消
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <main class="" id='ttst'>
        @yield('content')
        @if (Session::get('message'))
            <script>alert("{{Session::get('message')}}");</script>
            {{ Session::forget('message') }}
        @endif
    </main>

    <script src="{{ asset('js/myjavascript.js') }}"></script>
</body>

<script>
    $(document).ready(function(){
        function btn_pirnt(){
            var newstr = document.getElementsByTagName("main")[0].innerHTML; //列印範圍。
            var oldstr = document.body.innerHTML //原來body中的內容。
            document.body.innerHTML =newstr //用將要列印的內容替換原來body中的內容。
            setTimeout(function(){
                window.print() //開始列印。
                document.body.innerHTML=oldstr //再將原來body中的內容還原。
            },1000);
        };
        $(document).on('click','#btn_print',function(){
            btn_pirnt();
        });
        function btn_pirnt2(){
            var oldstr = document.body.innerHTML //原來body中的內容。
            $("#calendar_print2").removeClass("hidden"); //簡易列印介面
            $("#calendar_detail").addClass("hidden"); //行程編輯介面
            var newstr = document.getElementsByTagName("main")[0].innerHTML; //列印範圍。
            document.body.innerHTML =newstr //用將要列印的內容替換原來body中的內容。
            setTimeout(function(){
                window.print() //開始列印。
                document.body.innerHTML=oldstr //再將原來body中的內容還原。
                $("#calendar_print2").addClass("hidden"); //簡易列印介面
                $("#calendar_detail").removeClass("hidden"); //行程編輯介面
            },1000);
        };
        $(document).on('click','#btn_print2',function(){
            btn_pirnt2();
        });
    })
</script>