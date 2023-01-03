<?php

namespace App\Http\Controllers\leaveData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use UserHelper; //myfunction

class LeaveDataController extends Controller
{
    public function leave_first_page_data_json(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            //取得農會請假平台資料(今日請假人員)
            $md5 = md5('OD_GLD');
            $url = "https://wuchi.azurewebsites.net/getLeaveData?data=".UserHelper::lock_url($member_id,$md5);
            $json = file_get_contents($url);
            $leave_first_array = json_decode($json);
            $a_check_list = "";
            for( $i=3 ; $i<5 ; $i++){
                $a_check_list .= "<a href='https://wuchi.azurewebsites.net' target='_blank' class='bg-blue-500 hover:bg-blue-600 rounded-lg px-2 py-3 my-1 mx-1'>".$leave_first_array[$i]->type."<i class='material-icons'>receipt_long</i>".$leave_first_array[$i]->count."</a>";
            }
            $a_today_leave_member = "";
            for( $i=0 ; $i<3 ; $i++){
                $a_today_leave_member_list = "";
                if($leave_first_array[$i]->list_data){
                    $a_today_leave_member_list_col="";
                    foreach($leave_first_array[$i]->list_data as $v){
                        if($v->schedule == '審核中'){
                            $a_today_leave_member_list_col.="
                            <div class='bg-yellow-200 text-yellow-800 py-2 border-b-2 border-gray-400'>
                                <div class='text-xl'>".$v->leave_title."</div>
                                <div class='text-base text-right pr-3'>".$v->date1."</div>
                            </div>";
                        }else{
                            $a_today_leave_member_list_col.="
                            <div class='bg-green-300 text-green-900 py-2 border-b-2 border-gray-400'>
                                <div class='text-xl'>".$v->leave_title."</div>
                                <div class='text-base text-right pr-3'>".$v->date1."</div>
                            </div>";
                        }
                    }
                    $a_today_leave_member_list ="
                    <div id='list_data".$i."' class='max-h-0 overflow-hidden'>
                        ".$a_today_leave_member_list_col."
                    </div>";
                }else{
                    $a_today_leave_member_list ="
                    <div id='list_data".$i."' class='max-h-0 overflow-hidden'>
                        <div class='py-2'>今日沒有請假人員</div>
                    </div>";
                }
                $a_today_leave_member .="
                <div class='col-span-1 flex items-start justify-center text-2xl font-black m-3 lg:w-2/5 w-full'>
                    <div class='text-center w-full border-blue-400 border-4'>
                        <div data-toggle='mycollapse' data-target='#list_data".$i."' class='bg-blue-500 text-white w-full py-4'>".$leave_first_array[$i]->title."</div>
                        ".$a_today_leave_member_list."
                    </div>
                </div>";
            }

            
            // for($i=0;$i<3;$i++){
            //     return $leave_first_array[0]->title;
            // }
            // print_r($leave_first_array);
            // return $leave_first_array;
            $result = array(
                "a_check_list" => $a_check_list,
                "a_today_leave_member" => $a_today_leave_member,
            );
            return response()->json($result);
        }
    }

    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //     }
    //     return redirect('/login');
    // }
    
}
