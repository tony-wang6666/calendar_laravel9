<?php

namespace App\Http\Controllers\crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CalendarMember;
use App\Models\Crm_ChangeCustomerAoStaff;
use App\Models\Crm_CustomerBasicInformation;
use DB;
use Carbon\Carbon;
use Exception;

class CrmSystemManagementController extends Controller
{
    private $system_authority = 'E';
    public function manage_accounts(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $main_title = "系統管理";
            $type = ">使用者管理";
            $title = $main_title.$type;
            $select_search_array = ["所有人","AO人員","勸募員工"];
            $select_search_option = "";
            foreach($select_search_array as $v){
                $select_search_option .= "<option value='".$v."'>".$v."</option>";
            }

            return view("crm.member.crm_manage_accounts.crm_manage_account",[
                "title" => $title,
                "select_search_option" => $select_search_option,

            ]); 
        }
        return redirect('/login');
    }
    public function search_manage_accounts(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $search_val = $request->search_val;
            switch ($search_val){
                case "所有人" : $where = " "; break;
                case "AO人員" : $where = "WHERE cm_ao_staff = '是' "; break;
                case "勸募員工" : $where = "WHERE cm_ao_staff = '否' "; break;
            }
            $DB_calendar_members = DB::select("SELECT id, name, account, cm_ao_staff, cm_manager, cm_authority, cm_state FROM calendar_members $where ORDER BY account ");
            if($DB_calendar_members){
                $tbody ="";
                foreach($DB_calendar_members as $k=>$v){
                    if($k%2 == 1) $tr = "<tr class='bg-blue-200 h-16'>";
                    else $tr = "<tr class='bg-blue-300 h-16'>";
                    $tbody .= $tr.
                        "<td class='border p-2'><input type='checkbox' class='calendar_member_checkbox w-6 h-6' data-id='".$v->id."'></td>
                        <td class='border p-2'>".$v->account."</td>
                        <td class='border p-2'>".$v->name."</td>
                        <td class='border p-2'>".$v->cm_ao_staff."</td>
                        <td class='border p-2'>".$v->cm_manager."</td>
                        <td class='border p-2'>".$v->cm_authority."</td>
                        <td class='border p-2'>".$v->cm_state."</td>
                        <td class='border p-2'>
                            <button type='button' data-id='".$v->id."' data-toggle='mymodal' data-target='#calendar_memberEditModal' class='calendar_member_edit_data bg-yellow-500 text-white p-2 hover:bg-yellow-600'>編輯</button>
                        </td>
                    </tr>";
                }
                $calendar_member_set_table = "
                <thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2 w-16'><input type='checkbox' class='checked_all w-6 h-6'></th>
                        <th class='border p-2 w-48'>帳號</th>
                        <th class='border p-2 w-48'>姓名</th>
                        <th class='border p-2 w-24'>AO</th>
                        <th class='border p-2 w-24'>主管</th>
                        <th class='border p-2 '>權限</th>
                        <th class='border p-2 w-24'>狀態</th>
                        <th class='border p-2 w-24'></th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$tbody."</tbody>";
            }else{
                $calendar_member_set_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            // $data = $search_val;
            $result = [
                "calendar_member_set_table" => $calendar_member_set_table,
            ];
            return response()->json($result);
        }
    }
    public function manage_account_edit_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if($id = $request->id){ //編輯用
                $manage_account_edit_title2="編輯";
                $DB_calendar_members = DB::select("SELECT id, account, name, cm_ao_staff, cm_manager, cm_state, cm_authority, notification_token
                FROM calendar_members WHERE id = '$id' ");
                $cm_account = $DB_calendar_members[0]->account;
                $cm_name = $DB_calendar_members[0]->name;
                $cm_ao_staff = $DB_calendar_members[0]->cm_ao_staff;
                $cm_manager = $DB_calendar_members[0]->cm_manager;
                $cm_state = $DB_calendar_members[0]->cm_state;
                $cm_authority = $DB_calendar_members[0]->cm_authority;
                $cm_account_disabled = true;
                $notification_token = $DB_calendar_members[0]->notification_token;
            }else{ //新增用
                $manage_account_edit_title2="新增";
                $cm_account = "";
                $cm_name = "";
                $cm_ao_staff = "";
                $cm_manager = "";
                $cm_state = "";
                $cm_authority = "";
                $cm_account_disabled = false;
                $notification_token = "";
            }
            //ao
            $cm_ao_staff_array = ["是","否"];
            $cm_ao_staff_option = "";
            foreach($cm_ao_staff_array as $v){
                if($v == $cm_ao_staff) $cm_ao_staff_option .= "<option value='".$v."' selected>".$v."</option>";
                else $cm_ao_staff_option .= "<option value='".$v."'>".$v."</option>";
            }
            //主管
            $cm_manager_array = ["是","否"];
            $cm_manager_option = "";
            foreach($cm_manager_array as $v){
                if($v == $cm_manager) $cm_manager_option .= "<option value='".$v."' selected>".$v."</option>";
                else $cm_manager_option .= "<option value='".$v."'>".$v."</option>";
            }
            //狀態
            $cm_state_array = ["啟用","不啟用"];
            $cm_state_option = "";
            foreach($cm_state_array as $v){
                if($v == $cm_state) $cm_state_option .= "<option value='".$v."' selected>".$v."</option>";
                else $cm_state_option .= "<option value='".$v."'>".$v."</option>";
            }

            // 權限項目
            $cm_authority_array = [ 
                ['tag' => 'A', 'value' => '行事曆'], 
                ['tag' => 'B', 'value' => '客戶管理'], 
                ['tag' => 'C', 'value' => '拜訪紀錄'], 
                ['tag' => 'D', 'value' => '參數管理'], 
                ['tag' => 'E', 'value' => '系統管理'], 
                ['tag' => 'F', 'value' => '資料管理'], 
            ];
            $cm_authority_array_json= json_decode(json_encode($cm_authority_array));
            $checkbox_cm_authoritys = "";
            foreach($cm_authority_array_json as $k=>$v){
                if( in_array($v->tag,explode(",",$cm_authority)) ) $checkbox = "<input type='checkbox' id='cm_authority".$k."' name='cm_authority' value='".$v->tag."' class='px-2 h-6 w-6' checked>";
                else $checkbox = "<input type='checkbox' id='cm_authority".$k."' name='cm_authority' value='".$v->tag."' class='px-2 h-6 w-6' >";
                $checkbox_cm_authoritys .= "<div class='flex m-1'>
                    ".$checkbox."
                    <label for='cm_authority".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->tag.$v->value."</label>
                </div>";
            }

            $result = [
                "manage_account_edit_title2" => $manage_account_edit_title2,
                "cm_account" => $cm_account,
                "cm_name" => $cm_name,
                "cm_ao_staff_option" => $cm_ao_staff_option,
                "cm_manager_option" => $cm_manager_option,
                "cm_state_option" => $cm_state_option,
                "checkbox_cm_authoritys" => $checkbox_cm_authoritys,
                "cm_account_disabled" => $cm_account_disabled,
                "notification_token" => $notification_token,
            ];
            return response()->json($result);
        }
    }

    public function manage_account_edit(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $cm_account = $request->cm_account;
            $cm_password = $request->cm_password;
            $cm_name = $request->cm_name;
            $cm_ao_staff = $request->cm_ao_staff;
            $cm_manager = $request->cm_manager;
            $cm_state = $request->cm_state;
            $cm_authority = $request->cm_authority;
            $notification_token = $request->notification_token;
            if($vals = $request->vals){ //刪除參數
                foreach($vals as $v){
                    $calendar_member = CalendarMember::find($v); 
                    $calendar_member->delete();
                }
                $message = "成功";
            }elseif($cm_id = $request->cm_id){ //編輯 (有流水號， 編輯資料)
                if(!$cm_name){
                    $message = "編輯使用者，請輸入";
                    $input_name = "";
                    if(!$cm_name) $input_name .= "姓名 ";
                    $message .= $input_name;
                }else{
                    $calendar_member = CalendarMember::find($cm_id); 
                    // $calendar_member->account = $cm_account; //帳號不能修改
                    $calendar_member->name = $cm_name;
                    $calendar_member->cm_ao_staff = $cm_ao_staff;
                    $calendar_member->cm_manager = $cm_manager;
                    $calendar_member->cm_state = $cm_state;
                    $calendar_member->notification_token = $notification_token; //line權杖
                    if($cm_authority){
                        $calendar_member->cm_authority = implode(',',$cm_authority);
                    }else{
                        $calendar_member->cm_authority = "";
                    }
                    if($cm_password) {
                        $md5_key = 'cld';
                        $md5_pass = md5($cm_password.$md5_key);
                        $calendar_member->password = $md5_pass;
                    }
                    $calendar_member->save();
                    $message = "成功";
                }
            }else{ //新增 (沒有流水號，新增資料)
                if(!$cm_account || !$cm_password || !$cm_name){
                    $message = "新增使用者，請輸入";
                    $input_name = "";
                    if(!$cm_account) $input_name .= "帳號 ";
                    if(!$cm_password) $input_name .= "密碼 ";
                    if(!$cm_name) $input_name .= "姓名 ";
                    $message .= $input_name;
                }elseif( DB::select("SELECT * FROM calendar_members WHERE account = '$cm_account' ") ){
                    $message = "帳號重複";
                }else{
                    $md5_key = 'cld';
                    $md5_pass = md5($cm_password.$md5_key);
                    $calendar_member = new CalendarMember(); 
                    $calendar_member->account = $cm_account;
                    $calendar_member->name = $cm_name;
                    $calendar_member->cm_ao_staff = $cm_ao_staff;
                    $calendar_member->cm_manager = $cm_manager;
                    $calendar_member->cm_state = $cm_state;
                    $calendar_member->notification_token = $notification_token; //line權杖
                    if($cm_authority){
                        $calendar_member->cm_authority = implode(',',$cm_authority);
                    }
                    $calendar_member->password = $md5_pass;
                    $calendar_member->save();
                    $message = "成功";
                }
                
            }
            
            $result = [
                "message" => $message,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function change_customer_ao_staff_record(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            
            $main_title = "系統管理";
            $type = ">客戶AO異動管理";
            $title = $main_title.$type;
            $ao_staffs = DB::select("SELECT account, name FROM calendar_members WHERE cm_ao_staff = '是' AND cm_state = '啟用'");
            
            $ao_staff_option = "";
            foreach($ao_staffs as $v){
                $ao_staff_option .= "<option value='".$v->account."'>".$v->name."</option>";
            }

            return view("crm.member.crm_change_customer_ao_staff.crm_change_customer_ao_staff_record",[
                "title" => $title,
                "ao_staff_option" => $ao_staff_option,

            ]); 
        }
        return redirect('/login');
    }
    public function search_change_customer_ao_staff_record(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $search_type = $request->search_type;
            $search_old_ao = $request->search_old_ao;
            $search_new_ao = $request->search_new_ao;
            $search_customer_type = $request->search_customer_type;
            $search_customer_val = $request->search_customer_val;
            $where = "";
            if($search_type == 'AO人員查詢'){
                $where .= "ccas_old_ao LIKE '%$search_old_ao%' AND ccas_new_ao LIKE '%$search_new_ao%' ";
            }elseif($search_type == '客戶查詢'){
                if($search_customer_type == "phone"){
                    $where .= "(c2.c_telephone LIKE '%$search_customer_val%' OR c2.c_cellphone LIKE '%$search_customer_val%')";
                }else{
                    $where .= "$search_customer_type LIKE '%$search_customer_val%'";
                }
            }
            $select = "SELECT 
            c1.ccas_date, c1.ccas_old_ao, c1.ccas_old_name, c1.ccas_new_ao, c1.ccas_new_name,
            c1.c_id, c2.c_name_company, c2.c_telephone, c2.c_cellphone
            FROM crm__change_customer_ao_staffs AS c1
            LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id 
            WHERE $where ORDER BY c1.ccas_date ";
            $request->session()->put('search_change_customer_ao_staff_record_download',$select);
            $DB_crm__change_customer_ao_staffs = DB::select($select. "DESC LIMIT 20");
            if($DB_crm__change_customer_ao_staffs){
                $tbody ="";
                foreach($DB_crm__change_customer_ao_staffs as $k=>$v){
                    if($k%2 == 1) $tr = "<tr class='bg-blue-200 h-16'>";
                    else $tr = "<tr class='bg-blue-300 h-16'>";
                    $tbody .= $tr.
                        "<td class='border p-2'>".$v->ccas_date."</td>
                        <td class='border p-2'>".$v->ccas_old_ao."</td>
                        <td class='border p-2'>".$v->ccas_old_name."</td>
                        <td class='border p-2'>".$v->ccas_new_ao."</td>
                        <td class='border p-2'>".$v->ccas_new_name."</td>
                        <td class='border p-2'>".$v->c_id."</td>
                        <td class='border p-2'>".$v->c_name_company."</td>
                        <td class='border p-2'>".$v->c_telephone."</td>
                        <td class='border p-2'>".$v->c_cellphone."</td>
                    </tr>";
                }
                $change_customer_ao_staff_table = "
                <thead class='bg-blue-600 text-white'><tr>
                    <th class='border p-2 w-28'>異動日期</th>
                    <th class='border p-2 w-24'>原AO</th>
                    <th class='border p-2 w-28'>原AO姓名</th>
                    <th class='border p-2 w-24'>新AO</th>
                    <th class='border p-2 w-28'>新AO姓名</th>
                    <th class='border p-2 w-24'>編號</th>
                    <th class='border p-2 w-20'>姓名</th>
                    <th class='border p-2 w-20'>電話</th>
                    <th class='border p-2 w-20'>手機</th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$tbody."</tbody>";
            }else{
                $change_customer_ao_staff_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            $result = [
                "change_customer_ao_staff_table" => $change_customer_ao_staff_table,
            ];

            return response()->json($result);
        }
        return redirect('/login');
    }
    public function change_customer_ao_staff(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $main_title = "系統管理";
            $type = ">客戶AO異動管理";
            $title = $main_title.$type;
            if($request->session()->get('search_change_customer_ao_staff')) $search_ao_staff_id = $request->session()->get('search_change_customer_ao_staff');
            else $search_ao_staff_id = "";
            $ao_staffs = DB::select("SELECT id, name FROM calendar_members WHERE cm_ao_staff = '是' AND cm_state = '啟用'");
            $ao_staffs_option = $ao_staffs_new_option = "";
            if($ao_staffs){
                foreach($ao_staffs as $v){
                    if($search_ao_staff_id == $v->id) $ao_staffs_option .= "<option value='".$v->id."' selected>".$v->name."</option>";
                    else $ao_staffs_option .= "<option value='".$v->id."'>".$v->name."</option>";
                }
                foreach($ao_staffs as $v){
                   $ao_staffs_new_option .= "<option value='".$v->id."'>".$v->name."</option>";
                }
            }

            return view("crm.member.crm_change_customer_ao_staff.crm_change_customer_ao_staff",[
                "title" => $title,
                "ao_staffs_option" => $ao_staffs_option,
                "ao_staffs_new_option" => $ao_staffs_new_option,

            ]); 
        }
        return redirect('/login');
    }
    public function search_change_customer_ao_staff(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $ao_staff_id = $request->search_val;
            $request->session()->put('search_change_customer_ao_staff', $ao_staff_id);
            $DB_crm__customer_basic_informations = DB::select("SELECT 
            c1.ao_staff AS ao_staff_id, c1.id, c1.c_name_company, c1.deposit_level, c1.loan_level,
            c1.c_telephone, c1.c_cellphone, c1.postcode, c1.city, c1.city_area, c2.name AS ao_staff_name
            FROM crm__customer_basic_informations AS c1
            LEFT JOIN calendar_members AS c2 ON c1.ao_staff = c2.id
            WHERE ao_staff = '$ao_staff_id' ");
            if($DB_crm__customer_basic_informations){
                $tbody ="";
                foreach($DB_crm__customer_basic_informations as $k=>$v){
                    if($k%2 == 1) $tr = "<tr class='bg-blue-200 h-16'>";
                    else $tr = "<tr class='bg-blue-300 h-16'>";
                    $tbody .= $tr.
                        "<td class='border p-2'><input name='ao_staff_ids[]' type='checkbox' class='ao_staff_checkbox w-6 h-6' value='".$v->id."' ></td>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$v->c_name_company."</td>
                        <td class='border p-2'>".$v->deposit_level.",".$v->loan_level."</td>
                        <td class='border p-2'>".$v->c_telephone."</td>
                        <td class='border p-2'>".$v->c_cellphone."</td>
                        <td class='border p-2'>".$v->postcode."</td>
                        <td class='border p-2'>".$v->city."</td>
                        <td class='border p-2'>".$v->city_area."</td>
                        <td class='border p-2'>".$v->ao_staff_name."</td>
                    </tr>";
                }
                $customer_basic_information_table = "
                <thead class='bg-blue-600 text-white'><tr>
                    <th class='border p-2 w-12'><input type='checkbox' class='checked_all w-6 h-6'></th>
                    <th class='border p-2 w-24'>編號</th>
                    <th class='border p-2 '>姓名</th>
                    <th class='border p-2 w-28'>存-放等級</th>
                    <th class='border p-2 w-28'>電話</th>
                    <th class='border p-2 w-28'>手機</th>
                    <th class='border p-2 w-16'>區碼</th>
                    <th class='border p-2 w-20'>縣市</th>
                    <th class='border p-2 w-20'>鄉鎮</th>
                    <th class='border p-2 w-28'>AO</th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$tbody."</tbody>";
            }else{
                $customer_basic_information_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            $result = [
                "customer_basic_information_table" => $customer_basic_information_table,
            ];

            return response()->json($result);

        }
        return redirect('/login');
    }
    public function change_customer_ao_staff_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $old_ao_staff = $request->old_ao_staff;
            $new_ao_staff = $request->new_ao_staff;
            $ao_staff_ids = $request->ao_staff_ids;

            if($old_ao_staff == $new_ao_staff) {
                $request->session()->put('message',"原AO 與 新AO 相同");
            }elseif(!$ao_staff_ids){
                $request->session()->put('message',"未勾選要異動的客戶");
            }else{
                $now = Carbon::now();
                $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
                $now_date = $now->toDateString();
                $DB_old_ao = DB::select("SELECT account, name FROM calendar_members WHERE id = '$old_ao_staff' ");
                $DB_new_ao = DB::select("SELECT account, name FROM calendar_members WHERE id = '$new_ao_staff' ");
                foreach($ao_staff_ids as $v){
                    $crm__change_customer_ao_staff = new Crm_ChangeCustomerAoStaff();
                    $crm__change_customer_ao_staff->c_id = $v;
                    $crm__change_customer_ao_staff->ccas_date = $now_date;
                    $crm__change_customer_ao_staff->ccas_old_ao = $DB_old_ao[0]->account;
                    $crm__change_customer_ao_staff->ccas_old_name = $DB_old_ao[0]->name;
                    $crm__change_customer_ao_staff->ccas_new_ao = $DB_new_ao[0]->account;
                    $crm__change_customer_ao_staff->ccas_new_name = $DB_new_ao[0]->name;
                    $crm__change_customer_ao_staff->save();
                    $crm__customer_basic_information = Crm_CustomerBasicInformation::find($v);
                    $crm__customer_basic_information->ao_staff = $new_ao_staff;
                    $crm__customer_basic_information->save();
                } 
                $request->session()->put('message',"成功");
            }
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function database_backup(Request $request){ //資料庫備份 介面
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_date = $now->toDateString();

            $mysqlHostName      = env('DB_HOST').":".env('DB_PORT'); //20201106不是每個port都是3306，所以要變化
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = "calendar_backup";//env('DB_DATABASE');
            
            //取得backups資料表
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $select = "SELECT created_at, updated_at, backup_name, backup_remark, 0 AS backup_route FROM backups ORDER BY updated_at DESC LIMIT 10";
            $statement = $connect->prepare($select);
            $statement->execute();
            $result = $statement->fetchAll();
            // foreach($result as $v){
            //     // return md5($v['backup_name'].'cld_crm'.substr( $v['created_at'], 0, 10) ).'.sql';
            //     $backup_root = url("file/f1f458be0cc3a/");
            //     $backup_route = $backup_root.md5($v['backup_name'].'cld_crm'.substr( $v['created_at'], 0, 10) ).'.sql'; //加密檔名 cld：calendar,crm：CRM客戶管理
            //     $v['backup_route'] = $backup_route;
            //     return $v['backup_route'] ;
            // }
            // return $result;
            $title = "資料庫備份";
            return view("crm.member.crm_database.crm_database_backup",[
                "title" => $title,
                "result" => $result,
                "now_date" => $now_date,
            ]);
        }
        return redirect('/login');
    }
    public function database_backup_go(Request $request){ //資料庫備份
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $mysqlHostName      = env('DB_HOST').":".env('DB_PORT'); //2020106不是每個port都是3306，所以要變化
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = env('DB_DATABASE');
            
            //取得所有tables與function
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $get_all_table_query = "SHOW TABLES"; //取得所有table
            $statement = $connect->prepare($get_all_table_query);
            $statement->execute();
            $result = $statement->fetchAll(); //所有tables;
            foreach($result as $v){
                $tables[] = $v[0];
            }

            $get_all_function_query = "SHOW FUNCTION STATUS WHERE Db LIKE '$DbName'"; //取的所有預存程序
            $statement = $connect->prepare($get_all_function_query);
            $statement->execute();
            $result = $statement->fetchAll(); 
            $functions = [];
            foreach($result as $v){
                $functions[] = $v[1];
            }
            /************** 製作SQL***********/
            //自定義的函數
            $output_function = "DELIMITER $$ \n";
            if($functions){
                foreach($functions as $v){
                    $show_function_query = "SHOW CREATE FUNCTION ".$v;
                    $statement = $connect->prepare($show_function_query);
                    $statement->execute();
                    $show_function_result = $statement->fetchAll();
                    foreach($show_function_result as $v){
                        $output_function .= "\n\n" . $v["Create Function"] . "$$\n\n";
                    }
                }
            }
            $output_function .= "DELIMITER ; \n";
            //資料表格式與資料
            $output_table = '';
            foreach($tables as $table){
                //資料表格式
                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();
                foreach($show_table_result as $show_table_row){ 
                    $output_table .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                //資料表欄位
                $columns = DB::select("SHOW COLUMNS FROM ". $table);
                $output_table .= "\nINSERT INTO $table (";
                $columns_array = [];
                foreach($columns as $v){ 
                    $columns_array[] = $v->Field;
                    // $output_table .= $v->Field.", ";
                }
                $columns_array = implode(', ',$columns_array);
                $output_table .= $columns_array.") VALUES \n";
                //資料表資料
                $select_query = "SELECT * FROM " . $table;
                // $records = DB::select(DB::raw($select_query));
                $records = DB::select($select_query);
                foreach($records as $k=>$v){
                    $v = (array)$v;
                    $table_value_array = array_values($v);
                    $content='';
                    foreach($table_value_array as $k2=>$v2){
                        // echo $v2;
                        if(empty($v2)){
                            $content .= '"'.$v2.'"';
                        }elseif (isset($v2)){
                            $content .= '"'.$v2.'"' ; 
                        }else{   
                            $content .= 'NULL';
                        }
                        if($k2 != count($table_value_array)-1 ) $content .=",";
                    }
                    // return $content;
                    // $output_table .="('" . implode("','", $table_value_array) . "')";
                    $output_table .="(" . $content . ")";
                    if($k != count($records)-1 ) $output_table .=",\n";
                }
                $output_table .=";\n";
            }
            $output = $output_function."\n".$output_table;
            // return $output;
            $file_name = 'database_backup_on_' . date('y-m-d') . '.sql'; //建立一個 副檔名為sql的 文件
            $file_handle = fopen($file_name, 'w+'); //開啟 文件 (w+:嘗試讀寫，將文件指針指向文件頭並將文件大小截為零。如果文件不存在則嘗試創建之)
            fwrite($file_handle, $output); //文件寫入
            fclose($file_handle); //文件關閉
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            ob_clean();
            flush();
            readfile($file_name);
            unlink($file_name);
        }
        return redirect('/login');
    }
    public function database_backup_add(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_date = $now->toDateString();
            $backup_name = $request->backup_name;
            $backup_remark = $request->backup_remark;
            $backup_root = "file/f1f458be0cc3a/";
            $backup_route = $backup_root.md5($backup_name.'cld_crm').'.sql'; //加密檔名 cld：calendar,crm：CRM客戶管理
            
            //製作 SQL
            $mysqlHostName      = env('DB_HOST').":".env('DB_PORT'); //2020106不是每個port都是3306，所以要變化
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = env('DB_DATABASE');
            //取得所有tables  //20210622function不取得
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $get_all_table_query = "SHOW TABLES"; //取得所有table
            $statement = $connect->prepare($get_all_table_query);
            $statement->execute();
            $result = $statement->fetchAll(); //所有tables;
            foreach($result as $v){
                $tables[] = $v[0];
            }
            
            // $get_all_function_query = "SHOW FUNCTION STATUS WHERE Db LIKE '$DbName'"; //取的所有預存程序
            // $statement = $connect->prepare($get_all_function_query);
            // $statement->execute();
            // $result = $statement->fetchAll(); 
            // $functions = [];
            // foreach($result as $v){
            //     $functions[] = $v[1];
            // }
            /************** 製作SQL***********/
            //自定義的函數
            // $output_function = "DELIMITER $$ \n";
            // if($functions){
            //     foreach($functions as $v){
            //         $show_function_query = "SHOW CREATE FUNCTION ".$v;
            //         $statement = $connect->prepare($show_function_query);
            //         $statement->execute();
            //         $show_function_result = $statement->fetchAll();
            //         foreach($show_function_result as $v){
            //             $output_function .= "\n\n" . $v["Create Function"] . "$$\n\n";
            //         }
            //     }
            // }
            // $output_function .= "DELIMITER ; \n";
            //資料表格式與資料
            $output_table = '';
            foreach($tables as $table){
                //資料表格式
                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();
                foreach($show_table_result as $show_table_row){ 
                    $output_table .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                //資料表欄位
                $columns = DB::select("SHOW COLUMNS FROM ". $table);
                $output_table .= "\nINSERT INTO $table (";
                $columns_array = [];
                foreach($columns as $v){ 
                    $columns_array[] = $v->Field;
                }
                $columns_array = implode(', ',$columns_array);
                $output_table .= $columns_array.") VALUES \n";
                //資料表資料
                $select_query = "SELECT * FROM " . $table;
                $records = DB::select($select_query);
                foreach($records as $k=>$v){
                    $v = (array)$v;
                    $table_value_array = array_values($v);
                    $content='';
                    foreach($table_value_array as $k2=>$v2){
                        // echo $v2;
                        if(empty($v2)){
                            $content .= '"'.$v2.'"';
                        }elseif (isset($v2)){
                            $content .= '"'.$v2.'"' ; 
                        }else{   
                            $content .= 'NULL';
                        }
                        if($k2 != count($table_value_array)-1 ) $content .=",";
                    }
                    $output_table .="(" . $content . ")";
                    if($k != count($records)-1 ) $output_table .=",\n";
                }
                $output_table .=";\n";
            }
            // $output = $output_function."\n".$output_table;
            $output = $output_table;
            // return $output;

            $file_name = $backup_route; //建立一個 副檔名為sql的 文件
            $file_handle = fopen($file_name, 'w+'); //開啟 文件 (w+:嘗試讀寫，將文件指針指向文件頭並將文件大小截為零。如果文件不存在則嘗試創建之)
            fwrite($file_handle, $output); //文件寫入
            fclose($file_handle); //文件關閉
            // move_uploaded_file($file_handle); //儲存sql檔，路徑位置已存在此檔名上 // 2022/12/30好像用不到，而且也不能用

            //儲存在另外一個資料庫，儲存1.檔名 2.備註
            $mysqlHostName      = env('DB_HOST').":".env('DB_PORT'); //2020106不是每個port都是3306，所以要變化
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = "calendar_backup";//env('DB_DATABASE');
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $statement = $connect->prepare("SELECT * FROM backups WHERE backup_name = '$backup_name' ");
            $statement->execute();
            if($result = $statement->fetchAll()){ //檔名相同且建立日期相同，就更新
                $id = $result[0]['id'];
                $query = "UPDATE backups SET backup_remark='$backup_remark' WHERE id = '$id'";
            }else{
                $query = "INSERT INTO backups(backup_name, backup_remark ) VALUES ('$backup_name','$backup_remark')";
            }
            $statement = $connect->prepare($query);
            $statement->execute();
            $request->session()->put('message',"備份成功");
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function database_restore(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            // $now = Carbon::now();
            // $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            // $now_date = $now->toDateString();

            $mysqlHostName      = env('DB_HOST').":".env('DB_PORT'); //2020106不是每個port都是3306，所以要變化
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = "calendar_backup";//env('DB_DATABASE');
            
            //取得backups資料表
            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $query = "SELECT id, created_at, updated_at, backup_name, backup_remark FROM backups ORDER BY updated_at DESC LIMIT 10";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $title = "資料庫還原";
            return view("crm.member.crm_database.crm_database_restore",[
                "title" => $title,
                "result" => $result,
            ]);
        }
        return redirect('/login');
    }
    public function database_restore_go(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $backup_id = $request->backup_id;
            $mysqlHostName      = env('DB_HOST').":".env('DB_PORT'); //2020106不是每個port都是3306，所以要變化
            $mysqlUserName      = env('DB_USERNAME');
            $mysqlPassword      = env('DB_PASSWORD');
            $DbName             = "calendar_backup";

            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword",array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $query = "SELECT id, created_at, updated_at, backup_name, backup_remark FROM backups WHERE id = '$backup_id' ";
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $backup_root = "file/f1f458be0cc3a/";
            $backup_route = $backup_root.md5($result[0]['backup_name'].'cld_crm').'.sql';
            $fop = fopen(url($backup_route), "r");
            $frd = fread($fop,filesize($backup_route));
            $DB_table = DB::select("SHOW TABLES");
            foreach($DB_table as $v){
                $table = $v->Tables_in_calendar_schedule;
                DB::select("DROP TABLE $table");
            }
            $templine = "";
            $lines = file($backup_route);
            foreach ($lines as $line){
                if (substr($line, 0, 2) == '--' || $line == '') continue;
                // echo $line;
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';'){
                    // print($templine);
                    try { //檢查是否有錯誤，如果沒錯誤，不執行catch，繼續往下執行try catch以下的程式
                        DB::select($templine) or print('Error performing query');
                        throw new Exception();
                    } catch (Exception $e) { //錯誤會執行這段

                    }
                    $templine = '';
                }
            }
            // DB::select($templine);
            // return count($lines);
            // return $lines;
            $request->session()->put('message',"還原成功");
            
            return redirect()->back();
        }
        return redirect('/login');
    }

    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //         if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
    //     }
    //     return redirect('/login');
    // }
}
