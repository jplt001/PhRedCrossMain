<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class AjaxController extends Controller
{
    //

    public function getUserInf($id){

    	$info = DB::table('users')
    					->join('branch', 'users.branch_id', '=', 'branch.id')
    					->select('users.*' , 'branch.branch_name')
    					->where('users.id', '=', $id)->first();
    	return response()->json($info);
    }
    public function getBloodReferrals(){

    	$info = DB::table('blood_referral')
    					->join('patient', 'blood_referral.patient_id', '=', 'patient.id')
    					->join('hospitals', 'blood_referral.hospital_id', '=', 'hospitals.id')
    					->select('blood_referral.date', DB::raw("CONCAT(`patient`.`last_name`, ', ', `patient`.`first_name`) as name"), 'hospitals.hospital_name', 'blood_referral.no_of_units', 'blood_referral.blood_type', 'blood_referral.address')
    					->orderBy('blood_referral.id', 'DESC')
    					->get();
        $data['data'] = $info;
    	return response()->json($data);
    }

    function getTotalAccountedRedemeed(){
        $data = array();
        $tmpTotalAccounted = DB::table('activity_held')->select(DB::raw('SUM(`ten_percent`) as tmpAccounted'))->first();
        $tmpTotalRedemmed = DB::table('blood_referral')->select(DB::raw('SUM(`no_of_units`) as tmpRedemmeds'))->first();
        $totalAccounted = round((float) $tmpTotalAccounted->tmpAccounted, 0, PHP_ROUND_HALF_UP);
        $totalRedemmed = (int) $tmpTotalRedemmed->tmpRedemmeds;
        $data['total_accounted'] = $totalAccounted;
        $data['total_redemmed'] = $totalRedemmed;
        $data['remaided'] = $totalAccounted - $totalRedemmed;

        return response()->json($data);

    }

    function getActivityHeld(){
        $data['data'] = array();
        $tmpActivityHeld = DB::table('activity_held')->get();    
        
        $i = 1;
        foreach ($tmpActivityHeld as $key => $value) {
            $tmpData['no'] = $i;
            $tmpData['date'] = $value->date;
            $tmpData['no_of_blood_collected'] = $value->no_of_blood_collected;
            $tmpData['ten_percent'] = $value->ten_percent;
            array_push($data['data'], $tmpData);
            $i++;
        }       

        return response()->json($data);
    }

    function getHospitals(){
        
        $res = DB::table('hospitals')->where('is_deleted', 0)->orderBy('hospital_name')->get();
        
        $i = 0;
        foreach ($res as $v) {
            $data['data'][$i]['id']             = $v->id;
            $data['data'][$i]['hospital_name']  = $v->hospital_name;
            $data['data'][$i]['address']        = $v->address;
            $data['data'][$i]['contact_no']     = $v->contact_no;
            $data['data'][$i]['actions']        = '<button onclick="editHospital('.$v->id.')" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-pencil "></i></button> <button onclick="deleteHospital('.$v->id.')" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash "></i></button>';
            $i++;
        }

        
        return response()->json($data);
    }

    function getPatients(){
        $tmpData= DB::table('patient')->where('deleted', '=', 0)->orderBy('last_name')->get();
        $i = 0;
        foreach ($tmpData as $v) {
            $data[$i]['id'] =  $v->id;
            $data[$i]['patient_name'] =  $v->last_name.", ".$v->first_name;
            $data[$i]['address'] =  is_null($v->address) ? "N/A" : $v->address;
            $data[$i]['contact_no'] =  is_null($v->contact_no) ? "N/A" : $v->contact_no;
            $data[$i]['age'] =  is_null($v->age) ? "N/A" : $v->age;
            $data[$i]['gender'] =  is_null($v->gender) ? "N/A" : $v->gender;
            if($v->is_approved == 0){
                $data[$i]['status'] = "<span class='text-info'>Pending</span>";
            }elseif($v->is_approved == 1){
                $data[$i]['status'] = "<span class='text-green'>Approved</span>";
            }else{
                $data[$i]['status'] = "<span class='text-red'>Denied</span>";
            }
            $data[$i]['reason'] = $v->reason;

            $data[$i]['actions'] = '<button onclick="editPatient('.$v->id.')" class="btn btn-info btn-flat btn-xs"><i class="fa fa-edit"></i></button>';
            $data[$i]['actions'] .= '<button class="btn btn-danger btn-flat  btn-xs" onclick="deletesPatient('."'".$v->id."'".')"><i class="fa fa-trash"></i></button>';
            // $data[$i]['actions'] = "<a href='' class='btn btn-info btn-flat btn-xs'><i class='fa fa-edit'></i><button onclick="" class='btn btn-danger btn-flat btn-xs'><i class='fa fa-trash'></i></a>";


            $i++;
        }

        $raw_data['data'] = $data;

        return response()->json($raw_data);
    }


    function getOrganizations(){
        $data = array();
        $res_data = DB::table('organizations')->orderBy('organization_name')->get();
        
        $i = 0;
        foreach ($res_data as $v) {
            $data['data'][$i]['organization_name']  =  $v->organization_name;
            $data['data'][$i]['address']            =  $v->address;
            $data['data'][$i]['added_when']         =  date('M d, Y', strtotime($v->added_when));
            $data['data'][$i]['actions']             =  "<button data-title='Edit' data-toggle='tooltip' onclick='editOrganization(".$v->id.")'class='btn btn-info btn-flat btn-xs'><i class='fa fa-pencil'></i></button><button data-title='Delete' data-toggle='tooltip' onclick='deleteOrganization(".$v->id.")'class='btn btn-danger btn-flat btn-xs'><i class='fa fa-ban'></i></button>";
            $i++;
        }
        return response()->json($data);
    }


    function getOrgInfo($id){
        
        $data = DB::table('organizations')->where('id',$id)->first();
        
        return response()->json($data);
    }


    function getHospitalInfo($id){
        
        $data = DB::table('hospitals')->where('id',$id)->first();
        
        return response()->json($data);
    }


    function getPatientInfo($id){
        
        $data = DB::table('patient')->where('id',$id)->first();
        
        return response()->json($data);
    }

    function getDonorInfo($id){
        $data = DB::table('blood_donors')->where('id',$id)->first();
        
        return response()->json($data);
    }

    function getPublicSerology(){
        date_default_timezone_set("Asia/Manila");
        $curr_date = date('Y-m-d');
        $curr_start_time = $curr_date. " 00:00:00";
        $curr_end_time = $curr_date. " 23:59:59";

        $data = DB::table('bloodbank_storage as a')
            ->join('bloodbank_storage_details as b', 'b.bloodbank_storage_id', '=', 'a.id')
            // ->select('blood_type')
            // ->groupBy('blood_type')
            ->get();

        return response()->json($data);
    }


    function getBloodAvailableToday(){
        $data = [];
        $component_types = DB::table('component')->get();
        $blood_types = DB::table('blood_types')->pluck('id', 'type');

        // pre($blood_types);
        $i = 0;
        foreach ($component_types as $v) {
            $raw = $this->getTotalBloodByComponent($v->id);
            $data['data'][$i]['si_no'] = $i+1;            
            $data['data'][$i]['collection_type'] = $v->name;
            $total = 0;
            foreach ($blood_types as $k=>$v) {
                
                if(array_key_exists($k, $raw)){
                    $data['data'][$i][$k] = $raw[$k];
                    $total += $raw[$k];
                }else{
                    $data['data'][$i][$k] = 0;
                }
            }
            $data['data'][$i]['total'] = $total;
            $i++;

        }

        return response()->json($data);
    }

    function getTotalBloodByComponent($id){
            $data = DB::select("SELECT c.id,SUM(a.qty) AS total, `c`.`type` FROM `bloodbank_storage_components` AS a
                            INNER JOIN `bloodbank_storage` AS b ON b.id = a.`inventory_id`
                            INNER JOIN `blood_types` AS c ON c.id = b.`blood_type`
                            WHERE a.component_id = ".$id."
                            GROUP BY blood_type, c.id, c.type");
            
            $dat =[];
            foreach ($data as $v) {
                $dat[$v->type] = $v->total;
            }

            return $dat;
                
    }

} 