<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Redirect;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')->with([
            'side_active'   => 'dashboard',
        ]);
    }

    public function getAggregates(){
        try{
            $reserved = DB::table('reservation')
                            ->whereRaw('status = 1 AND availability = "RESERVED"')
                            ->count();
            $released = DB::table('reservation')
                            ->whereRaw('status = 1 AND availability = "RELEASED"')
                            ->count();
            $dailyReserved = DB::table('reservation')
                            ->whereRaw('DATE(date_created) = CURDATE() AND status = 1 AND availability = "RESERVED"')
                            ->count();
            $patients = DB::table('patient')
                            ->whereRaw('is_approved = 1')
                            ->count();

            return array(
                "reserved" => $reserved,
                "released" => $released,
                "dailyReserved" => $dailyReserved,
                "patients" => $patients
            );
            
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function uplodReport(){
        $user = \Auth::user();
        return view('upload')->with(['user_info'=>$user, 'side_active'=>'upload']);
    }


    public function user(){
        $user = \Auth::user();
        
        if(!acl_roles($user->user_type, 'users' )){
            return view('errors.403');
        }
        if($user->user_type == 1){
            $users = DB::table('users')->get();
        }else{
            $users = DB::table('users')->where('user_type', '!=', 1)->where('branch_id', '=', $user->branch_id)->get();
        }
        return view('users.index')->with(['user_info'=>$user, 'side_active'=>'user', 'list_users'=> $users]);
    }

    public function createUser(){
        $user = \Auth::user();
        $branch = DB::table('branch')->pluck('branch_name','id')->all();
        $user_type = [
            '3'=>'Receptionist',
            '2'=>'MedTech',
            '1'=>'Administrator'
        ];
        $user_access_type = DB::table('user_access_type')->pluck('user_access_name', 'id')->all();

        return view('users.create')->with(['user_info'=>$user, 'side_active'=>'user', 'branch'=>$branch, 'user_type'=> $user_type, 'user_access_type'=>$user_access_type]);
    }

    public function updateUser(Request $req){
        $set['name'] = $req->get('name');
        $set['email'] = $req->get('email');
        $set['user_type'] = $req->get('user_type');
        
        DB::table('users')->where('id', $req->get('emp_id'))->update($set);
        return Redirect::to('users/view/'.$req->get('emp_id'))->with('message', 'Updating User Success.');
    }

    public function saveUser(Request $req)
    {  
        $user = \Auth::user();
        $user_name = Input::get('name');
        $arrName = explode(" ", $user_name);
        if(count($arrName)>1){
            $i = 0;
            $email = "";
            while ($i < count($arrName)-1) {
                $email .= strtolower(substr($arrName[$i], 0,1));

                $i++;
            }
            $email .= strtolower($arrName[count($arrName)-1])."@prdcrs.com";
        }else{
            $email = $arrName[0]."@prdcrs.com";
        }
        if(Input::get('branch')){
            $branch_id = Input::get('branch');
        }else{
            $branch_id = $user->branch_id;
        }

        if($this->validateEmail($email)){
            return Redirect::to('users/create')->with('message', 'Email already exist.');
        }else{
            $insert['name']         = $user_name;
            $insert['email']        = $email;
            $insert['user_type']    = Input::get('user_type');
            $insert['branch_id']    = $branch_id;
            $insert['created_at']   = date('Y-m-d H:i:s');
            $insert['password']     = bcrypt(Input::get('password'));
            $insert['gender']       = Input::get('gender');
            
            $id = DB::table('users')->insertGetId($insert);
                       

            // return Redirect::to('users/view/'.$id);
            return Redirect::to('users/view/'.$id)->with('message', 'Creating new User Success.');
        }
        
    }

    public function getEditUser($id){
        $user = \Auth::user();

        $users_inf = DB::table('users')->where('id','=', $id)->first();
        $branch = DB::table('branch')->pluck('branch_name','id')->all();
        $user_type = [
                '3'=>'Receptionist',
                '2'=>'MedTech',
                '1'=>'Administrator'
            ];
        $users_access = DB::table('user_access')->where('user_id', '=', $id)->pluck('id')->all();
        
        $user_access_type = DB::table('user_access_type')->pluck('user_access_name', 'id')->all();

        return view('users.edit')->with([
                'user_info' => $user
                , 'side_active' => 'users'
                , 'branch' => $branch
                , 'user_type' => $user_type
                , 'users_inf' => $users_inf
                , 'user_access_type' => $user_access_type
                , 'users_access' => $users_access]
        );

    }

    public function deleteUser($id){
        DB::table('users')->where('id', '=', $id)->delete();
        return Redirect::to('/users');
    }

    public function viewUser($id){
        $user = \Auth::user();
        $users_inf = DB::table('users')->where('id','=', $id)->first();
        
        return view('users.view')->with(['user_info'=>$user, 'side_active'=>'user', 'user_inf' =>$users_inf]);
    }

    private function validateEmail($email){
        $emailExist = DB::table('users')->where('email', '=', $email)->get()->toArray();
        if(count($emailExist)>0){
            return true;
        }else{
            return false;
        }
    }


    public function dre(){
        $user = \Auth::user();
        $transactions = DB::table('transactions')->get();
        return view('dre.index')->with(['user_info'=>$user, 'side_active'=>'dre', 'transactions'=>$transactions]);
    }


    public function addDre(){
        $user = \Auth::user();
        // Array of Availability
        $arrAvailability['none'] = "SELECT AVAILABILITY";
        $arrAvailability['RESERVED'] = "RESERVED";
        $arrAvailability['RELEASED'] = "RELEASED";
        $arrAvailability['ENDORSED'] = "ENDORSED";
        $arrAvailability['CANCELLED'] = "CANCELLED";

        // Array of Blood Type
        $arrBloodType['none'] = "SELECT BLOOD TYPE";
        $arrBloodType['A+'] = "A+";
        $arrBloodType['O+'] = "O+";
        $arrBloodType['B+'] = "B+";
        $arrBloodType['AB+'] = "AB+";
        $arrBloodType['A-'] = "A-";
        $arrBloodType['O-'] = "O-";
        $arrBloodType['B-'] = "B-";
        $arrBloodType['AB-'] = "AB-";

        // Array of Component
        $arrComponent['none'] = "SELECT COMPONENT";
        $arrComponent['WB'] = "WB";
        $arrComponent['PRBC'] = "PRBC";
        $arrComponent['FFP'] = "FFP";
        $arrComponent['Plt Con'] = "Plt Con";
        $arrComponent['Cpt'] = "Cpt";
        $arrComponent['Cst'] = "Cst";


        $hospitals = DB::table('hospitals')->pluck('hospital_name','id')->all();
        
        return view('dre.create')->with([
                'user_info'=>$user
              , 'side_active'=>'dre'
              , 'arrAvailability'=> $arrAvailability
              , 'arrBloodType'=>$arrBloodType
              , 'arrComponent' => $arrComponent
              , 'hospitals' => $hospitals
        ]);
    }

    public function postCreateDre(){
        $user_name = Input::get('name');
    }

    public function generateDre(){
        $user = \Auth::user();
        return view('dre.generate')->with(['user_info'=>$user, 'side_active'=>'dre']);
    }
}

