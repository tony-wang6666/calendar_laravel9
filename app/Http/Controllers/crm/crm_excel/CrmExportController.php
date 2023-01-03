<?php

namespace App\Http\Controllers\crm\crm_excel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Excel;
use App\Exports\crm\CrmCustomerBasicInformationExport;
use App\Exports\crm\CrmAccountBalanceExport;
use App\Exports\crm\CrmChangeCustomerExport;
use App\Exports\crm\CrmContributionExport;
use App\Exports\crm\CrmInsuranceInformationExport;
use App\Exports\crm\CrmVipManagementExport;
use App\Exports\crm\CrmChangeCustomerAoStaffExport;
use App\Exports\crm\CrmVisitRecordExport;

use Response;

use DB;

class CrmExportController extends Controller
{
    private $system_authority = 'F';
    public function basic_customer_data_export(Request $request){ // 客戶資料      
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            // $data = ['測試1','測試2','測試3'];

            $DB_field_name = ['編號','姓名/公司','身分證/統編','性別','生日/開業日','客戶種類','電話','手機','宗教','戶號','開戶親屬','區碼','縣市','鄉鎮區','地址','本會開戶','農會會員','農保','健康狀況','溝通狀況','回應態度','存款等級','貸款等級','主要往來銀行','VIP年度','勸募員工','AO人員','備註','可拜訪時段','性格','興趣','偏好投資','開放性較高業務','開放性較低業務','資料來源'];
            $DB_data = DB::select("SELECT c1.id, c1.c_name_company, c1.identification_gui_number, c1.c_sex, 
            c1.c_birth_opening_date, c1.c_type, c1.c_telephone, c1.c_cellphone, c1.religion, 
            c1.c_number, c1.c_family, c1.postcode, c1.city, c1.city_area, c1.c_address, c1.open_account, 
            c1.farmer_association_member, c1.farmer_insurance, c1.health_state, c1.communicate_state, 
            c1.response_attitude, c1.deposit_level, c1.loan_level, c1.c_bank, 
            (SELECT GROUP_CONCAT(cyear ORDER BY cyear DESC SEPARATOR '|') FROM crm__vip_managements c2 WHERE c1.id = c2.c_id ORDER BY cyear) AS cyears,
            c1.encourage_raise_staff, c1.ao_staff, c1.remark, c1.visitable_times, c1.dispositions,
            c1.interests, c1.prefer_invests, c1.openness_high_business, c1.openness_low_business, c1.c_source
            FROM crm__customer_basic_informations AS c1 ");
    
            $table = $DB_data;
            $output=pack('CCC', 0xef, 0xbb, 0xbf); //具有BOM utf8編碼
            $output.= implode(",",$DB_field_name)."\n";
            foreach ($table as $v) {
                $output.=  implode(",",(array)$v)."\n";
            }
            $filename = "客戶基本資料.csv";
            $encoded_filename = urlencode($filename);
            $headers = array(
                'Content-Type' => "text/csv;charset=utf8",
                'Content-Disposition' => "attachment; filename=$encoded_filename",
            );
            // return rtrim($output, "\n");
            return Response::make($output, 200, $headers);

            //2021/11/16以上的程式碼可以下載csv，但是有逗號問題
            //2021/11/17以下的程式碼可以下載xlsx，但是下載時間很長，目前預計5000筆 會花15秒左右

            // $DB_data = DB::select("SELECT c1.id, c1.c_name_company, c1.identification_gui_number, c1.c_sex, 
            // c1.c_birth_opening_date, c1.c_type, c1.c_telephone, c1.c_cellphone, c1.religion, 
            // c1.c_number, c1.c_family, c1.postcode, c1.city, c1.city_area, c1.c_address, c1.open_account, 
            // c1.farmer_association_member, c1.farmer_insurance, c1.health_state, c1.communicate_state, 
            // c1.response_attitude, c1.deposit_level, c1.loan_level, c1.c_bank, 
            // (SELECT GROUP_CONCAT(cyear ORDER BY cyear DESC SEPARATOR ',') FROM crm__vip_managements c2 WHERE c1.id = c2.c_id ORDER BY cyear) AS cyears,
            // c1.encourage_raise_staff, c1.ao_staff, c1.remark, c1.visitable_times, c1.dispositions,
            // c1.interests, c1.prefer_invests, c1.openness_high_business, c1.openness_low_business, c1.c_source
            // FROM crm__customer_basic_informations AS c1
            // ORDER BY c1.id
            // ");//LIMIT 10000
            // $DB_arrry[] = ['編號','姓名/公司','身分證/統編','性別','生日/開業日','客戶種類','電話','手機','宗教','戶號','開戶親屬','區碼','縣市','鄉鎮區','地址','本會開戶','農會會員','農保','健康狀況','溝通狀況','回應態度','存款等級','貸款等級','主要往來銀行','VIP年度','勸募員工','AO人員','備註','可拜訪時段','性格','興趣','偏好投資','開放性較高業務','開放性較低業務','資料來源'];
            
            // // $dateTime = '2020-02-02T22:22:22';
            // $worktable = '客戶';
            // // return 123;
            // ini_set('memory_limit', '3096M'); //可下載的檔案大小設定
            // ini_set("max_execution_time", "60"); //可下載的運行時間設定
            // return Excel::download(new CrmCustomerBasicInformationExport($DB_data,$DB_arrry,$worktable), '客戶基本資料.xlsx');
        }
        return redirect('/login');
    }

