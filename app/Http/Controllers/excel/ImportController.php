<?php

namespace App\Http\Controllers\excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Excel;
use App\Imports\CalendarDatetimeRecordImport;

use DB;
use App\Models\CalendarDatetimeRecord; //行程資料表model
use App\Models\CalendarThingRecord; //行程事件處理表model
use App\Models\CalendarGroupRecord; //邀請對象model

use UserHelper; //myfunction
use iCalEasyReader; //myfunction

use Carbon\Carbon;

class ImportController extends Controller
{
    public function EU_form(Request $request){ //測試匯入EXCEL介面
        return view("excel.excel_import.import_test.import_test1");
    }
    public function EU_post(Request $request){ //測試匯入EXCEL執行;
        $record = Excel::toCollection(new CalendarDatetimeRecordImport(), $request->file('import_file'));
        print_r($record[0]); //顯示excel 資料
        $day = 0.55;    $time = $day * 86400;  echo date('H:i:s', $time); //天 換算成 時間
        return array();
        // //測試客製化函數用(單位天 變成 時間)
        // $day = 0.55;
        // return  UserHelper::numberTOtime($day);
    }
    public function calendar_import_form(Request $request){ //EXCEL匯入介面
        // $DB_things = DB::select("SELECT id, name FROM things ");
        // return 123;
        $DB_calendar_datetime_records = DB::select(
            "SELECT YEAR(case_begin) AS case_begin_year
            FROM calendar_datetime_records
            GROUP BY YEAR(case_begin)
            ORDER BY case_begin_year
        ");
        $option_year = "";
        foreach($DB_calendar_datetime_records as $v){
            $option_year .= "<option value='".$v->case_begin_year."'>".$v->case_begin_year."</option>";
        }
        // return $DB_calendar_datetime_records;
        return view("excel.excel_import.import_calendar.import_calendar",[
            'option_year' => $option_year,
            // 'DB_things' => $DB_things,
        ]);
    }
    public function calendar_import_post(Request $request){ //EXCEL匯入執行
        if($member_id = $request->session()->get('member_id') ){
            $data = Excel::toCollection(new CalendarDatetimeRecordImport(), $request->file('import_file'));
            // print_r($data[0]); //顯示excel 資料
            // return array();
            //行程新增
            $count_import = 0;
            foreach($data[0] as $k=>$v){
                if($k==0) {
                    if($v[0]!='行程人員' || $v[1]!='關係人員' || $v[2]!='行程顏色' || $v[3]!='通報單位' || 
                    $v[4]!='通報樣態' || $v[5]!='標題' || $v[6]!='開始日期' || $v[7]!='開始時間' || 
                    $v[8]!='結束日期' || $v[9]!='結束時間' || $v[10]!='是否整天' || $v[11]!='內容' || 
                    $v[12]!='地點' || $v[13]!='處理情形' || $v[14]!='備註'){ //檢查EXCEL欄位名稱
                        return '錯誤';
                    }
                }else{
                    if(!$DB_calendar_members = DB::select("SELECT account FROM calendar_members WHERE name='$v[0]'")) continue;
                    if($v[6] > $v[8]) continue; //日期錯誤 跳過
                    if($v[10] == '否'){ //行程新增 時段
                        if($v[6] == $v[8] && $v[7] >= $v[9]) continue; //時段錯誤 跳過
                        if(gettype($v[6]) == 'string'){
                            $case_all_day = 0;
                            $case_begin = $v[6]."T".$v[7];
                            $case_end = $v[8]."T".$v[9];
                        }else{
                            $case_all_day = 0;
                            $case_begin = date('Y-m-d', ($v[6] - 25569) * 86400)."T".date('H:i:s', $v[7]*86400);
                            $case_end = date('Y-m-d', ($v[8] - 25569) * 86400)."T".date('H:i:s', $v[9]*86400);
                        }
                    }else{ //行程新增 整天
                        if(gettype($v[6]) == 'string'){
                            $case_all_day = 0;
                            $case_begin = $v[6]."T00:00:00";
                            $case_end = $v[8]."T00:00:00";
                        }else{
                            $case_all_day = 1;
                            $case_begin = date('Y-m-d', ($v[6] - 25569) * 86400)."T00:00:00";
                            $case_end = date('Y-m-d', ($v[8] - 25569) * 86400)."T00:00:00";
                        }
                    }
                    $color_class_array = array(
                        "紅色" => 1,
                        "橙色" => 2,
                        "黃色" => 3,
                        "綠色" => 4,
                        "藍色" => 5,
                        "靛色" => 6,
                        "紫色" => 7,
                    );
                    $calendar_datetime_record = CalendarDatetimeRecord::create([
                        'member_id' => $DB_calendar_members[0]->account,
                        'repeat_group' => NULL, //連續行程， 匯入 預設為NULL 
                        'relevant_members' => $v[1], //潘客戶 0123456,陳客戶 987654
                        'case_level' => $color_class_array[$v[2]],
                        'informant' => $v[3],
                        'informant_type' => $v[4],
                        'case_title' => $v[5],
                        'case_content' => $v[11],
                        'case_location' => $v[12],
                        'case_remarks' => $v[14],
                        'case_begin' => $case_begin,
                        'case_end' => $case_end,
                        'case_all_day' => $case_all_day,
                    ]);


                    //處理事件新增
                    if($v[13]){
                        $things_array = explode(',',$v[13]);
                        for($i=0;$i<count($things_array);$i=$i+2){
                            $thing = $things_array[$i];
                            $thing_state = $things_array[$i+1];
                            $DB_things = DB::select("SELECT id, schedule FROM things WHERE name LIKE '$thing'");
                            $thing_id = $DB_things[0]->id;
                            CalendarThingRecord::create([
                                'calendar_datetime_record_id' => $calendar_datetime_record->id,
                                'thing_id' => $thing_id,
                                'thing_state' => $thing_state,
                            ]);
                            // $thing_schedule_array = explode(',',$DB_things[0]->schedule);
                            // $thing_state_key = array_search($thing_state, $thing_schedule_array)+1; 
                        }
                    }
                    //邀請對象
                    if($v[15]){
                        $relevant_member = explode(',',$v[15]);
                        foreach($relevant_member as $v){
                            if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE name = '$v'")){ //如果有此人才執行
                                CalendarGroupRecord::create([
                                    "calendar_datetime_record_id" => $calendar_datetime_record->id,
                                    "calendar_member_id" => $DB_calendar_members[0]->account,
                                ]); 
                            }
                        }
                    }

                    $count_import++;

                    // print_r($v); //顯示excel 資料
                    // return array();
                }
            }
            $request->session()->put('message', '匯入成功(匯入'.$count_import.'筆)');
            return redirect()->back();
        }
        return redirect('/login');
        
    }
    
