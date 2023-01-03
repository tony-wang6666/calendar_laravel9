<?php

namespace App\Http\Controllers\crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crm_CustomerBasicInformation;
use App\Models\Crm_VisitRecord;
use App\Models\Crm_VipManagement;
use DB;

use Carbon\Carbon;

class CreateFormController extends Controller
{
    public function crmFrom(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            return view("crm.crm_welcome");
        }
        return redirect('/login');
    }

    public function basic_customer_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array('B', $request->session()->get('member_authority')) ) return "error"; //權限
            $interface = $request->session()->get('search_customer_data_interface');
            $search_type = $request->session()->get('search_customer_data_search_type');
            $search_val = $request->session()->get('search_customer_data_search_val');
            $search_id = $request->session()->get('search_customer_data_search_id');
            //查詢條件
            $search_type_array=["客戶姓名","身分證號","電話手機","客戶編號"];
            $search_val_array=["c_name_company","identification_gui_number","phone","id"];
            $option_search_type ="";
            foreach($search_type_array as $k=>$v){
                if($search_val_array[$k] == $search_type){
                    $option_search_type .= "<option value='".$search_val_array[$k]."' selected>".$v."</option>";
                }else{
                    $option_search_type .= "<option value='".$search_val_array[$k]."'>".$v."</option>";
                }
            }
            //顯示介面
            $DB_crm__customer_basic_informations= $option_encourage_raise_staff = $option_ao_staff = "";
            $checkbox_visitable_times = $checkbox_dispositions = $checkbox_interests = $checkbox_prefer_invests = $checkbox_openness_high_business = $checkbox_openness_low_business ="";
            $customer_types = $religions = $response_attitudes = "";
            if($interface == "search_list"){
                if($search_type == "phone"){
                    $where = "c_telephone LIKE '%$search_val%' OR c_cellphone LIKE '%$search_val%'";
                }else{
                    $where = "$search_type LIKE '%$search_val%'";
                }
                $DB_crm__customer_basic_informations= DB::select("SELECT 
                    id, c_name_company, c_telephone, c_cellphone, postcode, city, city_area, c_address
                    FROM crm__customer_basic_informations 
                    WHERE $where");
            }elseif($interface == "basic_customer_data"){
                $DB_crm__customer_basic_informations= DB::select("SELECT 
                    c1.id, c1.c_name_company, c1.identification_gui_number, c1.c_sex, c1.c_family, 
                    c1.c_birth_opening_date, c1.c_type, c1.c_telephone, c1.c_cellphone, c1.religion, 
                    c1.c_number, c1.postcode, c1.city, c1.city_area, c1.c_address, c1.open_account, 
                    c1.farmer_association_member, c1.farmer_insurance, c1.health_state, c1.communicate_state, 
                    c1.response_attitude, c1.deposit_level, c1.loan_level, c1.c_bank, c1.encourage_raise_staff,
                    c1.ao_staff, c1.transfer_item, c1.remark, c1.visitable_times, c1.dispositions,
                    c1.interests, c1.prefer_invests, c1.openness_high_business, c1.openness_low_business, c1.c_source,
                    (SELECT substring_index(GROUP_CONCAT(CONCAT('VIP-',cyear) ORDER BY cyear DESC SEPARATOR ','), ',', 2) FROM crm__vip_managements c2 WHERE c1.id = c2.c_id ORDER BY cyear) AS cyears
                    FROM crm__customer_basic_informations AS c1
                    WHERE id='$search_id' ");
                
                if(!$DB_crm__customer_basic_informations) { //如果查無資料，就跳回去查詢
                    $interface = "search_list";
                    $request->session()->put('search_customer_data_interface',$interface);
                    return redirect('crm/basic_customer_data');
                }
                //表單勸募員工選項
                $encourage_raise_staff = $DB_crm__customer_basic_informations[0]->encourage_raise_staff;
                $DB_calendar_members = DB::select("SELECT id, name FROM calendar_members WHERE cm_ao_staff ='否' AND cm_state = '啟用' ORDER BY name");
                $option_encourage_raise_staff = "<option value=''>無</option>";
                foreach($DB_calendar_members as $v){
                    if($v->id == $encourage_raise_staff) $option_encourage_raise_staff .= "<option value='".$v->id."' selected>".$v->name."</option>";
                    else $option_encourage_raise_staff .= "<option value='".$v->id."'>".$v->name."</option>";
                }
                //表單AO人員選項
                $ao_staff = $DB_crm__customer_basic_informations[0]->ao_staff;
                $ao_staff_array = DB::select("SELECT id, name FROM calendar_members WHERE cm_ao_staff ='是' AND cm_state = '啟用' ORDER BY name");
                $option_ao_staff = "<option value='無'>無</option>";
                foreach($ao_staff_array as $v){
                    if($v->id == $ao_staff) $option_ao_staff .= "<option value='".$v->id."' selected>".$v->name."</option>";
                    else $option_ao_staff .= "<option value='".$v->id."'>".$v->name."</option>";
                }
                //表單上方選項(客戶種類、宗教、回應態度)
                $DB_customer_types= DB::select("SELECT * FROM crm__parameter_customer_types WHERE p_state = 1 ORDER BY p_order");
                $DB_religions= DB::select("SELECT * FROM crm__parameter_customer_religions WHERE p_state = 1 ORDER BY p_order");
                $DB_response_attitudes= DB::select("SELECT * FROM crm__parameter_customer_response_attitudes WHERE p_state = 1 ORDER BY p_order");
                

                $c_type = $DB_crm__customer_basic_informations[0]->c_type;
                $religion = $DB_crm__customer_basic_informations[0]->religion;
                $response_attitude = $DB_crm__customer_basic_informations[0]->response_attitude;

                foreach($DB_customer_types as $k=>$v){
                    if($v->p_item == $c_type) $customer_types .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                    else $customer_types .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
                }
                foreach($DB_religions as $k=>$v){
                    if($v->p_item == $religion) $religions .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                    else $religions .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
                }
                foreach($DB_response_attitudes as $k=>$v){
                    if($v->p_item == $response_attitude) $response_attitudes .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                    else $response_attitudes .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
                }

                //顧客細項
                $DB_visitable_times= DB::select("SELECT * FROM crm__parameter_customer_visitable_times WHERE p_state = 1 ORDER BY p_order");
                $DB_dispositions= DB::select("SELECT * FROM crm__parameter_customer_dispositions WHERE p_state = 1 ORDER BY p_order");
                $DB_interests= DB::select("SELECT * FROM crm__parameter_customer_interests WHERE p_state = 1 ORDER BY p_order");
                $DB_prefer_invests= DB::select("SELECT * FROM crm__parameter_customer_prefer_invests WHERE p_state = 1 ORDER BY p_order");
                $openness_high_business_array = ["存款業務","放款業務","薪資轉帳","保險業務","信用卡","供銷產品","轉繳業務"];
                $openness_low_business_array = ["存款業務","放款業務","薪資轉帳","保險業務","信用卡","供銷產品","轉繳業務"];
                
                
                $visitable_times = $DB_crm__customer_basic_informations[0]->visitable_times;
                $dispositions = $DB_crm__customer_basic_informations[0]->dispositions;
                $interests = $DB_crm__customer_basic_informations[0]->interests;
                $prefer_invests = $DB_crm__customer_basic_informations[0]->prefer_invests;
                $openness_high_business = $DB_crm__customer_basic_informations[0]->openness_high_business;
                $openness_low_business = $DB_crm__customer_basic_informations[0]->openness_low_business;

                foreach($DB_visitable_times as $k=>$v){
                    if( in_array($v->p_item,explode("|",$visitable_times)) ) $checkbox = "<input type='checkbox' id='visitable_time".$k."' name='visitable_time[]' value='".$v->p_item."' class='px-2 h-6 w-6' checked>";
                    else $checkbox = "<input type='checkbox' id='visitable_time".$k."' name='visitable_time[]' value='".$v->p_item."' class='px-2 h-6 w-6'>";
                    $checkbox_visitable_times .= "<div class='flex m-1'>
                        ".$checkbox."
                        <label for='visitable_time".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                    </div>";
                }
                foreach($DB_dispositions as $k=>$v){
                    if( in_array($v->p_item,explode("|",$dispositions)) ) $checkbox = "<input type='checkbox' id='dispositions".$k."' name='dispositions[]' value='".$v->p_item."' class='px-2 h-6 w-6' checked>";
                    else $checkbox = "<input type='checkbox' id='dispositions".$k."' name='dispositions[]' value='".$v->p_item."' class='px-2 h-6 w-6'>";
                    $checkbox_dispositions .= "<div class='flex m-1'>
                        ".$checkbox."
                        <label for='dispositions".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                    </div>";
                }
                foreach($DB_interests as $k=>$v){
                    if( in_array($v->p_item,explode("|",$interests)) ) $checkbox = "<input type='checkbox' id='interests".$k."' name='interests[]' value='".$v->p_item."' class='px-2 h-6 w-6' checked>";
                    else $checkbox = "<input type='checkbox' id='interests".$k."' name='interests[]' value='".$v->p_item."' class='px-2 h-6 w-6'>";
                    $checkbox_interests .= "<div class='flex m-1'>
                        ".$checkbox."
                        <label for='interests".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                    </div>";
                }
                foreach($DB_prefer_invests as $k=>$v){
                    if( in_array($v->p_item,explode("|",$prefer_invests)) ) $checkbox = "<input type='checkbox' id='prefer_invests".$k."' name='prefer_invests[]' value='".$v->p_item."' class='px-2 h-6 w-6' checked>";
                    else $checkbox = "<input type='checkbox' id='prefer_invests".$k."' name='prefer_invests[]' value='".$v->p_item."' class='px-2 h-6 w-6'>";
                    $checkbox_prefer_invests .= "<div class='flex m-1'>
                        ".$checkbox."
                        <label for='prefer_invests".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                    </div>";
                }
                foreach($openness_high_business_array as $k=>$v){
                    if( in_array($v,explode("|",$openness_high_business)) ) $checkbox = "<input type='checkbox' id='openness_high_business".$k."' name='openness_high_business[]' value='".$v."' class='px-2 h-6 w-6' checked>";
                    else $checkbox = "<input type='checkbox' id='openness_high_business".$k."' name='openness_high_business[]' value='".$v."' class='px-2 h-6 w-6'>";
                    $checkbox_openness_high_business .= "<div class='flex m-1'>
                        ".$checkbox."
                        <label for='openness_high_business".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v."</label>
                    </div>";
                }
                foreach($openness_low_business_array as $k=>$v){
                    if( in_array($v,explode("|",$openness_low_business)) ) $checkbox = "<input type='checkbox' id='openness_low_business".$k."' name='openness_low_business[]' value='".$v."' class='px-2 h-6 w-6' checked>";
                    else $checkbox = "<input type='checkbox' id='openness_low_business".$k."' name='openness_low_business[]' value='".$v."' class='px-2 h-6 w-6'>";
                    $checkbox_openness_low_business .= "<div class='flex m-1'>
                        ".$checkbox."
                        <label for='openness_low_business".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v."</label>
                    </div>";
                }
            }
            return view("crm.member.crm_basic_customer_data",[
                "interface" => $interface, //介面
                "search_val" => $search_val, //查詢值
                "option_search_type" => $option_search_type, //查詢項目
                "DB_crm__customer_basic_informations" => $DB_crm__customer_basic_informations, //查詢資料
                "option_encourage_raise_staff" => $option_encourage_raise_staff, //勸募員工
                "option_ao_staff" => $option_ao_staff, //ao人員
                "customer_types" => $customer_types, //客戶種類
                "religions" => $religions, //宗教
                "response_attitudes" => $response_attitudes, //回應態度
                "checkbox_visitable_times" => $checkbox_visitable_times, //可拜訪時段
                "checkbox_dispositions" => $checkbox_dispositions, //性格
                "checkbox_interests" => $checkbox_interests, //興趣
                "checkbox_prefer_invests" => $checkbox_prefer_invests, //偏好投資
                "checkbox_openness_high_business" => $checkbox_openness_high_business, //開放性較高業務
                "checkbox_openness_low_business" => $checkbox_openness_low_business, //開放性較低業務
            ]);
        }
        return redirect('/login');
    }
    public function search_customer_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $interface = $request->interface;
            if($interface == "search_list"){
                $search_type = $request->search_type;
                $search_val = $request->search_val;
                $request->session()->put('search_customer_data_search_type',$search_type);
                $request->session()->put('search_customer_data_search_val',$search_val);
            }elseif($interface == "basic_customer_data"){
                $search_id = $request->search_id;
                $request->session()->put('search_customer_data_search_id',$search_id);
            }
            $request->session()->put('search_customer_data_interface',$interface);
            return redirect('crm/basic_customer_data');
        }
        return redirect('/login');
    }

    public function basic_customer_data_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $visitable_times = $dispositions = $interests = $prefer_invests = $openness_high_business = $openness_low_business = "";
            if($request->visitable_time) $visitable_times = implode("|",$request->visitable_time);
            if($request->dispositions) $dispositions = implode("|",$request->dispositions);
            if($request->interests) $interests = implode("|",$request->interests);
            if($request->prefer_invests) $prefer_invests = implode("|",$request->prefer_invests);
            if($request->openness_high_business) $openness_high_business = implode("|",$request->openness_high_business);
            if($request->openness_low_business) $openness_low_business = implode("|",$request->openness_low_business);
            
            $crm__customer_basic_information = Crm_CustomerBasicInformation::find($request->c_id);
            $crm__customer_basic_information->c_name_company = $request->c_name_company;
            $crm__customer_basic_information->identification_gui_number =  $request->identification_gui_number;
            $crm__customer_basic_information->c_sex =  $request->c_sex;
            $crm__customer_basic_information->c_birth_opening_date =  $request->c_birth_opening_date;
            $crm__customer_basic_information->c_type =  $request->c_type;
            $crm__customer_basic_information->c_telephone =  $request->c_telephone;
            $crm__customer_basic_information->c_cellphone =  $request->c_cellphone;
            $crm__customer_basic_information->religion =  $request->religion;
            $crm__customer_basic_information->c_number =  $request->c_number;
            $crm__customer_basic_information->postcode =  $request->postcode;
            $crm__customer_basic_information->city =  $request->city;
            $crm__customer_basic_information->city_area =  $request->city_area;
            $crm__customer_basic_information->c_address =  $request->c_address;
            $crm__customer_basic_information->open_account =  $request->open_account;
            $crm__customer_basic_information->farmer_association_member =  $request->farmer_association_member;
            $crm__customer_basic_information->farmer_insurance =  $request->farmer_insurance;
            $crm__customer_basic_information->health_state =  $request->health_state;
            $crm__customer_basic_information->communicate_state =  $request->communicate_state;
            $crm__customer_basic_information->response_attitude =  $request->response_attitude;
            $crm__customer_basic_information->deposit_level =  $request->deposit_level;
            $crm__customer_basic_information->loan_level =  $request->loan_level;
            $crm__customer_basic_information->c_bank =  $request->c_bank;
            // $crm__customer_basic_information->vip_cyear =  $request->;
            $crm__customer_basic_information->encourage_raise_staff =  $request->encourage_raise_staff;
            $crm__customer_basic_information->ao_staff =  $request->ao_staff;
            // $crm__customer_basic_information->transfer_item =  $request->;
            $crm__customer_basic_information->remark =  $request->remark;
            $crm__customer_basic_information->visitable_times =  $visitable_times;
            $crm__customer_basic_information->dispositions =  $dispositions;
            $crm__customer_basic_information->interests = $interests;
            $crm__customer_basic_information->prefer_invests =  $prefer_invests;
            $crm__customer_basic_information->openness_high_business = $openness_high_business;
            $crm__customer_basic_information->openness_low_business =  $openness_low_business;
            $crm__customer_basic_information->c_source =  $request->c_source;
            $crm__customer_basic_information->save();
            return redirect()->back();
            
        }
        return redirect('/login');
    }

    public function create_customer_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array('B', $request->session()->get('member_authority')) ) return "error"; //權限
            //新建客戶編號
            $crm__customer_basic_informations = DB::select("SELECT id AS customer_number FROM crm__customer_basic_informations ORDER BY id DESC LIMIT 1");
            if($crm__customer_basic_informations) $customer_number = $crm__customer_basic_informations[0]->customer_number + 1;
            else $customer_number = 9000001;
            //表單地址選項
            $DB_taiwan_city_areas = DB::select("SELECT city FROM taiwan_city_areas GROUP BY city ORDER BY id");
            $option_city = "";
            foreach($DB_taiwan_city_areas as $v){
                $option_city .= "<option value='".$v->city."'>".$v->city."</option>";
            }
            //表單勸募員工選項
            $DB_calendar_members = DB::select("SELECT name FROM calendar_members ORDER BY name");
            $option_encourage_raise_staff = "<option value=''>無</option>";
            foreach($DB_calendar_members as $v){
                $option_encourage_raise_staff .= "<option value='".$v->name."'>".$v->name."</option>";
            }

            //表單上方選項(客戶種類、宗教、回應態度)
            $DB_customer_types= DB::select("SELECT * FROM crm__parameter_customer_types WHERE p_state = 1 ORDER BY p_order");
            $DB_religions= DB::select("SELECT * FROM crm__parameter_customer_religions WHERE p_state = 1 ORDER BY p_order");
            $DB_response_attitudes= DB::select("SELECT * FROM crm__parameter_customer_response_attitudes WHERE p_state = 1 ORDER BY p_order");
            $customer_types = $religions = $response_attitudes = "";
            foreach($DB_customer_types as $k=>$v){
                $customer_types .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }
            foreach($DB_religions as $k=>$v){
                $religions .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }
            foreach($DB_response_attitudes as $k=>$v){
                $response_attitudes .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }

            //表單下方選項(可拜訪時段、性格、興趣、偏好投資、開放性較高業務、開放性較低業務)
            $DB_visitable_times= DB::select("SELECT * FROM crm__parameter_customer_visitable_times WHERE p_state = 1 ORDER BY p_order");
            $DB_dispositions= DB::select("SELECT * FROM crm__parameter_customer_dispositions WHERE p_state = 1 ORDER BY p_order");
            $DB_interests= DB::select("SELECT * FROM crm__parameter_customer_interests WHERE p_state = 1 ORDER BY p_order");
            $DB_prefer_invests= DB::select("SELECT * FROM crm__parameter_customer_prefer_invests WHERE p_state = 1 ORDER BY p_order");
            $openness_high_business_array = ["存款業務","放款業務","薪資轉帳","保險業務","信用卡","供銷產品","轉繳業務"];
            $openness_low_business_array = ["存款業務","放款業務","薪資轉帳","保險業務","信用卡","供銷產品","轉繳業務"];
            $visitable_times = $dispositions = $interests = $prefer_invests = $openness_high_business = $openness_low_business ="";
            foreach($DB_visitable_times as $k=>$v){
                $visitable_times .= "<div class='flex m-1'>
                    <input type='checkbox' id='visitable_time".$k."' name='visitable_times[]' value='".$v->p_item."' class='px-2 h-6 w-6' />
                    <label for='visitable_time".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                </div>";
            }
            foreach($DB_dispositions as $k=>$v){
                $dispositions .= "<div class='flex m-1'>
                    <input type='checkbox' id='dispositions".$k."' name='dispositions[]' value='".$v->p_item."' class='px-2 h-6 w-6'>
                    <label for='dispositions".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                </div>";
            }
            foreach($DB_interests as $k=>$v){
                $interests .= "<div class='flex m-1'>
                    <input type='checkbox' id='interests".$k."' name='interests[]' value='".$v->p_item."' class='px-2 h-6 w-6'>
                    <label for='interests".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                </div>";
            }
            foreach($DB_prefer_invests as $k=>$v){
                $prefer_invests .= "<div class='flex m-1'>
                    <input type='checkbox' id='prefer_invests".$k."' name='prefer_invests[]' value='".$v->p_item."' class='px-2 h-6 w-6'>
                    <label for='prefer_invests".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                </div>";
            }
            foreach($openness_high_business_array as $k=>$v){
                $openness_high_business .= "<div class='flex m-1'>
                    <input type='checkbox' id='openness_high_business".$k."' name='openness_high_business[]' value='".$v."' class='px-2 h-6 w-6'>
                    <label for='openness_high_business".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v."</label>
                </div>";
            }
            foreach($openness_low_business_array as $k=>$v){
                $openness_low_business .= "<div class='flex m-1'>
                    <input type='checkbox' id='openness_low_business".$k."' name='openness_low_business[]' value='".$v."' class='px-2 h-6 w-6'>
                    <label for='openness_low_business".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v."</label>
                </div>";
            }


            return view("crm.member.crm_create_customer_data",[
                "customer_number" => $customer_number, //新建編號
                "option_city" => $option_city, //地址(城市)
                "option_encourage_raise_staff" => $option_encourage_raise_staff, //勸募員工
                "customer_types" => $customer_types, //客戶種類
                "religions" => $religions, //宗教
                "response_attitudes" => $response_attitudes, //回應態度
                "visitable_times" => $visitable_times, //可拜訪時段
                "dispositions" => $dispositions, //性格
                "interests" => $interests, //興趣
                "prefer_invests" => $prefer_invests, //偏好投資
                "openness_high_business" => $openness_high_business, //開放性較高業務
                "openness_low_business" => $openness_low_business, //開放性較低業務
            ]);
        }
        return redirect('/login');
    }
    public function create_customer_data_post(Request $request){ //新建客戶基本資料
        if($member_id = $request->session()->get('member_id') ){
            //新建客戶資料
            $visitable_times = $dispositions = $interests = $prefer_invests = $openness_high_business = $openness_low_business=""; 
            if($request->visitable_times) $visitable_times = implode(",",$request->visitable_times);
            if($request->dispositions) $dispositions = implode(",",$request->dispositions);
            if($request->interests) $interests = implode(",",$request->interests);
            if($request->prefer_invests) $prefer_invests = implode(",",$request->prefer_invests);
            if($request->openness_high_business) $openness_high_business = implode(",",$request->openness_high_business);
            if($request->openness_low_business) $openness_low_business = implode(",",$request->openness_low_business);
            if(DB::select("SELECT * FROM crm__customer_basic_informations WHERE id='$request->c_id'")){
                $request->session()->put('message',"編號重複，請修改編號");
                return redirect()->back();
            }
            if($request->c_number) $c_number = $request->c_number;
            else $c_number = $request->c_id;
            Crm_CustomerBasicInformation::create([
                "id" => $request->c_id,
                "c_name_company" => $request->c_name_company,
                "identification_gui_number" => $request->identification_gui_number,
                "c_sex" => $request->c_sex,
                "c_birth_opening_date" => $request->c_birth_opening_date,
                "c_type" => $request->c_type,
                "c_telephone" => $request->c_telephone,
                "c_cellphone" => $request->c_cellphone,
                "religion" => $request->religion,
                "c_number" => $c_number,
                "postcode" => $request->postcode,
                "city" => $request->city,
                "city_area" => $request->city_area,
                "address" => $request->address,
                "open_account" => $request->open_account,
                "farmer_association_member" => $request->farmer_association_member,
                "farmer_insurance" => $request->farmer_insurance,
                "health_state" => $request->health_state,
                "communicate_state" => $request->communicate_state,
                "response_attitude" => $request->response_attitude,
                "deposit_level" => $request->deposit_level,
                "loan_level" => $request->loan_level,
                "c_bank" => $request->c_bank,
                // "vip_cyear" => "$request->vip_cyear",
                "encourage_raise_staff" => $request->encourage_raise_staff,
                "ao_staff" => $request->ao_staff,
                "transfer_item" => $request->transfer_item,
                "remark" => $request->remark,
                "visitable_times" => $visitable_times,
                "dispositions" => $dispositions,
                "interests" => $interests,
                "prefer_invests" => $prefer_invests,
                "openness_high_business" => $openness_high_business,
                "openness_low_business" => $openness_low_business,
                "c_source" => $request->c_source
            ]);
            //vip建立
            if($request->vip_cyear){
                $vip_cyear = explode(',',$request->vip_cyear);
                foreach($vip_cyear as $v){
                    if(is_numeric($v)){
                        Crm_VipManagement::create([
                            "cyear" => $v,
                            "c_id" => $request->c_id
                        ]);
                    }
                }
            }
            $request->session()->put('message',"新增成功");
            return redirect()->back();
        }
        return redirect('/login');
    }
    public function delete_customer_data(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $crm__customer_basic_information = Crm_CustomerBasicInformation::find($request->c_id);
            $crm__customer_basic_information->delete();
            $interface = "search_list";
            $request->session()->put('search_customer_data_interface',$interface);
            return redirect('crm/basic_customer_data');
        }
        return redirect('/login');
    }

    public function get_city_area(Request $request){ //jquey get cityarea and postcode
        if($member_id = $request->session()->get('member_id') ){
            if(($city = $request->city) && ($city_area = $request->city_area)){
                $DB_taiwan_city_areas = DB::select("SELECT postcode FROM taiwan_city_areas WHERE city = '$city' && city_area = '$city_area' ");
                $postcode = $DB_taiwan_city_areas[0]->postcode;
                $result = [
                    "postcode" => $postcode,
                ];
            }elseif($city = $request->city){
                $c_city_area="";
                if($c_id = $request->c_id){
                    $DB_crm__customer_basic_informations= DB::select("SELECT c1.city_area FROM crm__customer_basic_informations AS c1 WHERE id='$c_id'");
                    $c_city_area = $DB_crm__customer_basic_informations[0]->city_area; //取得顧客 鄉鎮區
                }
                $DB_taiwan_city_areas = DB::select("SELECT city_area FROM taiwan_city_areas WHERE city = '$city' ORDER BY id");
                $option_city_area = "";
                foreach($DB_taiwan_city_areas as $v){
                    if($v->city_area == $c_city_area) $option_city_area .= "<option value='".$v->city_area."' selected>".$v->city_area."</option>";
                    else $option_city_area .= "<option value='".$v->city_area."'>".$v->city_area."</option>";
                }
                $result = [
                    "option_city_area" => $option_city_area,
                ];
            }elseif($c_id = $request->c_id){
                $DB_crm__customer_basic_informations= DB::select("SELECT c1.city FROM crm__customer_basic_informations AS c1 WHERE id='$c_id'");
                $c_city = $DB_crm__customer_basic_informations[0]->city; //取得顧客縣市
                $DB_taiwan_citys = DB::select("SELECT city FROM taiwan_city_areas GROUP BY city ORDER BY id");
                $option_city = "";
                foreach($DB_taiwan_citys as $v){
                    if($v->city == $c_city) $option_city .= "<option value='".$v->city."' selected>".$v->city."</option>";
                    else $option_city .= "<option value='".$v->city."'>".$v->city."</option>";
                }
                $result = [
                    "option_city" => $option_city,
                ];
            }
            return response()->json($result);
        }
        return redirect('/login');
    }

    public function crm_search_customer(Request $request){ //jquey ajax 同戶歸屬 查詢 添加
        if($member_id = $request->session()->get('member_id') ){
            $use_type = $request->use_type;
            $basic_c_number = $request->basic_c_number;
            $search_type = $request->search_type;
            $search_val = $request->search_val;
            if($use_type == "visit_records_manage_add"){ //新增拜訪紀錄用
                $where = " ";
            }else{ //同戶親屬用
                $where = "(c_number != '$basic_c_number' OR c_number IS NULL )AND ";  //已加入同戶的不顯示
            }
            if($search_type == "phone"){
                $where .= "(c_telephone LIKE '%$search_val%' OR c_cellphone LIKE '%$search_val%')";
            }else{
                $where .= "$search_type LIKE '%$search_val%'";
            }
            $DB_crm__customer_basic_informations= DB::select("SELECT 
                    id, c_name_company, identification_gui_number, c_sex, c_birth_opening_date, c_telephone, 
                    c_cellphone, c_number, postcode, city, city_area, c_address
                    FROM crm__customer_basic_informations 
                    WHERE $where");
            if($DB_crm__customer_basic_informations){
                $search_tbody ="";
                foreach($DB_crm__customer_basic_informations as $v){
                    if($use_type == "visit_records_manage_add"){
                        $button = "<button id='btn_visit_add' class='check_c_id bg-gray-500 text-white p-2 hover:bg-gray-600' data-id='".$v->id."' data-toggle='mymodal' data-target='#VisitRecordAddModal'>選取</button>";
                    }else{
                        $button = "<button type='button' data-id='".$v->id."' data-type='add' class='check_c_id bg-gray-500 text-white p-2 hover:bg-gray-600'>選取</button>";
                    }
                    $search_tbody .= "<tr class='h-16'>
                        <td class='border p-2'>".$v->c_number."</td>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$v->c_name_company."</td>
                        <td class='border p-2'>".$v->identification_gui_number."</td>
                        <td class='border p-2'>".$v->c_sex."</td>
                        <td class='border p-2'>".$v->c_birth_opening_date."</td>
                        <td class='border p-2'>".$v->c_telephone."</td>
                        <td class='border p-2'>".$v->c_cellphone."</td>
                        <td class='border p-2 w-24'>
                            ".$button."
                        </td>
                    </tr>";
                }
                $search_table = "
                <thead id='search_thead' class='bg-gray-600 text-white'><tr>
                        <th class='border p-2'>戶號</th>
                        <th class='border p-2'>會號</th>
                        <th class='border p-2'>姓名</th>
                        <th class='border p-2'>身分</th>
                        <th class='border p-2'>性別</th>
                        <th class='border p-2'>生日</th>
                        <th class='border p-2'>電話</th>
                        <th class='border p-2'>手機</th>
                        <th class='border p-2'></th>
                </tr></thead>
                <tbody id='search_tbody' class='bg-gray-300'>".$search_tbody."</tbody>";
            }else{
                $search_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            
            $result = [
                "search_table" => $search_table,
                // "where" => $where,
                
            ];
            return response()->json($result);
        }

    }
    public function change_c_number_family(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $type = $request->type;
            $c_id = $request->c_id;
            $basic_c_number = $request->basic_c_number;
            $c_family = $request->c_family;
            if($type =="add"){
                $crm_customer_basic_information = Crm_CustomerBasicInformation::find($c_id);
                $crm_customer_basic_information->c_number = $basic_c_number;
                $crm_customer_basic_information->save();
            }elseif($type =="delete"){
                $crm_customer_basic_information = Crm_CustomerBasicInformation::find($c_id);
                $crm_customer_basic_information->c_number = $c_id;
                $crm_customer_basic_information->c_family = "本人";
                $crm_customer_basic_information->save();
            }elseif($type =="edit"){
                $crm_customer_basic_information = Crm_CustomerBasicInformation::find($c_id);
                $crm_customer_basic_information->c_family = $c_family;
                $crm_customer_basic_information->save();
            }


            $result = [
                "type" => $type,
                "c_id" => $c_id,
                "basic_c_number" => $basic_c_number,
            ];
            return response()->json($result);
        }
    }
    public function customer_family(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $basic_c_number = $request->basic_c_number;
            $DB_crm__customer_basic_informations= DB::select("SELECT 
                    id, c_name_company, identification_gui_number, c_sex, c_family, c_birth_opening_date, c_telephone, 
                    c_cellphone, c_number, postcode, city, city_area, c_address
                    FROM crm__customer_basic_informations 
                    WHERE c_number = '$basic_c_number'");
            
            if($DB_crm__customer_basic_informations){
                $DB_relationships= DB::select("SELECT * FROM crm__parameter_customer_relationships WHERE p_state = 1 ORDER BY p_order");
                
                $customer_family_tbody ="";
                foreach($DB_crm__customer_basic_informations as $v){
                    $kinships_option = ""; //親屬關係下拉式
                    foreach($DB_relationships as $v2){
                        if($v->c_family != $v2->p_item) $kinships_option .= "<option value='".$v2->p_item."'>".$v2->p_item."</option>";
                        else $kinships_option .= "<option value='".$v2->p_item."' selected>".$v2->p_item."</option>";
                    }
                    $kinships_select = "<select name='' data-id='".$v->id."' data-type='edit' class='change_c_number_family_edit'>".$kinships_option."</select>";
                    $customer_family_tbody .= "<tr class='h-16'>
                        <td class='border p-2'>".$v->c_number."</td>
                        <td class='border p-2'>".$v->id."</td>
                        <td class='border p-2'>".$kinships_select."</td>
                        <td class='border p-2'>".$v->c_name_company."</td>
                        <td class='border p-2'>".$v->c_sex."</td>
                        <td class='border p-2'>".$v->c_birth_opening_date."</td>
                        <td class='border p-2'>".$v->c_telephone."</td>
                        <td class='border p-2'>".$v->c_cellphone."</td>
                        <td class='border p-2 w-24'>
                            <button type='button' data-id='".$v->id."' data-type='delete' class='check_c_id bg-red-500 text-white p-2 hover:bg-red-600'>刪除</button>
                        </td>
                    </tr>";
                }
                $customer_family_table = "
                <thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2'>戶號</th>
                        <th class='border p-2'>會號</th>
                        <th class='border p-2'>親屬關係</th>
                        <th class='border p-2'>姓名</th>
                        <th class='border p-2'>性別</th>
                        <th class='border p-2'>生日</th>
                        <th class='border p-2'>電話</th>
                        <th class='border p-2'>手機</th>
                        <th class='border p-2'></th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$customer_family_tbody."</tbody>";
            }else{
                $customer_family_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            $result = [
                "customer_family_table" => $customer_family_table,
            ];
            return response()->json($result);
            
        }
        return redirect('/login');
    }

    public function visit_record(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $basic_c_id = $request->basic_c_id;
            $DB_crm__visit_records= DB::select("SELECT c1.id, c1.c_id, c1.visit_date, c1.visit_type, c1.visit_title, 
                    c1.visit_content, c1.visit_follow, c1.visit_follow_phrase, c1.customer_analysis, 
                    c1.supervisor_suggest, c1.supervisor_suggest_phrase, c2.ao_staff, c3.name AS ao_staff_name
                    FROM crm__visit_records AS c1
                    LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id
                    LEFT JOIN calendar_members AS c3 ON c2.ao_staff = c3.id
                    WHERE c_id = '$basic_c_id' 
                    ORDER BY c1.visit_date DESC");
            
            if($DB_crm__visit_records){
                $visit_records_tbody ="";
                foreach($DB_crm__visit_records as $v){
                    $visit_records_tbody .= "<tr class='h-16'>
                        <td class='border p-2 w-16'><input type='checkbox' class='checked_visit_record_delete w-6 h-6' data-id='".$v->id."'></td>
                        <td class='border p-2 w-36'>".$v->visit_date."</td>
                        <td class='border p-2 w-48'>".$v->visit_title."</td>
                        <td class='border p-2'>".$v->visit_content."</td>
                        <td class='border p-2 w-36'>".$v->ao_staff_name."</td>
                        <td class='border p-2 w-24'>
                            <button type='button' data-id='".$v->id."' data-c_id='".$v->c_id."' data-toggle='mymodal' data-target='#VisitRecordEditModal' class='visit_record_edit_get bg-yellow-500 text-white p-2 hover:bg-yellow-600'>編輯</button>
                        </td>
                    </tr>";
                }
                $visit_record_table = "
                <thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2'><input type='checkbox' class='checked_all_visit_records_delete w-6 h-6'></th>
                        <th class='border p-2'>拜訪日期</th>
                        <th class='border p-2'>目的</th>
                        <th class='border p-2'>分析報告</th>
                        <th class='border p-2'>AO人員</th>
                        <th class='border p-2'></th>
                </tr></thead>
                <tbody class='bg-blue-300'>".$visit_records_tbody."</tbody>";
            }else{
                $visit_record_table = "<div class='text-2xl font-bold'>查無資料</div>";
            }
            $result = [
                "visit_record_table" => $visit_record_table,
            ];
            return response()->json($result);
            
        }
        return redirect('/login');
    }
    public function visit_record_add(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $member_name = $request->session()->get('member_name');
            $form_data = $request->form_data;
            Crm_VisitRecord::create([
                "c_id" => $form_data[0],
                "visit_date" => $form_data[1],
                "visit_report_date" => "2021-04-01",
                "visit_type" => $form_data[2],
                "visit_title" => $form_data[3],
                "visit_content" => $form_data[4],
                "creator_name" => $member_name,
            ]);
            $result = [
                "form_data" => $form_data,
            ];
            return response()->json($result);
        }
    }
    public function visit_customer_basic_information_get(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $c_id = $request->c_id;
            $ccbif = DB::select("SELECT 
            c1.id, c1.c_name_company, c1.c_type, c1.c_sex, c1.c_birth_opening_date, 
            c1.identification_gui_number, c1.c_telephone, c1.c_cellphone, c1.religion, 
            c1.deposit_level, c1.loan_level, c1.visitable_times, 
            (SELECT substring_index(GROUP_CONCAT(CONCAT('VIP-',cyear) ORDER BY cyear DESC SEPARATOR ','), ',', 2) FROM crm__vip_managements c2 WHERE c1.id = c2.c_id ORDER BY cyear) AS vip_cyears
            FROM crm__customer_basic_informations AS c1
            WHERE id='$c_id' ");
            
            $customer_types = $customer_sexs = $religions = $checkbox_visitable_times = "";
            $DB_customer_types= DB::select("SELECT * FROM crm__parameter_customer_types WHERE p_state = 1 ORDER BY p_order");
            $DB_religions= DB::select("SELECT * FROM crm__parameter_customer_religions WHERE p_state = 1 ORDER BY p_order");
            $DB_visitable_times= DB::select("SELECT * FROM crm__parameter_customer_visitable_times WHERE p_state = 1 ORDER BY p_order");
            $customer_sex_array = ["男","女","無"];
            
            foreach($DB_customer_types as $k=>$v){ //客戶類別
                if($v->p_item == $ccbif[0]->c_type) $customer_types .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                else $customer_types .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }
            foreach($customer_sex_array as $v){ //性別
                if($v == $ccbif[0]->c_sex) $customer_sexs .= "<option value='".$v."' selected>".$v."</option>";
                else $customer_sexs .= "<option value='".$v."'>".$v."</option>";
            }
            foreach($DB_religions as $k=>$v){ //宗教
                if($v->p_item == $ccbif[0]->religion) $religions .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                else $religions .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }
            foreach($DB_visitable_times as $k=>$v){ //客戶可拜訪時間
                if( in_array($v->p_item,explode(",",$ccbif[0]->visitable_times)) ) $checkbox = "<input type='checkbox' id='visit_time".$k."' name='visit_time[]' value='".$v->p_item."' class='px-2 h-6 w-6' checked onclick='return false'>";
                else $checkbox = "<input type='checkbox' id='visit_time".$k."' name='visit_time[]' value='".$v->p_item."' class='h-6 w-6' onclick='return false'>";
                $checkbox_visitable_times .= "<div class='flex mb-1'>
                    ".$checkbox."
                    <label for='visit_time".$k."' class='text-white bg-blue-500 mx-2 px-2'>".$v->p_item."</label>
                </div>";
            }
            $visit_customer_basic_information = "
                <div class='grid grid-cols-5'>
                    <label for='vc_id' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>會員編號</label> 
                    <input type='text'  name='vc_id' value='".$ccbif[0]->id."' class='col-span-3 p-2' readonly>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='vc_name_company' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>姓名/公司名</label> 
                    <input type='text'  name='vc_name_company' value='".$ccbif[0]->c_name_company."' class='col-span-3 px-2' readonly>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='vc_type' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>客戶種類</label>
                    <div class='bg-white col-span-3 flex items-center px-2'>
                        <select  name='vc_type' class='w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2' disabled>
                            ".$customer_types."
                        </select>
                    </div>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='vc_sex' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>性別</label>
                    <div class='bg-white col-span-3 flex items-center px-2'>
                        <select  name='vc_sex' class='w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2'disabled>
                            ".$customer_sexs."
                        </select>
                    </div>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='vc_birth_opening_date' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>生日/開業日</label> 
                    <input type='date'  name='vc_birth_opening_date' value='".$ccbif[0]->c_birth_opening_date."' class='col-span-3 px-2' readonly>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='v_identification_gui_number' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>身分證/統編</label> 
                    <input type='text'  name='v_identification_gui_number' value='".$ccbif[0]->identification_gui_number."' class='col-span-3 px-2' readonly>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='vc_telephone' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>電話</label> 
                    <input type='text'  name='vc_telephone' value='".$ccbif[0]->c_telephone."' class='col-span-3 px-2' readonly>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='vc_cellphone' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>手機</label> 
                    <input type='text'  name='vc_cellphone' value='".$ccbif[0]->c_cellphone."' class='col-span-3 px-2' >
                </div>
                <div class='grid grid-cols-5'>
                    <label for='v_religion' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>宗教</label> 
                    <div class='bg-white col-span-3 flex items-center px-2'>
                        <select  name='v_religion' class='w-full block appearance-none w-auto bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 mr-2' disabled>
                            ".$religions."
                        </select>
                    </div>
                </div>
                <div class='grid grid-cols-5'>
                    <label for='v_vip_cyear' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>VIP年度</label> 
                    <input type='text' name='v_vip_cyear' value='".$ccbif[0]->vip_cyears."' class='col-span-3 px-2' readonly>
                </div>
                <div class='col-span-2 grid grid-cols-10'>
                    <label for='v_visit_time0' class='col-span-2 bg-blue-400 mx-2 p-2 flex items-center justify-center'>可拜訪時段</label> 
                    <div class='col-span-8 grid grid-cols-4 px-2 flex items-center '>
                        ".$checkbox_visitable_times."
                    </div>
                </div>
                <div class='col-span-3'>
                    <div class='flex items-center justify-end'>
                        <form action='".url('crm/search_customer_data')."' method='post'>
                            <input type='hidden' name='_token' value='".csrf_token()."'>
                            <input type='hidden' name='interface' value='basic_customer_data'>
                            <input type='hidden' name='search_id' value='".$c_id."'>
                            <button type='submit' class='bg-yellow-500 text-white p-2 hover:bg-yellow-600 text-xl font-bold'>回會員管理</button>
                        </form>
                    </div>
                </div>
                ";
            $result = [
                "visit_customer_basic_information" => $visit_customer_basic_information,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function visit_record_edit_get(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $visit_record_id = $request->visit_record_id;
            $crm__visit_record = Crm_VisitRecord::find($visit_record_id);
            $visit_date = $crm__visit_record->visit_date;
            $visit_type = $crm__visit_record->visit_type;
            $visit_title = $crm__visit_record->visit_title;
            $visit_content = $crm__visit_record->visit_content;
            $visit_follow = $crm__visit_record->visit_follow;
            $visit_follow_phrase = $crm__visit_record->visit_follow_phrase;
            $customer_analysis = $crm__visit_record->customer_analysis;
            $supervisor_suggest = $crm__visit_record->supervisor_suggest;
            $supervisor_suggest_phrase = $crm__visit_record->supervisor_suggest_phrase;

            $visit_type_array = ['定期','生日','大額'];
            // $visit_follow_phrase_array = ['無','電話問候'];
            // $supervisor_suggest_phrase_array = ['無','已簽核(穩定持平)','大額異動變動','不易明晰客戶','加強分配服務資源'];
            $DB_visit_follow_phrases= DB::select("SELECT * FROM crm__parameter_visit_follow_phrases WHERE p_state = 1 ORDER BY p_order");
            $DB_visit_supervisor_suggest_phrases= DB::select("SELECT * FROM crm__parameter_visit_supervisor_suggest_phrases WHERE p_state = 1 ORDER BY p_order");
            $option_visit_type = "";
            foreach($visit_type_array as $v){
                if($v == $visit_type) $option_visit_type .= "<option value='".$v."' selected>".$v."</option>";
                else $option_visit_type .= "<option value='".$v."'>".$v."</option>";
            }
            $option_visit_follow_phrase = "<option value=''></option>";
            foreach($DB_visit_follow_phrases as $v){
                if($v->p_item == $visit_follow_phrase) $option_visit_follow_phrase .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                else $option_visit_follow_phrase .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }
            $option_supervisor_suggest_phrase = "<option value=''></option>";
            foreach($DB_visit_supervisor_suggest_phrases as $v){
                if($v->p_item == $supervisor_suggest_phrase) $option_supervisor_suggest_phrase .= "<option value='".$v->p_item."' selected>".$v->p_item."</option>";
                else $option_supervisor_suggest_phrase .= "<option value='".$v->p_item."'>".$v->p_item."</option>";
            }

            $result = [
                "visit_record_id" => $visit_record_id,
                "visit_date" => $visit_date,
                "option_visit_type" => $option_visit_type,
                "visit_title" => $visit_title,
                "visit_content" => $visit_content,
                "visit_follow" => $visit_follow,
                "option_visit_follow_phrase" => $option_visit_follow_phrase,
                "customer_analysis" => $customer_analysis,
                "supervisor_suggest" => $supervisor_suggest,
                "option_supervisor_suggest_phrase" => $option_supervisor_suggest_phrase,
            ];
            return response()->json($result);
        }
    }
    public function visit_record_edit(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $member_name = $request->session()->get('member_name');
            $btn_type = $request->btn_type;
            $form_data = $request->form_data;
            $crm__visit_record = Crm_VisitRecord::find($form_data[0]);
            if($btn_type == 'edit'){
                $crm__visit_record->visit_date = $form_data[1];
                $crm__visit_record->visit_type = $form_data[2];
                $crm__visit_record->visit_title = $form_data[3];
                $crm__visit_record->visit_content = $form_data[4];
            }elseif($btn_type == 'follow'){
                $crm__visit_record->visit_follow_phrase = $form_data[5];
                $crm__visit_record->visit_follow = $form_data[6];
            }elseif($btn_type == 'analysis'){
                $crm__visit_record->customer_analysis = $form_data[7];
                $crm__visit_record->customer_analysis_name = $member_name;
            }elseif($btn_type == 'suggest'){
                $crm__visit_record->supervisor_suggest_phrase = $form_data[8];
                $crm__visit_record->supervisor_suggest = $form_data[9];
                $crm__visit_record->supervisor_suggest_name = $member_name;
            }
            $crm__visit_record->save();
            $result = [
                // "form_data" => $form_data,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function visit_record_delete(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $vals = $request->vals;
            foreach($vals as $v){
                $crm__visit_record = Crm_VisitRecord::find($v);
                $crm__visit_record->delete();
            }
            $result = [
                "vals" => $vals,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }

    public function account_balance(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $basic_c_id = $request->basic_c_id;
            $DB_crm__account_balances = DB::select("SELECT ab_date, c_id, ab_bank_number, ab_class, ab_acount, ab_balances,
                ab_deposit_money_average, ab_time_deposit_average, ab_credit_first, ab_last_year_interest_recover_money,
                ab_deposit_money, ab_credit_money
            FROM crm__account_balances WHERE c_id='$basic_c_id' ORDER BY ab_date DESC");
            if($DB_crm__account_balances){
                $tbody="";
                foreach($DB_crm__account_balances as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
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
    
    public function change_customer(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $basic_c_id = $request->basic_c_id;
            $DB_crm__change_customers = DB::select("SELECT c_id, cc_date, cc_branch_id, cc_class, cc_acount, cc_day_balance,
            cc_last_day_balance, cc_grow, cc_settlement_money
            FROM crm__change_customers WHERE c_id='$basic_c_id' ORDER BY cc_date DESC");
            if($DB_crm__change_customers){
                $tbody="";
                foreach($DB_crm__change_customers as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
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
                $change_customers_table = "<thead class='bg-blue-600 text-white'><tr>
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
                $change_customers_table = "<div class='text-2xl font-bold'>查無資料</div>";;
            }
            $result = [
                "change_customers_table" => $change_customers_table,
            ];
            return response()->json($result);
        }
    }
    public function contribution(Request $request){ //貢獻度
        if($member_id = $request->session()->get('member_id') ){
            //貢獻度說明
            $contribution_descriptions = "";
            $DB_crm__contribution_descriptions = DB::select("SELECT description_type FROM crm__contribution_descriptions GROUP BY description_type ORDER BY id");
            foreach($DB_crm__contribution_descriptions as $v){
                $div_description_type = "<div class='text-2xl text-left'><span class='material-icons'>near_me</span>".$v->description_type."</div>";
                $div_title_val = $div_description_type."<div class='grid grid-cols-6 border gap-1 text-white'>";
                $DB_title_val = DB::select("SELECT title, val FROM crm__contribution_descriptions WHERE description_type LIKE '$v->description_type' ORDER BY val DESC");
                $title_val = "";
                foreach($DB_title_val as $v2){
                    $title_val .= "<div class='bg-white'>
                        <div class='bg-blue-600 py-2'>".$v2->title."</div>
                        <div class='bg-blue-400 py-2'>".$v2->val."</div>
                    </div>";
                }
                $div_title_val .= $title_val ."</div>";
                $contribution_descriptions .= $div_title_val;
            }
            
            //貢獻度圖表
            $basic_c_id = $request->basic_c_id;
            $cols_array = ['定儲','放款','活儲','轉帳'];
            $DB_crm__contributions = DB::select("SELECT c_id, c_date, c_current_deposits, c_time_deposits, c_loan, c_transfer,
            CONCAT(LEFT(c_date, 7),' 貢獻度') AS title
            FROM crm__contributions WHERE c_id='$basic_c_id' ORDER BY c_date DESC");
            $array_color = ['rgb(164, 204, 134)','rgb(247, 152, 130)','rgb(254, 209, 116)','rgb(99, 126, 137)'];
            //google chart用
            if($DB_crm__contributions){
                $vals_array = array();
                $title = array();
                foreach($DB_crm__contributions as $k=>$v){
                    $title[] = $v->title;
                    $vals_array[] = [$v->c_current_deposits,$v->c_time_deposits,$v->c_loan,$v->c_transfer];
                    $title_val_array = [['title','val']];
                    for($i=0;$i<4;$i++){
                        // $title = $cols_array[i];
                        // $val = $vals_array[i];
                        $array = [$cols_array[$i],$vals_array[$k][$i]];
                        $title_val_array[]=$array;
                    }
                    $title_val_arrays[] = $title_val_array;
                }
                $array_color_google = [
                    [ 'color' => 'rgb(164, 204, 134)' ],
                    [ 'color' => 'rgb(247, 152, 130)' ],
                    [ 'color' => 'rgb(254, 209, 116)' ],
                    [ 'color' => 'rgb(99, 126, 137)' ],
                ];
                $result = [
                    "contribution_descriptions" => $contribution_descriptions, //貢獻度說明
                    "title_val_arrays" => $title_val_arrays, //google chart 欄位名稱與值
                    "array_color_google" => $array_color_google, //google chart 顏色
                    "title" => $title, //google chart 標題
                ];
            }else{
                $result = [
                    "contribution_descriptions" => $contribution_descriptions, //貢獻度說明
                    "no" => '1', //無資料
                ];
            }
            
            return response()->json($result);
        }
    }
    public function insurance_information(Request $request){ //保險資訊
        if($member_id = $request->session()->get('member_id') ){
            $basic_c_id = $request->basic_c_id;
            $DB_crm__insurance_informations = DB::select("SELECT ii_date, ii_insured, ii_insurer, ii_company, 
                ii_insurance_date, ii_type, ii_cost, ii_commission, ii_car_number
            FROM crm__insurance_informations WHERE c_id='$basic_c_id' ORDER BY ii_date DESC");
            if($DB_crm__insurance_informations){
                $tbody="";
                foreach($DB_crm__insurance_informations as $v){
                    $tbody .= "<tr class='bg-blue-300 h-16'>
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
        return redirect('/login');
    }

    public function vip_management(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array('B', $request->session()->get('member_authority')) ) return "error"; //權限
            $now = Carbon::now();
            $now->timezone = new \DateTimeZone('Asia/Taipei'); //台北時間
            $now_cyear = $now->year - 1911;
            $search_val = $request->session()->get('search_vip_management_search_val');
            $interface = $request->session()->get('search_vip_management_interface');
            // $DB_crm__vip_managements = DB::select("SELECT c1.id, c1.c_id, c1.cyear, c1.cyear_level, c2.c_name_company, c2.c_telephone, c2.c_cellphone, c2.ao_staff
            // FROM crm__vip_managements AS c1
            // LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id
            // WHERE cyear='$search_val'");
            return view('crm.member.crm_vip_management.crm_vip_management',[
                "search_val" => $search_val,
                "interface" => $interface,
                "now_cyear" => $now_cyear,
                // "DB_crm__vip_managements" => $DB_crm__vip_managements,
            ]);
        }
        return redirect('/login');
    }
    public function search_vip_management(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $interface = $request->interface;
            $search_val = $request->search_val;
            if($interface == "search_list"){
                $request->session()->put('search_vip_management_search_val',$search_val);
            }
            $request->session()->put('search_vip_management_interface',$interface);

            $DB_crm__vip_managements = DB::select("SELECT c1.id, c1.c_id, c1.cyear, c1.cyear_level, c2.c_name_company, c2.c_telephone, c2.c_cellphone, c2.ao_staff
            FROM crm__vip_managements AS c1
            LEFT JOIN crm__customer_basic_informations AS c2 ON c1.c_id = c2.id
            WHERE cyear='$search_val'");

            if($DB_crm__vip_managements){
                $tbody="";
                foreach($DB_crm__vip_managements as $k=>$v){
                    if($k%2 == 1) $tr = "<tr class='bg-blue-200 h-16'>";
                    else $tr = "<tr class='bg-blue-300 h-16'>";
                    $tbody .= $tr."
                        <td class='border p-2'><input type='checkbox' class='checked_vip_management w-6 h-6' data-id='".$v->id."'></td>
                        <td class='border p-2'>".$v->cyear."</td>
                        <td class='border p-2'>".$v->c_id."</td>
                        <td class='border p-2'>".$v->c_name_company."</td>
                        <td class='border p-2'>".$v->cyear_level."</td>
                        <td class='border p-2'>".$v->c_telephone."</td>
                        <td class='border p-2'>".$v->c_cellphone."</td>
                        <td class='border p-2'>".$v->ao_staff."</td>
                        <td class='border p-2'>
                        <form action='".url('crm/search_customer_data')."' method='post'>
                            <input type='hidden' name='_token' value='".csrf_token()."'>
                            <input type='hidden' name='interface' value='basic_customer_data'>
                            <input type='hidden' name='search_id' value='".$v->c_id."'>
                            <button type='submit' class='bg-blue-500 text-white p-2 hover:bg-blue-600'>編輯</button>
                        </form>
                        </td>
                    </tr>";
                }
                $vip_managements_table = "<thead class='bg-blue-600 text-white'><tr>
                        <th class='border p-2 w-16'><input type='checkbox' class='checked_all w-6 h-6'></th>
                        <th class='border p-2 w-20'>年度</th>
                        <th class='border p-2 w-32'>編號</th>
                        <th class='border p-2'>姓名</th>
                        <th class='border p-2 w-32'>等級</th>
                        <th class='border p-2 w-32'>電話</th>
                        <th class='border p-2 w-32'>手機</th>
                        <th class='border p-2 w-32'>AO</th>
                        <th class='border p-2 w-24'></th>
                    </tr></thead>
                <tbody>".$tbody."</tbody>";
            }else{
                $vip_managements_table = "<div class='text-2xl font-bold'>查無資料</div>";;
            }
            $result = [
                "vip_managements_table" => $vip_managements_table,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function add_vip_management(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            return view('crm.member.crm_vip_management.crm_add_vip_management',[
                
                // "search_val" => $search_val,
                // "interface" => $interface,
                // "DB_crm__vip_managements" => $DB_crm__vip_managements,
            ]);
        }
        return redirect('/login');
    }
    public function get_customer_data(Request $request){ //jquery 取得顧客資料
        if($member_id = $request->session()->get('member_id') ){
            $type = $request->type;
            $c_id = $request->c_id;
            $crm__customer_basic_information = Crm_CustomerBasicInformation::find($c_id);
            $result = [
                "crm__customer_basic_information" => $crm__customer_basic_information,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function add_vip_management_post(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if(DB::select("SELECT * FROM crm__vip_managements WHERE cyear = '$request->cyear' AND c_id = '$request->c_id' ")){
                $message = "重複添加，失敗";
            }else{
                Crm_VipManagement::create([
                    "cyear" => $request->cyear,
                    "c_id" => $request->c_id
                ]);
                $message = "添加成功";
            }
            // $request->session()->put('message',"添加成功");
            $result = [
                "message" => $message,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function delete_vip_management(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $vals = $request->vals;
            foreach($vals as $v){
                $crm__vip_management = Crm_VipManagement::find($v);
                $crm__vip_management->delete();
            }
            $result = [
                "vals" => $vals,
            ];
            return response()->json($result);
        }
        return redirect('/login');
    }
    public function example(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            if ( !in_array('B', $request->session()->get('member_authority')) ) return "error"; //權限
        }
        return redirect('/login');
    }

    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //         if ( !in_array('B', $request->session()->get('member_authority')) ) return "error"; //權限
    //     }
    //     return redirect('/login');
    // }
}
