<!doctype html>
<head>
    <!-- ... --->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- JS -->
    <script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- <script src="https://apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script> -->
    <!-- <script src="https://apps.bdimg.com/libs/jquerymobile/1.4.5/jquery.mobile-1.4.5.min.js"></script> -->
  
    <!-- 下拉清單 查詢 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />

    <!-- Style --->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- tailwind CSS --->
    <link href="{{ asset('css/mycss.css') }}" rel="stylesheet"> <!-- mycss --->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- icon --->

    <title>行事曆</title>
</head>

<body class='min-h-screen bg-gray-100'>
    <!-- <nav class='relative z-40 bg-white flex flex-wrap px-3 py-2 w-full top-0 opacity-90 max-h-16'> -->
    <div class="pb-16">
    <nav class='fixed bg-white z-40 w-full px-3 py-2 flex flex-wrap justify-between items-center opacity-80 font-black'>
        @if(Session::get('member_id'))
            <!-- <div class='hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto' id='navigation'>
                <a href="#" class='p-2 mr-4 inline-flex items-center'>
                    <div class='fill-current text-black h-8 w-8 mr-2' >
                        <i class="material-icons md-36" >list_alt</i>
                    </div>
                    <span class='text-xl text-dark font-bold uppercase tracking-wide'>date</span>
                </a>
            </div> -->
            <button class="text-dark inline-flex p-2 hover:bg-gray-400 rounded lg:hidden nav-toggler" data-target="#navigation">
                <i class="material-icons md-24">menu</i>
            </button>
            
            <a href='@if(!empty($a_last)) {{$a_last}} @endif' class="py-2 px-1 hover:bg-gray-100">
                <span class="material-icons">arrow_back_ios_new</span>
            </a>
            <a href='@if(!empty($a_next)) {{$a_next}} @endif' class="py-2 px-1 hover:bg-gray-100">
                <span class="material-icons">arrow_forward_ios</span>
            </a>
            <a href="#" class='py-2 inline-flex items-center relative hover:bg-gray-100'>
                <div class="">
                    <div class="">
                        <!-- <span class="material-icons">expand_less</span> -->
                        <!-- <span class="material-icons">expand_more</span> -->
                    </div>
                    <!-- <label data-domain="月" class='mydatelabel z-10' for='#mydate'></label> -->
                    <input type="date" id='mydate' class='w-auto font-black bg-white hover:bg-gray-100' value='@if(!empty($this_date)){{$this_date}}@endif'>
                </div>
            </a>
            
            <a href="{{url('member/search_calendar')}}" class='p-2 ml-auto inline-flex hover:bg-gray-100'>
                <span class="material-icons">search</span>
            </a>
            <a href="@if(!empty($a_today)) {{$a_today}} @endif" class='p-2 inline-flex hover:bg-gray-100'>
                <span class="material-icons">today</span>
            </a>
            
            <div class="inline-block flex hover:bg-gray-100 rounded-t relative">
                <button class='p-2 ml-auto inline-flex items-center' data-toggle='mycollapse' data-target="#CollapseMoreTool">
                    <span class="material-icons">more_vert</span>
                </button>
                <div class="max-h-0 overflow-hidden bg-white shadow rounded flex-1 rounded-b w-40 absolute top-10 right-0 " id='CollapseMoreTool'>
                    <a href="{{url('member/personalSet')}}" class="block text-black p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">manage_accounts</i>{{Session::get('member_name')}}({{Session::get('member_id')}})</a>
                    <a href="{{url('crm')}}" class="block text-black p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">groups</i>客戶管理</a>
                    <a href="{{url('member/calendar_import')}}" class="block text-black p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>其他</a>
                    <a href="" class="block text-black p-2 hover:bg-gray-300 rounded-tr flex"><i class="material-icons">refresh</i>重新整理</a>
                    <a href="{{url('logout')}}" class="block text-black p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">logout</i>登出</a>
                </div>
            </div>
            
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
                    <!-- <select name="" id="" onchange="location = this.value;" class='outline-none bg-grey-lighter border border-grey-lighter text-grey-darker py-2 px-2 rounded'>
                        <option value="{{url('member/home')}}" @if(!empty($state) && $state=='時間表')selected @endif >行程表</option>
                        <option value="{{url('member/calendar_day')}}" @if(!empty($state) && $state=='天')selected @endif >天</option>
                        <option value="">3天</option>
                        <option value="">週</option>
                        <option value="{{url('member/calendar_month')}}" @if(!empty($state) && $state=='月')selected @endif>月</option>
                    </select> -->
                </div>
            </div>
            <button id='btn_add_case' class="show-modal fixed z-30 bg-green-800 bottom-8 right-8 w-10 h-10 rounded-full flex items-center justify-center focus:outline-none hover:bg-green-900">
                <i class="material-icons text-white">add</i>
            </button>
        @else
            <div class='top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto ' id='navigation'>
                
            </div>
            <div href="#" class='p-2 mr-auto inline-flex items-center'>
                <span class='select-none'>登入</span>
            </div>
        @endif
    </nav>
    </div>
    <main class="">
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
        $(document).on('click','#btn_add_case',function(){
            // var date = $('#mydate').val();
            //取得網址參數
            // var url_string = window.location.href; 
            // var url = new URL(url_string);
            // var year = url.searchParams.get("year");
            // var month = url.searchParams.get("month")
            // var day = url.searchParams.get("day")
            // if(year && month && day){ //現在時間
            //     var date = year+'-'+month+'-'+day;
            // }else{
            // }
             //今天
            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var dd = String(today.getDate()).padStart(2, '0');
            var date = yyyy+'-'+mm+'-'+dd;
            // console.log(date)

            $(".date1,.date2").val(date);
            var dt = new Date();
            if(pad(dt.getMinutes(),2) < 30 ){
                dt.setTime(dt.getTime() - ((dt.getMinutes()-30)*60*1000));
            }else{
                dt.setTime(dt.getTime() - ((dt.getMinutes()-60)*60*1000));
            }
            var time1 = pad(dt.getHours(),2) + ":"+pad(dt.getMinutes(),2)+":00";
            dt.setTime(dt.getTime() + (30*60*1000));
            var time2 = pad(dt.getHours(),2) + ":"+pad(dt.getMinutes(),2)+":00";
            $(".time1").val(time1);
            $(".time2").val(time2);
        });

    })
</script>