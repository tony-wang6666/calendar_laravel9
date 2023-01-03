<?php

namespace App\Http\Controllers\crm\crm_excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Crm_CustomerBasicInformation;
use App\Models\Crm_AccountBalance;
use App\Models\Crm_ChangeCustomer;
use App\Models\Crm_Contribution;
use App\Models\Crm_InsuranceInformation;
use App\Models\Crm_VipManagement;


use Carbon\Carbon;

use Excel;
use App\Imports\crm\CrmExcelImport;

class CrmImportController extends Controller
{
    private $system_authority = 'F';
    public function basic_customer_data_import_check(Request $request){
        //2021/11/17 修改資料儲存方式， 把 逗號分隔, 改成 一條線分隔|
        // $DB_data = DB::select("SELECT * FROM crm__customer_basic_informations 
        // WHERE visitable_times LIKE '%,%' OR 
        //     dispositions LIKE '%,%' OR 
        //     interests LIKE '%,%' OR 
        //     prefer_invests LIKE '%,%' OR 
        //     openness_high_business LIKE '%,%' OR 
        //     openness_low_business LIKE '%,%' 
        //     ");
        // // return $DB_data;
        // foreach($DB_data as $v){
        //     $CCBI = Crm_CustomerBasicInformation::find($v->id);
        //     $visitable_times = explode(",",$CCBI->visitable_times);
        //     $CCBI->visitable_times = implode("|",$visitable_times);
        //     $dispositions = explode(",",$CCBI->dispositions);
        //     $CCBI->dispositions = implode("|",$dispositions);
        //     $interests = explode(",",$CCBI->interests);
        //     $CCBI->interests = implode("|",$interests);
        //     $prefer_invests = explode(",",$CCBI->prefer_invests);
        //     $CCBI->prefer_invests = implode("|",$prefer_invests);
        //     $openness_high_business = explode(",",$CCBI->openness_high_business);
        //     $CCBI->openness_high_business = implode("|",$openness_high_business);
        //     $openness_low_business = explode(",",$CCBI->openness_low_business);
        //     $CCBI->openness_low_business = implode("|",$openness_low_business);
        //     $CCBI->save();
        // }
        // return $DB_data;//$DB_data;

        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $crm__customer_basic_informations_new_created_at = DB::select("SELECT DATE(max(created_at)) AS new_date FROM crm__customer_basic_informations");
            $new_date = $crm__customer_basic_informations_new_created_at[0]->new_date;
            $DB_crm__customer_basic_informations = DB::select("SELECT * FROM crm__customer_basic_informations 
            WHERE created_at LIKE '%$new_date%'
            ORDER BY created_at limit 30");
            return view("crm.crm_excel.crm_excel_import.import_basic_customer_data_check",[
                'new_date' => $new_date,
                'DB_crm__customer_basic_informations' => $DB_crm__customer_basic_informations,
            ]);
        }
        return redirect('/login');
    }
    public function basic_customer_data_import(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            return view("crm.crm_excel.crm_excel_import.import_basic_customer_data",[
                // 'new_date' => $new_date,
            ]);
        }
        // import_basic_customer_data.blade.php
        return redirect('/login');
    }
    public function basic_customer_data_import_post(Request $request){ //匯入客戶資料
        if($member_id = $request->session()->get('member_id') ){
            ini_set('memory_limit', '2048M'); //檔案大小設定
            // ini_set("max_execution_time", "120"); //可運行時間設定 ，系統預設30秒
            $data = Excel::toCollection(new CrmExcelImport(), $request->file('excel_file'));
            $fileName = $_FILES['excel_file']['name']; //名稱
            // $fileType = $_FILES['excel_file']['type']; //單位類型
            // $fileTmpName = $_FILES['excel_file']['tmp_name']; //
            // $fileError = $_FILES['excel_file']['error']; //
            // $fileSize = $_FILES['excel_file']['size']; //大小
            $fileExtension = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); //副檔名
            if($fileExtension != 'xlsx') {
                return '麻煩請選副檔名為xlsx，謝謝。';
            }
            // return $data;
            //客戶資料更新(新增)
            $count_import = 0;
            foreach($data[0] as $k=>$v){
                // $v = explode(',',$row[0]);
                if($k==0) {
                    if($v[0]!='編號' || $v[1]!='姓名/公司' || $v[2]!='身分證/統編'){ //檢查EXCEL欄位名稱
                        return '錯誤';
                    }
                }else{
                    if(is_numeric($v[4])) $c_birth_opening_date = gmdate("Y-m-d", ($v[4] - 25569) * 86400);
                    else $c_birth_opening_date = $v[4];
                    $c_id = $v[0];
                    echo $c_id."<br>";
                    // continue;
                    if(!$DB_crm__customer_basic_informations = DB::select("SELECT * FROM crm__customer_basic_informations WHERE id = '$c_id'")){
                        Crm_CustomerBasicInformation::create([
                            "id" => $c_id,
                            "c_name_company" => $v[1],
                            "identification_gui_number" => $v[2],
                            "c_sex" => $v[3],
                            "c_birth_opening_date" => $c_birth_opening_date,
                            "c_type" => $v[5],
                            "c_telephone" => $v[6],
                            "c_cellphone" => $v[7],
                            "religion" => $v[8],
                            "c_number" => $v[9],
                            "c_family" => $v[10],
                            "postcode" => $v[11],
                            "city" => $v[12],
                            "city_area" => $v[13],
                            "c_address" => $v[14],
                            "open_account" => $v[15],
                            "farmer_association_member" => $v[16],
                            "farmer_insurance" => $v[17],
                            "health_state" => $v[18],
                            "communicate_state" => $v[19],
                            "response_attitude" => $v[20],
                            "deposit_level" => $v[21],
                            "loan_level" => $v[22],
                            "c_bank" => $v[23],
                            // "vip_cyear" => $v[24],
                            "encourage_raise_staff" => $v[25],
                            "ao_staff" => $v[26],
                            "remark" => $v[27],
                            "visitable_times" => $v[28],
                            "dispositions" => $v[29],
                            "interests" => $v[30],
                            "prefer_invests" => $v[31],
                            "openness_high_business" => $v[32],
                            "openness_low_business" => $v[33],
                            "c_source" => $v[34],
                        ]);
                        
                        //vip建立
                        if($v[24]){
                            $vip_cyear = explode('|',$v[24]);
                            foreach($vip_cyear as $v){
                                if(!DB::select("SELECT * FROM crm__vip_managements WHERE cyear = '$v' AND c_id = '$c_id' ")){
                                    if(is_numeric($v)){
                                        Crm_VipManagement::create([
                                            "cyear" => $v,
                                            "c_id" => $c_id
                                        ]);
                                    }
                                }
                            }
                        }
                    }else{
                        $crm__customer_basic_information = Crm_CustomerBasicInformation::find($c_id);
                        $crm__customer_basic_information->c_name_company = $v[1];
                        $crm__customer_basic_information->identification_gui_number =  $v[2];
                        $crm__customer_basic_information->c_sex =  $v[3];
                        $crm__customer_basic_information->c_birth_opening_date =  $c_birth_opening_date;
                        $crm__customer_basic_information->c_type =  $v[5];
                        $crm__customer_basic_information->c_telephone =  $v[6];
                        $crm__customer_basic_information->c_cellphone =  $v[7];
                        $crm__customer_basic_information->religion =  $v[8];
                        $crm__customer_basic_information->c_number =  $v[9];
                        $crm__customer_basic_information->c_family = $v[10];
                        $crm__customer_basic_information->postcode =  $v[11];
                        $crm__customer_basic_information->city =  $v[12];
                        $crm__customer_basic_information->city_area =  $v[13];
                        $crm__customer_basic_information->c_address =  $v[14];
                        $crm__customer_basic_information->open_account =  $v[15];
                        $crm__customer_basic_information->farmer_association_member =  $v[16];
                        $crm__customer_basic_information->farmer_insurance =  $v[17];
                        $crm__customer_basic_information->health_state =  $v[18];
                        $crm__customer_basic_information->communicate_state =  $v[19];
                        $crm__customer_basic_information->response_attitude =  $v[20];
                        $crm__customer_basic_information->deposit_level =  $v[21];
                        $crm__customer_basic_information->loan_level =  $v[22];
                        $crm__customer_basic_information->c_bank =  $v[23];
                        // $crm__customer_basic_information->vip_cyear =  $v[24];
                        $crm__customer_basic_information->encourage_raise_staff =  $v[25];
                        $crm__customer_basic_information->ao_staff =  $v[26];
                        // $crm__customer_basic_information->transfer_item =  $request->;
                        $crm__customer_basic_information->remark =  $v[27];
                        $crm__customer_basic_information->visitable_times =  $v[28];
                        $crm__customer_basic_information->dispositions =  $v[29];
                        $crm__customer_basic_information->interests = $v[30];
                        $crm__customer_basic_information->prefer_invests =  $v[31];
                        $crm__customer_basic_information->openness_high_business = $v[32];
                        $crm__customer_basic_information->openness_low_business =  $v[33];
                        $crm__customer_basic_information->c_source =  $v[34];
                        $crm__customer_basic_information->save();
                    }
                    $count_import++;
                }
            }
            // return "end";
            $request->session()->put('message', '匯入成功(匯入'.$count_import.'筆)');
            return redirect()->back();
        }
        return redirect('/login');
    }
    //每月餘額轉入
    public function account_balance_import_check(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_year = $now->year;
            $now_month = $now->month;
            return view("crm.crm_excel.crm_excel_import.import_account_balance_check",[
                'now_year' => $now_year,
                'now_month' => $now_month,
            ]);
        }
        return redirect('/login');
    }
    public function account_balance_import(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限 
            return view("crm.crm_excel.crm_excel_import.import_account_balance",[
            ]);
        }
        return redirect('/login');
    }
    public function account_balance_import_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $data = Excel::toCollection(new CrmExcelImport(), $request->file('excel_file'));
            // print_r($data[0]); //顯示excel 資料
            // return array();
            //客戶資料更新(新增)
            $count_import = 0;
            foreach($data[0] as $k=>$v){
                if($k==0) {
                    if($v[0]!='流水號' || $v[1]!='客戶編號' || $v[2]!='營業日期'){ //檢查EXCEL欄位名稱
                        return '錯誤';
                    }
                }else{
                    if(is_numeric($v[2])) $ab_date = gmdate("Y-m-d", ($v[2] - 25569) * 86400);
                    else $ab_date = $v[2];
                    $id = $v[0];
                    if(!$DB_crm__account_balances = DB::select("SELECT * FROM crm__account_balances WHERE id = '$id'")){
                        Crm_AccountBalance::create([
                            "id" => $id,
                            "c_id" => $v[1],
                            "ab_date" => $ab_date,
                            "ab_bank_number" => $v[3],
                            "ab_class" => $v[4],
                            "ab_acount" => $v[5],
                            "ab_balances" => $v[6],
                            "ab_deposit_money_average" => $v[7],
                            "ab_time_deposit_average" => $v[8],
                            "ab_credit_first" => $v[9],
                            "ab_last_year_interest_recover_money" => $v[10],
                            "ab_deposit_money" => $v[11],
                            "ab_credit_money" => $v[12],
                        ]);
                    }else{
                        $crm__account_balance = Crm_AccountBalance::find($id);
                        $crm__account_balance->c_id = $v[1];
                        $crm__account_balance->ab_date =  $ab_date;
                        $crm__account_balance->ab_bank_number =  $v[3];
                        $crm__account_balance->ab_class =  $v[4];
                        $crm__account_balance->ab_acount =  $v[5];
                        $crm__account_balance->ab_balances =  $v[6];
                        $crm__account_balance->ab_deposit_money_average =  $v[7];
                        $crm__account_balance->ab_time_deposit_average =  $v[8];
                        $crm__account_balance->ab_credit_first =  $v[9];
                        $crm__account_balance->ab_last_year_interest_recover_money = $v[10];
                        $crm__account_balance->ab_deposit_money =  $v[11];
                        $crm__account_balance->ab_credit_money =  $v[12];
                        $crm__account_balance->save();
                    }
                    $count_import++;
                }
            }
            $request->session()->put('message', '匯入成功(匯入'.$count_import.'筆)');
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function account_balance_check(Request $request){ //ajax json get data
        if($member_id = $request->session()->get('member_id') ){
            $year_month = $request->year.'-'.$request->month;
            $DB_crm__account_balances = DB::select("SELECT * FROM crm__account_balances WHERE ab_date LIKE '%$year_month%' ORDER BY id");
            if($DB_crm__account_balances){
                $tbody="";
                foreach($DB_crm__account_balances as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$v->c_id."</td>
                        <td class='border p-2'>".$v->ab_date."</td>
                        <td class='border p-2'>".$v->ab_bank_number."</td>
                        <td class='border p-2'>".$v->ab_class."</td>
                        <td class='border p-2'>".$v->ab_acount."</td>
                        <td class='border p-2'>".$v->ab_balances."</td>
                        <td class='border p-2'>".$v->ab_deposit_money_average."</td>
                        <td class='border p-2'>".$v->ab_time_deposit_average."</td>
                        <td class='border p-2'>".$v->ab_credit_first."</td>
                        <td class='border p-2'>".$v->ab_last_year_interest_recover_money."</td>
                        <td class='border p-2'>".$v->ab_deposit_money."</td>
                        <td class='border p-2'>".$v->ab_credit_money."</td>
                    </tr>";
                }
                $account_balance_table = "<thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2'>流水號</th>
                        <th class='border p-2'>客戶編號</th>
                        <th class='border p-2'>日期</th>
                        <th class='border p-2'>行別</th>
                        <th class='border p-2'>科目</th>
                        <th class='border p-2'>帳號</th>
                        <th class='border p-2'>餘額</th>
                        <th class='border p-2'>存摺戶<br>前六月均額</th>
                        <th class='border p-2'>定期戶<br>去年度均額</th>
                        <th class='border p-2'>放款戶<br>初貸額</th>
                        <th class='border p-2'>去年度利息<br>回收總額</th>
                        <th class='border p-2'>存款總額</th>
                        <th class='border p-2'>放款總額</th>
                    </tr></thead>
                <tbody>".$tbody."</tbody>";
            }else{
                $account_balance_table = "<div class='text-2xl font-bold'>查無資料</div>";;
            }
            
            $result = [
                "account_balance_table" => $account_balance_table,
            ];

            return response()->json($result);
        }
    }
    //每日大額轉入
    public function change_customer_import_check(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_year = $now->year;
            $now_month = $now->month;
            return view("crm.crm_excel.crm_excel_import.import_change_customer_check",[
                'now_year' => $now_year,
                'now_month' => $now_month,
            ]);
        }
        return redirect('/login');
    }
    public function change_customer_import(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限 
            return view("crm.crm_excel.crm_excel_import.import_change_customer",[
            ]);
        }
        return redirect('/login');
    }
    public function change_customer_import_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $data = Excel::toCollection(new CrmExcelImport(), $request->file('excel_file'));
            // print_r($data[0]); //顯示excel 資料
            // return array();
            //客戶資料更新(新增)
            $count_import = 0;
            foreach($data[0] as $k=>$v){
                if($k==0) {
                    if($v[0]!='流水號' || $v[1]!='客戶編號' || $v[2]!='日期'){ //檢查EXCEL欄位名稱
                        return '錯誤';
                    }
                }else{
                    if(is_numeric($v[2])) $cc_date = gmdate("Y-m-d", ($v[2] - 25569) * 86400);
                    else $cc_date = $v[2];
                    $id = $v[0];
                    if(!$DB_crm__change_customers = DB::select("SELECT * FROM crm__change_customers WHERE id = '$id'")){
                        Crm_ChangeCustomer::create([
                            "id" => $id,
                            "c_id" => $v[1],
                            "cc_date" => $cc_date,
                            "cc_branch_id" => $v[3],
                            "cc_class" => $v[4],
                            "cc_acount" => $v[5],
                            "cc_day_balance" => $v[6],
                            "cc_last_day_balance" => $v[7],
                            "cc_grow" => $v[8],
                            "cc_settlement_money" => $v[9],
                        ]);
                    }else{
                        $crm__change_customer = Crm_ChangeCustomer::find($id);
                        $crm__change_customer->c_id = $v[1];
                        $crm__change_customer->cc_date = $cc_date;
                        $crm__change_customer->cc_branch_id = $v[3];
                        $crm__change_customer->cc_class = $v[4];
                        $crm__change_customer->cc_acount = $v[5];
                        $crm__change_customer->cc_day_balance = $v[6];
                        $crm__change_customer->cc_last_day_balance = $v[7];
                        $crm__change_customer->cc_grow = $v[8];
                        $crm__change_customer->cc_settlement_money = $v[9];
                        $crm__change_customer->save();
                    }
                    $count_import++;
                }
            }
            $request->session()->put('message', '匯入成功(匯入'.$count_import.'筆)');
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function change_customer_check(Request $request){ //ajax json get data
        if($member_id = $request->session()->get('member_id') ){
            $year_month = $request->year.'-'.$request->month;
            $DB_crm__change_customers = DB::select("SELECT * FROM crm__change_customers WHERE cc_date LIKE '%$year_month%' ORDER BY id");
            if($DB_crm__change_customers){
                $tbody="";
                foreach($DB_crm__change_customers as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$v->c_id."</td>
                        <td class='border p-2'>".$v->cc_date."</td>
                        <td class='border p-2'>".$v->cc_branch_id."</td>
                        <td class='border p-2'>".$v->cc_class."</td>
                        <td class='border p-2'>".$v->cc_acount."</td>
                        <td class='border p-2'>".$v->cc_day_balance."</td>
                        <td class='border p-2'>".$v->cc_last_day_balance."</td>
                        <td class='border p-2'>".$v->cc_grow."</td>
                        <td class='border p-2'>".$v->cc_settlement_money."</td>
                    </tr>";
                }
                $change_customer_table = "<thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2'>流水號</th>
                        <th class='border p-2'>客戶編號</th>
                        <th class='border p-2'>日期</th>
                        <th class='border p-2'>分會id</th>
                        <th class='border p-2'>科目</th>
                        <th class='border p-2'>帳號</th>
                        <th class='border p-2'>本日餘額</th>
                        <th class='border p-2'>前日餘額</th>
                        <th class='border p-2'>正負成長</th>
                        <th class='border p-2'>增減金額</th>
                    </tr></thead>
                <tbody>".$tbody."</tbody>";
            }else{
                $change_customer_table = "<div class='text-2xl font-bold'>查無資料</div>";;
            }
            
            $result = [
                "change_customer_table" => $change_customer_table,
            ];

            return response()->json($result);
        }
    }

    //每月貢獻度轉入
    public function contribution_import_check(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_year = $now->year;
            $now_month = $now->month;
            return view("crm.crm_excel.crm_excel_import.import_contribution_check",[
                'now_year' => $now_year,
                'now_month' => $now_month,
            ]);
        }
        return redirect('/login');
    }
    public function contribution_import(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            return view("crm.crm_excel.crm_excel_import.import_contribution",[
            ]);
        }
        return redirect('/login');
    }
    public function contribution_import_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $data = Excel::toCollection(new CrmExcelImport(), $request->file('excel_file'));
            // print_r($data[0]); //顯示excel 資料
            // return array();
            //客戶資料更新(新增)
            $count_import = 0;
            foreach($data[0] as $k=>$v){
                if($k==0) {
                    if($v[0]!='流水號' || $v[1]!='客戶編號' || $v[2]!='日期' || $v[3]!='活儲'){ //檢查EXCEL欄位名稱
                        return '錯誤';
                    }
                }else{
                    if(is_numeric($v[2])) $c_date = gmdate("Y-m-d", ($v[2] - 25569) * 86400);
                    else $c_date = $v[2];
                    $id = $v[0];
                    if(!$DB_crm__contributions = DB::select("SELECT * FROM crm__contributions WHERE id = '$id'")){
                        Crm_Contribution::create([
                            "id" => $id,
                            "c_id" => $v[1],
                            "c_date" => $c_date,
                            "c_current_deposits" => $v[3],
                            "c_time_deposits" => $v[4],
                            "c_loan" => $v[5],
                            "c_transfer" => $v[6],
                            "c_insurance" => $v[7],
                            "c_score" => $v[8],
                        ]);
                    }else{
                        $crm__contribution = Crm_Contribution::find($id);
                        $crm__contribution->c_id = $v[1];
                        $crm__contribution->c_date = $c_date;
                        $crm__contribution->c_current_deposits = $v[3];
                        $crm__contribution->c_time_deposits = $v[4];
                        $crm__contribution->c_loan = $v[5];
                        $crm__contribution->c_transfer = $v[6];
                        $crm__contribution->c_insurance = $v[7];
                        $crm__contribution->c_score = $v[8];
                        $crm__contribution->save();
                    }
                    $count_import++;
                }
            }
            $request->session()->put('message', '匯入成功(匯入'.$count_import.'筆)');
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function contribution_check(Request $request){ //ajax json get data
        if($member_id = $request->session()->get('member_id') ){
            $year_month = $request->year.'-'.$request->month;
            $DB_crm__contributions = DB::select("SELECT * FROM crm__contributions WHERE c_date LIKE '%$year_month%' ORDER BY id");
            if($DB_crm__contributions){
                $tbody="";
                foreach($DB_crm__contributions as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$v->c_id."</td>
                        <td class='border p-2'>".$v->c_date."</td>
                        <td class='border p-2'>".$v->c_current_deposits."</td>
                        <td class='border p-2'>".$v->c_time_deposits."</td>
                        <td class='border p-2'>".$v->c_loan."</td>
                        <td class='border p-2'>".$v->c_transfer."</td>
                        <td class='border p-2'>".$v->c_insurance."</td>
                        <td class='border p-2'>".$v->c_score."</td>
                    </tr>";
                }
                $contribution_table = "<thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2'>流水號</th>
                        <th class='border p-2'>客戶編號</th>
                        <th class='border p-2'>日期</th>
                        <th class='border p-2'>活儲</th>
                        <th class='border p-2'>定儲</th>
                        <th class='border p-2'>放款</th>
                        <th class='border p-2'>轉帳</th>
                        <th class='border p-2'>保險</th>
                        <th class='border p-2'>貢獻度</th>
                    </tr></thead>
                <tbody>".$tbody."</tbody>";
            }else{
                $contribution_table = "<div class='text-2xl font-bold'>查無資料</div>";;
            }
            
            $result = [
                "contribution_table" => $contribution_table,
            ];

            return response()->json($result);
        }
    }
    
    //每月保險資訊轉入
    public function insurance_information_import_check(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_year = $now->year;
            $now_month = $now->month;
            return view("crm.crm_excel.crm_excel_import.import_insurance_information_check",[
                'now_year' => $now_year,
                'now_month' => $now_month,
            ]);
        }
        return redirect('/login');
    }
    public function insurance_information_import(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            return view("crm.crm_excel.crm_excel_import.import_insurance_information",[
            ]);
        }
        return redirect('/login');
    }
    public function insurance_information_import_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $data = Excel::toCollection(new CrmExcelImport(), $request->file('excel_file'));
            // print_r($data[0]); //顯示excel 資料
            // return array();
            //客戶資料更新(新增)
            $count_import = 0;
            foreach($data[0] as $k=>$v){
                if($k==0) {
                    if($v[0]!='流水號' || $v[1]!='客戶編號' || $v[2]!='日期' || $v[3]!='被保人'){ //檢查EXCEL欄位名稱
                        return '錯誤';
                    }
                }else{
                    if(is_numeric($v[2])) $ii_date = gmdate("Y-m-d", ($v[2] - 25569) * 86400);
                    else $ii_date = $v[2];
                    if(is_numeric($v[6])) $ii_insurance_date = gmdate("Y-m-d", ($v[6] - 25569) * 86400);
                    else $ii_insurance_date = $v[6];
                    $id = $v[0];
                    if(!$DB_crm__insurance_informations = DB::select("SELECT * FROM crm__insurance_informations WHERE id = '$id'")){
                        Crm_InsuranceInformation::create([
                            "id" => $id,
                            "c_id" => $v[1],
                            "ii_date" => $ii_date,
                            "ii_insured" => $v[3],
                            "ii_insurer" => $v[4],
                            "ii_company" => $v[5],
                            "ii_insurance_date" => $ii_insurance_date,
                            "ii_type" => $v[7],
                            "ii_cost" => $v[8],
                            "ii_commission" => $v[9],
                            "ii_car_number" => $v[10],
                        ]);
                    }else{
                        $crm__insurance_information = Crm_InsuranceInformation::find($id);
                        $crm__insurance_information->c_id = $v[1];
                        $crm__insurance_information->ii_date = $ii_date;
                        $crm__insurance_information->ii_insured = $v[3];
                        $crm__insurance_information->ii_insurer = $v[4];
                        $crm__insurance_information->ii_company = $v[5];
                        $crm__insurance_information->ii_insurance_date = $ii_insurance_date;
                        $crm__insurance_information->ii_type = $v[7];
                        $crm__insurance_information->ii_cost = $v[8];
                        $crm__insurance_information->ii_commission = $v[9];
                        $crm__insurance_information->ii_car_number = $v[10];
                        $crm__insurance_information->save();
                    }
                    $count_import++;
                }
            }
            $request->session()->put('message', '匯入成功(匯入'.$count_import.'筆)');
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function insurance_information_check(Request $request){ //ajax json get data
        if($member_id = $request->session()->get('member_id') ){
            $year_month = $request->year.'-'.$request->month;
            // $DB_crm__insurance_informations = DB::select("SELECT * FROM crm__insurance_informations WHERE c_date LIKE '%$year_month%'");
            $DB_crm__insurance_informations = DB::select("SELECT id, c_id, ii_date, ii_insured, ii_insurer, ii_company, 
                ii_insurance_date, ii_type, ii_cost, ii_commission, ii_car_number
            FROM crm__insurance_informations WHERE ii_date LIKE '%$year_month%' ORDER BY id");
            if($DB_crm__insurance_informations){
                $tbody="";
                foreach($DB_crm__insurance_informations as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$v->c_id."</td>
                        <td class='border p-2'>".$v->ii_date."</td>
                        <td class='border p-2'>".$v->ii_insured."</td>
                        <td class='border p-2'>".$v->ii_insurer."</td>
                        <td class='border p-2'>".$v->ii_company."</td>
                        <td class='border p-2'>".$v->ii_insurance_date."</td>
                        <td class='border p-2'>".$v->ii_type."</td>
                        <td class='border p-2'>".$v->ii_cost."</td>
                        <td class='border p-2'>".$v->ii_commission."</td>
                        <td class='border p-2'>".$v->ii_car_number."</td>
                    </tr>";
                }
                $insurance_information_table = "<thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2'>流水號</th>
                        <th class='border p-2'>客戶編號</th>
                        <th class='border p-2'>日期</th>
                        <th class='border p-2'>被保人</th>
                        <th class='border p-2'>要保人</th>
                        <th class='border p-2'>保險公司</th>
                        <th class='border p-2'>保單日期</th>
                        <th class='border p-2'>險別(小)</th>
                        <th class='border p-2'>保費</th>
                        <th class='border p-2'>農漁會佣金</th>
                        <th class='border p-2'>車號</th>
                    </tr></thead>
                <tbody>".$tbody."</tbody>";
            }else{
                $insurance_information_table = "<div class='text-2xl font-bold'>查無資料</div>";;
            }
            
            $result = [
                "insurance_information_table" => $insurance_information_table,
            ];

            return response()->json($result);
        }
    }
    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //     }
    //     return redirect('/login');
    // }
}
