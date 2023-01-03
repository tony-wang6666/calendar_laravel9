<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CalendarLunarCalendar;
use App\Models\Festival;
use DB;
use UserHelper; //myfunction
use Exception; //try catch
use Carbon\Carbon;


class LoginController extends Controller
{
    public function loginForm(Request $request){
        // return '123';
        // 檢查IP
        if (!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        $ip = explode(':',$ip);
        $ip = $ip[0];
        $url = 'https://nordvpn.com/wp-admin/admin-ajax.php?action=get_user_info_data&ip='.$ip;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0');
        $data = curl_exec($ch);
        curl_close($ch);
        $array = json_decode($data, true);

        // return '123';
        if(isset($array) && $ip){ //有資料
            $country =  "";
            $word_taiwan_data = array("Taiwan", "taiwan", "台灣","臺灣");
            try{
                if($array['coordinates'] != False) $country =  $array['country'];
                if(!in_array($country,$word_taiwan_data)) return 'error'; //如果不是臺灣，就顯示錯誤
                $ip_message = "(IP：".$ip.",".$country.")";
            }catch(Exception $e) { //網站提供的json改變，抓取資料失敗，顯示檢查失敗
                $ip_message = "IP：".$ip."，系統錯誤，檢查失敗1";
            }
        }else{ //無資料 //這邊要讓系統繼續執行，並且顯示ip檢查失敗
            $ip_message = "IP：".$ip."，系統錯誤，檢查失敗2";
        }
        // return $ip_message;

        //資料庫連接檢查
        // try{
        //     $DB_calendar_members = DB::select("SELECT account FROM calendar_members WHERE account = '9001'  ");
        // }catch(Exception $e) { //如果錯誤，使用pgsql
        //     $text = "";
        //     $file = fopen("../../../../data/mysql/MYSQLCONNSTR_localdb.txt", "r");
        //     while(! feof($file)){
        //         $text .= fgets($file); 
        //     }
        //     fclose($file);
        //     $text_array = explode(";",$text);
        //     $Data_Source = explode(":",$text_array[1]);
        //     $Data_Source_Port =  $Data_Source[1];
            
        //     $path = base_path(".env");
        //     if (file_exists($path)){ // 文件存在 
        //         $origin = file_get_contents($path);
        //         $old_port = env('DB_PORT');
        //         $new_port = "DB_PORT=".$Data_Source_Port;
        //         $result = str_replace('DB_PORT=' . $old_port, $new_port, $origin);
        //         file_put_contents($path,$result);
        //     }
        //     $request->session()->put('message', '程式錯誤，請再試一次');
        //     $token = "Zos8Zr5LK740ex5KwhLybJvNrADSPmVFDmaWSpeGc80"; //測試 我的line
        //     $message = "總幹事行事曆\n舊port: ".$old_port."\n新port: ".$Data_Source_Port;
        //     UserHelper::line_notify_message($token,$message);

        //     return redirect('/login');
        // }

        //登入檢查
        if($member_id = $request->cookie('member')){
            if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE account = '$member_id' ")){
                $request->session()->put('member_id', $DB_calendar_members[0]->account); //編號
                $request->session()->put('member_name', $DB_calendar_members[0]->name); //姓名
                $request->session()->put('member_authority', explode(",",$DB_calendar_members[0]->cm_authority)); //權限
            }
        }
        if($request->session()->get('member_id')){
            return redirect('member/home');
        }
        // return '1234';
        return view("member.login.member_login",[
            'ip_message' => $ip_message,
        ]);
    }
    public function loginPost(Request $request){
        // \Cookie::queue('test', '儲存的東東2',1234567 ); // 儲存cookie ， 1,036,800分鐘 = 接近2年  (1234567儲存分鐘)
        // \Cookie::queue(\Cookie::forget('test')); // 刪除cookie
        $md5_key = 'cld';
        $md5_pass = md5($request->password.$md5_key);
        if($DB_calendar_members = DB::select("SELECT * FROM calendar_members WHERE account = '$request->username' AND password = '$md5_pass' ")){
            $request->session()->put('member_id', $DB_calendar_members[0]->account); //編號
            $request->session()->put('member_name', $DB_calendar_members[0]->name); //姓名
            $request->session()->put('member_authority', explode(",",$DB_calendar_members[0]->cm_authority)); //權限
            if($request->keep_login) \Cookie::queue('member', $DB_calendar_members[0]->account, 1234567 ); // 儲存cookie ， 1,036,800分鐘 = 接近2年  (1234567儲存分鐘)
        }else{
            $request->session()->put('message', '登入失敗');
        }
        // return $request->all();
        
        return redirect('/login');
    }
    public function logout(Request $request){
        if($request->session()->get('member_id') ){ //登出後 清除notification_token
            $request->session()->flush(); //刪除所有session
            \Cookie::queue(\Cookie::forget('member')); // 刪除保持登入
        }
        return redirect('/login');
    }
    
}
