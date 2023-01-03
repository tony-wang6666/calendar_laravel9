<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\CalendarDatetimeRecord;
use App\Models\CalendarThingRecord;
use App\Models\CalendarFile;
use App\Models\CalendarGroupRecord;
use App\Models\CalendarMember;
use Exception;

use UserHelper; //myfunction

class MemberController extends Controller
{
    public function first_page(Request $request){
        //20210821 以下更新資料，把處理事件的狀態欄位更新
        // $DB_calendar_thing_records = DB::select("SELECT c1.id, c1.thing_id, t2.name, c1.thing_state,
        // SPLIT_STR(t2.schedule,',', c1.thing_state) AS schedule
        // FROM calendar_thing_records AS c1
        // LEFT JOIN things AS t2 ON c1.thing_id = t2.id");
        // foreach($DB_calendar_thing_records as $v){
        //     if($v->schedule){
        //         $calendar_thing_record = CalendarThingRecord::find($v->id);
        //         $calendar_thing_record->thing_state = $v->schedule;
        //         $calendar_thing_record->save();
        //     }
        // }
        // return $DB_calendar_thing_records;

        if($member_id = $request->session()->get('member_id') ){
            //今天的日期
            $now = Carbon::now(); 
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $today = $now->ToDateString();
            //提交的日期
            $year = $request->year; //如果沒輸入，會抓今年
            $month = $request->month; //如果沒輸入，會抓這個月
            $day = $request->day; //如果沒輸入，會抓這天
            $timezone = 'Asia/Taipei';
            $this_month = Carbon::createFromDate($year, $month, $day,$timezone);
            if(empty($year) || empty($month) ){
                $year = $this_month->year;
                $month = $this_month->month;
                $day = $this_month->day;
            }
            // $request->session()->put('a_back_url',url("member/first_page?year=".$year."&month=".$month."&day=".$day)); //編輯返回用
            $request->session()->put('a_back_url',url("member/first_page")); //編輯返回用
            
            $date =  $this_month->ToDateString();   //日期選擇器用
            //節日與行程
            $date_event = array();
            $DB_festivals = DB::select("SELECT id, festival_name FROM festivals WHERE festival_date LIKE '$date' AND festival_name != '' ");
            foreach($DB_festivals as $v){ //節日
                $date_event[] = [ 'id' => $v->id, 
                                'add_case_type' => 'general',  //分類 是否是婚喪喜慶
                                'type' => 0 ,
                                'time' => '' ,
                                'title' => $v->festival_name,
                                'things' => '',
                                'things_ok' => 0, // 花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                                'which_day' =>'',
                            ];
            }
            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            $DB_calendar_datetime_records = DB::select("SELECT id, case_title, thing_remark,
                CASE WHEN informant_type IS NULL OR informant_type = '' THEN 'general'
                    ELSE 'ungeneral' END AS weddings_funerals,
                CASE WHEN case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end)
                    THEN CONCAT(SUBSTR(case_begin,12,5),'-',SUBSTR(case_end,12,5)) ELSE '' END AS case_time,
                case_level AS case_type,
                CASE WHEN case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end)
                    THEN 2 ELSE 1 END AS case_all_day,
                CASE WHEN DATE(case_begin) NOT LIKE DATE(case_end)
                    THEN CONCAT('(第',TIMESTAMPDIFF(DAY, DATE(case_begin), '$date')+1,'天，共',TIMESTAMPDIFF(DAY, DATE(case_begin), DATE(case_end))+1,'天)') 
                    ELSE '' END AS which_day
                FROM calendar_datetime_records
                WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND
                DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date'
                ORDER BY case_all_day, case_begin"); //找個人行事曆(時段)

            //處理情形
            if($DB_calendar_datetime_records){ //有資料才抓
                foreach($DB_calendar_datetime_records as $v){ //時段行程
                    $things_ok = 0; //預設為0 花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                    $date_things = array();
                    $DB_calendar_thing_records = DB::select("SELECT c1.id, c1.thing_id, t2.name, c1.thing_state
                        FROM calendar_thing_records AS c1
                        LEFT JOIN things AS t2 ON c1.thing_id = t2.id
                        WHERE calendar_datetime_record_id = '$v->id'");
                    if($DB_calendar_thing_records) {
                        foreach($DB_calendar_thing_records as $v2){
                            $date_things[] = [ 'name'=> $v2->name, 'schedule' => $v2->thing_state];
                            if( $v2->thing_state == '送達' && ($v2->name == '花圈' || $v2->name == '花籃' || $v2->name == '水' || $v2->name == '羅馬柱') ){
                                $things_ok = 1; //花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                            }
                        }
                    }
                    $date_event[] = [ 'id' => $v->id, 
                                    'add_case_type' => $v->weddings_funerals,  //分類 是否是婚喪喜慶
                                    'thing_remark' => $v->thing_remark,  //喪 拈香
                                    'type' => $v->case_type,
                                    'time' => $v->case_time,
                                    'title' => $v->case_title,
                                    'things' => $date_things, //花圈、花籃...等事件處理
                                    'things_ok' => $things_ok, //花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                                    'which_day' => $v->which_day, //跨天用的
                                 ];
                }
            }
            $date_event_json= json_decode(json_encode($date_event));

            // 導覽列 、 超連結
            // $this_month = Carbon::create($year, $month);
            // $this_month->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $last_date = $this_month->subDay(1);
            $a_last = url('member/first_page'."?year=".$last_date->year."&month=".str_pad($last_date->month,2,'0',STR_PAD_LEFT)."&day=".str_pad($last_date->day,2,'0',STR_PAD_LEFT) ); //給切換用超連結
            $next_date = $this_month->addDay(2);
            $a_next = url('member/first_page'."?year=".$next_date->year."&month=".str_pad($next_date->month,2,'0',STR_PAD_LEFT)."&day=".str_pad($next_date->day,2,'0',STR_PAD_LEFT) ); //給切換用超連結
            $a_today = url('member/first_page'); //給切換今日用超連結

            return view('member.first_page',[
                'date_event_json' => $date_event_json,
                // 'state' => '時間表',
                'a_last' => $a_last,
                'a_next' => $a_next,
                'a_today' => $a_today,
                'this_date' => $date,
                // 'DB_things' => $DB_things,
            ]);
            
        }
        return redirect('/login');
    }
    public function home(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            //今天的日期
            $now = Carbon::now(); 
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $today = $now->ToDateString();
            //提交的日期
            $year = $request->year; //如果沒輸入，會抓今年
            $month = $request->month; //如果沒輸入，會抓這個月
            $day = $request->day; //如果沒輸入，會抓這天
            // $day = null;
            $timezone = 'Asia/Taipei';
            $this_month = Carbon::create($year, $month, 1, 12);
            // $this_month = Carbon::createFromDate($year, $month, $day,$timezone); //這個有問題
            $this_month->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            if(empty($year) || empty($month) ){
                $year = $this_month->year;
                $month = $this_month->month;
                $day = $this_month->day;
            }
            // $request->session()->put('a_back_url',url("member/home?year=".$year."&month=".$month."&day=".$day)); //編輯返回用
            $request->session()->put('a_back_url',url("member/home")); //編輯返回用
            // return '123';
            $this_date =  $this_month->ToDateString(); // 導覽列  日期選擇器用
            $year_month = substr($this_month,0,7); //抓資料用

            // print($this_date); echo "系統的<br>"; //檢測用 可刪除
            // print($year."-".$month."-".$day);echo "我的<br>"; //檢測用 可刪除
            //抓資料用
            $this_month_endday_week = $this_month->endOfMonth()->dayOfWeek;  //最後一天星期幾
            $this_month_endday = $this_month->day; //這個月有幾X天
            $this_month_month = $this_month->month; 
            $this_month_year = $this_month->year;
            $schedule_array = array();

            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號

            for($i=1;$i<=$this_month_endday;$i++){ //這個月的日期
                $date = $this_month_year .'-'. str_pad($this_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($i, 2, "0", STR_PAD_LEFT);
                //節日與行程
                $date_event = array();
                $DB_festivals = DB::select("SELECT id, festival_name FROM festivals WHERE festival_date LIKE '$date' AND festival_name != '' ");
                foreach($DB_festivals as $v){ //節日
                    $date_event[] = [ 'id' => $v->id, 
                                    'add_case_type' => 'general',  //分類 是否是婚喪喜慶
                                    'type' => 0 ,
                                    'time' => '' ,
                                    'title' => $v->festival_name,
                                    'things' => '',
                                    'things_ok' => 0, // 花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                                    'which_day' =>''];
                }
                // $DB_calendar_date_records = DB::select("SELECT * FROM calendar_datetime_records WHERE member_id = '$member_id' AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND ( case_all_day = '1' OR DATE(case_end) > DATE(case_begin) ) "); //找個人行事曆(整天)
                // foreach($DB_calendar_date_records as $v){ //整天行程
                //     $date_event[] = [ 'id' => $v->id, 'type' => 99 ,'time' => '' ,'title' => $v->case_title];
                // }
                // $date = '2021-03-05';//測試用 要刪除
                $DB_calendar_datetime_records = DB::select("SELECT c1.id, c1.case_title, c1.thing_remark,
                        CASE WHEN c1.informant_type IS NULL OR c1.informant_type = '' THEN 'general'
                            ELSE 'ungeneral' END AS weddings_funerals,
                        CASE WHEN c1.case_all_day = 0 AND DATE(c1.case_begin) LIKE DATE(c1.case_end)
                            THEN CONCAT(SUBSTR(c1.case_begin,12,5),'-',SUBSTR(c1.case_end,12,5)) ELSE '' END AS case_time,
                        c1.case_level AS case_type,
                        CASE WHEN c1.case_all_day = 0 AND DATE(c1.case_begin) LIKE DATE(c1.case_end)
                            THEN 2 ELSE 1 END AS case_all_day,
                        CASE WHEN DATE(c1.case_begin) NOT LIKE DATE(c1.case_end)
                            THEN CONCAT('(第',TIMESTAMPDIFF(DAY, DATE(c1.case_begin), '$date')+1,'天，共',TIMESTAMPDIFF(DAY, DATE(c1.case_begin), DATE(c1.case_end))+1,'天)') 
                            ELSE '' END AS which_day
                        FROM calendar_datetime_records AS c1
                        LEFT JOIN informant_types AS i2 ON i2.informant_type_items LIKE concat('%',c1.informant_type,'%')
                        WHERE (c1.member_id = '$member_id' OR c1.id in ('".$group_calendar_datetime_record_ids."') ) AND 
                        DATE(c1.case_begin) <= '$date' AND DATE(c1.case_end) >= '$date' 
                        ORDER BY case_all_day, case_begin"); //找個人行事曆(時段)
                // $DB_informant_types = DB::select("SELECT * FROM informant_types WHERE informant_type_items LIKE '%$save_informant_type_item_radio%'");
                // $save_informant_type_option = $DB_informant_types[0]->informant_type_name;
                // print_r($DB_calendar_datetime_records); //測試用 要刪除
                // return array(); //測試用 要刪除
                if($DB_calendar_datetime_records){ //有資料才抓
                    // return $DB_calendar_datetime_records[0]->informant_type_name;
                    foreach($DB_calendar_datetime_records as $v){ //時段行程
                        $things_ok = 0; //預設為0 花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                        $date_things = array();
                        $DB_calendar_thing_records = DB::select("SELECT c1.id, c1.thing_id, t2.name, c1.thing_state
                                FROM calendar_thing_records AS c1
                                LEFT JOIN things AS t2 ON c1.thing_id = t2.id
                                WHERE calendar_datetime_record_id = '$v->id'");
                        if($DB_calendar_thing_records) {
                            foreach($DB_calendar_thing_records as $v2){
                                $date_things[] = [ 'name'=> $v2->name, 'schedule' => $v2->thing_state];
                                if( $v2->thing_state == '送達' && ($v2->name == '花圈' || $v2->name == '花籃' || $v2->name == '水' || $v2->name == '羅馬柱') ){
                                    $things_ok = 1; //花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                                }
                            }
                        }
                        $date_event[] = [ 'id' => $v->id,
                                        'add_case_type' => $v->weddings_funerals,  //分類 是否是婚喪喜慶
                                        'thing_remark' => $v->thing_remark,  //喪 拈香
                                        'type' => $v->case_type,
                                        'time' => $v->case_time,
                                        'title' => $v->case_title,
                                        'things' => $date_things,
                                        'things_ok' => $things_ok, //花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                                        'which_day' => $v->which_day];
                        }
                }
                
                if(count($date_event)>0){
                    switch(Carbon::create($date)->month){
                        case 1: $case_month='一月'; break; case 2: $case_month='二月'; break; case 3: $case_month='三月'; break; case 4: $case_month='四月'; break; case 5: $case_month='五月'; break; 
                        case 6: $case_month='六月'; break; case 7: $case_month='七月'; break; case 8: $case_month='八月'; break; case 9: $case_month='九月'; break; case 10: $case_month='十月'; break;
                        case 11: $case_month='十一月'; break; case 12: $case_month='十二月'; break;
                    }
                    switch(Carbon::create($date)->dayOfWeek){
                        case 0:$week='週日';break; case 1:$week='週一';break; case 2:$week='週二';break; case 3:$week='週三';break; 
                        case 4:$week='週四';break; case 5:$week='週五';break; case 6:$week='週六';break;
                    }
                    //農曆 20211108
                    $lunarDate = '';
                    $DB_calendar_lunar_calendars = DB::select("SELECT clc_year, clc_month, calendar_data FROM calendar_lunar_calendars WHERE clc_year = '$this_month_year' AND clc_month = '$this_month_month'"); 
                    if($DB_calendar_lunar_calendars){
                        $lunar_calendars_json_calendarData = json_decode($DB_calendar_lunar_calendars[0]->calendar_data, true)['calendarData'];
                        foreach ($lunar_calendars_json_calendarData as $v) if ($v['solarDate'] == $i) $lunarDate = $v['lunarDate'];
                    }
                    $schedule_array[] = [   'type' => 'this', 
                                            'date' => $date,
                                            'day' => $i ,
                                            'month' => $case_month, 
                                            'week' => $week,
                                            'data_number' => count($date_event),
                                            'data_event' => $date_event,
                                            'lunarDate' => $lunarDate,
                                        ];
                }
            }
            $schedule_array_json= json_decode(json_encode($schedule_array));
            // print_r($schedule_array_json);
            // return "end";
            //超連結
            $this_month = Carbon::create($year, $month);
            $this_month->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $last_date = $this_month->subMonth(1);
            $a_last = url('member/home'."?year=".$last_date->year."&month=".str_pad($last_date->month,2,'0',STR_PAD_LEFT) ); //給切換用超連結
            $next_date = $this_month->addMonth(2);
            $a_next = url('member/home'."?year=".$next_date->year."&month=".str_pad($next_date->month,2,'0',STR_PAD_LEFT) ); //給切換用超連結
            $a_today = url('member/home'); //給切換今日用超連結

            return view('member.home',[
                'schedule_array_json' => $schedule_array_json,
                'state' => '時間表',
                'a_last' => $a_last,
                'a_next' => $a_next,
                'a_today' => $a_today,
                'this_date' => $this_date,
                'today' => $today,
            ]);
        }
        return redirect('/login');
    }
    public function calendar_month(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            // $request->session()->put('a_back_url',url("member/calendar_month")); //編輯返回用 (月份可能用不到20210310)
            $year = $request->year; //如果沒輸入，會抓今年
            $month = $request->month; //如果沒輸入，會抓這個月
            $this_month = Carbon::create($year, $month, 1, 12);
            $this_month->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            if(empty($year) || empty($month) ){
                $year = $this_month->year;
                $month = $this_month->month;
            }
            $this_date =  $this_month->ToDateString();

            //這個月 1.第一天星期幾 2.最後一天星期幾 3.有幾天
            $this_month_startday_week = $this_month->dayOfWeek; //第一天星期幾
            $this_month_endday_week = $this_month->endOfMonth()->dayOfWeek;  //最後一天星期幾
            $this_month_endday = $this_month->day; //這個月有幾X天
            $this_month_month = $this_month->month; 
            $this_month_year = $this_month->year;
            $this_year_month = $this_month_year."-".str_pad($this_month_month,2,'0',STR_PAD_LEFT)."-"; //給modal的年月
            $a_today = url('member/calendar_month'); //給切換今日用超連結

            //上個月的最後一天
            $last_month = $this_month->startofMonth()->subMonth()->lastOfMonth();
            $last_month_day = $last_month->day;
            $last_month_month = $last_month->month;
            $last_month_year = $last_month->year;
            $last_year_month = $last_month_year."-".str_pad($last_month_month,2,'0',STR_PAD_LEFT)."-"; //給modal的年月
            $a_last = url('member/calendar_month'."?year=".$last_month_year."&month=".str_pad($last_month_month,2,'0',STR_PAD_LEFT)); //給切換用超連結
            
            // 下個月的最後一天
            $next_month = $this_month->startofMonth()->addMonth()->addMonth()->lastOfMonth(); 
            $next_month_day = $next_month->day;
            $next_month_month = $next_month->month;
            $next_month_year = $next_month->year;
            $next_year_month = $next_month_year."-".str_pad($next_month_month,2,'0',STR_PAD_LEFT)."-"; //給modal的年月
            $a_next = url('member/calendar_month'."?year=".$next_month_year."&month=".str_pad($next_month_month,2,'0',STR_PAD_LEFT)); //給切換用超連結
            // return response()->json($next_month);
            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            //日期
            for($i=$this_month_startday_week;$i>0;$i--){ //前一個月的日期
                $day =$last_month_day - $i +1;
                $date = $last_month_year .'-'. str_pad($last_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($day, 2, "0", STR_PAD_LEFT);
                //節日與行程
                $date_event = array();
                $DB_festivals = DB::select("SELECT id, festival_name FROM festivals WHERE festival_date LIKE '$date'");
                // $date_month_day = str_pad($last_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($day, 2, "0", STR_PAD_LEFT);
                // $DB_solar_calendars = DB::select("SELECT festival_name FROM solar_calendars WHERE festival_date = '$date_month_day'");
                foreach($DB_festivals as $v){ //節日
                    $date_event[] = [ 'type' => 0 ,'title' => $v->festival_name];
                }
                $DB_calendar_date_records = DB::select("SELECT * FROM calendar_datetime_records WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND ( case_all_day = '1' OR DATE(case_end) > DATE(case_begin) )"); //找個人行事曆(整天)
                foreach($DB_calendar_date_records as $v){ //整天行程
                    $date_event[] = [ 'type' => $v->case_level ,'title' => $v->case_title];
                }
                $DB_calendar_datetime_records = DB::select("SELECT * FROM calendar_datetime_records WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end) "); //找個人行事曆(時段)
                foreach($DB_calendar_datetime_records as $v){ //時段行程
                    $date_event[] = [ 'type' => $v->case_level ,'title' => $v->case_title];
                }
                //農曆 20211108
                $lunarDate = '';
                $DB_calendar_lunar_calendars = DB::select("SELECT clc_year, clc_month, calendar_data FROM calendar_lunar_calendars WHERE clc_year = '$last_month_year' AND clc_month = '$last_month_month'"); 
                if($DB_calendar_lunar_calendars){
                    $lunar_calendars_json_calendarData = json_decode($DB_calendar_lunar_calendars[0]->calendar_data, true)['calendarData'];
                    foreach ($lunar_calendars_json_calendarData as $v) if ($v['solarDate'] == $day) $lunarDate = $v['lunarDate'];
                }

                $month_day_array[] = [ 'type' => 'last', 'day' => $day ,'data_number' => '', 
                                        "data_event" => $date_event, 'lunarDate' => $lunarDate,
                                    ];
            }
            for($i=1;$i<=$this_month_endday;$i++){ //這個月的日期
                $date = $this_month_year .'-'. str_pad($this_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($i, 2, "0", STR_PAD_LEFT);
                //節日與行程
                $date_event = array();
                $DB_festivals = DB::select("SELECT id, festival_name FROM festivals WHERE festival_date LIKE '$date'");
                // $date_month_day = str_pad($this_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($i, 2, "0", STR_PAD_LEFT);
                // $DB_solar_calendars = DB::select("SELECT festival_name FROM solar_calendars WHERE festival_date = '$date_month_day'");
                foreach($DB_festivals as $v){ //節日
                    $date_event[] = [ 'type' => 0 ,'title' => $v->festival_name];
                }
                $DB_calendar_date_records = DB::select("SELECT * FROM calendar_datetime_records WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND ( case_all_day = '1' OR DATE(case_end) > DATE(case_begin) )"); //找個人行事曆(整天)
                foreach($DB_calendar_date_records as $v){ //整天行程
                    $date_event[] = [ 'type' => $v->case_level ,'title' => $v->case_title];
                }
                $DB_calendar_datetime_records = DB::select("SELECT * FROM calendar_datetime_records WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end) "); //找個人行事曆(時段)
                foreach($DB_calendar_datetime_records as $v){ //時段行程
                    $date_event[] = [ 'type' => $v->case_level ,'title' => $v->case_title];
                }
                // $total = $date_record_count + $leave_record_count;
                $total='';
                //農曆 20211108
                $lunarDate = '';
                $DB_calendar_lunar_calendars = DB::select("SELECT clc_year, clc_month, calendar_data FROM calendar_lunar_calendars WHERE clc_year = '$this_month_year' AND clc_month = '$this_month_month'"); 
                if($DB_calendar_lunar_calendars){
                    $lunar_calendars_json_calendarData = json_decode($DB_calendar_lunar_calendars[0]->calendar_data, true)['calendarData'];
                    foreach ($lunar_calendars_json_calendarData as $v) if ($v['solarDate'] == $i) $lunarDate = $v['lunarDate'];
                }
                // return $lunar_calendars_json_calendarData;
                
                $month_day_array[] = [ 'type' => 'this', 'day' => $i ,'data_number' => $total,
                                        "data_event" => $date_event, 'lunarDate' => $lunarDate,
                                    ];
            }
            for($i=1;$i<7-$this_month_endday_week;$i++){ //下個月日期
                $date = $next_month_year .'-'. str_pad($next_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($i, 2, "0", STR_PAD_LEFT);
                //節日與行程
                $date_event = array();
                $DB_festivals = DB::select("SELECT id, festival_name FROM festivals WHERE festival_date LIKE '$date'");
                // $date_month_day = str_pad($next_month_month, 2, "0", STR_PAD_LEFT)  .'-'. str_pad($i, 2, "0", STR_PAD_LEFT);
                // $DB_solar_calendars = DB::select("SELECT festival_name FROM solar_calendars WHERE festival_date = '$date_month_day'");
                foreach($DB_festivals as $v){ //節日
                    $date_event[] = [ 'type' => 0 ,'title' => $v->festival_name];
                }
                $DB_calendar_date_records = DB::select("SELECT * FROM calendar_datetime_records WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND ( case_all_day = '1' OR DATE(case_end) > DATE(case_begin) )"); //找個人行事曆(整天)
                foreach($DB_calendar_date_records as $v){ //整天行程
                    $date_event[] = [ 'type' => $v->case_level ,'title' => $v->case_title];
                }
                $DB_calendar_datetime_records = DB::select("SELECT * FROM calendar_datetime_records WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end) "); //找個人行事曆(時段)
                foreach($DB_calendar_datetime_records as $v){ //時段行程
                    $date_event[] = [ 'type' => $v->case_level ,'title' => $v->case_title];
                }
                //農曆 20211108
                $lunarDate = '';
                $DB_calendar_lunar_calendars = DB::select("SELECT clc_year, clc_month, calendar_data FROM calendar_lunar_calendars WHERE clc_year = '$next_month_year' AND clc_month = '$next_month_month'"); 
                if($DB_calendar_lunar_calendars){
                    $lunar_calendars_json_calendarData = json_decode($DB_calendar_lunar_calendars[0]->calendar_data, true)['calendarData'];
                    foreach ($lunar_calendars_json_calendarData as $v) if ($v['solarDate'] == $i) $lunarDate = $v['lunarDate'];
                }
                $month_day_array[] = [ 'type' => 'next', 'day' => $i ,'data_number' => '', 
                                        "data_event" => $date_event, 'lunarDate' => $lunarDate,
                                    ];
            }
            $month_day_array_json= json_decode(json_encode($month_day_array));
            // return $month_day_array;
            // return $month_day_array_json;
            // return $month_day_array_json[0]->data_event[0];

            $now = Carbon::now(); 
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $today_day="";
            if($now->year == $year && $now->month == $month ){
                $today_day = $now->day;
            }
            $DB_things = DB::select("SELECT id, name FROM things ");
            
            // $today_day = 10;
            // return $DB_festivals;
            return view('member.calendar_month',[
                'month_day_array_json' => $month_day_array_json,
                'today_day' => $today_day,
                'state' => '月', //下拉是清單切換用的
                'last_year_month' => $last_year_month,
                'this_year_month' => $this_year_month,
                'next_year_month' => $next_year_month,
                'a_last' => $a_last,
                'a_next' => $a_next,
                'a_today' => $a_today,
                'month' => $month,
                'this_date' => $this_date,
                'DB_things' => $DB_things,                
            ]);
        }
        return redirect('/login');
    }
    public function calendar_day(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            //取得現在的日期
            $now = Carbon::now(); 
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_hour = $now->hour;
            $now_year = $now->year;
            $now_month = $now->month;
            $now_day = $now->day;
            //取得提交的年月日
            $year = $request->year;
            $month = $request->month;
            $day = $request->day;
            //取得提交的日期(把年月日轉化後的資料)
            $timezone = 'Asia/Taipei';
            $request_date = Carbon::createFromDate($year, $month, $day,$timezone);
            // $request_date = Carbon::create($year, $month, $day);
            $year = $request_date->year;
            $month = $request_date->month;
            $day = $request_date->day;

            // $request->session()->put('a_back_url',url("member/calendar_day?year=".$year."&month=".$month."&day=".$day."")); //編輯返回用
            $request->session()->put('a_back_url',url("member/calendar_day")); //編輯返回用
            
            if($now_year == $year && $now_month == $month && $now_day == $day ) $today = true;
            else $today = false;
            $this_date = $request_date->toDateString();

            switch($request_date->dayOfWeek){
                case 0:$title_week='週日';break; case 1:$title_week='週一';break; case 2:$title_week='週二';break; case 3:$title_week='週三';break; 
                case 4:$title_week='週四';break; case 5:$title_week='週五';break; case 6:$title_week='週六';break;
            }
            // $request_date->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $last_date = $request_date->subDay(1);
            $a_last = url('member/calendar_day'."?year=".$last_date->year."&month=".str_pad($last_date->month,2,'0',STR_PAD_LEFT)."&day=".str_pad($last_date->day,2,'0',STR_PAD_LEFT)); //給切換用超連結
            $next_date = $request_date->addDay(2);
            $a_next = url('member/calendar_day'."?year=".$next_date->year."&month=".str_pad($next_date->month,2,'0',STR_PAD_LEFT)."&day=".str_pad($next_date->day,2,'0',STR_PAD_LEFT)); //給切換用超連結
            $a_today = url('member/calendar_day'); //給切換今日用超連結
        
            $title_time = array();
            for($i=1;$i<=11;$i++) $title_time[] = '上午'.$i.'點';
            $title_time[] = '中午12點';
            for($i=1;$i<=11;$i++) $title_time[] = '下午'.$i.'點';
            // for($i=1;$i<=24;$i++) $title_time[] = str_pad($i,2,'0',STR_PAD_LEFT).":00";
            
            return view('member.calendar_day',[
                'title_time' => $title_time,
                'this_date' => $this_date,
                'state' => '天',
                'a_last' => $a_last,
                'a_next' => $a_next,
                'a_today' => $a_today,
                'title_day' => $day,
                'title_week' => $title_week,
                'today' => $today, //判斷是否今天
                'month' => $month,

            ]);
        }
        return redirect('/login');
    }
    public function calendar_day_dataget(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $date = $request->date;
            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            //這天行事曆 整天
            $DB_festivals_union_datetimeRecords = 
                DB::select("SELECT id, festival_name AS title, holidayCategory AS content, 0 AS AllDayType, 0 AS case_level FROM festivals WHERE festival_date LIKE '$date' AND festival_name !=''
                            UNION
                            SELECT id, case_title, case_content, 
                                CASE WHEN case_all_day = 1 THEN 1 ELSE 2 END AS AllDayType,
                                case_level
                            FROM calendar_datetime_records 
                            WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' 
                                AND  (case_all_day = '1' OR DATE(case_end) > DATE(case_begin) ) ");
            
            $calendar_all_day = "";
            foreach($DB_festivals_union_datetimeRecords as $v){
                if($v->AllDayType == 0){ //節日
                    $calendar_all_day .= "<div class='border-2 border-green-300 text-white bg-green-700 rounded-lg w-full truncate outline-none px-2' tabindex='0' aria-hidden='true'>
                        <label class='font-bold'>".$v->title."</label>:
                        <span class=''>".$v->content."</span>
                    </div>";
                }else{
                    $calendar_all_day .= "<div class='a_detail_edit ".UserHelper::dateEventTypeTOcolor($v->case_level)." border-2 rounded-lg w-full truncate outline-none px-2' tabindex='0' aria-hidden='true' data-id='".$v->id."'>
                        <label class='font-bold'>".$v->title."</label>:
                        <span class=''>".$v->content."</span>
                    </div>";
                }
            }


            //這天行事曆 時段 (test1 欄位暫時放資料的排列)
            $DB_datetime_records = DB::select("SELECT id, case_begin, case_title, case_level, case_level=0 as test1,
            FLOOR(HOUR(case_begin)*40 + MINUTE(case_begin)*2/3) AS time_top,
            FLOOR(HOUR(SUBTIME(TIME(case_end) , TIME(case_begin)))*40 + MINUTE(SUBTIME(TIME(case_end) , TIME(case_begin)))*2/3) AS time_height
            FROM calendar_datetime_records 
            WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND DATE(case_begin) <= '$date' AND DATE(case_end) >= '$date' AND 
            case_all_day = 0 AND
            DATE(case_begin) LIKE DATE(case_end)
                ORDER BY case_begin");
            foreach($DB_datetime_records as $k=>$v){
                $same_time_count = 0;
                foreach($DB_datetime_records as $k2=>$v2){
                    if($k != $k2 && $k < $k2){
                        $begin1 = $v->time_top;
                        $end1 = $v->time_top + $v->time_height;
                        $begin2 = $v2->time_top;
                        $end2 = $v2->time_top + $v2->time_height;
                        if($begin1 <= $begin2 && $begin2 < $end1){
                            $same_time_count++;
                        }
                    }
                }
                if($same_time_count>0){
                    $v->test1 = $same_time_count/5;
                }
            }
            // $DB_festivals = DB::select("SELECT id, festival_name, holidayCategory FROM festivals WHERE festival_date LIKE '$date'");
            $calendar_day = "";
            foreach($DB_datetime_records as $v){
                $top = $v->time_top.'px';
                $time_height = $v->time_height.'px';
                $z_index=$v->test1 * 5;
                $style = "style='top:".$top."; height: ".$time_height."; left: calc((100% - 0px) * ".$v->test1." + 0px); width: calc((100% - 0px) * 0.34); z-index: ".$z_index."; outline: none; '";
                
                $calendar_day .= "<div class='a_detail_edit ".UserHelper::dateEventTypeTOcolor($v->case_level)." absolute border-2 rounded-lg truncate text-left px-2 font-black' 
                                 ".$style." tabindex='0' data-id='".$v->id."' data-type='2'>";
                
                $calendar_day .= $v->case_title."</div>";
            }
            $result = array(
                "calendar_day" => $calendar_day,
                "calendar_all_day" => $calendar_all_day,
            );
            
            return response()->json($result);
        }
    }
    public function add_case(Request $request){
        // return $request->all();
        if($member_id = $request->session()->get('member_id') ){
            if($request->date1 > $request->date2){
                $request->session()->put('message', '日期錯誤，新增失敗');
                return redirect()->back();
            }
            //行程新增
            if($request->case_all_day){ //行程新增 整天
                $case_begin_time = "T00:00:00";
                $case_end_time = "T00:00:00";
                $case_all_day = 1;
            }else{ //行程新增 時段
                if($request->date1 == $request->date2 && $request->time1 >= $request->time2){
                    $request->session()->put('message', '時段錯誤，新增失敗');
                    return redirect()->back();
                }
                $case_begin_time = "T".$request->time1;
                $case_end_time = "T".$request->time2;
                $case_all_day = 0;
            }

            //有無重複設定
            if(!$request->repeat_type) { //沒重複
                $request->repeat_number = 1; //重複1次
                $repeat_group = NULL; //沒重複，群組名稱為NULL
            }else{ //重複
                //重複群組
                $now = Carbon::now(); 
                $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                switch ($request->repeat_type){
                    case '每日': $type='d'; break;
                    case '每週': $type='w'; break;
                    case '每月': $type='m'; break;
                    case '每年': $type='y'; break;
                }
                $repeat_group = $type.$now->timestamp; //時間線群組名稱

            }
            if($request->repeat_number > 100) $request->repeat_number = 100; //限制重複次數

            $date1 = Carbon::createFromDate($request->date1);
            $date1->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $date2 = Carbon::createFromDate($request->date2);
            $date2->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間

            //圖片新增 (上傳圖片資料)
            if($request->img_file){
                for($j=0;$j<count($request->img_file);$j++){
                    $fileName = $_FILES['img_file']['name'][$j]; //圖片名稱
                    $fileType = $_FILES['img_file']['type'][$j]; //圖片單位類型
                    $fileTmpName = $_FILES['img_file']['tmp_name'][$j]; //
                    $fileError = $_FILES['img_file']['error'][$j]; //
                    $fileSize = $_FILES['img_file']['size'][$j]; //圖片大小
                    $imageType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); //圖片副檔名
                    $fileNewName = md5(uniqid()); //圖片名稱(亂碼，避免覆蓋)
                    $target_dir = "images/upload/add_case_file/";
                    $target_file = $target_dir . $fileNewName . "." . $imageType;
                    move_uploaded_file($fileTmpName, $target_file);
                    //存取圖片名稱與圖片類型， 建立資料，方便抓取圖片出來
                    $fileNewName_array[] = $fileNewName;
                    $imageType_array[] = $imageType;
                }
            }
            //關係人員
            $relevant_customer = "";
            if($request->relevant_customer){
                $relevant_customer = implode(',',$request->relevant_customer);
            }
            //行程資料建立
            for($i=1;$i<=$request->repeat_number;$i++){
                // $thing_remark = NULL;
                // if($request->informant_type){
                //     $DB_informant_types = DB::select("SELECT informant_type_name FROM informant_types WHERE informant_type_items LIKE '%$request->informant_type%'");
                //     $informant_type_name = $DB_informant_types[0]->informant_type_name;
                //     switch($informant_type_name){
                //         case '婚': $thing_remark = NULL; break;
                //         case '喪': $thing_remark = "未拈香"; break;
                //         case '喜': $thing_remark = NULL; break;
                //         case '慶': $thing_remark = NULL; break;
                //     }
                // }
                $calendar_datetime_record = CalendarDatetimeRecord::create([
                    'member_id' => $request->calendar_datetime_record_member,
                    'repeat_group' => $repeat_group,
                    // 'relevant_phone' => $request->relevant_phone,
                    'relevant_members' => $relevant_customer,
                    'case_level' => $request->case_level,
                    'informant' => $request->informant_unit,
                    'informant_type' => $request->informant_type,
                    // 'thing_remark' => $thing_remark,
                    'case_title' => $request->case_title,
                    'case_content' => $request->case_content,
                    'case_location' => $request->case_location,
                    'case_remarks' => $request->case_remarks,
                    'case_begin' => $date1->ToDateString().$case_begin_time,
                    'case_end' => $date2->ToDateString().$case_end_time,
                    'case_all_day' => $case_all_day,
                ]);
                //處理事件新增
                if($request->thing_id){
                    foreach($request->thing_id as $v){
                        if($DB_things = DB::select("SELECT name, schedule FROM things WHERE id = $v")){
                            if($DB_things[0]->name == "禮金"){ //禮金
                                $thing_state = $request->thing_state;
                            }else{
                                $schedule_array = explode(",",$DB_things[0]->schedule);
                                $thing_state = $schedule_array[0];
                            }
                        }else{
                            $thing_state = "請選擇";
                        }
                        // if($v == 5){ //禮金
                        //     $thing_state = $request->thing_state;
                        // }elseif($v == 6){ //拈香
                        //     $thing_state = "未拈香";
                        // }else{ //
                        //     $thing_state = "未訂";
                        // }
                        CalendarThingRecord::create([
                            'calendar_datetime_record_id' => $calendar_datetime_record->id,
                            'thing_id' => $v,
                            'thing_state' => $thing_state,
                        ]);
                    }
                }
                //邀請對象
                if($request->relevant_member){
                    foreach($request->relevant_member as $v){
                        if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE name = '$v'")){ //如果有此人才執行
                            CalendarGroupRecord::create([
                                "calendar_datetime_record_id" => $calendar_datetime_record->id,
                                "calendar_member_id" => $DB_calendar_members[0]->account,
                            ]); 
                        }
                    }
                }
                //圖片新增 (圖片上傳紀錄)
                if($request->img_file){
                    for($j=0;$j<count($fileNewName_array);$j++){
                        $fileNewName = $fileNewName_array[$j];
                        $imageType = $imageType_array[$j];
                        CalendarFile::create([
                            'calendar_datetime_record_id' => $calendar_datetime_record->id,
                            'file_name' => $fileNewName,
                            'file_type' => $imageType
                        ]);
                    }
                }

                switch ($request->repeat_type){
                    case '每日': $date1->addDay(); $date2->addDay(); break;
                    case '每週': $date1->addWeek(); $date2->addWeek(); break;
                    case '每月': $date1->addMonth(); $date2->addMonth(); break;
                    case '每年': $date1->addYear(); $date2->addYear(); break;
                }
            }
        
            //新建行程通知
                $case_title = $request->case_title; //行程標題
                if(!$case_title) $case_title = "無標題行程";
                $case_begin = $request->date1." ".$request->time1; //行程開始時間
                if(!$case_begin) $case_begin = "無時間";
                $case_end = $request->date2." ".$request->time2; //行程結束時間
                if(!$case_end) $case_end = "無時間";
                $message = "\n新增行程：".$case_title."\n時間：\n".$case_begin."\n".$case_end."\n".url("/");
    
                //行程人員的權杖
                $DB_calendar_members = DB::select("SELECT notification_token FROM calendar_members WHERE account = '$request->calendar_datetime_record_member' ");
                $token = $DB_calendar_members[0]->notification_token;
                UserHelper::line_notify_message($token,$message);
                //相關人員的權杖
            $relevant_member = $request->relevant_member;
            if($relevant_member){
                foreach($relevant_member as $v){
                    $DB_calendar_members = DB::select("SELECT notification_token FROM calendar_members WHERE name = '$v' ");
                    $token = $DB_calendar_members[0]->notification_token;
                    UserHelper::line_notify_message($token,$message);
                }
            }
            // $result = UserHelper::line_notify_message($token,$message);
            
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function detail_edit(Request $request){ //編輯行程
        if($member_id = $request->session()->get('member_id') ){
            $search_id = $request->id;
            //行程詳細內容
            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            $DB_edit_data = '';
            $DB_edit_data = DB::select("SELECT c1.id, c1.member_id, c1.repeat_group, c1.relevant_members, c1.relevant_phone, c1.informant, c1.informant_type, c1.case_title, c1.case_content, c1.case_location, c1.case_remarks, c1.case_all_day, c1.case_level, c1.thing_remark, c2.name AS member_name,
                CONCAT(MONTH(c1.case_begin),'月',DAY(c1.case_begin),'日 ',
                        CASE WEEKDAY(c1.case_begin) WHEN 0 THEN '星期一 ' WHEN 1 THEN '星期二 ' WHEN 2 THEN '星期三 ' WHEN 3 THEN '星期四 ' WHEN 4 THEN '星期五 ' WHEN 5 THEN '星期六 ' WHEN 6 THEN '星期日 ' END,
                        CASE WHEN DATE(c1.case_begin) = DATE(c1.case_end) AND c1.case_all_day = 0 THEN CONCAT(LPAD(HOUR(c1.case_begin), 2, 0),':',LPAD(MINUTE(c1.case_begin), 2, 0) ) ELSE ''  END ) AS case_time,
                CONCAT(MONTH(c1.case_end),'月',DAY(c1.case_end),'日 ',
                        CASE WEEKDAY(c1.case_end) WHEN 0 THEN '星期一 ' WHEN 1 THEN '星期二 ' WHEN 2 THEN '星期三 ' WHEN 3 THEN '星期四 ' WHEN 4 THEN '星期五 ' WHEN 5 THEN '星期六 ' WHEN 6 THEN '星期日 ' END,    
                        CASE WHEN DATE(c1.case_begin) = DATE(c1.case_end) AND c1.case_all_day = 0 THEN CONCAT(LPAD(HOUR(c1.case_end), 2, 0),':',LPAD(MINUTE(c1.case_end), 2, 0) ) ELSE ''  END ) AS case_time2,
                DATE(c1.case_begin) AS case_begin_date, DATE(c1.case_end) AS case_end_date, TIME(c1.case_begin) AS case_begin_time, TIME(c1.case_end) AS case_end_time,
                YEAR(c1.case_begin) AS case_begin_year, MONTH(c1.case_begin) AS case_begin_month, DAY(c1.case_begin) AS case_begin_day 
                FROM calendar_datetime_records AS c1
                LEFT JOIN calendar_members AS c2 ON c1.member_id = c2.account
                WHERE c1.id='$search_id' AND (c1.member_id = '$member_id' OR c1.id in ('".$group_calendar_datetime_record_ids."') )");
            

            // 行程事件處理狀態
            // $checked_calendar_thing_records = DB::select("SELECT c1.id, c1.thing_id, t2.name, 
            //     SPLIT_STR(t2.schedule,',', c1.thing_state) AS schedule
            //     FROM calendar_thing_records AS c1
            //     LEFT JOIN things AS t2 ON c1.thing_id = t2.id
            //     WHERE calendar_datetime_record_id = '$search_id'");
            // 行程附加資料
            $DB_calendar_files = DB::select("SELECT c1.id, c1.file_name, c1.file_type,
                CONCAT('images/upload/add_case_file/',c1.file_name,'.',c1.file_type) AS file_dir
                FROM calendar_files AS c1
                WHERE c1.calendar_datetime_record_id = '$search_id'");
            //處理事件
            $DB_calendar_thing_records = DB::select("SELECT t1.id AS thing_id, t1.name AS thing_name, 
                IF(c2.id, 1, 0) AS checked,
                t1.schedule AS schedule_array,
                IFNULL(c2.id, 0) AS calendar_thing_record_id,
                IFNULL(c2.thing_state, 0) AS thing_state,
                c2.thing_state AS thing_state_name
                FROM things AS t1
                LEFT JOIN calendar_thing_records AS c2 ON t1.id = c2.thing_id AND c2.calendar_datetime_record_id = '$search_id'");
            $thing_check = 0; //檢查是否有處理事件，預設為0
            $things_ok = 0; //預設為0 花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
            foreach($DB_calendar_thing_records as $v){ //schedule 變成陣列
                $v->schedule_array = explode(',',$v->schedule_array);
                if($v->checked) $thing_check = 1; ///如果有處理事件，顯示處理事件的開關打開
                if( $v->thing_state == '送達' && ($v->thing_name == '花圈' || $v->thing_name == '花籃' || $v->thing_name == '水' || $v->thing_name == '羅馬柱') ){
                    $things_ok = 1; //花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                }
    
            }
            // return $DB_calendar_thing_records;
            //邀請對象
            $relevant_member_names = DB::select("SELECT name FROM calendar_members WHERE account in(SELECT calendar_member_id FROM calendar_group_records WHERE calendar_datetime_record_id = '$search_id') ");
            
            if(!$DB_edit_data){
                return redirect('member/home'); //無此行程就返回，
            }else{ 
                $a_delete = url('member/detail_edit_delete?id='.$DB_edit_data[0]->id); //返回上一頁超連結
            }
            $case_begin_year = $DB_edit_data[0]->case_begin_year;
            $case_begin_month = str_pad($DB_edit_data[0]->case_begin_month,2,"0",STR_PAD_LEFT);
            $case_begin_day = str_pad($DB_edit_data[0]->case_begin_day,2,"0",STR_PAD_LEFT);
            
            
            $a_back_url = $request->session()->get('a_back_url')."?year=".$case_begin_year."&month=".$case_begin_month."&day=".$case_begin_day; //返回連結
            $select_option_thing_remark = ""; //20210911目前拈香選項用
            $edit_case_type = 0; //判斷是否 婚喪喜慶 預設 0否
            if($DB_edit_data[0]->informant){ //判斷是否 婚喪喜慶
                $edit_case_type = 1;
                //拈香動作
                // $informant_type = $DB_edit_data[0]->informant_type;
                // $DB_informant_types = DB::select("SELECT informant_type_name FROM informant_types WHERE informant_type_items LIKE '%$informant_type%' ");
                // $informant_type_name = $DB_informant_types[0]->informant_type_name;
                // switch($informant_type_name){
                //     case '婚': $option_array = []; break;
                //     case '喪': $option_array = ["未拈香","已拈香"]; break;
                //     case '喜': $option_array = []; break;
                //     case '慶': $option_array = []; break;
                // }
                // if($option_array){
                //     $option = "";
                //     foreach($option_array as $v){
                //         if($DB_edit_data[0]->thing_remark == $v) $option .= "<option value='".$v."' selected>".$v."</option>";
                //         else $option .= "<option value='".$v."'>".$v."</option>";
                //     }
                //     $select_option_thing_remark = "<select name='thing_remark' class='block w-full bg-gray-200 border-2 border-gray-200 hover:border-gray-500 px-4 py-2 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500 text-gray-700' >
                //     ".$option."
                //     </select>";
                    
                // }
            }
            //判斷是否重複群組
            $repeat_type_array = ['不重複','每日','每週','每月','每年'];
            $option_repeat_type = "";
            if($DB_edit_data[0]->repeat_group){
                //重複類型項目
                switch($DB_edit_data[0]->repeat_group[0]){
                    case 'd' : $selected_repeat_type = '每日'; break;
                    case 'w' : $selected_repeat_type = '每週'; break;
                    case 'm' : $selected_repeat_type = '每月'; break;
                    case 'y' : $selected_repeat_type = '每年'; break;
                }
                foreach($repeat_type_array as $v){
                    if($v != $selected_repeat_type) $option_repeat_type .= "<option value='$v'>$v</option>";
                    else $option_repeat_type .= "<option value='$v' selected>$v</option>";
                }
                //重複筆數
                $repeat_group_name = $DB_edit_data[0]->repeat_group;
                $repeat_group_count = DB::select("SELECT COUNT(*) AS n FROM calendar_datetime_records WHERE repeat_group = '$repeat_group_name' ");
                $repeat_group_count = $repeat_group_count[0]->n;
                $repeat_group = 1;
            }else {
                foreach($repeat_type_array as $v){
                    $option_repeat_type .= "<option value='$v'>$v</option>";
                }
                $selected_repeat_type = "不重複";
                $repeat_group_count = 5;
                $repeat_group = 0;
            }
            // return $DB_edit_data;
            
            return view('member.detail_edit',[
                'DB_edit_data' => $DB_edit_data,
                'a_delete' => $a_delete,
                'DB_calendar_thing_records' => $DB_calendar_thing_records,
                'thing_check' => $thing_check, //檢查是否有處理事件，預設為0
                'things_ok' => $things_ok, //預設為0 花圈、花籃、水、羅馬柱，四個其中一個達成，就為1
                'select_option_thing_remark' => $select_option_thing_remark, //目前拈香選項用
                // 'checked_calendar_thing_records' => $checked_calendar_thing_records,
                'a_back_url' => $a_back_url,
                'edit_case_type' => $edit_case_type,
                'DB_calendar_files' => $DB_calendar_files, //附加資料
                'relevant_member_names' => $relevant_member_names, //邀請對象
                'repeat_group' => $repeat_group, //重複群組 編輯判斷用
                'option_repeat_type' => $option_repeat_type, //重複群組項目
                'selected_repeat_type' => $selected_repeat_type, //重複群組
                'repeat_group_count' => $repeat_group_count, //重複群組總共次數
            ]);
        }
        return redirect('/login');
    }
    public function detail_edit_post(Request $request){  //修改行程
        // return $request->all();
        // return $request->informant_type;
        if($member_id = $request->session()->get('member_id') ){
            //行程修改
            $case_id = $request->case_id;
            if($request->thing_id) $thing_id_array = $request->thing_id;
            else $thing_id_array = array();
            //日期檢查
            if($request->date1 > $request->date2){
                $request->session()->put('message', '日期錯誤，修改失敗');
                return redirect()->back()->withInput();
            }
            $calendar_datetime_record = CalendarDatetimeRecord::find($case_id);
            $calendar_datetime_record_member_id = $calendar_datetime_record->member_id;
            $repeat_group = $calendar_datetime_record->repeat_group;
            $repeat_group_count = DB::select("SELECT COUNT(*) AS n FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' ");
            $repeat_group_count = $repeat_group_count[0]->n;
            //檢查是否有更改重複值
            if($repeat_group){
                switch($repeat_group[0]){
                    case 'd' : $selected_repeat_type = '每日'; break;
                    case 'w' : $selected_repeat_type = '每週'; break;
                    case 'm' : $selected_repeat_type = '每月'; break;
                    case 'y' : $selected_repeat_type = '每年'; break;
                }
            }else{
                $selected_repeat_type = '不重複';
            }
            $new_repeat_group = ""; //不改變群組
            if($selected_repeat_type != $request->repeat_type){ //與原本重複群組的不相同
                if($request->repeat_type == '不重複'){ //群組取消 repeat_group = null
                    $delete_select_ids = "SELECT id FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' AND id !='$case_id'";
                    DB::select("DELETE FROM `calendar_group_records` WHERE calendar_datetime_record_id IN ( $delete_select_ids )");
                    DB::select("DELETE FROM `calendar_thing_records` WHERE calendar_datetime_record_id IN ( $delete_select_ids )");
                    DB::select("DELETE FROM `calendar_files` WHERE calendar_datetime_record_id IN ( $delete_select_ids )");
                    DB::select("DELETE FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' AND id !='$case_id' ");
                    $calendar_datetime_record->repeat_group = null;
                    $calendar_datetime_record->save();
                }else{ //群組變化  1.原本不重複改為重複 ex:不重複改為每月 2.重複群組改變 ex:每週改每日  
                    $now = Carbon::now(); 
                    $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                    switch ($request->repeat_type){
                        case '每日': $type='d'; break;
                        case '每週': $type='w'; break;
                        case '每月': $type='m'; break;
                        case '每年': $type='y'; break;
                    }
                    $new_repeat_group = $type.$now->timestamp; //時間線群組名稱
                    // return '與原本的不相同';
                }
            }else{ //
                //與原本的相同
                //
                // return '例外(再說)';
            }
            //判斷修改方式1.修改全部行程(1.修改全部2.增加重複行程) 2.修改此行程和後續行程 3.修改此行程(1.修改此行程為重複2.修改此行程)
            if($request->change_repeat_type == '3'){ //修改全部行程
                if($repeat_group_count != $request->repeat_number){
                    if($repeat_group_count < $request->repeat_number){ //比原來還多的時候
                        $DB_repeat_group_ids = DB::select("SELECT id, SUBSTR(case_begin, 1, 10) AS case_begin, SUBSTR(case_end, 1, 10) AS case_end  FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' ");
                        foreach(range(1,$request->repeat_number - $repeat_group_count) as $v){
                            $DB_repeat_group_ids[] = ['id' => 'copy'];
                        }
                        $DB_repeat_group_ids = json_decode(json_encode($DB_repeat_group_ids));
                        if(!$new_repeat_group) $new_repeat_group = $repeat_group;
                    }else{ //比原來還少的時候 "先刪除不要的資料，再處理所有資料"
                        //先刪除不要的資料
                        $delete_select_ids = "SELECT id FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' LIMIT $request->repeat_number, 99999 ";
                        // return $delete_select_ids;
                        //下面這些刪除方式 (奇葩刪除方式，為了繞過此錯誤"Syntax error or access violation: 1235")
                        DB::select("DELETE FROM calendar_group_records WHERE calendar_datetime_record_id IN (select id from ( $delete_select_ids ) temp_tab)");
                        DB::select("DELETE FROM calendar_thing_records WHERE calendar_datetime_record_id IN (select id from ( $delete_select_ids ) temp_tab)");
                        DB::select("DELETE FROM calendar_files WHERE calendar_datetime_record_id IN (select id from ( $delete_select_ids ) temp_tab)");
                        DB::select("DELETE FROM calendar_datetime_records WHERE id IN (select id from ( $delete_select_ids ) temp_tab)");
                        //再處理所有資料
                        $DB_repeat_group_ids = DB::select("SELECT id, SUBSTR(case_begin, 1, 10) AS case_begin, SUBSTR(case_end, 1, 10) AS case_end FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' ");
                        // return $DB_repeat_group_ids;
                        // $request->session()->put('message', '請使用刪除功能');
                        // return redirect()->back();
                    }
                }else{
                    $DB_repeat_group_ids = DB::select("SELECT id, SUBSTR(case_begin, 1, 10) AS case_begin, SUBSTR(case_end, 1, 10) AS case_end FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' ");
                }
                //初始時間
                $date1 = Carbon::createFromDate($DB_repeat_group_ids[0]->case_begin);
                $date1->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                $date2 = Carbon::createFromDate($DB_repeat_group_ids[0]->case_end);
                $date2->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            }elseif($request->change_repeat_type == '2'){ //修改此行程和後續行程
                $case_begin = $calendar_datetime_record->case_begin;
                $DB_repeat_group_ids = DB::select("SELECT id FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' AND case_begin >= '$case_begin' ");
                //初始時間
                $date1 = Carbon::createFromDate($request->date1);
                $date1->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                $date2 = Carbon::createFromDate($request->date2);
                $date2->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            }else{ // 1.修改此行程為重複 2.修改此行程
                if($new_repeat_group ){ //1.修改此行程為重複
                    $array[] = ['id' => $case_id];
                    foreach(range(1,$request->repeat_number - 1 ) as $v){
                        $array[] = ['id' => 'copy'];
                    }
                    $DB_repeat_group_ids = json_decode(json_encode($array));
                    // return $request->all();
                    // return $DB_repeat_group_ids[1]->id;
                }else{ //2.修改此行程
                    $id = $calendar_datetime_record->id;
                    $DB_repeat_group_ids = DB::select("SELECT id FROM calendar_datetime_records WHERE id = '$id' ");
                }
                //初始時間
                $date1 = Carbon::createFromDate($request->date1);
                $date1->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                $date2 = Carbon::createFromDate($request->date2);
                $date2->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            }

            //取得時間
            if($request->case_all_day){ //行程新增 整天
                $case_begin_time = "T00:00:00";
                $case_end_time = "T00:00:00";
                $case_all_day = 1;
            }else{ //行程新增 時段
                if($request->date1 == $request->date2 && $request->time1 >= $request->time2){
                    $request->session()->put('message', '時段錯誤，新增失敗');
                    return redirect()->back();
                }
                $case_begin_time = "T".$request->time1;
                $case_end_time = "T".$request->time2;
                $case_all_day = 0;
            }

            
            // return $request->repeat_type;
            // return $request->all();
            //關係人員
            $relevant_customer = "";
            if($request->relevant_customer){
                $relevant_customer = implode(',',$request->relevant_customer);
            }
            foreach($DB_repeat_group_ids as $v){
                if($v->id == 'copy'){
                    $calendar_datetime_record = new CalendarDatetimeRecord();
                    $calendar_datetime_record->member_id = $calendar_datetime_record_member_id;
                }else{
                    $calendar_datetime_record = CalendarDatetimeRecord::find($v->id);
                }
                //使用mysql copy資料的方式
                // DB::select("INSERT INTO `calendar_datetime_records`(`member_id`, `repeat_group`, `relevant_members`, `case_level`, `informant`, `informant_type`, `case_title`, `case_content`, `case_location`, `case_remarks`, `case_begin`, `case_end`, `case_all_day`) 
                // SELECT `member_id`, `repeat_group`, `relevant_members`, `case_level`, `informant`, `informant_type`, `case_title`, `case_content`, `case_location`, `case_remarks`, `case_begin`, `case_end`, `case_all_day` FROM `calendar_datetime_records` WHERE id = 3567")
                
                // if($calendar_datetime_record->informant_type != $request->informant_type){
                //     $DB_informant_types = DB::select("SELECT informant_type_name FROM informant_types WHERE informant_type_items LIKE '%$request->informant_type%'");
                //     $informant_type_name = $DB_informant_types[0]->informant_type_name;
                //     switch($informant_type_name){
                //         case '婚': $request->thing_remark = NULL; break;
                //         case '喪': $request->thing_remark = "未拈香"; break;
                //         case '喜': $request->thing_remark = NULL; break;
                //         case '慶': $request->thing_remark = NULL; break;
                //     }
                // }

                if($new_repeat_group) $calendar_datetime_record->repeat_group = $new_repeat_group;
                $calendar_datetime_record->informant = $request->informant_unit;
                $calendar_datetime_record->informant_type = $request->informant_type;
                // $calendar_datetime_record->thing_remark = $request->thing_remark;
                $calendar_datetime_record->case_title = $request->case_title;
                $calendar_datetime_record->case_content = $request->case_content;
                $calendar_datetime_record->relevant_members = $relevant_customer;
                // $calendar_datetime_record->relevant_phone = $request->relevant_phone;
                $calendar_datetime_record->case_location = $request->case_location;
                $calendar_datetime_record->case_remarks = $request->case_remarks;
                $calendar_datetime_record->case_all_day = $case_all_day;
                $calendar_datetime_record->case_begin = $date1->ToDateString().$case_begin_time;
                $calendar_datetime_record->case_end = $date2->ToDateString().$case_end_time;
                $calendar_datetime_record->case_level = $request->case_level;
                $calendar_datetime_record->save();

                $calendar_datetime_record_id_array[] = $calendar_datetime_record->id; //圖片新增刪除用

                //行程處理事件狀態 (新增)
                if(count($thing_id_array)>0){
                    foreach($thing_id_array as $v ){ //如果沒有此事件，就新增
                        if(!DB::select("SELECT * FROM calendar_thing_records WHERE calendar_datetime_record_id = '$calendar_datetime_record->id' AND thing_id = '$v' ") ){
                            if($DB_things = DB::select("SELECT name, schedule FROM things WHERE id = $v")){
                                if($DB_things[0]->name == "禮金"){ //禮金
                                    $thing_state = $request->giftMoney[1];
                                }else{
                                    $schedule_array = explode(",",$DB_things[0]->schedule);
                                    $thing_state = $schedule_array[0];
                                }
                            }else{
                                $thing_state = "請選擇";
                            }
                            // if($v == 5){ //禮金
                            //     $thing_state = $request->giftMoney[1];
                            // }else if($v == 6){ //拈香
                            //     $thing_state = "未拈香";
                            // }else{ //其他未訂
                            //     $thing_state = "未訂";
                            // }
                            CalendarThingRecord::create([
                                'calendar_datetime_record_id' => $calendar_datetime_record->id,
                                'thing_id' => $v,
                                'thing_state' => $thing_state,
                            ]);

                            // CalendarThingRecord::create([
                            //     'calendar_datetime_record_id' => $calendar_datetime_record->id,
                            //     'thing_id' => $v,
                            //     'thing_state' => 1,
                            // ]);
                        }
                    }
                }
                //行程處理事件狀態 (刪除)
                $DB_calendar_thing_record_all_id = array();
                $DB_calendar_thing_record_all = DB::select("SELECT id, thing_id FROM calendar_thing_records WHERE calendar_datetime_record_id = '$calendar_datetime_record->id'");
                if($DB_calendar_thing_record_all){
                    foreach($DB_calendar_thing_record_all as $v){
                        $DB_calendar_thing_record_all_id[] = $v->thing_id; 
                    }
                }
                $delete_thing_records = array_merge(array_diff($DB_calendar_thing_record_all_id, $thing_id_array));
                foreach($delete_thing_records as $v ){ //如果沒有此事件，否則就刪除
                    $DB_calendar_thing_records = DB::select("SELECT * FROM calendar_thing_records WHERE calendar_datetime_record_id = '$calendar_datetime_record->id' AND thing_id = '$v'");
                    $calendar_thing_record = CalendarThingRecord::find($DB_calendar_thing_records[0]->id);
                    $calendar_thing_record->delete();
                }
                //行程處理事件狀態修改
                if($request->thing_state){
                    foreach($request->thing_state as $v){
                        $v_array = explode(',',$v);
                        $calendar_thing_record = CalendarThingRecord::find($v_array[0]);
                        if($calendar_thing_record){
                            $calendar_thing_record->thing_state = $v_array[1];
                            $calendar_thing_record->save();
                        }
                    }
                }
                if(!empty($request->giftMoney[0])){ 
                    $calendar_thing_record = CalendarThingRecord::find($request->giftMoney[0]);
                    if($calendar_thing_record){
                        $calendar_thing_record->thing_state = $request->giftMoney[1];
                        $calendar_thing_record->save();
                    }
                }
                //新增邀請對象 
                if($request->relevant_member){
                    foreach($request->relevant_member as $v){
                        if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE name = '$v'")){ //如果有此人才執行
                            $account = $DB_calendar_members[0]->account;
                            if(!DB::select("SELECT * FROM calendar_group_records WHERE calendar_datetime_record_id = '$calendar_datetime_record->id' AND calendar_member_id = '$account'")){
                                CalendarGroupRecord::create([
                                    "calendar_datetime_record_id" => $calendar_datetime_record->id,
                                    "calendar_member_id" => $account,
                                ]); 
                            }
                        }
                    }
                }
                //刪除邀請對象 (1)刪除其他重複行程的邀請人員
                if(!$request->relevant_member) $request->relevant_member = array();
                $relevant_member = implode("','",$request->relevant_member);
                $DB_calendar_group_record_delete_ids = DB::select("DELETE c1
                    FROM calendar_group_records AS c1
                    INNER JOIN calendar_members AS c2 ON c1.calendar_member_id = c2.account
                    WHERE c1.calendar_datetime_record_id = '$calendar_datetime_record->id' AND c2.name NOT IN ('".$relevant_member."') ");
                //(2)勾選刪除邀請人員
                if($request->delete_relevant_member){
                    foreach($request->delete_relevant_member as $v){
                        if($DB_calendar_member = DB::select("SELECT account FROM calendar_members WHERE name='$v' ")){
                            $account = $DB_calendar_member[0]->account;
                            if($DB_calendar_group_record = DB::select("SELECT id FROM calendar_group_records WHERE calendar_datetime_record_id='$calendar_datetime_record->id' AND calendar_member_id ='$account' ")){
                                $CalendarGroupRecord = CalendarGroupRecord::find($DB_calendar_group_record[0]->id); 
                                $CalendarGroupRecord->delete();
                            }
                        }
                    }
                }
                if($request->file_name){
                    foreach($request->file_name as $file_name){ //一個圖片共用所有行程
                        $array = explode(".",$file_name);
                        if(!DB::select("SELECT * FROM calendar_files WHERE calendar_datetime_record_id = '$calendar_datetime_record->id' ")){
                            CalendarFile::create([
                                'calendar_datetime_record_id' => $calendar_datetime_record->id,
                                'file_name' => $array[0],
                                'file_type' => $array[1]
                            ]);
                        }
                    }
                }

                switch ($request->repeat_type){
                    case '每日': $date1->addDay(); $date2->addDay(); break;
                    case '每週': $date1->addWeek(); $date2->addWeek(); break;
                    case '每月': $date1->addMonth(); $date2->addMonth(); break;
                    case '每年': $date1->addYear(); $date2->addYear(); break;
                }

            }
            //因為共用，所以圖片修改，是修改全部
            //圖片新增
            if($request->img_file){
                for($i=0;$i<count($request->img_file);$i++){
                    $fileName = $_FILES['img_file']['name'][$i]; //圖片名稱
                    $fileType = $_FILES['img_file']['type'][$i]; //圖片單位類型
                    $fileTmpName = $_FILES['img_file']['tmp_name'][$i]; //
                    $fileError = $_FILES['img_file']['error'][$i]; //
                    $fileSize = $_FILES['img_file']['size'][$i]; //圖片大小
                    $imageType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); //圖片副檔名
                    $fileNewName = md5(uniqid()); //圖片名稱(亂碼，避免覆蓋)
                    $target_dir = "images/upload/add_case_file/";
                    $target_file = $target_dir . $fileNewName . "." . $imageType;
                    move_uploaded_file($fileTmpName, $target_file);
                    foreach($calendar_datetime_record_id_array as $v){ //一個圖片共用所有行程
                        CalendarFile::create([
                            'calendar_datetime_record_id' => $v,
                            'file_name' => $fileNewName,
                            'file_type' => $imageType
                        ]);
                    }
                }
            }
            //圖片刪除
            if($request->delete_file){
                foreach($request->delete_file as $v){ 
                    //查找要刪除的所有圖片位置，刪除圖片
                    $calendar_file = CalendarFile::find($v); 
                    $file = $calendar_file->file_name.".".$calendar_file->file_type;
                    $target_file = "images/upload/add_case_file/".$file;
                    try { //檢查是否有錯誤，如果沒錯誤，不執行catch，繼續往下執行try catch以下的程式
                        unlink($target_file);
                        throw new Exception();
                    } catch (Exception $e) { //錯誤會執行這段
                        echo 'Caught exception: ',  $e->getMessage(), "\n"; 
                    }
                    //刪除(重複)行程的圖片
                    $file_name = $calendar_file->file_name;
                    DB::select("DELETE FROM `calendar_files` WHERE file_name = '$file_name' ");
                }
            }
            //檢查目前重複群組，如果此群組只有一個，修改為null
            $DB_repeat_group_null_ids = DB::select("SELECT repeat_group, CASE WHEN COUNT(id) <= 1 THEN id END AS repeat_group_null_id FROM calendar_datetime_records WHERE repeat_group IS NOT NULL GROUP BY repeat_group");
            if($DB_repeat_group_null_ids){
                foreach($DB_repeat_group_null_ids as $v){
                    if($v->repeat_group_null_id){
                        DB::select("UPDATE calendar_datetime_records SET repeat_group = NULL WHERE id = '$v->repeat_group_null_id' ");
                    }
                }
            }
            // return redirect()->back();
            // return back()->withInput();
            return redirect()->back()->withInput();
        }
        return redirect('/login');
    }
    public function detail_edit_delete(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $calendar_datetime_record = CalendarDatetimeRecord::find($request->id);
            if($request->delete_type == '2'){ //刪除此行程和後續行程
                $repeat_group = $calendar_datetime_record->repeat_group;
                $case_begin = $calendar_datetime_record->case_begin;
                $DB_repeat_group_ids = DB::select("SELECT id FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' AND case_begin >= '$case_begin' ");
                
            }elseif($request->delete_type == '3'){ //刪除全部行程
                $repeat_group = $calendar_datetime_record->repeat_group;
                $DB_repeat_group_ids = DB::select("SELECT id FROM calendar_datetime_records WHERE repeat_group = '$repeat_group' ");
                
            }else{ //刪除此行程
                $id = $calendar_datetime_record->id;
                $DB_repeat_group_ids = DB::select("SELECT id FROM calendar_datetime_records WHERE id = '$id' ");
            }
            // return $DB_repeat_group_ids;
            // return $request->all();

            foreach($DB_repeat_group_ids as $v){
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
            }
            //圖片刪除
            $DB_calendar_files = DB::select("SELECT * FROM calendar_files WHERE calendar_datetime_record_id = '$request->id'");
            foreach($DB_calendar_files as $v){
                //查找要刪除的所有圖片位置，刪除圖片
                $calendar_file = CalendarFile::find($v->id);
                $file = $calendar_file->file_name.".".$calendar_file->file_type;
                $target_file = "images/upload/add_case_file/".$file;
                try { //檢查是否有錯誤，如果沒錯誤，不執行catch，繼續往下執行try catch以下的程式
                    unlink($target_file);
                    throw new Exception();
                } catch (Exception $e) { //錯誤會執行這段
                    echo 'Caught exception: ',  $e->getMessage(), "\n"; 
                }
                // $calendar_file->delete();
                //刪除(重複)行程的圖片
                $file_name = $calendar_file->file_name;
                DB::select("DELETE FROM `calendar_files` WHERE file_name = '$file_name' ");
            }
            //檢查目前重複群組，如果此群組只有一個，修改為null
            $DB_repeat_group_null_ids = DB::select("SELECT repeat_group, CASE WHEN COUNT(id) <= 1 THEN id END AS repeat_group_null_id FROM calendar_datetime_records WHERE repeat_group IS NOT NULL GROUP BY repeat_group");
            if($DB_repeat_group_null_ids){
                foreach($DB_repeat_group_null_ids as $v){
                    if($v->repeat_group_null_id){
                        DB::select("UPDATE calendar_datetime_records SET repeat_group = NULL WHERE id = '$v->repeat_group_null_id' ");
                    }
                }
            }

            return redirect()->back();
        }
        return redirect('/login');
    }
    public function get_informant_types(Request $request){ //取得通報類型(json)
        if($member_id = $request->session()->get('member_id') ){
            //通報單位
            $save_informant_option = $request->informant;
            $DB_informants = DB::select("SELECT I1.id, I1.informant_name,
                                        CASE WHEN I1.informant_name = '$save_informant_option' THEN 1 ELSE 0 END AS informant_name_select,
                                        I2.informant_name_check
                                        FROM informants AS I1
                                        CROSS JOIN (
                                            SELECT SUM(CASE WHEN informant_name = '$save_informant_option' THEN 1 ELSE 0 END) AS informant_name_check
                                            FROM informants
                                        ) AS I2");
            if($DB_informants[0]->informant_name_check){
                $informants_option = "<option value=''></option>";
            }else{
                $informants_option = "<option value='".$save_informant_option."'>".$save_informant_option."</option>";
            }
            foreach($DB_informants as $v){
                if($v->informant_name_select){
                    $informants_option .= "<option value='".$v->informant_name."' selected>".$v->informant_name."</option>";
                }else{
                    $informants_option .= "<option value='".$v->informant_name."'>".$v->informant_name."</option>";
                }
            }
            //編輯畫面用的資料的
            $save_informant_type_item_radio = $request->item;
            $save_informant_type_option = "";
            if($save_informant_type_item_radio){
                $DB_informant_types = DB::select("SELECT * FROM informant_types WHERE informant_type_items LIKE '%$save_informant_type_item_radio%'");
                $save_informant_type_option = $DB_informant_types[0]->informant_type_name;
            }
            //取得通報選項與細項
            $DB_informant_types = DB::select("SELECT id, informant_type_name, informant_type_items FROM informant_types");
            $type_option =""; //通報類型
            $type_checkbox_array_items =""; //通報細分項目
            foreach($DB_informant_types as $k=>$v){
                //通報類型
                if($v->informant_type_name == $save_informant_type_option){
                    $type_option .= "<option value='type".$v->id."' selected>".$v->informant_type_name."</option>";
                }else{
                    $type_option .= "<option value='type".$v->id."'>".$v->informant_type_name."</option>";
                }
                //通報細分項目
                $array_items = explode(',',$v->informant_type_items);
                $checkbox_array_items = "";
                foreach($array_items as $k2=>$v2){
                    if($save_informant_type_item_radio == $v2){
                        $checkbox_array_items .="
                            <label class='inline-flex items-center px-1'>
                                <input type='radio' class='form-checkbox h-4 w-4' value='".$v2."' name='informant_type' required checked>
                                <span class='ml-2'>".$v2."</span>
                            </label>";
                    }else{
                        $checkbox_array_items .="
                        <label class='inline-flex items-center px-1'>
                            <input type='radio' class='form-checkbox h-4 w-4' value='".$v2."' name='informant_type' required >
                            <span class='ml-2'>".$v2."</span>
                        </label>";
                    }
                }
                $type_checkbox_array_items.= "<div class='blcok hidden' id='type".$v->id."'>".$checkbox_array_items."</div>";
            }
            $informat_type_checkbox =  "<div class='w-2/6'></div>
                                        <div class='w-4/6'>
                                            ".$type_checkbox_array_items."
                                        </div>";
            //處理事項
            $DB_things = DB::select("SELECT id, name FROM things ");
            $checkbox_things = "";
            foreach($DB_things as $v){
                $checkbox_things .= "<label class='inline-flex items-center mr-3'>
                    <input type='checkbox' class='form-checkbox h-6 w-6' value='".$v->id."' name='thing_id[]'>
                    <span class='ml-2 text-lg'>".$v->name."</span>
                </label>";
            }
            $checkbox_things .= "<div id='thing_state' class='hidden'>
                <input type='text' name='thing_state' value='' placeholder='請輸入禮金' class=' bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500'>
            </div>";
            //取得邀請對象
            if($request->relevant_member){
                $relevant_member = implode("','",$request->relevant_member);
            }else{
                $relevant_member = "";
            }
            $DB_calendar_members = DB::select("SELECT name FROM calendar_members WHERE name NOT IN ('".$relevant_member."')");
            $option_calendar_members = "<option value=''>請選擇</option>";
            foreach($DB_calendar_members as $v){
                $option_calendar_members .= "<option value='".$v->name."'>".$v->name."</option>";
            }
            //取得行程人員
            $DB_calendar_members = DB::select("SELECT * FROM calendar_members ORDER BY name = '王秝毅' DESC ");
            $option_calendar_members_id = "";
            foreach($DB_calendar_members as $v){
                $option_calendar_members_id .= "<option value='".$v->account."'>".$v->name."</option>";
            }
            //固定邀請對象 (婚喪喜慶)
            $relevant_member_array1 = ["1556","1341","0856"];
            $add_div_relevant_member1 = "";
            foreach($relevant_member_array1 as $v){
                if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE account = $v")){
                    $name = $DB_calendar_members[0]->name;
                    $add_div_relevant_member1 .= "<div class='flex mb-1'>
                        <input type='text' name='relevant_member[]' value='".$name."' class='w-2/5 py-2 pr-8 mr-1 text-center text-base text-gray-700 outline-none rounded-lg text-lg' readonly>
                        <button type='button' class='btn_delete_relevant_member bg-red-500 hover:bg-red-700 text-white font-bold w-11 h-11 rounded-lg'>
                            <i class='material-icons'>clear</i>
                        </button>
                    </div>";
                }
            }
            //固定邀請對象 (一般行程)
            $relevant_member_array2 = ["1556","1341"];
            $add_div_relevant_member2 = "";
            foreach($relevant_member_array2 as $v){
                if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE account = $v")){
                    $name = $DB_calendar_members[0]->name;
                    $add_div_relevant_member2 .= "<div class='flex mb-1'>
                        <input type='text' name='relevant_member[]' value='".$name."' class='w-2/5 py-2 pr-8 mr-1 text-center text-base text-gray-700 outline-none rounded-lg text-lg' readonly>
                        <button type='button' class='btn_delete_relevant_member bg-red-500 hover:bg-red-700 text-white font-bold w-11 h-11 rounded-lg'>
                            <i class='material-icons'>clear</i>
                        </button>
                    </div>";
                }
            }
            
            // $DB_calendar_members = DB::select("SELECT * FROM calendar_members");
            // $option_calendar_members_id = "";
            // foreach($DB_calendar_members as $v){
            //     if($v->account == $member_id){
            //         $option_calendar_members_id .= "<option value='".$v->account."' selected>".$v->name."</option>";
            //     }else{
            //         $option_calendar_members_id .= "<option value='".$v->account."'>".$v->name."</option>";
            //     }
            // }
            

                
            $result = array(
                "type_option" => $type_option,
                "informat_type_checkbox" => $informat_type_checkbox,
                "informants_option" => $informants_option,
                "checkbox_things" => $checkbox_things,
                "option_calendar_members" => $option_calendar_members,
                "option_calendar_members_id" => $option_calendar_members_id,
                "add_div_relevant_member1" => $add_div_relevant_member1,
                "add_div_relevant_member2" => $add_div_relevant_member2,
            );
            
            return response()->json($result);

            
            // $result = array(
            //     "informants_option" => $informants_option,
            //     "tt" => 123,
            //     "type_option" => $type_option,
            //     "informat_type_checkbox" => $informat_type_checkbox,
            //     "checkbox_things" => $checkbox_things,
            //     "option_calendar_members" => $option_calendar_members,
            // );
            // try { 
            //     throw new Exception();
            // } catch (Exception $e) { //錯誤會執行這段
            //     $ttt="";
            // }
            // return response()->json($result);
        }
    }
    

    public function phoneToMember(Request $request){ //根據電話取得客戶名字(json)
        if($member_id = $request->session()->get('member_id') ){
            $relevant_phone = $request->relevant_phone;
            $informant_type = $request->informant_type;
            if($relevant_phone){
                $DB_crm__customer_basic_informations = DB::select("SELECT c_name_company, c_sex FROM crm__customer_basic_informations WHERE c_telephone = '$relevant_phone' OR c_cellphone = '$relevant_phone' ");
            }

            $first_label = "";
            if($informant_type == "喪" && $DB_crm__customer_basic_informations[0]->c_sex == "男") $first_label = '孝男';
            elseif($informant_type == "喪" && $DB_crm__customer_basic_informations[0]->c_sex == "女") $first_label = '孝女';
            
            $result = array(
                "c_name" => $first_label . " " . $DB_crm__customer_basic_informations[0]->c_name_company,
            );
            
            return response()->json($result);
        }
        return redirect('/login');
    }


    public function things_list_form(Request $request){ //處理清單
        if($member_id = $request->session()->get('member_id') ){
            $request->session()->put('a_back_url',url("member/things_list")); //編輯返回用
            if(!$date_array = $request->session()->get('things_list_form_date_array')){
                $now = Carbon::now(); 
                $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                for($i=0;$i<10;$i++){
                    $date_array[] = $now->ToDateString();
                    $now->addDay();
                }
            }
            $start_date = $date_array[0];
            $end_date = $date_array[count($date_array)-1];
            
            // return $date_array;
            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            $DB_calendar_datetime_records = DB::select("SELECT id, case_title, 
                SUBSTR(DATE(case_begin),6,5) AS case_begin_date, DATE(case_end) AS case_end_date, 
                SUBSTR(TIME(case_begin),1,5) AS case_begin_time, TIME(case_end) AS case_end_time,
                0 AS complete, '' as things
                FROM calendar_datetime_records
                WHERE (member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) AND
                ((DATE(case_begin) >= '$start_date' AND DATE(case_begin) <= '$end_date') OR 
                (DATE(case_end) >= '$start_date' AND DATE(case_end) < '$end_date') OR 
                (DATE(case_begin) <= '$start_date' AND DATE(case_end) >= '$end_date' ) OR 
                ( DATE(case_begin) = '$start_date' AND DATE(case_end) = '$end_date' )) AND
                id IN (select calendar_datetime_record_id FROM calendar_thing_records)
                ORDER BY case_begin");
            // return $DB_calendar_datetime_records;
            foreach($DB_calendar_datetime_records as $v){
                $DB_calendar_thing_records = DB::select("SELECT c1.id, c1.thing_id, t2.name,
                    t2.schedule AS schedule_array,
                    c1.thing_state
                    FROM calendar_thing_records AS c1
                    LEFT JOIN things AS t2 ON c1.thing_id = t2.id
                    WHERE c1.calendar_datetime_record_id = '$v->id'"); 

                $date_things = array();
                $count_complete = 0;
                // return $DB_calendar_thing_records;
                foreach($DB_calendar_thing_records as $v2){
                    $schedule_array = explode(",",$v2->schedule_array);
                    if( ($v2->thing_state == '送達') || $v2->name == "禮金") {
                        $count_complete++;
                    }
                    $date_things[] = [ 'id'=>$v2->id, 'name'=> $v2->name, 'schedule' => $v2->thing_state, 'schedule_array' => $schedule_array ];
                }
                // return count($DB_calendar_thing_records);
                $v->things = $date_things;
                if(count($DB_calendar_thing_records) == $count_complete) $v->complete = 1;
            }
            // return $DB_calendar_datetime_records;
            // $DB_calendar_thing_records = DB::select("SELECT * 
            // FROM calendar_thing_records 
            // WHERE calendar_datetime_record_id IN (SELECT id FROM calendar_datetime_records
            //     WHERE member_id = '$member_id' AND
            //     ((case_begin > '$start_date' AND case_begin < '$end_date') OR 
            //     (case_end > '$start_date' AND case_end < '$end_date') OR 
            //     (case_begin <= '$start_date' AND case_end >= '$end_date' ) OR 
            //     ( case_begin = '$start_date' AND case_end = '$end_date' )))
            // ");
            // return $DB_calendar_thing_records;
            
            return view('member.list.things_list',[
                "DB_calendar_datetime_records" => $DB_calendar_datetime_records,
                "start_date" => $start_date,
                "end_date" => $end_date,
                
            ]);
        }
        return redirect('/login');
    }
    public function things_list_post(Request $request){ //查詢處理清單
        if($member_id = $request->session()->get('member_id') ){
            $date1 = $request->date1;
            $date2 = $request->date2;
            if($date1>$date2){
                $request->session()->put('message','日期錯誤');
                return redirect()->back();
            }
            $days=round((strtotime($date2)-strtotime($date1))/3600/24);
            
            $date = Carbon::create($date1);
            for($i=0;$i<=$days;$i++){
                $date_array[] = $date->ToDateString();
                $date->addDay();
            }
            $request->session()->put('things_list_form_date_array',$date_array);
            return redirect()->back();
        }
        return redirect('/login');
    }
    
    public function thing_state_change(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $v_array = explode(',',$request->data);
            $calendar_thing_record = CalendarThingRecord::find($v_array[0]);
            if($calendar_thing_record){
                $calendar_thing_record->thing_state = $v_array[1];
                $calendar_thing_record->save();
            }
            $result = array(
                "tt" => 1,
            );
            
            return response()->json($result);
        }
    }

    public function file_delete(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $id = $request->id;
            $calendar_file = CalendarFile::find($id);
            $file = $calendar_file->file_name.".".$calendar_file->file_type;
            $target_file = "images/upload/add_case_file/".$file;
            unlink($target_file);
            $calendar_file->delete();

            $result = array(
                "tt" => 1,
            );
            return response()->json($result);
        }
    }
    public function search_calendar(Request $request){ //行程查詢
        if($member_id = $request->session()->get('member_id') ){
            $search_type = $request->session()->get('search_calendar_search_type');
            $search_value = $request->session()->get('search_calendar_search_value');

            //去除特殊符號
            $search_value = str_replace(array("\r", "\n", "\r\n", "\n\r","'","%"), '', $search_value);

            $now = Carbon::now(); 
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $today = $now->ToDateString();;

            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            $where = "(member_id = '$member_id' OR id in ('".$group_calendar_datetime_record_ids."') ) ";
            switch ($search_type){
                case '無': $where .= "AND (case_title LIKE '%$search_value%' OR case_content LIKE '%$search_value%' OR case_location LIKE '%$search_value%' OR case_remarks LIKE '%$search_value%' ) "; break;
                case '標題': $where .= "AND case_title LIKE '%$search_value%' "; break;
                case '內容': $where .= "AND case_content LIKE '%$search_value%' "; break;
                case '地點': $where .= "AND case_location LIKE '%$search_value%' "; break;
                case '備註': $where .= "AND case_remarks LIKE '%$search_value%' "; break;
            }
            $DB_calendar_datetime_records = "";
            if($search_value){
                // GROUP_CONCAT (DISTINCT col ORDER BY col DESC SEPARATOR ',') AS col  //DISTINCT是處理掉重複值
                // JSON_ARRAYAGG (DISTINCT id ORDER BY case_begin DESC) AS col  //JSON_ARRAYAGG 新的欄位字串方式(MariaDB 10.5以上才有 或 mysqql 5.7.22以上才有)
                // $DB_calendar_datetime_records = DB::select("SELECT case_title, 
                //     SUBSTRING(case_begin, 1, 10) AS case_date, 
                //     SUBSTRING(case_begin, 9, 2) AS case_day,
                //     CASE WEEKDAY(case_begin) 
                //         WHEN 0 THEN '週日' WHEN 1 THEN '週一' WHEN 2 THEN '週二' WHEN 3 THEN '週三' WHEN 4 THEN '週四' WHEN 5 THEN '週五' WHEN 6 THEN '週六'
                //     END AS case_weekday,
                //     CASE WHEN SUBSTRING(case_begin, 1, 10) LIKE '$today' THEN 1 ELSE 0 END AS case_today,
                //     JSON_ARRAYAGG(
                //         JSON_OBJECT(
                //         'case_id', id,
                //         'case_level', case_level,
                //         'case_time', CASE WHEN case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end) THEN CONCAT(SUBSTR(case_begin,12,5),'-',SUBSTR(case_end,12,5)) ELSE '' END ,
                //         'case_title', case_title
                //     )ORDER BY case_begin ) AS case_event
                //     FROM calendar_datetime_records 
                //     WHERE $where
                //     GROUP BY case_date
                //     ORDER BY case_all_day, case_begin DESC");

                $DB_calendar_datetime_records = DB::select("SELECT case_title,
                    SUBSTRING(case_begin, 1, 10) AS case_date, 
                    SUBSTRING(case_begin, 9, 2) AS case_day,
                    CASE WEEKDAY(case_begin) 
                        WHEN 0 THEN '週日' WHEN 1 THEN '週一' WHEN 2 THEN '週二' WHEN 3 THEN '週三' WHEN 4 THEN '週四' WHEN 5 THEN '週五' WHEN 6 THEN '週六'
                    END AS case_weekday,
                    CASE WHEN SUBSTRING(case_begin, 1, 10) LIKE '$today' THEN 1 ELSE 0 END AS case_today,
                    CONCAT('[',COALESCE(
                        GROUP_CONCAT(CONCAT(
                            '{',
                                '\"case_id\": \"', id, '\", ',
                                '\"case_level\": \"', case_level, '\",',
                                '\"case_time\": \"', CASE WHEN case_all_day = 0 AND DATE(case_begin) LIKE DATE(case_end) THEN CONCAT(SUBSTR(case_begin,12,5),'-',SUBSTR(case_end,12,5)) ELSE '' END, '\",',
                                '\"case_title\": \"', case_title, '\"',
                            '}')
                        ORDER BY case_begin
                        SEPARATOR ','),
                        ''),
                    ']') AS case_event
                    FROM calendar_datetime_records 
                    WHERE $where
                    GROUP BY case_date
                    ORDER BY case_all_day, case_begin DESC"); 
                
                //JSON_ARRAYAGG搭配php json_decode 啟用json格式
                foreach ($DB_calendar_datetime_records as $v) {
                    $v->case_event = json_decode($v->case_event);
                }
            }
            $request->session()->put('a_back_url',url("member/search_calendar"));
            return view("member.search_calendar",[
                "DB_calendar_datetime_records" => $DB_calendar_datetime_records,
                "search_type" => $search_type,
                "search_value" => $search_value,
            ]);
        }
        return redirect('/login');
    }
    public function search_calendar_post(Request $request){ //行程查詢
        if($member_id = $request->session()->get('member_id') ){

            $request->session()->put('search_calendar_search_type',$request->search_type);
            $request->session()->put('search_calendar_search_value',$request->search_value);

            return redirect()->back();
        }
        return redirect('/login');
    }

    public function personalSet(Request $request){ //修改個人資料頁面(密碼)
        if($member_id = $request->session()->get('member_id') ){

            $member = DB::select("SELECT * FROM calendar_members WHERE account = $member_id");
            $md5_password = $member[0]->password; //原來的密碼

            //編號md5
            $md5_key='cld';
            $md5_account = md5($member[0]->account.$md5_key);

            $suggest = '';
            if($md5_account == $md5_password){
                $suggest = '建議修改初始密碼';
            }
            return view("member.personal_set",[
                'suggest' => $suggest,
            ]);
        }
        return redirect('/login');
    }
    public function personalSetPost(Request $request){ //修改個人資料(密碼)
        if($member_id = $request->session()->get('member_id') ){ //123456Aa
            $member = DB::select("SELECT * FROM calendar_members WHERE account = $member_id");
            $md5_key='cld';
            $md5_original = md5($request->original_pass.$md5_key);

            if($member[0]->password != $md5_original){
                $message = '目前密碼錯誤';
            }elseif($request->new_pass != $request->again_new_pass ){
                $message = '再次輸入錯誤';
            }elseif($request->new_pass == $request->original_pass){
                $message = '新密碼與原始密碼相同';
            }elseif(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/', $request->new_pass)){
                $message = '格式錯誤';
            }else{
                $calendar_member = CalendarMember::find($member[0]->id);
                $md5_pass = md5($request->new_pass.$md5_key);
                $calendar_member->password = $md5_pass;
                $calendar_member->save();
                $message = '成功';
            }
            $request->session()->put('message', $message);
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function calendarBatchGroup(Request $request){ // 批次處理，邀請對象 (介面)
        if($member_id = $request->session()->get('member_id') ){

            $date1 = $request->session()->get('calendarBatchGroupSearch_date1');
            $date2 = $request->session()->get('calendarBatchGroupSearch_date2');
            if(!$date1 || !$date2){
                $now = Carbon::now(); 
                $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                $today = $now->ToDateString();
                $date1 = $today;
                $date2 = $now->addMonth(1)->ToDateString();
            }

            $DB_calendar_members = DB::select("SELECT name FROM calendar_members WHERE cm_state = '啟用'");
            $option = "<option value=''>請選擇</option>";
            
            foreach($DB_calendar_members as $v){
                $option .= "<option value='".$v->name."'>".$v->name."</option>";
            }


            $group_calendar_datetime_record_ids = UserHelper::get_group_calendar_datetime_record_ids($member_id); //取得被邀請的行程編號
            $DB_calendar_datetime_records = DB::select("SELECT c1.id, c1.case_title, 
                CONCAT(MONTH(c1.case_begin),'月',DAY(c1.case_begin),'日') AS case_begin_date, 
                CONCAT(MONTH(c1.case_end),'月',DAY(c1.case_end),'日') AS case_end_date, 
                SUBSTR(TIME(c1.case_begin),1,5) AS case_begin_time, TIME(c1.case_end) AS case_end_time,
                c2.calendar_member_id, c2.calendar_member_name
                FROM calendar_datetime_records AS c1
                LEFT JOIN (
                    SELECT c11.calendar_datetime_record_id, 
                        GROUP_CONCAT(c11.calendar_member_id) as calendar_member_id,
                        GROUP_CONCAT(c12.name SEPARATOR'，') as calendar_member_name
                    FROM calendar_group_records AS c11
                    LEFT JOIN calendar_members AS c12 ON c11.calendar_member_id = c12.account
                    GROUP BY calendar_datetime_record_id
                ) AS c2 ON c1.id = c2.calendar_datetime_record_id
                WHERE (c1.member_id = '$member_id' OR c1.id in ('".$group_calendar_datetime_record_ids."') ) AND
                ((DATE(c1.case_begin) >= '$date1' AND DATE(c1.case_begin) <= '$date2') OR 
                (DATE(c1.case_end) >= '$date1' AND DATE(c1.case_end) < '$date2') OR 
                (DATE(c1.case_begin) <= '$date1' AND DATE(c1.case_end) >= '$date2' ) OR 
                ( DATE(c1.case_begin) = '$date1' AND DATE(c1.case_end) = '$date2' )) 
                ORDER BY case_begin");

            // "SELECT name FROM calendar_members WHERE 
            // account in(
            //      SELECT calendar_member_id FROM calendar_group_records 
            //      WHERE calendar_datetime_record_id = '$search_id') 
            // "
            
            // print_r($DB_calendar_datetime_records);
            // return array();
            $tbody = "";
            foreach($DB_calendar_datetime_records as $k=>$v){
                if($k%2 ==0) $tr = "<tr class='bg-blue-300 h-16'>";
                else $tr = "<tr class='bg-blue-200 h-16'>";

                $tbody .= $tr."
                    <td class='border p-2'><input type='checkbox' name='calendar_id[]' value='".$v->id."' class='calendar_checkbox w-6 h-6'></td>
                    <td class='border p-2'><div class='font-bold'>"
                    .$v->case_title."</div><div>"
                    .$v->case_begin_date."~".$v->case_end_date."</div><div class='font-bold text-green-700 bg-green-200'>"
                    .$v->calendar_member_name."</div></td>
                </tr>";

            }


            // return $date_array;
            return view("member.calendar_batchGroup",[
                'date1' => $date1,
                'date2' => $date2,
                'tbody' => $tbody,
                'option' => $option,
            ]);
        }
        return redirect('/login');
    }
    public function calendarBatchGroupSearch(Request $request){ // 批次處理，邀請對象 (查詢)
        if($member_id = $request->session()->get('member_id') ){
            $date1 = $request->date1;
            $date2 = $request->date2;
            if($date1>$date2){
                $request->session()->put('message','日期錯誤');
                return redirect()->back();
            }
            $request->session()->put('calendarBatchGroupSearch_date1',$date1);
            $request->session()->put('calendarBatchGroupSearch_date2',$date2);

            return redirect()->back();
        }
        return redirect('/login');
    }
    public function calendarBatchGroupPost(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            // print_r($request->all());
            // return array();
            $calendar_id = $request->calendar_id; //要新增邀請對象的行程
            $relevant_member = $request->relevant_member; //要新增的邀請對象
            $btn_val = $request->btn_val; //按鈕
            if(!$calendar_id) return redirect()->back();
            if(!$relevant_member) return redirect()->back();

            if($btn_val == 'add'){
                foreach($calendar_id as $v){
                    foreach($relevant_member as $v2){
                        $DB_calendar_members = DB::select("SELECT account FROM calendar_members WHERE name = '$v2' ");
                        $calendar_member_id = $DB_calendar_members[0]->account;
                        if(!$calendar_group_records = DB::select("SELECT * FROM calendar_group_records WHERE calendar_datetime_record_id = '$v' AND calendar_member_id = '$calendar_member_id' ")){
                            CalendarGroupRecord::create([
                                "calendar_datetime_record_id" => $v,
                                "calendar_member_id" => $calendar_member_id,
                            ]); 
                        }
                    }
                }
            }elseif($btn_val == 'delete'){ //刪除的條件，需要有 1.行程編號 2.員工編號
                foreach($calendar_id as $v){
                    foreach($relevant_member as $v2){
                        $DB_calendar_members = DB::select("SELECT account FROM calendar_members WHERE name = '$v2' ");
                        $calendar_member_id = $DB_calendar_members[0]->account;
                        if($calendar_group_records = DB::select("SELECT * FROM calendar_group_records WHERE calendar_datetime_record_id = '$v' AND calendar_member_id = '$calendar_member_id' ")){
                            CalendarGroupRecord::find($calendar_group_records[0]->id)->delete(); 
                        }
                    }
                }
            }
            return redirect()->back();
        }
        return redirect('/login');
    }
    

    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //     }
    //     return redirect('/login');
    // }
    
}
