<?php

namespace App\Http\Controllers\crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Crm_VisitRecord;
use DB;

class CrmVisitRecordManagementController extends Controller
{
    private $system_authority = 'C';

    public function visit_records_manage(Request $request){
        // $DB_crm__visit_records = DB::select("SELECT c1.visit_type, c2.c_name_company, 
        // c3.name AS ao_staff_name
        // FROM crm__visit_records AS c1 
        // LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id
        // LEFT JOIN calendar_members AS c3 ON c2.ao_staff = c3.id
        // WHERE c3.name = 'ao_test2'
        // ORDER BY c1.visit_date DESC");
        // return $DB_crm__visit_records;
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            
            $title = "拜訪紀錄管理";
            $supervisor_suggest_phrase = $request->session()->get('search_visit_records_manage_supervisor_suggest_phrase');
            $date1 = $request->session()->get('search_visit_records_manage_date1');
            $date2 = $request->session()->get('search_visit_records_manage_date2');
            $visit_type = $request->session()->get('search_visit_records_manage_visit_type');
            $ao_staff = $request->session()->get('search_visit_records_manage_ao_staff');

            

            $supervisor_suggest_phrase_array = ["未簽核","已簽核"];
            $visit_type_array = ["定期","生日","大額"];
            $DB_ao_staffs = DB::select("SELECT account, name FROM calendar_members WHERE cm_ao_staff = '是' AND cm_state = '啟用'");

            $option_supervisor_suggest_phrase = $option_visit_type = $option_ao_staff = "";
            foreach($supervisor_suggest_phrase_array as $v){
                if($v == $supervisor_suggest_phrase) $option_supervisor_suggest_phrase .= "<option value='".$v."' selected>".$v."</option>";
                else $option_supervisor_suggest_phrase .= "<option value='".$v."'>".$v."</option>";
            }
            foreach($visit_type_array as $v){
                if($v == $visit_type) $option_visit_type .= "<option value='".$v."' selected>".$v."</option>";
                else $option_visit_type .= "<option value='".$v."'>".$v."</option>";
            }
            foreach($DB_ao_staffs as $v){
                if($v->name == $ao_staff) $option_ao_staff .= "<option value='".$v->name."' selected>".$v->name."</option>";
                else $option_ao_staff .= "<option value='".$v->name."'>".$v->name."</option>";
            }
            // $ao_staff_option = "";
            // foreach($ao_staffs as $v){
            //     $ao_staff_option .= "<option value='".$v->account."'>".$v->name."</option>";
            // }

        
            return view("crm.member.crm_visit_records_manage.crm_visit_records_manage",[
                'title' => $title,
                'option_supervisor_suggest_phrase' => $option_supervisor_suggest_phrase,
                'date1' => $date1,
                'date2' => $date2,
                'option_visit_type' => $option_visit_type,
                'option_ao_staff' => $option_ao_staff,
            ]);
        }
        return redirect('/login');
    }
    public function search_visit_records_manage(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $supervisor_suggest_phrase = $request->supervisor_suggest_phrase;
            $date1 = $request->date1;
            $date2 = $request->date2;
            $visit_type = $request->visit_type;
            $ao_staff = $request->ao_staff;
            $request->session()->put('search_visit_records_manage_supervisor_suggest_phrase',$supervisor_suggest_phrase);
            $request->session()->put('search_visit_records_manage_date1',$date1);
            $request->session()->put('search_visit_records_manage_date2',$date2);
            $request->session()->put('search_visit_records_manage_visit_type',$visit_type);
            $request->session()->put('search_visit_records_manage_ao_staff',$ao_staff);
            if($supervisor_suggest_phrase == "已簽核"){
                $where = "supervisor_suggest_phrase IS NOT NULL ";
            }else{
                $where = "supervisor_suggest_phrase IS NULL ";
            }
            
            if($date1 && $date2 && ($date1<=$date2) ){
                // $where .= "AND visit_date <= $date2 "; //AND visit_date >= '$date1' 
                $where .= "AND visit_date BETWEEN '$date1' AND '$date2' ";
            }
            if($visit_type ){
                $where .= "AND visit_type = '$visit_type' ";
            }
            if($ao_staff ){
                $where .= "AND c3.name = '$ao_staff' ";
            }
            $select = "SELECT c1.visit_date, c1.visit_report_date, c1.visit_type,
            c2.c_name_company, c1.visit_content, c1.creator_name, c3.name AS ao_staff_name, c1.customer_analysis_name, c1.supervisor_suggest_name,
            c1.id, c1.c_id
            FROM crm__visit_records AS c1 
            LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id
            LEFT JOIN calendar_members AS c3 ON c2.ao_staff = c3.id
            WHERE $where 
            ORDER BY c1.visit_date DESC";
            $request->session()->put('search_visit_records_manage_download',$select);
            $DB_crm__visit_records = DB::select("$select");
            $DB_crm__visit_records_count = 0;
            if($DB_crm__visit_records){
                $DB_crm__visit_records_count = count($DB_crm__visit_records);
                $tbody ="";
                foreach($DB_crm__visit_records as $k=>$v){
                    if($k%2 == 1) $tr = "<tr class='bg-blue-200 h-16'>";
                    else $tr = "<tr class='bg-blue-300 h-16'>";
                    $tbody .= $tr.
                        "<td class='border p-2'><input type='checkbox' name='visit_record_delete[]' value='".$v->id."' class='checks w-6 h-6'></td>
                        <td class='border p-2'>".$v->visit_date."</td>
                        <td class='border p-2'>".$v->visit_report_date."</td>
                        <td class='border p-2'>".$v->visit_type."</td>
                        <td class='border p-2'>".$v->c_name_company."</td>
                        <td class='border p-2'>".$v->visit_content."</td>
                        <td class='border p-2'>".$v->creator_name."</td>
                        <td class='border p-2'>".$v->ao_staff_name."</td>
                        <td class='border p-2'>".$v->customer_analysis_name."</td>
                        <td class='border p-2'>".$v->supervisor_suggest_name."</td>
                        <td class='border p-2'>
                            <button type='button' data-id='".$v->id."' data-c_id='".$v->c_id."' data-toggle='mymodal' data-target='#VisitRecordEditModal' class='visit_record_edit_get bg-yellow-500 text-white p-2 hover:bg-yellow-600'>編輯</button>
                        </td>
                    </tr>";
                }
                $visit_record_table = "
                <thead class='bg-blue-600 text-white'><tr>
                    <th class='border p-2 w-10'><input type='checkbox' class='checked_all w-6 h-6'></th>
                    <th class='border p-2 w-32'>訪談日期</th>
                    <th class='border p-2 w-32'>報告日期</th>
                    <th class='border p-2 w-14'>種類</th>
                    <th class='border p-2 w-32'>姓名</th>
                    <th class='border p-2 '>內容</th>
                    <th class='border p-2 w-20'>建立者</th>
                    <th class='border p-2 w-20'>AO</th>
                    <th class='border p-2 w-20'>客管</th>
                    <th class='border p-2 w-20'>主管</th>
                    <th class='border p-2 w-20'></th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$tbody."</tbody>";
            }else{
                $visit_record_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            $result = [
                "visit_record_table" => $visit_record_table,
                "DB_crm__visit_records_count" => $DB_crm__visit_records_count,
                "where" => $where,
            ];

            return response()->json($result);
        }
        return redirect('/login');
    }
    public function visit_records_manage_delete(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $visit_record_delete = $request->visit_record_delete;
            if($visit_record_delete){
                foreach($visit_record_delete as $v){
                    $crm__visit_record = Crm_VisitRecord::find($v);
                    $crm__visit_record->delete();
                }
            }
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function visit_records_manage_add(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $title = "拜訪記錄新增";
            return view("crm.member.crm_visit_records_manage.crm_visit_records_manage_add",[
                'title' => $title,
            ]);
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
