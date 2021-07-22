<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Redirect;
use Auth;
class BloodReferralController extends Controller
{


    public function index(){
        
        $user = \Auth::user();
        
        if(!acl_roles($user->user_type, 'blood_referral')){
            return view('errors.403');
        }
    	$raw_patients = DB::table('patient')->orderBy('last_name')->get();
        $patients = [];        
        foreach ($raw_patients as $v) {
            
            if($v->patient_type === 0){
                $patients[$v->id] = $v->last_name . ", ". $v->first_name;
            }else{               
                $org = $this->getMyOrg($v->org_id);
                $patients[$v->id] = $v->last_name . ", ". $v->first_name. $org;
            }
        }
    	$hospitals = DB::table('hospitals')->pluck('hospital_name', 'id');
    	$blood_type = DB::table('blood_types')->pluck('type', 'id');
        $organizations = DB::table('organizations')->pluck('organization_name', 'id')->all();
        $blood_types = DB::table('blood_types')->get();
		return view('bloodreferral.index')->with(
			array('user_info'=> $user
				, 'side_active'=>'blood_referral'
				, 'patients'=>$patients
				, 'hospitals' => $hospitals
                , 'blood_type'=> $blood_type
                , 'organizations'=> $organizations
				, 'blood_types'=> $blood_types
			)
		);
    }

    private function getMyOrg($id){
        $data = DB::table('organizations')->find($id);
        if(count((array) $data)>0 ){
            return " - ".$data->organization_name;
        }
        return "";
        
    }

    function saveNewPatientReferall(Request $req){
        // pre($req->all());
        $insert['first_name']           = $req->get('first_name');
        $insert['middle_name']          = $req->get('middle_name');
        $insert['last_name']            = $req->get('middle_name');
        $insert['address']              = $req->get('address');
        $insert['gender']               = $req->get('gender');
        $insert['age']                  = $req->get('age');
        $insert['patient_type']         = $req->get('patient_type');
        
        $id                             = DB::table('patient')->insertGetId($insert);
        unset($insert);
        $insert['date']                 =date('Y-m-d H:i:s');
        $insert['patient_id']           = $id;
        $insert['hospital_id']          = $req->get('hospital_id');
        $insert['no_of_units']          = $req->get('no_of_units');
        $insert['blood_type']           = $req->get('blood_type');
        $insert['address']              = $req->get('address');
        

        $Insert  = DB::table('blood_referral')->insert($insert
        );
        return redirect('blood_referral')->with(['sucess', 'sucess']);
    }

    public function savePatientReferral(Request $req){
        $insert = $req->all();
        unset($insert['_token']);
    	$insert['date'] = date('Y-m-d');
    	

    	DB::table('blood_referral')->insert($insert);

        return response()->json(['detail'=>'Success']);
    }

    public function saveActivityHeld(Request $req){
        $insert = $req->all();
        unset($insert['_token']);
    	$insert['date'] = date('Y-m-d');    	
    	DB::table('activity_held')->insert($insert);

        return response()->json(['detail'=>'success']);
    }
}


function pr($data =null){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function pre($data = null){
	pr($data);
	exit();
}