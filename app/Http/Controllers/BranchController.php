<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Redirect;


class BranchController extends Controller
{
    //

    public function index()
    {
        $user = \Auth::user();
        
        if(!acl_roles($user->user_type, 'branch')){
            return view('errors.403');
        }
        $branch = DB::table('branch')->get();
        return view('branch.index')->with(['user_info'=>$user, 'side_active'=>'branch', 'branch_list'=> $branch]);
    }

    public function getCreate(){
    	$user = \Auth::user();
    	return view('branch.create')->with(['user_info'=>$user]);
    }

    public function postCreate(){
    	$insert['branch_name']      = Input::get('branch_name');        
        $insert['address']    		= Input::get('address');
        $insert['contact_no']		= Input::get('contact_num');
      	
        $id = DB::table('branch')->insertGetId($insert);
        
      
        return Redirect::to('branch')->with('message', 'Creating new User Success.');
    }

    public function getEdit($id){
        $user = \Auth::user();
        $branch = DB::table('branch')->find($id);
        return view('branch.edit')->with(['user_info'=>$user, 'branch'=>$branch]);
    }
    public function postEdit(Request $req){
        $set['branch_name'] = $req->get('branch_name');
        $set['address']     = $req->get('address');
        $set['contact_no']  = $req->get('contact_num');

        DB::table('branch')->where('id', $req->get('branch_id'))->update($set);
        return Redirect::to('branch')->with('message', 'Updating Branch Success.');

    }
}

