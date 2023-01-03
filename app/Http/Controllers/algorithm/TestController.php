<?php

namespace App\Http\Controllers\algorithm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use App\Models\Kinship;
use App\Models\CalendarDatetimeRecord;

use Excel;
use App\Exports\MyExport;
use App\Exports\MyTableSheet;


class TestController extends Controller
{
    public function T1(Request $request){
        // $man = ['鼻祖','遠祖','太祖','烈祖','天祖','高祖','曾祖','祖父','父親','兒子','孫子','曾孫','玄孫','來孫','晜孫','仍孫','雲孫','耳孫'];
        // $man = ['曾祖','祖父','父親','兒子','孫子','曾孫'];
        $man1 = [-3=>'曾孫',-2=>'孫子',-1=>'兒子',1=>'父親',2=>'祖父',3=>'曾祖'];
        $man_up = [1=>'父親',2=>'祖父',3=>'曾祖'];
        $female = [1=>'母親',2=>'祖母',3=>'曾祖母'];
        $man_down = [-3=>'曾孫',-2=>'孫子',-1=>'兒子'];
        // $key = array_keys($man1, "祖父");

        $man2 = ['兒子','孫子','曾孫'];
        $female = ['曾祖母','祖母','母親','女兒','孫女','曾孫女'];

        // ----測試用資料 (暫時不要刪除) -----
        // $DB_kinships = DB::select("SELECT id, name, 
        //                         kin_id1, relationship1, 
        //                         kin_id2, relationship2, 
        //                         kin_id3, relationship3, 
        //                         kin_id4, relationship4, 
        //                         kin_id5, relationship5, 
        //                         kin_id6, relationship6, 
        //                         kin_id7, relationship7
        //                         FROM kinships WHERE id =1");
        // print_r($DB_kinships);
        // return array();

        // $long = 7;
        // foreach($DB_kinships as $v){
        //     for($i=1;$i<=$long;$i++){
        //         $kin_id = "kin_id".$i;
        //         $relationship = "relationship".$i;
        //         if($v->$kin_id){
        //             $title_id = $v->$kin_id;
        //             $title = $v->$relationship;
        //             //長輩檢查 (父親、祖父、曾祖父)
        //             if(array_search($title,$man_up) ){
        //                 $DB_kinships = DB::select("SELECT id, name, kin_id1, relationship1, 
        //                         kin_id2, relationship2, kin_id3, relationship3, kin_id4, relationship4, 
        //                         kin_id5, relationship5, kin_id6, relationship6, kin_id7, relationship7
        //                         FROM kinships WHERE id = $title_id");
        //                 print_r($DB_kinships);
        //                 return array();
        //             }

        //             // $title = $v->$relationship;
        //             // $number = array_search($title,$man1); //取得加幾的數字 (假如父親+1 , 祖父+2, 兒子-1, 孫子-2)
        //             // echo $number;
        //         }
        //     }
        //     // $number = array_search($title,$man1); //取得加幾的數字
        // }
        // ----測試用資料 (暫時不要刪除) -----

        // 目前系統資料表儲存順序排列 '1父親','2祖父','3曾祖',
        // 以下處理查詢相關父親編號
        $DB_kinships = DB::select("SELECT id FROM kinships");
        foreach($DB_kinships as $v){
            $title_ids = array();
            $kinship_id = $v->id; //更新的資料編號
            $title_id = $kinship_id;// 查詢的編號 (查詢父親以上)
            // $title_ids[] = $title_id;
            for($i=1;$i<=3;$i++){ //往上查找父親 目前三等親(~曾祖父)
                $DATA = DB::select("SELECT id,
                CASE WHEN relationship1 = '父親' THEN kin_id1
                    WHEN relationship2 = '父親' THEN kin_id2
                    WHEN relationship3 = '父親' THEN kin_id3
                    WHEN relationship4 = '父親' THEN kin_id4
                    WHEN relationship5 = '父親' THEN kin_id5
                    WHEN relationship6 = '父親' THEN kin_id6
                    WHEN relationship7 = '父親' THEN kin_id7
                    END AS kin_id
                FROM kinships WHERE id = '$title_id'");
                // return $DATA;
                if($DATA[0]->kin_id){
                    $title_id = $DATA[0]->kin_id; //父親編號
                    $title_ids[] = $title_id; //父親陣列(增加)
                }
                if(count($title_ids) < $i) break; //父親陣列($title_ids)資料沒有增加，就跳出
            }
            foreach($title_ids as $k2=>$v2){
                $kinship = Kinship::find($kinship_id);
                $kin_id = "kin_id".($k2+1);
                $relationship = "relationship".($k2+1);
                $kinship->$kin_id = $v2;
                $kinship->$relationship = $man_up[$k2+1];
                $kinship->save();
            }
        }
        
        // print_r($kinship);
        // return array();
        return 'end';


        // $tt_array[-2] = -2;
        // $tt_array[11] = 11;
        // $tt_array[9] = 9;
        // $tt_array[-1] = -1;
        
        // ksort($tt_array);
        // print_r($tt_array);
        return array();

    }
    public function color_change(Request $request){ //修改顏色順序變化用 (修改後可刪除)(20210315都修改完了，可刪除)
        return '只跑一次，再跑就出錯';
        $calendar_datetime_record_99to5 = DB::select("SELECT * FROM calendar_datetime_records WHERE case_level = 99");
        foreach($calendar_datetime_record_99to5 as $v){
            $change = CalendarDatetimeRecord::find($v->id);
            $change->case_level = 5;
            $change->save();
        }
        
        $calendar_datetime_record_1to4 = DB::select("SELECT * FROM calendar_datetime_records WHERE case_level = 1");
        foreach($calendar_datetime_record_1to4 as $v){
            $change = CalendarDatetimeRecord::find($v->id);
            $change->case_level = 4;
            $change->save();
        }

        $calendar_datetime_record_3to1 = DB::select("SELECT * FROM calendar_datetime_records WHERE case_level = 3");
        foreach($calendar_datetime_record_3to1 as $v){
            $change = CalendarDatetimeRecord::find($v->id);
            $change->case_level = 1;
            $change->save();
        }

        $calendar_datetime_record_2to3 = DB::select("SELECT * FROM calendar_datetime_records WHERE case_level = 2");
        foreach($calendar_datetime_record_2to3 as $v){
            $change = CalendarDatetimeRecord::find($v->id);
            $change->case_level = 3;
            $change->save();
        }
        return 'success';
    }

    public function rangeonetobignumberrandom(Request $request){
        $number_array = explode(",",$request->number);
        $number_range_array = [];
        $names = [];
        foreach($number_array as $k=>$v){
            $number_array = explode("~",$v);
            $start = $number_array[0];
            $end = $number_array[1];
            $number_range = range($start, $end, 1);
            shuffle($number_range);

            $number_range_array[] = $number_range;
            $names [] = "亂數".strval($k+1);
        }
        $type = "rangeTOrandom";
        // return $request->all();
        // return $number_range_array;
        return Excel::download(new MyTableSheet($type,$number_range_array,$names),'亂數.xlsx');
        // return Excel::download(new MyExport($number_range, "亂數1"), '亂數.xlsx');

        // return $number_range;
    }

    // public function example(Request $request){
    //     return ;
    // }
}
