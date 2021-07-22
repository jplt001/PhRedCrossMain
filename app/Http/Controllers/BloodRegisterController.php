<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use DB;
class BloodRegisterController extends Controller
{
    //

    function index(){
        date_default_timezone_set ('Asia/Manila');
    	$user = \Auth::user();
        $blood_donors = DB::table('blood_donors')->get();
    	$organiztions = DB::table('organizations')
            ->pluck('organization_name', 'id')
            ->all();
        $curDate= date('Y-m-d');
        $start_date = $curDate." 00:00:00";
        $end_date = $curDate." 23:59:59";
        
        $today_blood_donors = DB::table('blood_donors_history as a')
        ->select('a.*', DB::raw('CONCAT(b.last_name,", ", b.first_name) as donor_name'), 'b.is_new')
        ->join('blood_donors as b', 'a.donor_id', '=', 'b.id')
        ->whereBetween('a.donation_date', [$start_date, $end_date])
        ->get();

		return view('bloodregister.index')->with(array('user_info'=> $user, 'side_active'=>'blood_register', 'blood_donors'=>$blood_donors
            ,'orgs'=>$organiztions, 'today_blood_donors' => $today_blood_donors));
    }

    function postSave(Request $req){
    	$insert['first_name'] = $req->get('first_name');
    	$insert['middle_name'] = $req->get('middle_name');
    	$insert['last_name'] = $req->get('last_name');
    	$insert['birth_date'] = $req->get('birth_date');
    	$insert['civil_status'] = $req->get('civil_status');
    	$insert['age'] = $req->get('age');
    	$insert['gender'] = $req->get('gender');
    	$insert['lot_no'] = $req->get('lot_no');
    	$insert['street'] = $req->get('street');
        if($req->get('is_individual') == 1){
            $insert['org_id'] = $req->get('org_id');
        }
    	$insert['barangay'] = $req->get('barangay');
    	$insert['town_municipality'] = $req->get('town_municipality');
    	$insert['province_city'] = $req->get('province_city');
    	$insert['zip_code'] = $req->get('zip_code');
    	$insert['office_address'] = $req->get('office_address');
    	$insert['nationality'] = $req->get('nationality');
    	$insert['religion'] = $req->get('religion');
    	$insert['education'] = $req->get('education');
    	$insert['occupation'] = $req->get('occupation');
    	$insert['tel_no'] = $req->get('tel_no');
    	$insert['cell_no'] = $req->get('cell_no');
        $insert['email_address'] = $req->get('email_address');
    	$insert['is_individual'] = $req->get('is_individual');
    	$insert['added_by'] = \Auth::user()->id;


    	$id = DB::table('blood_donors')->insertGetId($insert);
        unset($insert);
        $insert['donor_id'] = $id;
        DB::table('blood_donors_history')->insert($insert);
    	return Redirect::to('blood_register')->with('message', 'Updating Donor Success.');
    }

    function getDelete($id){
    	DB::table('blood_donors')->where('id', $id)->delete();
    }

    function setDeffered(Request $req){
        $id = $req->get('history_id');

        $remarks = $req->all();
        unset($remarks['_token']);
        unset($remarks['history_id']);
        $remarks['is_passed'] = 2;
        DB::table('blood_donors_history')->where('id', $id)->update($remarks);
        return Redirect::to('blood_register')->with('message', 'Updating Branch Success.');
    }

    function postOldDonorSave(Request $req){
        $donor_id = $req->get('donor_id');
        $res = DB::table('blood_donors_history')->where('donor_id', $donor_id)->get();

        $insert['donor_id'] = $req->get('donor_id');

        $insert['source'] = $req->get('source');
        $insert['donor_no_per_mbd'] = $req->get('donor_no_per_mbd');
        $insert['category'] = $req->get('category');
        $insert['no_of_times'] = count($res);
        DB::table('blood_donors_history')->insert($insert);

        if(count($res)>1){
            DB::table('blood_donors')->where('id', $donor_id)->update(['is_new'=>2]);
        }
        return Redirect::to('blood_register')->with('success', 'Saving Donation Success.');
    }

    function postUpdateDonorReg(Request $req){
        $update = $req->all();
        unset($update['_token']);
        unset($update['donor_history_id']);
        $id = $req->get('donor_history_id');
        
        DB::table('blood_donors_history')->where('id', $id)->update($update);
        return Redirect::to('blood_register')->with('success', "Updating Donation Details success.");
    }

    function getInfo($id){
        $res = DB::table('blood_donors_history')->where('id', $id)->first();

        return response()->json($res);
    }

    function setApproved($id){
        DB::table('blood_donors_history')->where('id', $id)->update(['is_passed'=> 1]);
         return Redirect::to('blood_register')->with('success', "Approving Success.");
    }


    function postUpdate(Request $req){
        $id = $req->get('donor_id');
        $set = $req->all();
        unset($set['_token']);
        unset($set['donor_id']);
        DB::table('blood_donors')->where('id', $id)->update($set);

        return Redirect::to('blood_register')->with('success', "Updating Donor Information Success.");

    }


}
