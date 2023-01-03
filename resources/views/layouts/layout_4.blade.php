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

    <!--chart 圖表 -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!--google chart 圖表 -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    

</head>
            
<body class='min-h-screen bg-gray-100'>
    <nav class='flex relative z-40 bg-yellow-400 flex-wrap px-5 py-2 w-full top-0 opacity-90 font-black'>
        @if(Session::get('member_id'))
            <button class="text-dark inline-flex p-2 hover:bg-gray-400 rounded lg:hidden nav-toggler" data-target="#navigation">
                <i class="material-icons md-24">menu</i>
            </button>
            <a href="{{url('member/first_page')}}" class="lg:inline-flex lg:w-auto mx-1 px-5 py-2 rounded text-blue-800 hover:text-white hover:bg-blue-700">
                行程
            </a>
            <!-- <a href="{{url('member/set')}}" class="lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400">
                其他
            </a> -->
            <div class='mr-auto'></div>
            <div class='hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto' id='navigation'>
                <div class='text-lg lg:inline-flex lg:ml-auto lg:flex-row flex flex-col'> <!-- lg:flex-row -->
                    @if ( in_array('B', Session::get('member_authority')) )
                    <button class='h-12 font-bold lg:inline-flex lg:w-auto px-5 py-2 rounded text-black hover:text-white hover:bg-gray-400' data-toggle='mycollapse' data-target="#CollapseCustomerManagement">
                        <span>客戶管理</span>
                    </button>
                    <div class="inline-block flex hover:bg-gray-100 rounded-t relative">
                        <div class="max-h-0 overflow-hidden bg-gray-500 text-white shadow rounded flex-1 rounded-b w-max lg:absolute lg:top-12 right-0 " id='CollapseCustomerManagement'>
                            <a href="{{url('crm/basic_customer_data')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">person_search</i>客戶查詢</a>
                            <a href="{{url('crm/create_customer_data')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">person_add</i>新增客戶</a>
                            <a href="{{url('crm/vip_management')}}" class="block p-2 hover:bg-gray-300 rounded-tr flex"><i class="material-icons">grade</i>VIP管理</a>
                            <!-- <a href="#" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">logout</i>登出</a> -->
                        </div>
                    </div>
                    @endif
                    @if ( in_array('C', Session::get('member_authority')) )
                    <button class='h-12 font-bold lg:inline-flex lg:w-auto px-5 py-2 rounded text-black hover:text-white hover:bg-gray-400' data-toggle='mycollapse' data-target="#CollapseVisitManagement">
                        <span>拜訪紀錄</span>
                    </button>
                    <div class="inline-block flex hover:bg-gray-100 rounded-t relative">
                        <div class="max-h-0 overflow-hidden bg-gray-500 text-white shadow rounded flex-1 rounded-b w-max lg:absolute lg:top-12 right-0 " id='CollapseVisitManagement'>
                            <a href="{{url('crm/visit_records_manage')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">feed</i>拜訪記錄管理</a>
                            <a href="{{url('crm/visit_records_manage_add')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">note_add</i>新增拜訪記錄</a>
                        </div>
                    </div>
                    @endif
                    @if ( in_array('F', Session::get('member_authority')) )
                    <button class='h-12 font-bold lg:inline-flex lg:w-auto px-5 py-2 rounded text-black hover:text-white hover:bg-gray-400' data-toggle='mycollapse' data-target="#CollapseDataManagement">
                        <span>資料管理</span>
                    </button>
                    <div class="inline-block flex hover:bg-gray-100 rounded-t relative">
                        <div class="max-h-0 overflow-hidden bg-gray-500 text-white shadow rounded flex-1 rounded-b w-max lg:absolute lg:top-12 right-0 " id='CollapseDataManagement'>
                            <a href="{{url('crm/account_balance_import_check')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">cloud_upload</i>每月餘額轉入</a>
                            <a href="{{url('crm/change_customer_import_check')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">cloud_upload</i>每日大額轉入</a>
                            <a href="{{url('crm/contribution_import_check')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">cloud_upload</i>每月貢獻度轉入</a>
                            <a href="{{url('crm/insurance_information_import_check')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">cloud_upload</i>每月保險資訊轉入</a>
                            <a href="{{url('crm/basic_customer_data_import_check')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">cloud_upload</i>客戶資料匯入</a>
                        </div>
                    </div>
                    @endif
                    @if ( in_array('D', Session::get('member_authority')) )
                    <button class='h-12 font-bold lg:inline-flex lg:w-auto px-5 py-2 rounded text-black hover:text-white hover:bg-gray-400' data-toggle='mycollapse' data-target="#CollapseParameterManagement">
                        <span>參數管理</span>
                    </button>
                    <div class="inline-block flex hover:bg-gray-100 rounded-t relative">
                        <div class="max-h-0 overflow-hidden bg-gray-500 text-white shadow rounded flex-1 rounded-b w-max lg:absolute lg:top-12 right-0 " id='CollapseParameterManagement'>
                            <a href="{{url('crm/parameter_set/customer_type')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶類型</a>
                            <a href="{{url('crm/parameter_set/customer_disposition')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶性格</a>
                            <a href="{{url('crm/parameter_set/customer_interest')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶興趣</a>
                            <a href="{{url('crm/parameter_set/customer_prefer_invest')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶偏好投資</a>
                            <a href="{{url('crm/parameter_set/customer_response_attitude')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶應對態度</a>
                            <a href="{{url('crm/parameter_set/customer_relationship')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶親屬關係</a>
                            <a href="{{url('crm/parameter_set/customer_religion')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶宗教信仰</a>
                            <a href="{{url('crm/parameter_set/customer_visitable_time')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>客戶拜訪時間</a>
                            <a href="{{url('crm/parameter_set/visit_follow_phrase')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>後續追蹤片語</a>
                            <a href="{{url('crm/parameter_set/visit_supervisor_suggest_phrase')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">settings</i>主管簽核片語</a>
                        </div>
                    </div>
                    @endif
                    @if ( in_array('E', Session::get('member_authority')) )
                    <button class='h-12 font-bold lg:inline-flex lg:w-auto px-5 py-2 rounded text-black hover:text-white hover:bg-gray-400' data-toggle='mycollapse' data-target="#CollapseSystemManagement">
                        <span>系統管理</span>
                    </button>
                    <div class="inline-block flex hover:bg-gray-100 rounded-t relative">
                        <div class="max-h-0 overflow-hidden bg-gray-500 text-white shadow rounded flex-1 rounded-b w-max lg:absolute lg:top-12 right-0 " id='CollapseSystemManagement'>
                            <a href="{{url('crm/manage_accounts')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">manage_accounts</i>使用者管理</a>
                            <a href="{{url('crm/change_customer_ao_staff_record')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">manage_accounts</i>客戶AO異動</a>
                            <a href="{{url('crm/database_backup')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">storage</i>資料庫備份</a>
                            <a href="{{url('crm/database_restore')}}" class="block p-2 hover:bg-gray-300 rounded-b z-auto flex"><i class="material-icons">restore</i>資料庫還原</a>
                        </div>
                    </div>
                    @endif
                    <!-- <a href="{{url('crm/customer_family')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>同戶親屬</span>
                    </a>
                    <a href="{{url('crm/visit_record')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>拜訪紀錄</span>
                    </a>
                    <a href="{{url('crm/account_balance')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>帳戶餘額</span>
                    </a>
                    <a href="{{url('crm/change_customer')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>大額異動</span>
                    </a>
                    <a href="{{url('crm/contribution')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>貢獻度</span>
                    </a>
                    <a href="{{url('crm/insurance_information')}}" class='lg:inline-flex lg:w-auto px-5 py-2 rounded text-black-400 hover:text-white hover:bg-gray-400'>
                        <span>保險資訊</span>
                    </a> -->
                </div>
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