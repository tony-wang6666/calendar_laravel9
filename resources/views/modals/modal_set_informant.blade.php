<div id="informantSetModal" style="background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full overflow-y-auto hidden">
    <div class="p-1 max-w-xl mx-auto relative absolute left-0 right-0 overflow-hidden mt-12">
        <div class="shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
            data-dismiss="mymodal" data-label="#informantSetModal">
            <span class="material-icons">clear</span>
        </div>

        <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-3">
            
            <div class="font-bold text-2xl mb-6 text-gray-800 border-b pb-2 text-center mx-auto">
                <span class='text-3xl'>通報單位設定</span>
            </div>
            <!-- <div class="mb-4">
                <label class="text-gray-800 block mb-1 font-bold text-xl  tracking-wide">標題</label>
                <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" name='case_title' required>
            </div> -->


            <div class=" inline-block w-full mb-4">
                <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide text-center text-2xl">通報單位</label>
                <div class="flex justify-center">
                    <div class="relative pr-4">
                        <select id="informant_list" class="block text-lg w-64 bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" name=''>
                            @foreach($DB_informant as $v)
                            <option value="{{$v->informant_name}}">{{$v->informant_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <button id="btn_delete" type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm">
                        <i class="material-icons md-24">delete</i>
                    </button>	
                </div>
            </div>
            <div class="mb-4 text-center">
                <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide text-2xl">新增通報單位</label>
                <div class="flex justify-center">
                    <div class="pr-4">
                        <input id="informant_add" class="block text-lg w-64 bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700" type="text" name=''>
                    </div>
                    <button id="btn_add" type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm">
                        <i class="material-icons md-24">add</i>
                    </button>	
                </div>
            </div>
                <!-- <div class="mb-4">
                    <label class="text-gray-800 block mb-1 font-bold text-xl tracking-wide">附件(圖)</label>
                    <label for="img_file1" class="cursor-pointer"><i class="material-icons border-2 border-black md-48">add</i></label>
                    <input class="hidden visible" type="file" id='img_file1' name='img_file[]' multiple>
                    <div id='upload_img_preview1' class=""></div>
                </div> -->

            <!-- <div class="mt-8 text-right">
                <button type="button" class="close-modal bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2">
                    取消
                </button>	
                <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm">
                    新增
                </button>	
            </div> -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click','#btn_delete',function(){
            var informant = $("#informant_list").val();
            if (confirm('確定刪除('+ informant +')嗎?')) {
                var informant = $("#informant_list").val();
                var type = "delete";
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('member/set/change_informant_list')!!}',
                    data:{'informant':informant,'type':type},
                    dataType:'json',
                    success:function(data){
                        // console.log(data);
                        document.getElementById('informant_list').innerHTML = data.informant_list;
                        // document.getElementById('select_informant_type').innerHTML = data.type_option;
                        // document.getElementById('informant_unit').innerHTML = data.informants_option;
                        // document.getElementById('checkbox_things').innerHTML = data.checkbox_things;
                        // $('#informant_unit').selectize({
                        //     create: true,
                        //     sortField: 'text'
                        // });
                    },
                    error:function(){
                        console.log('error');
                    }
                });
            }
        })
        $(document).on('click','#btn_add',function(){
            var informant = $("#informant_add").val();
            if (confirm('確定新增嗎?')) {
                var type = "add";
                $.ajax({
                    type:'get',
                    url:'{!!URL::to('member/set/change_informant_list')!!}',
                    data:{'informant':informant,'type':type},
                    dataType:'json',
                    success:function(data){
                        // console.log(data);
                        document.getElementById('informant_list').innerHTML = data.informant_list;
                    },
                    error:function(){
                        console.log('error');
                    }
                });
            }
        })
        // function get_informant_types() { //取得所有通報類型 與 選項
        //     $.ajax({
        //         type:'get',
        //         url:'{!!URL::to('member/get_informant_types')!!}',
        //         dataType:'json',
        //         success:function(data){
        //             document.getElementById('informant_type').innerHTML = data.informat_type_checkbox;
        //             document.getElementById('select_informant_type').innerHTML = data.type_option;
        //             document.getElementById('informant_unit').innerHTML = data.informants_option;
        //             document.getElementById('checkbox_things').innerHTML = data.checkbox_things;
        //             $('#informant_unit').selectize({
        //                 create: true,
        //                 sortField: 'text'
        //             });
        //             checkbox_informant_items(); //顯示所選的類型   隱藏未選的類型
        //         },
        //         error:function(){
        //             console.log('error');
        //         }
        //     });
        // }
        // get_informant_types(); //取得所有通報類型 與 選項

        // $(document).on('change','#img_file2',function(){
        //     const file = this.files[0];
        //     // console.log(this.files);
        //     if(file){
        //         var previews ="";
        //         for(var i=0;i<this.files.length;i++){
        //             const file = this.files[i];
        //             var src = URL.createObjectURL(file);
        //             previews += "<img src='"+src+"' alt='預覽'>"
        //         }
        //         $("#upload_img_preview2").html(previews);
        //     }else{
        //         $("#upload_img_preview2").html("");
        //     }
        // })
    });
</script>