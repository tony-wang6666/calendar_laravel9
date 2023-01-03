<?php

namespace App\Http\Controllers\crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use App\Models\crm_parameters\Crm_ParameterCustomerType;
use App\Models\crm_parameters\Crm_ParameterCustomerDisposition;
use App\Models\crm_parameters\Crm_ParameterCustomerInterest;
use App\Models\crm_parameters\Crm_ParameterCustomerPreferInvest;
use App\Models\crm_parameters\Crm_ParameterCustomerResponseAttitude;
use App\Models\crm_parameters\Crm_ParameterCustomerRelationship;
use App\Models\crm_parameters\Crm_ParameterCustomerReligion;
use App\Models\crm_parameters\Crm_ParameterCustomerVisitableTime;
use App\Models\crm_parameters\Crm_ParameterVisitFollowPhrase;
use App\Models\crm_parameters\Crm_ParameterVisitSupervisorSuggestPhrase;

class CrmParameterController extends Controller
{
    private $system_authority = 'D';
    public function parameter_set(Request $request, $select){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array($this->system_authority, $request->session()->get('member_authority')) ) return "error"; //權限
            $main_title = "參數管理";
            // if($select = "customer_type"){
            //     $title2 = $main_title.">客戶類型";
            //     // $DB_crm__parameter = DB::select("SELECT p_order, p_item, p_state FROM crm__parameter_customer_types WHERE p_state = 1 ORDER BY p_order");
            //     // return $select;
            // }
            switch($select){
                case "customer_type": $type = ">客戶類型"; break;
                case "customer_disposition": $type = ">客戶性格"; break;
                case "customer_interest": $type = ">客戶興趣"; break;
                case "customer_prefer_invest": $type = ">客戶偏好投資"; break;
                case "customer_response_attitude": $type = ">客戶應對態度"; break;
                case "customer_relationship": $type = ">客戶親屬關係"; break;
                case "customer_religion": $type = ">客戶宗教信仰"; break;
                case "customer_visitable_time": $type = ">客戶拜訪時間"; break;
                case "visit_follow_phrase": $type = ">後續追蹤片語"; break;
                case "visit_supervisor_suggest_phrase": $type = ">主管簽核片語"; break;
                default: return "error";
            }
            $title = $main_title.$type;
            return view("crm.member.crm_parameter.crm_parameter_set",[
                "title" => $title,
                "select" => $select,
            ]);
        }
        return redirect('/login');
    }
    public function parameter_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $select = $request->select;
            $TABLE = 'crm__parameter_'.$select.'s';
            $DB_crm__parameter_data = DB::select("SELECT id, p_order, p_item,
            CASE WHEN p_state = 1 THEN '啟用' WHEN p_state = 2 THEN '不啟用' ELSE '錯誤' END AS p_state
            FROM $TABLE ORDER BY p_order");
            $DB_crm__parameter_data_len = 0;
            if($DB_crm__parameter_data){
                $DB_crm__parameter_data_len = count($DB_crm__parameter_data);
                $tbody ="";
                foreach($DB_crm__parameter_data as $k=>$v){
                    if($k%2 == 1) $tr = "<tr class='bg-blue-200 h-16'>";
                    else $tr = "<tr class='bg-blue-300 h-16'>";
                    $tbody .= $tr.
                        "<td class='border p-2'><input type='checkbox' class='parameter_checkbox w-6 h-6' data-id='".$v->id."'></td>
                        <td class='border p-2'>".$v->p_order."</td>
                        <td class='border p-2'>".$v->p_item."</td>
                        <td class='border p-2'>".$v->p_state."</td>
                        <td class='border p-2'>
                            <button type='button' data-id='".$v->id."' data-toggle='mymodal' data-target='#parameterEditModal' class='parameter_edit_data bg-yellow-500 text-white p-2 hover:bg-yellow-600'>編輯</button>
                        </td>
                    </tr>";
                }
                $parameter_set_table = "
                <thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2 w-16'><input type='checkbox' class='checked_all w-6 h-6'></th>
                        <th class='border p-2 w-20'>順序</th>
                        <th class='border p-2'>項目</th>
                        <th class='border p-2 w-32'>狀態</th>
                        <th class='border p-2 w-24'></th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$tbody."</tbody>";
            }else{
                $parameter_set_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            $parameter_len = "參數共".$DB_crm__parameter_data_len."筆";
            $result = [
                "parameter_set_table" => $parameter_set_table,
                "parameter_len" => $parameter_len,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    
    public function parameter_edit_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $parameter_type = $request->parameter_type;
            $TABLE = 'crm__parameter_'.$parameter_type.'s';
            if($id = $request->id){ //編輯用
                $parameter_set_title2="編輯";
                $DB_crm__parameter_edit_data = DB::select("SELECT id, p_order, p_item,
                CASE WHEN p_state = 1 THEN '啟用' WHEN p_state = 2 THEN '不啟用' ELSE '錯誤' END AS p_state
                FROM $TABLE WHERE id = '$id' ");
                $p_order = $DB_crm__parameter_edit_data[0]->p_order;
                $p_item = $DB_crm__parameter_edit_data[0]->p_item;
                $p_state = $DB_crm__parameter_edit_data[0]->p_state;
    
                $p_state_array = ['啟用','不啟用'];
                $p_state_option = "";
                foreach($p_state_array as $k=>$v){
                    $val = $k + 1 ;
                    if($v == $p_state) $p_state_option .= "<option value='".$val."' selected>".$v."</option>";
                    else $p_state_option .= "<option value='".$val."'>".$v."</option>";
                }
            }else{ //新增用
                $parameter_set_title2="新增";
                $DB_crm__parameter_edit_data = DB::select("SELECT * FROM $TABLE ");
                $p_order = count($DB_crm__parameter_edit_data) +1 ;
                $p_item = '' ;
                $p_state_array = ['啟用','不啟用'];
                $p_state_option = "";
                foreach($p_state_array as $k=>$v){
                    $val = $k + 1 ;
                    $p_state_option .= "<option value='".$val."'>".$v."</option>";
                }
            }
            
            $result = [
                "p_order" => $p_order,
                "p_item" => $p_item,
                "p_state_option" => $p_state_option,
                "parameter_set_title2" => $parameter_set_title2,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function parameter_edit(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $parameter_type = $request->parameter_type;
            $p_item = $request->p_item;
            $p_order = $request->p_order;
            $p_state = $request->p_state;
            if($vals = $request->vals){ //刪除參數
                foreach($vals as $v){
                    switch($parameter_type){
                        case "customer_type": $crm__parameter = Crm_ParameterCustomerType::find($v); break;
                        case "customer_disposition": $crm__parameter = Crm_ParameterCustomerDisposition::find($v); break;
                        case "customer_interest": $crm__parameter = Crm_ParameterCustomerInterest::find($v); break;
                        case "customer_prefer_invest": $crm__parameter = Crm_ParameterCustomerPreferInvest::find($v); break;
                        case "customer_response_attitude": $crm__parameter = Crm_ParameterCustomerResponseAttitude::find($v); break;
                        case "customer_relationship": $crm__parameter = Crm_ParameterCustomerRelationship::find($v); break;
                        case "customer_religion": $crm__parameter = Crm_ParameterCustomerReligion::find($v); break;
                        case "customer_visitable_time": $crm__parameter = Crm_ParameterCustomerVisitableTime::find($v); break;
                        case "visit_follow_phrase": $crm__parameter = Crm_ParameterVisitFollowPhrase::find($v); break;
                        case "visit_supervisor_suggest_phrase": $crm__parameter = Crm_ParameterVisitSupervisorSuggestPhrase::find($v); break;
                    }
                    $crm__parameter->delete();
                }
            }elseif($id = $request->id){ //編輯 (有流水號， 編輯資料)
                switch($parameter_type){
                    case "customer_type": $crm__parameter = Crm_ParameterCustomerType::find($id); break;
                    case "customer_disposition": $crm__parameter = Crm_ParameterCustomerDisposition::find($id); break;
                    case "customer_interest": $crm__parameter = Crm_ParameterCustomerInterest::find($id); break;
                    case "customer_prefer_invest": $crm__parameter = Crm_ParameterCustomerPreferInvest::find($id); break;
                    case "customer_response_attitude": $crm__parameter = Crm_ParameterCustomerResponseAttitude::find($id); break;
                    case "customer_relationship": $crm__parameter = Crm_ParameterCustomerRelationship::find($id); break;
                    case "customer_religion": $crm__parameter = Crm_ParameterCustomerReligion::find($id); break;
                    case "customer_visitable_time": $crm__parameter = Crm_ParameterCustomerVisitableTime::find($id); break;
                    case "visit_follow_phrase": $crm__parameter = Crm_ParameterVisitFollowPhrase::find($id); break;
                    case "visit_supervisor_suggest_phrase": $crm__parameter = Crm_ParameterVisitSupervisorSuggestPhrase::find($id); break;
                }
                $crm__parameter->p_item = $p_item;
                $crm__parameter->p_order = $p_order;
                $crm__parameter->p_state = $p_state;
                $crm__parameter->save();
            }else{ //新增 (沒有流水號，新增資料)
                switch($parameter_type){
                    case "customer_type": $crm__parameter = new Crm_ParameterCustomerType(); break;
                    case "customer_disposition": $crm__parameter = new Crm_ParameterCustomerDisposition(); break;
                    case "customer_interest": $crm__parameter = new Crm_ParameterCustomerInterest(); break;
                    case "customer_prefer_invest": $crm__parameter = new Crm_ParameterCustomerPreferInvest(); break;
                    case "customer_response_attitude": $crm__parameter = new Crm_ParameterCustomerResponseAttitude(); break;
                    case "customer_relationship": $crm__parameter = new Crm_ParameterCustomerRelationship(); break;
                    case "customer_religion": $crm__parameter = new Crm_ParameterCustomerReligion(); break;
                    case "customer_visitable_time": $crm__parameter = new Crm_ParameterCustomerVisitableTime(); break;
                    case "visit_follow_phrase": $crm__parameter = new Crm_ParameterVisitFollowPhrase(); break;
                    case "visit_supervisor_suggest_phrase": $crm__parameter = new Crm_ParameterVisitSupervisorSuggestPhrase(); break;
                }
                $crm__parameter->p_item = $p_item;
                $crm__parameter->p_order = $p_order;
                $crm__parameter->p_state = $p_state;
                $crm__parameter->save();
            }
            

            $result = [
                "parameter_type" => $parameter_type,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }

    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //     }
    //     return redirect('/login');
    // }
}
