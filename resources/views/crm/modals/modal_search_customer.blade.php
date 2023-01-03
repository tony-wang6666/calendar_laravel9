<div id="SearchCustomerModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="p-1 max-w-3xl mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="close-modal shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            data-dismiss="mymodal" data-label="#SearchCustomerModal">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-3">
            <div class="text-lg">
            選擇會員
            </div>
            <div class="border-t-2 border-black"></div>
            
            <div class="m-2 p-2">
                <div class="">
                    <select name="" id="">
                        <option value="c_name_company">客戶姓名</option>
                        <option value="identification_gui_number">身分證號</option>
                        <option value="phone">電話手機</option>
                        <option value="id">客戶編號</option>
                    </select>
                    <input type="text" class="">
                    <i class="material-icons show-modal" >search</i>
                </div>

                <div id="search_customer_list" class="">
                <table class="shadow-lg bg-whtie mx-auto text-center text-lg">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="border p-2 items-center">編號</th>
                            <th class="border p-2">姓名</th>
                            <th class="border p-2">電話</th>
                            <th class="border p-2">手機</th>
                            <th class="border p-2">區碼</th>
                            <th class="border p-2">縣市</th>
                            <th class="border p-2">鄉鎮</th>
                            <th class="border p-2">地址</th>
                            <th class="border p-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-blue-200 h-16">
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2">xxxxx</td>
                            <td class="border p-2 w-24">
                                <form action="{{url('crm/search_customer_data')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="interface" value="">
                                    <input type="hidden" name="search_id" value="">
                                    <button type="submit" class="bg-blue-500 text-white p-2 hover:bg-blue-600">選取</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="border-t-2 border-black"></div>
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
    });
</script>