    public function account_balance_export(Request $request){ // 每月帳戶餘額
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $DB_data = DB::select("SELECT id, c_id, ab_date, ab_bank_number, ab_class, ab_acount, 
            ab_balances, ab_deposit_money_average, ab_time_deposit_average, ab_credit_first, 
            ab_last_year_interest_recover_money, ab_deposit_money, ab_credit_money
            FROM crm__account_balances ORDER BY id");
            $DB_arrry[] = ['流水號','客戶編號','營業日期','行別','科目','帳號','餘額','存摺戶'."\n".'前六月均額','定期戶'."\n".'去年度均額','放款戶'."\n".'初貸額','去年度利息'."\n".'回收總額','存款總額','放款總額'];
            $worktable = '每月帳戶餘額';
            return Excel::download(new CrmAccountBalanceExport($DB_data,$DB_arrry,$worktable), '每月帳戶餘額.xlsx');
        }
        return redirect('/login');
    }
    
    public function change_customer_export(Request $request){ // 每日大額
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $DB_data = DB::select("SELECT * FROM crm__change_customers ORDER BY id");
            $DB_arrry[] = ['流水號','客戶編號','日期','分會id','科目','帳號','本日餘額','前日餘額','正負成長','增減金額'];
            $worktable = '每日大額';
            return Excel::download(new CrmChangeCustomerExport($DB_data,$DB_arrry,$worktable), '每日大額.xlsx');
        }
        return redirect('/login');
    }
    public function contribution_export(Request $request){ // 每月貢獻度
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $DB_data = DB::select("SELECT * FROM crm__contributions ORDER BY id");
            $DB_arrry[] = ['流水號','客戶編號','日期','活儲','定儲','放款','轉帳','保險','貢獻度'];
            $worktable = '每月貢獻度';
            return Excel::download(new CrmContributionExport($DB_data,$DB_arrry,$worktable), '每月貢獻度.xlsx');
        }
        return redirect('/login');
    }
    public function insurance_information_export(Request $request){ // 每月保險資訊
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $DB_data = DB::select("SELECT * FROM crm__insurance_informations ORDER BY id");
            $DB_arrry[] = ['流水號','客戶編號','日期','被保人','要保人','保險公司','保單日期','險別(小)','保費','農漁會佣金','車號'];
            $worktable = '每月保險資訊';
            return Excel::download(new CrmInsuranceInformationExport($DB_data,$DB_arrry,$worktable), '每月保險資訊.xlsx');
        }
        return redirect('/login');
    }

    public function vip_management_export(Request $request){ //客戶管理VIP
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array("B", $request->session()->get('member_authority')) ) return "error"; //權限
            // return 1239195;
            $cyear = $request->cyear;
            $DB_data = DB::select("SELECT c1.id, c1.cyear, c1.c_id, c1.cyear, c1.cyear_level, c2.c_name_company, c2.c_telephone, c2.c_cellphone, c2.ao_staff
            FROM crm__vip_managements AS c1
            LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id
            WHERE cyear='$cyear'");
            $DB_arrry[] = ['年度','編號','姓名','本年等級','電話','手機','AO'];
            $worktable = $cyear.'VIP客戶';
            return Excel::download(new CrmVipManagementExport($DB_data,$DB_arrry,$worktable), $cyear.'VIP客戶.xlsx');
        }
        return redirect('/login');
    }

    public function change_customer_ao_staff_export(Request $request){ //ao異動
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array("E", $request->session()->get('member_authority')) ) return "error"; //權限
            $select =  $request->session()->get('search_change_customer_ao_staff_record_download');
            $DB_data = DB::select($select);
            $DB_arrry[] = ['異動日期','原AO','原AO姓名','新AO','新AO姓名','編號','姓名','電話','手機'];
            $worktable = 'AO異動紀錄';
            return Excel::download(new CrmChangeCustomerAoStaffExport($DB_data,$DB_arrry,$worktable), $worktable.'.xlsx');
        }
        return redirect('/login');
    }
    public function search_visit_records_manage_export(Request $request){ //拜訪紀錄
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array("C", $request->session()->get('member_authority')) ) return "error"; //權限
            $select =  $request->session()->get('search_visit_records_manage_download');
            $DB_data = DB::select($select);
            $DB_arrry[] = ['訪談日期','報告日期','種類','姓名','內容','建立者','AO','客管','主管'];
            $worktable = '拜訪紀錄';
            return Excel::download(new CrmVisitRecordExport($DB_data,$DB_arrry,$worktable), $worktable.'.xlsx');
        }
        // CrmVisitRecordExport
        return redirect('/login');
    }
    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //     }
    //     return redirect('/login');
    // }
}
