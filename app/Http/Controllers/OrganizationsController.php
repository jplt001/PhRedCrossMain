<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
class OrganizationsController extends Controller
{
    //

    function index(){
    	return view('organization.index')->with(['side_active'=>'organization']);
    }

    
    function postCreate(Request $req){
        $data = $req->all();
        unset($data['_token']);
        DB::table('organizations')->insert($data);;

        return Redirect::to('organization')->with('success', 'Creating new Organization Success.');

    }

    function postUpdate(Request $req){
        $id = $req->get('org_id');
        $data = $req->all();
        unset($data['_token']);
        unset($data['org_id']);
        DB::table('organizations')->where('id',$id)->update($data);;

        return Redirect::to('organization')->with('success', 'Updading Organization Success.');

    }

    function getCreate(){
    	return view('organization.create');
    }

    function setDelete($id){
        DB::table('organizations')->where('id',$id)->delete();
    }
}

