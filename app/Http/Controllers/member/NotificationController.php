<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use UserHelper; //myfunction
use Carbon\Carbon;
use App\Models\CalendarDatetimeRecord;

class NotificationController extends Controller
{
    public function line_notification(Request $request){
        $now = Carbon::now(); 
        $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
        $today_datetime = $now->toDateTimeString();

        $DB_calendar_datetime_records = DB::select("SELECT id, member_id, case_title, case_begin, case_end, 
            notification_record, TIMESTAMPDIFF(HOUR, '$today_datetime', case_begin) AS remain_hours,
            informant
            FROM calendar_datetime_records
            WHERE TIMESTAMPDIFF(HOUR, '$today_datetime', case_begin) <= 72 AND TIMESTAMPDIFF(HOUR, '$today_datetime', case_begin) > 0 
        ");
        // return $DB_calendar_datetime_records;
        foreach($DB_calendar_datetime_records as $v){
            print($v->remain_hours); echo "小時，";
            if($v->informant){ //婚喪喜慶
                //通知狀態 0.未通知 , 1. 24小時內第一次通知 , 2. 12小時內第二次通知 , 3. 1小時內第三次通知
                $notification_record = $v->notification_record; //紀錄通知狀態
                $remain_hours = $v->remain_hours; //離行程還有幾小時
                //檢測是否要通知
                $change_notification_record = ""; //預設空
                if( ($notification_record == '0' || $notification_record == '1' || $notification_record == '2') && $remain_hours <= 2){ //第三次通知
                    $change_notification_record = '3'; //通知成功後變成3
                }elseif( ($notification_record == '0' || $notification_record == '1') && $remain_hours <= 48){ //第二次通知
                    $change_notification_record = '2'; //通知成功後變成2
                }elseif($notification_record == '0' && $remain_hours <= 72){ //第一次通知
                    $change_notification_record = '1'; //通知成功後變成1
                }
                print($v->case_title); echo "<br>";
                // return $change_notification_record;
            }else{ //一般行程
                //通知狀態 0.未通知 , 1. 72小時內通知 一次
                $notification_record = $v->notification_record; //紀錄通知狀態
                $remain_hours = $v->remain_hours; //離行程還有幾小時
                //檢測是否要通知
                $change_notification_record = ""; //預設空
                if($notification_record == '0' && $remain_hours <= 72){ //第一次通知
                    $change_notification_record = '3'; //通知成功後變成3
                }
                print($v->case_title); echo "<br>";

            }
            //通知
            if($change_notification_record){ //有資料代表要通知
                //通知訊息
                $case_title = $v->case_title; //行程標題
                if(!$case_title) $case_title = "無標題行程";
                $case_begin = $v->case_begin; //行程開始時間
                if(!$case_begin) $case_begin = "無時間";
                $case_end = $v->case_end; //行程結束時間
                if(!$case_end) $case_end = "無時間";
                $message = "\n行程：".$case_title."\n時間：\n".$case_begin."\n".$case_end."\n".url("/");

                //通知人員
                    //行程人員的權杖

                
                $DB_calendar_members = DB::select("SELECT notification_token FROM calendar_members WHERE account = '$v->member_id' ");
                $token = $DB_calendar_members[0]->notification_token;
                $result = UserHelper::line_notify_message($token,$message);
                print($v->member_id.$message); echo "<br>";
                    //相關人員的權杖
                $DB_calendar_group_records = DB::select("SELECT calendar_member_id FROM calendar_group_records WHERE calendar_datetime_record_id = '$v->id' ");
                // return $DB_calendar_group_records;
                foreach($DB_calendar_group_records as $v2){
                    $DB_calendar_members = DB::select("SELECT notification_token FROM calendar_members WHERE account = '$v2->calendar_member_id' ");
                    $token = $DB_calendar_members[0]->notification_token;
                    UserHelper::line_notify_message($token,$message);
                    print($v2->calendar_member_id.$message); echo "<br>";
                }

                $result_json = json_decode($result, true);
                
                // echo $i;
                if($result_json['status'] == '200'){ //通知成功
                    $id = $v->id; //行程標題
                    $calendar_datetime_record = CalendarDatetimeRecord::find($id);
                    $calendar_datetime_record->notification_record = $change_notification_record;
                    $calendar_datetime_record->save();
                }else{ //通知失敗
                }
            }
        }
        // print(count($DB_calendar_datetime_records));
        return count($DB_calendar_datetime_records);
        // return $DB_calendar_datetime_records;
    }

    public function notificationDetailEdit(Request $request){ //行程變動通知 (手動按按鈕觸發)
        if($member_id = $request->session()->get('member_id') ){
            $member_name = $request->name;
            $calendarid = $request->calendarid;
            $DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE name = '$member_name' ");
            $token = $DB_calendar_members[0]->notification_token;

            $DB_calendar_datetime_records =  DB::select("SELECT case_title, case_begin, case_end
                FROM calendar_datetime_records
                WHERE id = '$calendarid'
            ");

            $case_title = $DB_calendar_datetime_records[0]->case_title; //行程標題
            if(!$case_title) $case_title = "無標題行程";
            $case_begin = $DB_calendar_datetime_records[0]->case_begin; //行程開始時間
            if(!$case_begin) $case_begin = "無時間";
            $case_end = $DB_calendar_datetime_records[0]->case_end; //行程開始時間
            if(!$case_end) $case_end = "無時間";

            $message = "\n`行程有所變動通知`\n行程：".$case_title."\n時間：\n".$case_begin."\n".$case_end."\n".url("/");

            $result = UserHelper::line_notify_message($token,$message);

            // $result = array(
            //     "DB_calendar_members" => $DB_calendar_members,
            //     "DB_calendar_datetime_records" => $DB_calendar_datetime_records,
            // );
            return response()->json($result);
        }
        return redirect('/login');
    }

    // public function example(Request $request){
    //     return redirect('/login');
    // }
}
