
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
@extends('layouts.layout_3')
@section('content')

<div class='bg-gray-100 container mx-auto px-2 w-full'>
    <div class='grid grid-cols-1 items-center m-auto mt-9 max-w-md mx-auto'>
        <div class="bg-white shadow-md rounded pb-8 mb-4 flex flex-col select-none">
            <div class="bg-gray-200 py-6 m-2">
                <div class='top-navbar w-full inline-flex'>
                    <div class='flex mx-auto'>
                        <div class='font-black w-32 md:w-36 text-2xl text-center' >
                            <span class="material-icons">lock</span>修改密碼
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ url('member/personalSetPost') }}" class='px-8 pt-6 '>
                <div class="text-xl text-center text-red-500 font-black">{{$suggest}}</div>
                @csrf
                <div class="mb-4">
                    <label class="block text-grey-darker text-base font-bold mb-2" for="original_pass">
                        目前密碼
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-200 font-normal placeholder-black" id="original_pass" name='original_pass' type="password" placeholder="" required>
                </div>
                <div class="mb-6">
                    <label class="block text-grey-darker text-base font-bold mb-2" for="new_pass">
                        新密碼
                    </label>
                    <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 bg-gray-200 font-normal placeholder-black mb-3" id="new_pass" name='new_pass' type="password" placeholder="" required>
                    <div id='check_remind' class="text-red-500"></div>
                </div>
                <div class="mb-6">
                    <label class="block text-grey-darker text-base font-bold mb-2" for="again_new_pass">
                        再次輸入新密碼
                    </label>
                    <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 bg-gray-200 font-normal placeholder-black mb-3" id="again_new_pass" name='again_new_pass' type="password" placeholder="" required>
                </div>
                <div class="flex items-center justify-between">
                    <button id='btn_save' disabled class="bg-yellow-600 hover:bg-yellow-400 text-white font-bold py-2 px-4 rounded text-lg" type="submit">
                        儲存修改
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->

<script>
    $(document).ready(function(){
        function check_password(new_pass){
            var check_remind = '';
            var pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/;
            if(!pattern.test(new_pass)){
                var pattern_d = /^(?=.*\d)/;
                if(!pattern_d.test(new_pass)) check_remind += '至少1個數字<br>';
                var pattern_a_z = /^(?=.*[a-z])/;
                if(!pattern_a_z.test(new_pass)) check_remind += '至少1個英文小寫<br>';
                var pattern_A_Z = /^(?=.*[A-Z])/;
                if(!pattern_A_Z.test(new_pass)) check_remind += '至少1個英文大寫<br>';
                var pattern_6_16 = /^.{6,16}$/;
                if(!pattern_6_16.test(new_pass)) check_remind += '長度需在6-16之間<br>';
            }
            return check_remind;
        }
        $(document).on('change','#new_pass',function(){
            var new_pass = $(this).val();
            check_remind = check_password(new_pass)
            if(check_remind){
                $("#btn_save").attr("disabled", true);
                $("#btn_save").removeClass("bg-yellow-600 hover:bg-yellow-400").addClass("bg-gray-400");
            }else{
                $("#btn_save").attr("disabled", false);
                $("#btn_save").removeClass("bg-gray-600").addClass("bg-yellow-600 hover:bg-yellow-400");
            }
            $('#check_remind').html(check_remind);
            
            var pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/;
            str = new_pass;
        });
    })
</script>
@endsection
