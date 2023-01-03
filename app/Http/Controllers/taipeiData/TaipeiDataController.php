<?php

namespace App\Http\Controllers\taipeiData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Festival;
use DB;

class TaipeiDataController extends Controller
{
    public function getFestivals(Request $request){
        // php爬蟲(抓節日)
        // $year = 2023;
        // $date_start = Carbon::create($year, 9, 1);
        // for($i=1;$i<=130;$i++){
        //     $date = $date_start->toDateString();
        //     $month = $date_start->month;
        //     $day = $date_start->day;
        //     // echo $date; echo "<br>";
        //     // echo $year.$month.$day; echo "<br>";  
        //     $url = 'https://ecal.click108.com.tw/api/get_calendar_day_detail.php?year='.$year.'&month='.$month.'&date='.$day.'';
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');
        //     $data = curl_exec($ch);
        //     curl_close($ch);
        //     $array = json_decode($data, true);
        //     if($array['holiDay']){
        //         if($DB_festivals = DB::select("SELECT * FROM festivals WHERE festival_date = '$date' ")){
        //             $festivals = Festival::find($DB_festivals[0]->id);
        //             $festivals->festival_name = $array['holiDay'];
        //             $festivals->isHoliday = "是";
        //             $festivals->holidayCategory = "節日";
        //             $festivals->festival_description = "來源https://ecal.click108.com.tw/";
        //             $festivals->save();
        //         }else{
        //             Festival::create([
        //                 'festival_date' => $date,
        //                 'festival_name' => $array['holiDay'],
        //                 'isHoliday' => "是",
        //                 'holidayCategory' => "節日",
        //                 'festival_description' => "來源https://ecal.click108.com.tw/",
        //             ]);
        //         }
        //         // echo $array['holiDay']; echo "<br>";
        //     }
        //     $date_start = $date_start->addDay(1);
        // }
        // return "目前抓到這天：".$date; //目前抓到這天：2024-01-08

        // php爬蟲(抓農曆)
        // $year = 2023;
        // for($i=1;$i<=12;$i++){
        //     echo $i;
        //     $month = $i;
        //     $url = 'https://ecal.click108.com.tw/api/get_calendar.php?year='.$year.'&month='.$month.'';
        //     $ch = curl_init();
        //     curl_setopt($ch, CURLOPT_URL, $url);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');
        //     $data = curl_exec($ch);
        //     curl_close($ch);
        //     $array = json_decode($data, true);
        //     print_r($data);
            
        //     // CalendarLunarCalendar::create([
        //     //     "clc_year" => $year,
        //     //     "clc_month" => $month,
        //     //     "calendar_data" => $data
        //     // ]);
        // }
        // php爬蟲(抓農曆) 取出資料的方式
        // $data = DB::select("SELECT clc_year, clc_month, calendar_data 
        //     FROM calendar_lunar_calendars
        //     WHERE clc_year = 2021 AND clc_month = 11");
        // $calendar_data_json = json_decode($data[0]->calendar_data, true);
        // $results = array_filter($calendar_data_json['calendarData'], function($calendarData) { //神奇的抓法
        //     return $calendarData['solarDate'] == 10;
        // });
        // echo "<br>";
        // print_r($results);
        // return array();


        // php爬蟲
        // $file=file_get_contents('https://youtils.cc/lunarCalendar?solar2lunar=2021-11-7#solar2lunar');
        // $regexp='/\<body\>(.*?)\<\/body\>/'; //解釋  符號需要添加 \   例如: < = \<
        // preg_match($regexp,$file,$string1);
        // $regexp='/\<div class\=\"col-md-12 text-center\"\>(.*?)\<\/div\>/';
        // preg_match($regexp,$string1[0],$string2);
        // $regexp='/\<span class\=\"lead\"\>(.*?)\<\/span\>/';
        // preg_match_all($regexp,$string2[0],$string3);
        // print_r($string3[1]);
        // return array();

        // // 冬至 計算
        // $len = 2088;
        // for($i=2000;$i<=$len;$i++){
        //     $EE = 0;
        //     if($i==2021) $EE = 1; 
        //     echo $year=($i); echo " [";
        //     echo $Y = (substr($year,2,2)); echo "*";
        //     echo $D = 0.2422; echo "+";
        //     echo $C = 21.94; echo "] - [";//21世紀
        //     echo $Y; echo "/4] = ";
        //     echo $left = intval($Y*$D+$C); echo '-';
        //     echo $L = intval($Y/4); echo '=';
        //     echo $ANS = $left-$L-$EE;
        //     echo '<br>';
        // }
        // return 'end';
        // $md5_key = 'cld';
        // $md5_pass = md5('9001'.$md5_key);
        
        // 節日計算
        // $a[1] = "01日開國紀念日 06日獸醫師節 11日司法節 15日藥師節 19日消防節 23日自由日";
        // $a[2] = "12日觀光節 14日情人節 15日戲劇節 19日炬光節 28日和平紀念日";
        // $a[3] = "01日兵役節 05日童子軍節 08日婦女節 12日植樹節、國父逝世紀念日 14日白色情人節 17日國醫節 20日郵政節 21日氣象節 25日美術節 26日廣播電視節 29日青年節";
        // $a[4] = "01日愚人節、主計日 04日兒童節 05日清明節、音樂節 07日衛生節";
        // $a[5] = "01日勞動節 04日文藝節、牙醫師節 12日護士節";
        // $a[6] = "03日禁煙節 06日水利節、工程師節 09日鐵路節 15日警察節";
        // $a[7] = "01日公路節、漁民節、稅務節 11日航海節";
        // $a[8] = "08日父親節";
        // $a[9] = "01日記者節 03日軍人節 09日體育節 28日教師節、孔子誕辰紀念日";
        // $a[10] = "10日國慶日 21日華僑節 25日台灣光復節 31日萬聖節";
        // $a[11] = "01日商人節 11日工業節、地政節 12日醫師節、國父誕辰紀念日 21日防空節";
        // $a[12] = "25日聖誕節、行憲紀念日 27日建築師節 28日電信節";

        
        // for($i=1;$i<=12;$i++){
        //     $a_array = explode(' ',$a[$i]);
        //     for($j=0;$j<count($a_array);$j++){
        //         $month = str_pad($i,2,"0",STR_PAD_LEFT);
        //         $day = substr($a_array[$j], 0, 2);
        //         $date = $month."-".$day;
        //         $title = mb_substr($a_array[$j],3,99,"utf-8");
        //         // DB::select("INSERT INTO solar_calendars( festival_name, festival_date) VALUES ('$date','$title')");
        //     }
        // }

        // return 'end';
        // print_r($a_array);
        // return array();
        // //以上測試 冬至日期用  1918 2021 冬至時間要扣一



        //set map api url
        $url = "https://data.ntpc.gov.tw/api/datasets/308DCD75-6434-45BC-A95F-584DA4FED251/json?page=3&size=359";
        
        //call api
        $json = file_get_contents($url);
        $json = json_decode($json);
        return $json;
        foreach($json as $v){
            if($v->holidayCategory == '星期六、星期日'){ //不儲存，不建立
            }elseif($DB_festivals = DB::select("SELECT * FROM festivals WHERE festival_date = '$v->date' ")){
                $festivals = Festival::find($DB_festivals[0]->id);
                $festivals->festival_name = $v->name;
                $festivals->isHoliday = $v->isHoliday;
                $festivals->holidayCategory = $v->holidayCategory;
                $festivals->description = $v->description;
                $festivals->save();
            }else{
                Festival::create([
                    'festival_date' => $v->date,
                    'festival_name' => $v->name,
                    'isHoliday' => $v->isHoliday,
                    'holidayCategory' => $v->holidayCategory,
                    'description' => $v->description,
                ]);
            }
        }
        return '成功';
    }
}
