<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;

class PatientController extends Controller
{
    public function index(){
    	$user = \Auth::user();
    	$patient = DB::table('patient')->get();
    	return view('patient.index')->with(['user_info'=>$user ,'patient_list'=>$patient, 'side_active'=>'patient']);
    }

    public function create(){
    	$user = \Auth::user();
    	return view('patient.create')->with(['user_info'=>$user, 'side_active'=>'patient']);
    }

    public function postCreate(){
        $set = array();
        $set['first_name'] = Input::get('first_name');
        $set['middle_name'] = Input::get('middle_name');
        $set['last_name'] = Input::get('last_name');
        $set['address'] = Input::get('address');
        $set['contact_no'] = Input::get('contact_no');
        $set['added_by'] = Input::get('added_by');

        $id = DB::table('patient')->insertGetId($set);               
        return redirect('patient')->with('success', 'New Patient created!');

    }

    public function postUpdate(Request $req){
        $id = $req->get('patient_id');
        
    	$set = $req->all();    	
        unset($set['patient_id']);
        unset($set['_token']);
        $set['updated_by'] = \Auth::user()->id;
        $set['updated_when'] = date('Y-m-d H:i:s');
        
    	try{
            DB::table('patient')->where('id', $id)->update($set);
        }catch(Exception $re){
            pre($e);    
        }
    	
        return redirect('patient')->with(['success'=> 'Patient has been updated.']);
    }

    public function view($id){
        $user = \Auth::user();
        $patient_info = DB::table('patient')->where('id', '=', $id)->first();
        return view('patient.view')->with(['user_info'=>$user, 'patient_info'=> $patient_info, 'side_active'=>'patient']);
    }

    /* AJAX */
    public function ajaxGetPatients(){
        return DB::table('patient')->where('is_approved', '=', '1')->get();
    }

    public function postApprove(Request $req){
        $patient_id = $req->get('patient_id');

        DB::table('patient')->where('id','=', $patient_id)->update(['is_approved'=>1]);

    }

    public function postDeny(Request $req){
        $patient_id = $req->get('patient_id');
        $reason = $req->get('reason');

        DB::table('patient')->where('id','=', $patient_id)->update(['is_approved'=>2, 'reason'=>$reason]);
        return redirect('patient');

    }

    function patientDelete($id){
        $patient = DB::table('patient')->where('id','=',$id)->update(['deleted'=>1]);

        // $patient->delete();

    }
}
