<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Redirect;
class HospitalController extends Controller
{
    //

    public function index(){
    	 $user = \Auth::user();
    	 $hospitals = DB::table('hospitals')->get();
    	return view('hospitals.index')->with(['user_info'=>$user ,'hospital_list'=>$hospitals,'side_active'=>'hospital']);
    }


    public function create(){
    	$user = \Auth::user();
    	return view('hospitals.create')->with(['user_info'=>$user]);
    }

    public function postCreate(){
        $insert['hospital_name']        = Input::get('hospital_name');        
        $insert['address']              = Input::get('address');
        $insert['contact_no']           = Input::get('contact_num');
        
        $id = DB::table('hospitals')->insertGetId($insert);
        
        
        return Redirect::to('hospital')->with('message', 'Creating new Hospital Success.');
    }

    public function postUpdate(Request $req){
        $data = $req->all();
        $id = $data['hopital_id'];

        unset($data['_token']);
      	unset($data['hopital_id']);
        
        DB::table('hospitals')->where('id', $id)->update($data);
        
        
        return Redirect::to('hospital')->with('success', 'Updating "'.$data['hospital_name'].'" detail success.');
            
    }

    public function view($id){
        $user = \Auth::user();
        $hospitals = DB::table('hospitals')->find($id);

        return view('hospitals.view')->with(['user_info'=>$user, 'hospital'=>$hospitals]);
    }

    public function getDelete($id){         
        try{
            DB::table('hospitals')->where('id', $id)->update(['is_deleted'=>1]);
        }catch(Exception $e){
            return response()->json(['detail'=>$e]);
        }

        return response()->json(['detail'=>'Deleting hopital success.']);
        // return Redirect::to('hospital')->with('success', 'Deleting "'.$data['hospital_name'].'" success.');
    }
}
