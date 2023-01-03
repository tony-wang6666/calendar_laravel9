<?php

namespace App\Http\Controllers\set;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Informant;


class SetController extends Controller
{
    public function set_form(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $DB_informant = DB::select("SELECT * FROM informants ORDER BY informant_name");
            
            return view("sets.set_form",[
                "DB_informant" => $DB_informant,
            ]);
        }
        return redirect('/login');
    }
    public function change_informant_list(Request $request){
        if($member_id = $request->session()->get('member_id') ){
            $informant = $request->informant;
            if( $request->type=="delete" ){
                // WHERE informant_name LIKE '%$informant%'
                $DB_informant = DB::select("SELECT * FROM informants WHERE informant_name LIKE '$informant'");
                $informant = Informant::find($DB_informant[0]->id);
                $informant->delete();
            }elseif($request->type=="add"){
                Informant::create([
                    'informant_name' => $informant,
                ]);
            }
            $DB_informant = DB::select("SELECT * FROM informants ORDER BY informant_name");
            $informant_list = "";
            foreach($DB_informant as $v){
                $informant_list .= "<option value=".$v->informant_name.">".$v->informant_name."</option>";
            }
            $result = array(
                "informant_list" => $informant_list,
            );
            return response()->json($result);
        }
    }

    // public function example(Request $request){
    //     if($member_id = $request->session()->get('member_id') ){
    //     }
    //     return redirect('/login');
    // }
}
