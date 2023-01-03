<?php
namespace App\Helpers; // Your helpers namespace 
use DB;
class UserHelper
{
    public static function numberTOtime(float $number){ //測試客製化函數 
        $time1 = $number * 24;
        $data_time1_array = explode('.',$time1);
        $hours = $data_time1_array[0];
        $minute = ($time1-$hours)*60;;
        $time = str_pad($hours,2,"0",STR_PAD_LEFT).":".str_pad($minute,2,"0",STR_PAD_LEFT).":00";
        return $time;
    }
    public static function dateEventTypeTOcolor(int $type){ //日期事件顏色變化 
        $color_class_array = array(
            0 => 'border-green-200 text-white bg-green-700 hover:bg-green-800',           //節日 (綠色)
            1 => 'border-red-200 text-red-800 bg-red-300 hover:bg-red-400',               //紅色
            2 => 'border-yellow-400 text-yellow-900 bg-yellow-500 hover:bg-yellow-600',   //橙色
            3 => 'border-yellow-200 text-yellow-800 bg-yellow-300 hover:bg-yellow-400',   //黃色
            4 => 'border-green-200 text-green-800 bg-green-300 hover:bg-green-400',       //綠色
            5 => 'border-blue-200 text-blue-800 bg-blue-300 hover:bg-blue-400',           //藍色
            6 => 'border-indigo-200 text-indigo-800 bg-indigo-400 hover:bg-indigo-500',   //靛色
            7 => 'border-purple-200 text-purple-800 bg-purple-400 hover:bg-purple-500',   //紫色
        );
        if( isset( $color_class_array[$type] ) ){ 
            $class = $color_class_array[$type];
        }else{
            $class = 'bg-gray-400';
        }
        return $class;
    }
    public static function lock_url($txt,$key){  
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";  
        $nh = rand(0,64);  
        $ch = $chars[$nh];  
        $mdKey = md5($key.$ch);  
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);  
        $txt = base64_encode($txt);  
        $tmp = '';  
        $i=0;$j=0;$k = 0;  
        for ($i=0; $i<strlen($txt); $i++) {  
            $k = $k == strlen($mdKey) ? 0 : $k;  
            $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;  
            $tmp .= $chars[$j];  
        }  
        return urlencode($ch.$tmp);  
    }
    public static function get_group_calendar_datetime_record_ids($member_id){  
        $group_calendar_datetime_record_ids = array();
        if($DB_calendar_group_records = DB::select("SELECT calendar_datetime_record_id FROM calendar_group_records WHERE calendar_member_id = '$member_id'")){
            foreach($DB_calendar_group_records as $v){
                $group_calendar_datetime_record_ids[] = $v->calendar_datetime_record_id;
            }
        }
        $group_calendar_datetime_record_ids = implode("','", $group_calendar_datetime_record_ids);
        return $group_calendar_datetime_record_ids;  
    }
    public static function line_notify_message($token,$message){
        $token = str_replace(array("\r", "\n", "\r\n", "\n\r"), '', $token); //20210327 如果權杖有空格、換行...，此方式可以去除
        $headers = array(
            'Content-Type: multipart/form-data',
            'Authorization: Bearer '.$token,
        );
        $message = array(
            'message' => $message
        );
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL , "https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //2022/01/30 這個的功用是，回傳 成功或失敗 的訊息
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // public static function getCurrentUser(): ?object
    // {
    //      return Auth::user();
    // }
    // public static function getUserCompany(): ?object
    // {
    //     $companyId = Auth::user()->comp_id;
    //     return Company::find($companyId);
    // }
}