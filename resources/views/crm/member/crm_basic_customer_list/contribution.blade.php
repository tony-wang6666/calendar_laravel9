<div id="contribution" class="bg-blue-200 text-lg font-bold text-center select-none hidden">
    <!-- <div class="">
        <canvas id='myChart'></canvas>
    </div> -->
    <div id="contribution_chart" class="">
        <div class="flex grid grid-cols-2">
            <div id="piechart0" style="height: 500px;"></div>
            <div id="piechart1" style="height: 500px;"></div>
        </div>
    </div>
    <div id="" class="px-4 py-2">
        <div class="p-2 text-2xl font-black bg-blue-400 text-left hover:bg-blue-500" data-toggle='mycollapse' data-target="#contribution_descriptions">
            <span class="material-icons">
                description
            </span>
            客戶貢獻度配分說明：
        </div>
        <div id='contribution_descriptions' class="max-h-0 overflow-hidden">
            <div class="text-2xl text-left">
                <span class="material-icons">
                    near_me
                </span>
                定儲
            </div>
            <table class="shadow-lg bg-whtie mx-auto text-center text-lg w-full ">
                <tbody >
                    <tr class="bg-blue-600 text-white">
                        <th class="border p-2">800萬以上</th>
                        <th class="border p-2">650~800萬</th>
                        <th class="border p-2">500~650萬</th>
                        <th class="border p-2">350~500萬</th>
                        <th class="border p-2">200~350萬</th>
                        <th class="border p-2">100~200萬</th>
                    </tr>
                    <tr class="bg-blue-400 h-8 text-white font-normal">
                        <td class="border p-2">16</td>
                        <td class="border p-2">14</td>
                        <td class="border p-2">12</td>
                        <td class="border p-2">10</td>
                        <td class="border p-2">8</td>
                        <td class="border p-2">6</td>
                    </tr>
                    <tr class="bg-blue-600 text-white">
                        <th class="border p-2">50~100萬</th>
                        <th class="border p-2">20萬以下</th>
                        <th class="border p-2"></th>
                        <th class="border p-2"></th>
                        <th class="border p-2"></th>
                        <th class="border p-2"></th>
                    </tr>
                    <tr class="bg-blue-400 h-8 text-white font-normal">
                        <td class="border p-2">4</td>
                        <td class="border p-2">2</td>
                        <td class="border p-2"></td>
                        <td class="border p-2"></td>
                        <td class="border p-2"></td>
                        <td class="border p-2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function contribution(){
        var basic_c_id = $("#c_id").val();
        $.ajax({
            type:'get',
            url:'{!!URL::to('crm/contribution')!!}',
            data:{'basic_c_id':basic_c_id,},
            dataType:'json',
            success:function(data){
                // console.log(data);
                $("#contribution_descriptions").html(data.contribution_descriptions); //貢獻度說明
                if(data.no){
                    $search_table = "<div class='text-2xl font-bold'>查無資料</div>"
                    $("#contribution_chart").html($search_table);
                }else{
                    //google js 版本的圖表
                    for(var i=0 ; i<2 ; i++){
                        contribution_google_chart(data.title_val_arrays[i],data.array_color_google,data.title[i],i);
                    }
                }
                
            },
            error:function(){
                console.log('error');
            }
        });
    };
    function contribution_chart(len,cols_array,vals_array,array_color) {
        var ctx = document.getElementById('myChart').getContext('2d');
        ctx.canvas.parentNode.style.width = '400px';
        var chart = new Chart(ctx, {
            type: 'pie', //圖案類型
            data: {
                labels: cols_array,
                datasets: [{
                    label: '貢獻度',
                    data: vals_array,
                    backgroundColor: array_color,
                    // borderColor: 'rgb(255, 99, 132)',
                    borderWidth: 0,
                    hoverOffset: 4,
                }],
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: '2020-04月 貢獻度',
                        font: {
                            size: 24,
                        },
                    },
                    legend: {
                        position: 'right',
                        labels: {
                            // usePointStyle: true,
                            // textAlign: 'center',
                            font: {
                                size: 24
                            },
                        }
                    },
                }
            }
        });
    }
        
        
    $(document).ready( function(){
        contribution();
        // var promise = contribution();
        // promise.done(function(json){
        //     console.log(json);
        // });
        
        // promise.success:(function (data) {
        //     // alert(data);
        //     // console.log(data);
        // });
    });
</script>
<script type="text/javascript">
    function contribution_google_chart(title_val_array,array_color_google,title,num){
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            // console.log(title);
            var data = google.visualization.arrayToDataTable(title_val_array);
            var options = {
                title: title,
                titleTextStyle: {
                    fontSize:24,
                    // italic: true,   // true of false
                    // color: <string>,    // any HTML string color ('red', '#cc00cc')
                    // fontName: "<string>", // i.e. 'Times New Roman'
                    // bold: true,    // true or false
                },
                backgroundColor: 'none',
                slices: array_color_google,
                pieSliceTextStyle: {
                    color: 'black',
                },
                chartArea: {'width': '90%', 'height': '80%'},
                legend: {
                    position: 'bottom', 
                    textStyle: {fontSize: 20}
                },
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'+num));
            chart.draw(data, options);
        }
    }
    
</script>