    public function calendar_import_ics(Request $request){ //ics匯入執行  #從google行程匯出，匯入總幹事行程
        return 'error';
        if($member_id = $request->session()->get('member_id') ){
            $route = $request->file('import_file');
            $myfile = fopen($route, "r") ; //or die("Unable to open file!")
            $data = fread($myfile,filesize($route));
            $regex_opt = 'mib'; //不知道這啥
            $lines = mb_split( '[\r\n]+', $data );
            $last = count( $lines );
            for($i = 0; $i < $last; $i ++) { //清除字串前後空白
                if (trim( $lines[$i] ) == '')
                    unset( $lines[$i] );
            }
            $lines = array_values( $lines );
            // 查找第一個和最後一個項目的位置
            $first = 0;
            $last = count( $lines ) - 1;

            if (! ( mb_ereg_match( '^BEGIN:VCALENDAR', $lines[$first], $regex_opt ) and mb_ereg_match( '^END:VCALENDAR', $lines[$last], $regex_opt ) )){
                $first = null;
                $last = null;
                foreach ( $lines as $i => &$line ){
                    if (mb_ereg_match( '^BEGIN:VCALENDAR', $line, $regex_opt ))
                        $first = $i;
                    if (mb_ereg_match( '^END:VCALENDAR', $line, $regex_opt )){
                        $last = $i;
                        break;
                    }
                }
            }
            // 處理
            if (! is_null( $first ) and ! is_null( $last )) {
                // $lines = array_slice( $lines, $first + 1, ( $last - $first - 1 ), true );
                $BEGIN_VEVENT = 0 ;
                $group_array = array();
                $sum = 0;
                foreach ( $lines as $k=>$line ){
                    if($line == "BEGIN:VEVENT"){
                        $BEGIN_VEVENT = 1;
                        $arrry = array();
                        continue;
                    }elseif($line == "END:VEVENT"){
                        $group_array[] = $arrry;
                        $BEGIN_VEVENT = 0;
                        continue;
                    }
                    if($BEGIN_VEVENT){
                        if( mb_ereg_match(".*=", $line) ){
                            $item1 = explode( ';', $line, 2 );
                            $key_name = $item1[0];
                            if(empty($item1[1])) $key_value = "";
                            else {
                                $item2 = explode( ':', $item1[1], 2 );
                                if(empty($item2[1])) $key_value = "";
                                else{
                                    $key_value = $item2[1];
                                }
                            }
                        }else{
                            $item = explode( ':', $line, 2 );
                            $key_name = $item[0];
                            $key_value = $item[1];
                        }
                        // if($item[0] == "BEGIN"){
                        //     return count($group_array);
                        // }
                        if( empty($key_value) ) $key_value = ""; //null  就補空
                        
                        $arrry[$key_name] = $key_value;
                    }
                }
            }
            foreach($group_array as $k=>$v){
                $google_id = $v["UID"]; //google 流水號
                $case_begin = $v["DTSTART"]; // 開始日期 DTSTART
                $case_end = $v["DTEND"]; // 結束日期 DTEND
                // $createdate = $v["CREATED"]; // 建立日期 CREATED
                $case_title = $v["SUMMARY"]; // 標題 SUMMARY  
                $case_content = $v["DESCRIPTION"]; // 內容 DESCRIPTION
                $case_location = $v["LOCATION"]; // 地點 LOCATION
                $re_number = $v["SEQUENCE"]; // 重複順序 SEQUENCE
                echo $case_begin ."-". $case_title . "<br>";
                // echo $k;


                // $calendar_datetime_record = CalendarDatetimeRecord::create([
                //     'member_id' => $request->calendar_datetime_record_member,
                //     'repeat_group' => $repeat_group,
                //     'relevant_members' => "",
                //     'case_level' => $request->case_level, // 5 固定顏色(藍色)
                //     'informant' => "", // 可能為空
                //     'informant_type' => "", // 可能為空
                //     'case_title' => $case_title, //google標題
                //     'case_content' => $case_content, //google內容
                //     'case_location' => $case_location, //google地點
                //     'case_remarks' => "", // 可能為空
                //     'case_begin' => $case_begin, //google開始時間
                //     'case_end' => $case_end, //google結束時間
                //     'case_all_day' => $case_all_day, //判斷時間有無T 
                //     'calendar_source' => $google_id, //暫時為google 流水號
                // ]);

            }
            // return $group_array[7];
            

            return count($group_array);




            return array();

            // $route = $request->file('import_file');
            // $ical = new iCalEasyReader();
            // $lines = $ical->load( file_get_contents( $route ) );
            // // var_dump( $lines );
            // // print_r($lines["PRODID"][0]);
            // // print_r($lines[0]);
            // return count($lines);

            return redirect()->back();
        }
        return redirect('/login');
        
    }
    // public function example(Request $request){ //範例
    //     if($member_id = $request->session()->get('member_id') ){
            
    //         return redirect()->back();
    //     }
    //     return redirect('/login');
        
    // }
    

}
