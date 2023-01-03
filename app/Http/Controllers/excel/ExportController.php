<?php

namespace App\Http\Controllers\excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Excel;
use DB;


use App\Exports\LeaveRecordsExport;
use App\Exports\CalendarDatetimeRecordExport;

use App\Models\CalendarDatetimeRecord;
use App\Models\CalendarThingRecord;
use App\Models\CalendarGroupRecord;
use App\Models\CalendarFile;


class ExportController extends Controller
{

    public function calendar_export(Request $request){ 
        if($member_id = $request->session()->get('member_id') ){
            $data = ['測試1','測試2','測試3'];
            $dateTime = '2020-02-02T22:22:22';
            $payways = '工作工作表';
            
            // $color_class_array = array(
            //    1 => "紅色",
            //    2 => "橙色",
            //    3 => "黃色",
            //    4 => "綠色",
            //    5 => "藍色",
            //    6 => "靛色",
            //    7 => "紫色",
            // );

            $DB_head = ['行程人員','關係人員','行程顏色','通報單位','通報樣態','標題',
            '開始日期','開始時間','結束日期','結束時間',
            '是否整天','內容','地點','處理情形','備註','邀請對象'];
            $DB_datas = DB::select(
                "SELECT c2.name, c1.relevant_members, 
                    CASE WHEN c1.case_level = 1 THEN '紅色'
                        WHEN c1.case_level = 2 THEN '橙色'
                        WHEN c1.case_level = 3 THEN '黃色'
                        WHEN c1.case_level = 4 THEN '綠色'
                        WHEN c1.case_level = 5 THEN '藍色'
                        WHEN c1.case_level = 6 THEN '靛色'
                        WHEN c1.case_level = 7 THEN '紫色' END AS case_level,
                    c1.informant, c1.informant_type, c1.case_title, 
                    DATE(c1.case_begin) AS begin_date, LEFT(TIME(c1.case_begin),5) AS begin_time,
                    DATE(c1.case_end) AS end_date, LEFT(TIME(c1.case_end),5) AS end_time,
                    CASE WHEN c1.case_all_day = 1 THEN '是' ELSE '否' END AS case_all_day,
                    c1.case_content, c1.case_location,
                    c3.thing_state, c1.case_remarks, c4.calendar_member_names
                FROM calendar_datetime_records c1
                LEFT JOIN calendar_members AS c2 ON c1.member_id = c2.account
                LEFT JOIN (
                    SELECT c11.calendar_datetime_record_id,
                        GROUP_CONCAT(CONCAT(t12.name,',',c11.thing_state)) AS thing_state
                    FROM calendar_thing_records AS c11
                    LEFT JOIN things AS t12 ON c11.thing_id = t12.id
                    GROUP BY calendar_datetime_record_id
                )c3 ON c1.id = c3.calendar_datetime_record_id
                LEFT JOIN (
                    SELECT c21.calendar_datetime_record_id, 
                        GROUP_CONCAT(c22.name) AS calendar_member_names
                    FROM calendar_group_records AS c21
                    LEFT JOIN calendar_members AS c22 ON c21.calendar_member_id = c22.account
                    GROUP BY calendar_datetime_record_id
                )c4 ON c1.id = c4.calendar_datetime_record_id
                WHERE c1.member_id NOT IN ('9001','9002')
                ORDER BY c1.case_begin
                "); //WHERE c1.id = '3652'
            $worktablename = "歷史行程";
            // print_r($DB_datas);
            // return array();
            return Excel::download(new CalendarDatetimeRecordExport($DB_datas,$DB_head,$worktablename), '歷史行程.xlsx');
        }
        return redirect('/login');

    }
    public function calendar_delete_recored(Request $request){ //刪除不要的行程
        if($member_id = $request->session()->get('member_id') ){
            $record_year = $request->record_year;
            $now = Carbon::now(); 
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_year = $now->year;
            $prohibit_delete_year[] = $now->year;
            $prohibit_delete_year[] = $now->subYear(1)->year;

            if(in_array($record_year,$prohibit_delete_year) || $record_year >= $now_year){
                $request->session()->put('message', '禁止刪除'.$record_year);
                return redirect()->back();
            }
            $DB_calendar_datetime_records = DB::select(
                "SELECT id, case_begin FROM calendar_datetime_records 
                WHERE case_begin LIKE '$record_year%'
            ");
            foreach($DB_calendar_datetime_records as $v){
                $id = $v->id;
                //刪除行程
                $calendar_datetime_record = CalendarDatetimeRecord::find($id);
                // if($calendar_datetime_record->member_id == $member_id){ //2021/12/30 被邀請人，可以刪除行程
                $calendar_datetime_record->delete();
                // }
                //刪除處理事件
                $DB_calendar_thing_records = DB::select("SELECT * FROM calendar_thing_records WHERE calendar_datetime_record_id = '$id'");
                foreach($DB_calendar_thing_records as $v2){
                    $calendar_thing_record = CalendarThingRecord::find($v2->id);
                    $calendar_thing_record->delete();
                }
                //刪除邀請對象
                $DB_calendar_group_records = DB::select("SELECT * FROM calendar_group_records WHERE calendar_datetime_record_id = '$id'");
                foreach($DB_calendar_group_records as $v2){
                    $calendar_group_record = CalendarGroupRecord::find($v2->id);
                    $calendar_group_record->delete();
                }
                // //圖片刪除  20220601 不要刪除，因為可能有些未來重複的行程會用到這個圖片
                // $DB_calendar_files = DB::select("SELECT * FROM calendar_files WHERE calendar_datetime_record_id = '$id'");
                // foreach($DB_calendar_files as $v){
                //     //查找要刪除的所有圖片位置，刪除圖片
                //     $calendar_file = CalendarFile::find($v->id);
                //     $file = $calendar_file->file_name.".".$calendar_file->file_type;
                //     $target_file = "images/upload/add_case_file/".$file;
                //     try { //檢查是否有錯誤，如果沒錯誤，不執行catch，繼續往下執行try catch以下的程式
                //         unlink($target_file);
                //         throw new Exception();
                //     } catch (Exception $e) { //錯誤會執行這段
                //         echo 'Caught exception: ',  $e->getMessage(), "\n"; 
                //     }
                //     // $calendar_file->delete();
                //     //刪除(重複)行程的圖片
                //     $file_name = $calendar_file->file_name;
                //     DB::select("DELETE FROM `calendar_files` WHERE file_name = '$file_name' ");
                // }
            }
            
            $request->session()->put('message', '刪除'.count($DB_calendar_datetime_records)."筆");
            return redirect()->back();

        }
        return redirect('/login');
    }

    // public function ED(Request $request){ //測試下載EXCEL
    //     $data = ['測試1','測試2','測試3'];
    //     $dateTime = '2020-02-02T22:22:22';
    //     $payways = '工作工作表';
    //     // print_r($data);
    //     // return array();
    //     return Excel::download(new CalendarDatetimeRecordExport($data,$dateTime,$payways), '測試.xlsx');
    // }

    
}
