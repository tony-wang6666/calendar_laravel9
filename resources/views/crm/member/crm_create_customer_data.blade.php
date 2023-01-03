@extends('layouts.layout_4')
@section('title','新增客戶')
@section('content')

<div class="w-288 mx-auto mt-4">
    <div class="bg-blue-400 p-2 rounded-t-lg flex items-center justify-center ">
        <i class="material-icons">person_add</i>
        <span class="text-2xl font-bold ">會員新增</span> 
    </div>


    <div class="bg-blue-200 text-lg font-bold text-center select-none">
        <form action="{{url('crm/create_customer_data')}}" method="post">
            @csrf
            <div class="grid grid-cols-3 gap-4 p-2 ">
                <div class="grid grid-cols-5">
                    <label for="c_id" class="col-span-2  bg-blue-400 mx-2 p-2">會員編號</label> 
                    <input required type="text" id="c_id" name="c_id" value="{{$customer_number}}" class="col-span-3 p-2" placeholder="請輸入編號">
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_name_company" class="col-span-2  bg-blue-400 mx-2 p-2">姓名/公司名</label> 
                    <input required type="text" id="c_name_company" name="c_name_company" value="" class="col-span-3 px-2" placeholder="請輸入姓名或公司名">
                </div>
                <div class="grid grid-cols-5">
                    <label for="identification_gui_number" class="col-span-2  bg-blue-400 mx-2 p-2">身分證/統編</label> 
                    <input required type="text" id="identification_gui_number" name="identification_gui_number" value="" class="col-span-3 px-2" placeholder="請輸入身分證或統編">
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_sex" class="col-span-2  bg-blue-400 mx-2 p-2">性別</label>
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select required id="c_sex" name="c_sex" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="男">男</option>
                            <option value="女">女</option>
                            <option value="無">無</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_birth_opening_date" class="col-span-2  bg-blue-400 mx-2 p-2">生日/開業日</label> 
                    <input required type="date" id="c_birth_opening_date" name="c_birth_opening_date" value="" class="col-span-3 px-2" placeholder="請輸入生日或開業日">
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_type" class="col-span-2  bg-blue-400 mx-2 p-2">客戶種類</label>
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select required id="c_type" name="c_type" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            {!!$customer_types!!}
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_telephone" class="col-span-2  bg-blue-400 mx-2 p-2">電話</label> 
                    <input type="text" id="c_telephone" name="c_telephone" value="" class="col-span-3 px-2" placeholder="請輸入電話">
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_cellphone" class="col-span-2  bg-blue-400 mx-2 p-2">手機</label> 
                    <input type="text" id="c_cellphone" name="c_cellphone" value="" class="col-span-3 px-2" placeholder="請輸入手機">
                </div>
                <div class="grid grid-cols-5">
                    <label for="religion" class="col-span-2  bg-blue-400 mx-2 p-2">宗教</label> 
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select required id="religion" name="religion" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            {!!$religions!!}
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_number" class="col-span-2  bg-blue-400 mx-2 p-2">戶號</label> 
                    <input type="text" id="c_number" name="c_number" value="" class="col-span-3 px-2" placeholder="請輸入戶號">
                </div>
                <div class="col-span-2 grid grid-cols-10 ">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2">地址</label> 
                    <!-- <input type="text" id="" name="" value="" class="col-span-8 px-2"> -->
                    <div class="bg-white col-span-8 flex items-center px-2">
                        <label id="label_postcode" class='mr-2'></label>
                        <input id="input_postcode" type="hidden" name="postcode">
                        <select id="select_city" name="city" class="block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            {!!$option_city!!}
                        </select>
                        <select id="select_city_area" name="city_area" class="block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                        </select>
                        <input type="text" name="address" value="" class="px-2 h-full w-full" placeholder="請輸入地址">
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="open_account" class="col-span-2  bg-blue-400 mx-2 p-2">本會開戶</label> 
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="open_account" name="open_account" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="是">是</option>
                            <option value="否">否</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="farmer_association_member" class="col-span-2  bg-blue-400 mx-2 p-2">農會會員</label> 
                    <!-- <input type="text" id="farmer_association_member" name="farmer_association_member" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="farmer_association_member" name="farmer_association_member" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="贊助會員">贊助會員</option>
                            <option value="非贊助會員">非贊助會員</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="farmer_insurance" class="col-span-2  bg-blue-400 mx-2 p-2">農保</label> 
                    <!-- <input type="text" id="farmer_insurance" name="farmer_insurance" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="farmer_insurance" name="farmer_insurance" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="無">無</option>
                            <option value="有">有</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="health_state" class="col-span-2  bg-blue-400 mx-2 p-2">健康狀況</label> 
                    <!-- <input type="text" id="health_state" name="health_state" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="health_state" name="health_state" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="好">好</option>
                            <option value="正常">正常</option>
                            <option value="不佳">不佳</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="communicate_state" class="col-span-2  bg-blue-400 mx-2 p-2">溝通狀況</label> 
                    <!-- <input type="text" id="communicate_state" name="communicate_state" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="communicate_state" name="communicate_state" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="能">能</option>
                            <option value="不能">不能</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="response_attitude" class="col-span-2  bg-blue-400 mx-2 p-2">回應態度</label> 
                    <!-- <input type="text" id="response_attitude" name="response_attitude" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="response_attitude" name="response_attitude" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            {!!$response_attitudes!!}
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="deposit_level" class="col-span-2  bg-blue-400 mx-2 p-2">存款等級</label> 
                    <!-- <input type="text" id="deposit_level" name="deposit_level" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="deposit_level" name="deposit_level" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="">無</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="loan_level" class="col-span-2  bg-blue-400 mx-2 p-2">貸款等級</label> 
                    <!-- <input type="text" id="loan_level" name="loan_level" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="loan_level" name="loan_level" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="">無</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_bank" class="col-span-2  bg-blue-400 mx-2 p-2">主要往來銀行</label> 
                    <input type="text" id="c_bank" name="c_bank" value="" class="col-span-3 px-2" placeholder="請輸入銀行">
                </div>
                <div class="grid grid-cols-5">
                    <label for="vip_cyear" class="col-span-2  bg-blue-400 mx-2 p-2">VIP年度</label> 
                    <input type="text" id="vip_cyear" name="vip_cyear" value="" class="col-span-3 px-2" placeholder="請輸入VIP年度，如:110">
                </div>
                <div class="grid grid-cols-5">
                    <label for="encourage_raise_staff" class="col-span-2  bg-blue-400 mx-2 p-2">勸募員工</label> 
                    <!-- <input type="text" id="" name="" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="encourage_raise_staff" name="encourage_raise_staff" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            {!!$option_encourage_raise_staff!!}
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="ao_staff" class="col-span-2  bg-blue-400 mx-2 p-2">AO人員</label> 
                    <!-- <input type="text" id="" name="" value="" class="col-span-3 px-2"> -->
                    <div class="bg-white col-span-3 flex items-center px-2">
                        <select id="ao_staff" name="ao_staff" class="w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2">
                            <option value="無">無</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-5">
                    <label for="c_source" class="col-span-2 bg-blue-400 mx-2 p-2">資料來源</label> 
                    <input type="text" id="c_source" name="c_source" value="" class="col-span-3 px-2" placeholder="請輸入資料來源">
                </div>
                <div class="col-span-3"></div>
                <div class="col-span-3"></div>
                <div class="col-span-3"></div>
                <div class="grid grid-cols-5">
                    <!-- <label for="transfer_item" class="col-span-2  bg-blue-400 mx-2 p-2">轉繳項目</label> 
                    <input type="text" id="transfer_item" name="transfer_item" value="" class="col-span-3 px-2" placeholder="請輸入轉角項目"> -->
                </div>
                <div class="col-span-2 grid grid-cols-10">
                    <label for="remark" class="col-span-2  bg-blue-400 mx-2 p-2">其他備註</label> 
                    <input type="text" id="remark" name="remark" value="" class="col-span-8 px-2" placeholder="備註...">
                </div>
                
                <div class="col-span-3 grid grid-cols-12">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2 flex items-center justify-center">可拜訪時段</label> 
                    <div class="col-span-10 grid grid-cols-6 px-2 flex items-center ">
                        {!!$visitable_times!!}
                    </div>
                </div>
                <div class="col-span-3 grid grid-cols-12">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2 flex items-center justify-center">性格</label> 
                    <!-- <input type="text" id="" name="" value="" class="col-span-10 px-2"> -->
                    <div class="col-span-10 grid grid-cols-6 px-2 flex items-center ">
                        {!!$dispositions!!}
                    </div>
                </div>
                <div class="col-span-3 grid grid-cols-12">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2 flex items-center justify-center">興趣</label> 
                    <div class="col-span-10 grid grid-cols-6 px-2 flex items-center ">
                        {!!$interests!!}
                    </div>
                </div>
                <div class="col-span-3 grid grid-cols-12">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2 flex items-center justify-center">偏好投資</label> 
                    <div class="col-span-10 grid grid-cols-6 px-2 flex items-center ">
                        {!!$prefer_invests!!}
                    </div>
                </div>
                <div class="col-span-3 grid grid-cols-12">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2 flex items-center justify-center">開放性較高業務</label> 
                    <div class="col-span-10 grid grid-cols-6 px-2 flex items-center ">
                        {!!$openness_high_business!!}
                    </div>
                </div>
                <div class="col-span-3 grid grid-cols-12">
                    <label for="" class="col-span-2  bg-blue-400 mx-2 p-2 flex items-center justify-center">開放性較低業務</label> 
                    <div class="col-span-10 grid grid-cols-6 px-2 flex items-center ">
                        {!!$openness_low_business!!}
                    </div>
                </div>
                <div class="col-span-3 ">
                    <div class="px-2 flex items-center justify-center">
                        <button type="submit" class="mr-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 border border-green-700 rounded">
                            新增會員
                        </button>
                        <!-- <button type="button" class="bg-red-500 hover:bg-red-700  font-bold py-2 px-4 border border-red-700 rounded">
                            清空填寫
                        </button> -->
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<script>
    $(document).ready( function(){
        function getCityArea(){
            var city = $("#select_city").val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/get_city_area')!!}',
                data:{'city':city},
                dataType:'json',
                success:function(data){
                    $("#select_city_area").html(data.option_city_area);
                    getCityAreaPostcode();
                    // console.log('success');
                },
                error:function(){
                    console.log('error');
                }
            })
        }
        function getCityAreaPostcode(){
            var city = $("#select_city").val();
            var city_area = $("#select_city_area").val();
            $.ajax({
                type:'get',
                url:'{!!URL::to('crm/get_city_area')!!}',
                data:{'city':city,'city_area':city_area},
                dataType:'json',
                success:function(data){
                    $("#label_postcode").html(data.postcode);
                    $("#input_postcode").val(data.postcode);
                },
                error:function(){
                    console.log('error');
                }
            })
        }
        getCityArea();
        $(document).on("change","#select_city", function(){
            getCityArea();
        });
        $(document).on("change","#select_city_area", function(){
            getCityAreaPostcode();
        });
    });
</script>
@endsection

            <!-- $.ajax({
                type:'get',
                url:'{!!URL::to('member/calendar_day_dataget')!!}',
                data:{'date':date},
                dataType:'json',
                success:function(data){
                    // console.log('success');
                    // console.log(data);
                    document.getElementById('calendar_day').innerHTML = data.calendar_day; 
                    document.getElementById('calendar_all_day').innerHTML = data.calendar_all_day; 

                },
                error:function(){
                    console.log('error');
                }
            }); -->