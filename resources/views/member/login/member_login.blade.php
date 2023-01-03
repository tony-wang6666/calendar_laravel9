@extends('layouts.layout_1')
@section('content')
<div class='bg-gray-100 container mx-auto px-2 w-full'>
    <div class='grid grid-cols-1 items-center m-auto mt-9 max-w-md mx-auto'>
        <div class="bg-white shadow-md rounded pb-8 mb-4 flex flex-col select-none">
            <!-- <div class="text-center bg-yellow-400 py-6">
                <img src="{{url('images/logo/wuchiLogo.png')}}" width="100px">
                <h3 class='text-2xl font-black'>總幹事行事曆管理系統</h3>
            </div> -->
            <div class="bg-yellow-400 py-6 ">
                <div class='top-navbar w-full inline-flex'>
                    <div class='flex mx-auto'>
                        <div class='fill-current text-black w-32 md:w-36 mr-2' >
                            <!-- <i class="material-icons md-36" >list_alt</i> -->
                            <img src="{{url('images/logo/wuchiLogo.png')}}" >
                        </div>
                        <span class='text-base md:text-xl font-black mt-3'>總幹事行事曆管理系統</span>
                        <!-- <span class='text-xl text-dark font-bold uppercase tracking-wide'>date</span> -->
                    </div>
                    
                </div>
                <div class="text-center">{{$ip_message}}</div>
            </div>
            <form method="POST" action="{{ url('login') }}" class='px-8 pt-6 '>
                @csrf
                <div class="mb-4">
                    <label class="block text-grey-darker text-base font-bold mb-2" for="username">
                        帳號
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 bg-lime-200 font-normal placeholder-black" id="username" name='username' type="text" placeholder="請輸入帳號" required>
                </div>
                <div class="mb-6">
                    <label class="block text-grey-darker text-base font-bold mb-2" for="password">
                        密碼
                    </label>
                    <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 bg-lime-200 font-normal placeholder-black mb-3" id="password" name='password' type="password" placeholder="請輸入密碼" required>
                </div>
                <div class="mb-6 ">
                    <label class="flex items-center space-x-3 w-32">
                        <input type="checkbox" name="keep_login" value="1" class="h-6 w-6 border border-gray-300 rounded-md checked:bg-blue-600 checked:border-transparent focus:outline-none">
                        <span class="text-gray-900 font-medium text-lg">保持登入</span> 
                    </label>
                    <!-- "form-tick appearance-none h-6 w-6 border border-gray-300 rounded-md checked:bg-blue-600 checked:border-transparent focus:outline-none" -->
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded text-lg" type="submit">
                        登入
                    </button>
                    <!-- <a class="inline-block align-baseline font-bold text-sm text-blue hover:text-blue-darker" href="#">
                        Forgot Password?
                    </a> -->
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // $(document).ready(function(){
    //     console.log('5555');
    // })
</script>
@endsection
