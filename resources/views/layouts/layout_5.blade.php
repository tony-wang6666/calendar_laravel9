<!doctype html>
<head>
    <!-- ... --->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <!-- JS -->
    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Style --->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- tailwind CSS --->
    <link href="{{ asset('css/mycss.css') }}" rel="stylesheet"> <!-- mycss --->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- icon --->

</head>
            
<body class='min-h-screen bg-gray-100'>
    <nav class='flex relative z-40 bg-white flex-wrap px-5 py-2 w-full top-0 opacity-90 font-black'>
        @if(Session::get('member_id'))
            <button class="text-dark inline-flex p-2 hover:bg-gray-400 rounded lg:hidden nav-toggler" data-target="#navigation">
                <i class="material-icons md-24">menu</i>
            </button>
            <!-- <a href="{{url('member/calendar_import')}}" class="lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400">
                匯入
            </a>
            <a href="{{url('member/set')}}" class="lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400">
                設定
            </a> -->
            <form action="{{url('member/search_calendar_post')}}" method="post">
                @csrf
                <div class="flex items-center bg-gray-200 rounded-lg">
                    <div class="p-2 border-2 border-gray-200 rounded-l-lg">
                        <button type ="submit" class="flex items-center">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                    
                    <select name="search_type" class="block bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" name='case_level'>
                        <option value="無" @if($search_type == '無') selected @endif>無</option>
                        <option value="標題" @if($search_type == '標題') selected @endif>標題</option>
                        <option value="內容" @if($search_type == '內容') selected @endif>內容</option>
                        <option value="地點" @if($search_type == '地點') selected @endif>地點</option>
                        <option value="備註" @if($search_type == '備註') selected @endif>備註</option>
                    </select>
                    <input type="text" name="search_value" id="" value="@if(!empty($search_value)){{$search_value}}@endif" placeholder="請輸入查詢" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                </div>
            </form>
            <div class="w-16">
                <!-- <input type="number" name="repeat_number" value="5" min="1" max="365" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full pl-12 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500"> -->
                    <!-- <input type="text" name="price" id="price" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00"> -->
                    
                    <!-- <div class="absolute inset-y-0 left-24 flex items-center">
                        <span class="text-gray-500 font-bold">
                            次
                        </span>
                    </div> -->
            </div>
            <div class='mr-auto'></div>
            <div class='hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto' id='navigation'>
                <div class='text-lg lg:inline-flex lg:ml-auto lg:flex-row flex flex-col'> <!-- lg:flex-row -->
                    <a href="{{url('member/first_page')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>今日行程</span>
                    </a>
                    <a href="{{url('member/home')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>行程表</span>
                    </a>
                    <a href="{{url('member/calendar_day')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>天</span>
                    </a>
                    <a href="{{url('member/calendar_month')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>月</span>
                    </a>
                    <a href="{{url('member/things_list')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>處理清單</span>
                    </a>
                </div>
            </div>
            <!-- <div class="md:inline-block flex hover:bg-gray-100 rounded-t relative">
                <button class='p-2 ml-auto inline-flex items-center' data-toggle='mycollapse' data-target="#CollapseMoreTool">
                    <span class="material-icons">more_vert</span>
                </button>
                <div class="max-h-0 overflow-hidden bg-white shadow rounded flex-1 rounded-b w-32 absolute top-10 right-0 " id='CollapseMoreTool'>
                    <a href="" class="block text-black p-2 hover:bg-gray-300 rounded-tr flex"><i class="material-icons">refresh</i>重新整理</a>
                    <a href="{{url('logout')}}" class="block text-black p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">logout</i>登出</a>
                </div>
            </div> -->
        @else
            <div class='top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto' id='navigation'>
                
            </div>
            <div href="#" class='p-2 mr-auto inline-flex items-center'>
                <span class='select-none'>登入</span>
            </div>
        @endif
    </nav>
    
    <main class=" ">
        @yield('content')
        
        @if (Session::get('message'))
            <script>alert("{{Session::get('message')}}");</script>
            {{ Session::forget('message') }}
        @endif
    </main>

    <script src="{{ asset('js/myjavascript.js') }}"></script>
</body>

<script>
    // $(document).ready(function(){
    // })
</script>