<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use DB;
class SerologyController extends Controller
{
	function __construct(){
		set_time_limit(3000);
	}
    //
	function index(){

		$user = \Auth::user();
        
        if(!acl_roles($user->user_type, 'serology')){
            return view('errors.403');
        }
		// $bloodbank_storage = DB::table('bloodbank_storage as a')
		// 							->leftJoin('bloodbank_storage_details as b', 'b.bloodbank_storage_id' , '=', 'a.id')
		// 							->join('blood_donors as c', 'c.id', '=', 'a.patient_id')
		// 							->leftJoin('blood_types as d', 'd.id', '=', 'a.blood_type')
		// 							->join('branch as e', 'a.branch_id', '=', 'e.id')
		// 							->select(DB::raw('CONCAT(c.last_name, "," , c.first_name) AS patient_name'),
		// 									'a.id as bldbnk_id',
		// 									'b.id as bldbnk_detail_id',
		// 									'a.*',
		// 									'b.*', 
		// 									'd.name AS donor_blood_type', 											
		// 									'e.branch_name')
		// 							// ->select(
										
		// 							// 		'a.*',
		// 							// 		'b.*', 
		// 							// 		'd.name AS donor_blood_type', 											
		// 							// 		'e.branch_name',
		// 							// 		DB::raw('CONCAT(c.last_name, ',' , c.first_name) AS patient_name')											

		// 							// 	)

		// 							->orderBy('b.id', 'DESC')
		// 							->get();
		
		$blood_donors = DB::table('blood_donors')->get();
		$blood_types = DB::table('blood_types')->get();
		// $blood_bank = DB::table('blood_bank as a')
		// 				->leftJoin('blood_donors as b', 'a.donor_id','=', 'b.id')
		// 				->leftJoin('organization as c', 'a.org_id','=', 'c.id')
		// 				->join('branch as d', 'a.branch_id', '=', 'd.id')
		// 				->select('a.*', DB::raw('CONCAT(`last_name`,", ", `first_name`) as donor_name'),'c.organization_name' , 'd.branch_name')

		// // 				->get();
		$branch = DB::table('branch')->get();
		$component = DB::table('component')->pluck('code', 'id')->all();

		$arr_anti_hiv_mtd = array(
			'EVOLIS EIA'=>'EVOLIS EIA',
			'ARCHI CMIA'=>'ARCHI CMIA'
		);

		$arr_test_res = array(
			'VC Failed'=>'VC Failed',
			'VD Failed'=>'VD Failed',
			'INCCLSV'=>'INCCLSV',
			'NR'=>'NR',
			'IR'=>'IR',
			'R-GZ'=>'R-GZ',
			'NR-GZ'=>'NR-GZ',
			'EQ-GZ'=>'EQ-GZ'
		);

		return view('serology.index')->with([
			'user_info' => $user,
			'side_active' => 'serology',
			'blood_donors' => $blood_donors,
			'blood_types' => $blood_types,
			'branches' => $branch,
			'arr_anti_hiv_mtd' => $arr_anti_hiv_mtd,
			'arr_test_res' => $arr_test_res,
			'component' => $component
		]);
	}

	public function getDonorResults(){
		try{
			$requestData = $_REQUEST;

			$columns = array( 
				0 => 'patient_name', 
				1 => 'branch_name',
				2 => 'serial_no',
				3 => 'final_bt',
				4 => 'anti_hiv',
				5 => 'hbsag',
				6 => 'anti_hcv',
				7 => 'syphilis',
				8 => 'malaria',
				9 => 'status',
				10 => 'date_released'
			);

			try{
				$totalRows = DB::select('SELECT COUNT(id) AS count FROM bloodbank_storage')[0]->count;
			} catch(\Exception $e){
				return $e->getMessage();
			}

			try{
				$totalFiltered = DB::table('bloodbank_storage as a')
									->leftJoin('bloodbank_storage_details as b', 'b.bloodbank_storage_id' , '=', 'a.id')
									->join('blood_donors as c', 'c.id', '=', 'a.patient_id')
									->leftJoin('blood_types as d', 'd.id', '=', 'a.blood_type')
									->join('branch as e', 'a.branch_id', '=', 'e.id')
									->select(DB::raw('COUNT(a.id) AS count'))		
									->where('c.last_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('c.first_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('c.middle_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('e.branch_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.serial_no', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.final_bt', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.anti_hiv', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.hbsag', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.anti_hcv', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.syphilis', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.malaria', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('a.status', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.date_released', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->get()[0]->count;
			} catch(\Exception $e){
				return $e->getMessage();
			}

			try{
				switch($requestData['search']['value']){
					case "RELEASED":
					case "RESERVED":
						$requestData['search']['value'] = 3;
						break;
					case "PENDING":
						$requestData['search']['value'] = 0;
						break;
					case "PASSED":
						$requestData['search']['value'] = 1;
						break;
					case "FAILED":
						$requestData['search']['value'] = 2;
						break;
				}
				$inventory = DB::table('bloodbank_storage as a')
										->leftJoin('bloodbank_storage_details as b', 'b.bloodbank_storage_id' , '=', 'a.id')
										->join('blood_donors as c', 'c.id', '=', 'a.patient_id')
										->leftJoin('blood_types as d', 'd.id', '=', 'a.blood_type')
										->join('branch as e', 'a.branch_id', '=', 'e.id')
										->join('organizations as f', 'c.org_id', '=', 'f.id')
										->select(DB::raw(

														'CONCAT(c.last_name, ", " , c.first_name) AS patient_name,
														a.id as bldbnk_id,
														b.id as bldbnk_detail_id,
														b.serial_no AS serial_no,
														IFNULL(b.final_bt, "N/A") AS final_bt,
														b.anti_hiv AS anti_hiv,
														b.hbsag AS hbsag,
														b.anti_hcv AS anti_hcv,
														b.syphilis AS syphilis,
														b.malaria AS malaria,
														a.status AS status,
														IFNULL(b.date_released, "N/A") AS date_released,
														d.name AS donor_blood_type,											
																									
														e.branch_name AS branch_name'))
										->where('c.last_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('c.first_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('c.middle_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('e.branch_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.serial_no', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.final_bt', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.anti_hiv', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.hbsag', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.anti_hcv', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.syphilis', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.malaria', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('a.status', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orWhere('b.date_released', 'LIKE', '%' . $requestData['search']['value'] . '%')
										->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir'])
										->offset($requestData['start'])
										->limit($requestData['length'])
										->get();
			} catch(\Exception $e){
				return $e->getMessage();
			}

			$data = array();
			for($x = 0; $x < count($inventory); $x++){
				$nestedData=array(); 
				$nestedData[] = $inventory[$x]->patient_name;
				$nestedData[] = $inventory[$x]->branch_name;
				$nestedData[] = $inventory[$x]->serial_no;
				$nestedData[] = $inventory[$x]->final_bt;
				$nestedData[] = $inventory[$x]->anti_hiv;
				$nestedData[] = $inventory[$x]->hbsag;
				$nestedData[] = $inventory[$x]->anti_hcv;
				$nestedData[] = $inventory[$x]->syphilis;
				$nestedData[] = $inventory[$x]->malaria;
				$nestedData[] = $inventory[$x]->status;
				$nestedData[] = $inventory[$x]->date_released;
				$nestedData[] = "";
				$nestedData[] = "";
				$nestedData[] = "";
				$nestedData[] = $inventory[$x]->bldbnk_id;
 				$data[] = $nestedData;
			}

			$json_data = array(
				"draw"            => intval( $requestData['draw'] ),
				"recordsTotal"    => intval( $totalRows ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
			);

			return json_encode($json_data);	 	
		} catch(\Exception $e){
			return $e->getMessage();
		}
			
	}

	public function getLabResults(){
		try{
			$requestData = $_REQUEST;

			$columns = array( 
				0 => 'b.serial_no', 
				1 => 'b.extraction_date',
				2 => 'b.source',
				3 => 'c.last_name',
				4 => 'e.branch_name',
				5 => 'd.name',
				6 => 'b.rh',
				7 => 'b.orig_lab_no',
				8 => 'b.sample_taken_from'
			);

			try{
				$totalRows = DB::select('SELECT COUNT(id) AS count FROM bloodbank_storage')[0]->count;
			} catch(\Exception $e){
				return $e->getMessage();
			}

			try{
				$totalFiltered = DB::table('bloodbank_storage as a')
									->leftJoin('bloodbank_storage_details as b', 'b.bloodbank_storage_id' , '=', 'a.id')
									->join('blood_donors as c', 'c.id', '=', 'a.patient_id')
									->leftJoin('blood_types as d', 'd.id', '=', 'a.blood_type')
									->join('branch as e', 'a.branch_id', '=', 'e.id')
									->select(DB::raw('COUNT(a.id) AS count'))		
									->where('b.serial_no', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.extraction_date', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.source', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('e.branch_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('d.name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.rh', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.orig_lab_no', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.sample_taken_from', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->get()[0]->count;
			} catch(\Exception $e){
				return $e->getMessage();
			}

			try{
				$inventory = DB::table('bloodbank_storage as a')
									->leftJoin('bloodbank_storage_details as b', 'b.bloodbank_storage_id' , '=', 'a.id')
									->join('blood_donors as c', 'c.id', '=', 'a.patient_id')
									->leftJoin('blood_types as d', 'd.id', '=', 'a.blood_type')
									->join('branch as e', 'a.branch_id', '=', 'e.id')
									->join('organizations as f', 'c.org_id', '=', 'f.id')
									->select(DB::raw('a.id AS id,
													  IFNULL(b.serial_no, "N/A") AS serial_no,
													  IFNULL(b.extraction_date, "N/A") AS extraction_date,
													  IFNULL(b.source, "N/A") AS source,
													  e.branch_name,
													  d.name AS donor_blood_type,
													  IFNULL(b.rh, "N/A") AS rh,
													  IFNULL(b.orig_lab_no, "N/A") AS orig_lab_no,
													  IFNULL(b.sample_taken_from, "N/A") AS sample_taken_from,		
													  IFNULL(f.organization_name, "N/A") AS organization_name,			
													  IFNULL(CONCAT(c.last_name, ", ", c.first_name), "N/A") AS donor_name'))
									->where('c.last_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('c.first_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('c.middle_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.serial_no', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.extraction_date', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.source', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('e.branch_name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('d.name', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.rh', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.orig_lab_no', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orWhere('b.sample_taken_from', 'LIKE', '%' . $requestData['search']['value'] . '%')
									->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir'])
									->offset($requestData['start'])
									->limit($requestData['length'])
									->get();
			} catch(\Exception $e){
				return $e->getMessage();
			}

			$data = array();
			for($x = 0; $x < count($inventory); $x++){
				$nestedData=array(); 
				$nestedData[] = $inventory[$x]->serial_no;
				$nestedData[] = $inventory[$x]->extraction_date;
				$nestedData[] = $inventory[$x]->source;
				$nestedData[] = $inventory[$x]->organization_name;
				$nestedData[] = $inventory[$x]->branch_name;
				$nestedData[] = $inventory[$x]->donor_blood_type;
				$nestedData[] = $inventory[$x]->rh;
				$nestedData[] = $inventory[$x]->orig_lab_no;
				$nestedData[] = $inventory[$x]->sample_taken_from;
				$nestedData[] = "";
				$nestedData[] = $inventory[$x]->id;
 				$data[] = $nestedData;
			}

			$json_data = array(
				"draw"            => intval( $requestData['draw'] ),
				"recordsTotal"    => intval( $totalRows ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
			);

			return $json_data;
		} catch(\Exception $e){
			return $e->getMessage();
		}
		
	}
		

	function save(Request $req){
		$raw_bloodbank_components = $req->get('components');
		
		
		$res = DB::table('bloodbank_storage')->where('patient_id', $req->get('patient_id'))->get();
		if(count((array)$res)>1){
			DB::table('blood_donors')->where('id', $req->get('patient_id'))->update(['is_new'=>2]);
		}

		$insert['patient_id'] = $req->get('patient_id');
		$insert['branch_id'] = $req->get('branch_id');
		$insert['hospital_id'] =1;
		$insert['blood_type'] = $req->get('blood_type');
		$insert['status'] = 0;


		$id = DB::table('bloodbank_storage')->insertGetId($insert);

		unset($insert);
		$insert['serial_no'] = $req->get('serial_no');
		$insert['bloodbank_storage_id'] = $id;
		$insert['extraction_date'] = date('Y-m-d', strtotime($req->get('extraction_date')));
		$insert['anti_hiv'] = $req->get('anti_hiv');
		$insert['hbsag'] = $req->get('hbsag');
		$insert['anti_hcv'] = $req->get('anti_hcv');
		$insert['syphilis'] = $req->get('syphilis');
		$insert['malaria'] = $req->get('malaria');
		DB::table('bloodbank_storage_details')->insert($insert);

		unset($insert);
		$insert = [];
		$i=0;
		if( count( $raw_bloodbank_components ) > 0 ){
			foreach ($raw_bloodbank_components as $k => $v) {
				$insert[$i]['inventory_id'] = $id;
				$insert[$i]['component_id'] = $k;
				$insert[$i]['qty'] = $v;
				$i++;

			}

			DB::table('bloodbank_storage_components')->insert($insert);

		}
		

		// $insert['bloodbank_storage_id'] = 1;

		return Redirect::to('serology');


	}

	function getSerologyResults(){
		$res = DB::table('bloodbank_storage as a')
					->select('a.*', DB::raw('CONCAT(b.last_name, ", ", b.first_name) as patient_name'))
					->join('blood_donors as b', 'a.patient_id', '=', 'b.id')
					->get();
		$res['data'] = $res;
		return json_encode($res);
	}

	function getDelete(){
		// DB::table('');
	}

	function getBloodType($id){
		$res =  DB::table('blood_types')->where('id', $id)->get();
		return $res->name;
	}

	function setPassed($id){
		try{
			DB::table('bloodbank_storage')->where('id', $id)->update(['status'=>1]);
			echo json_encode(["title"=>"Success", "detail"=>" Success"]);
		}catch(Exception $e){
			echo json_encode(["title"=>"Error", "detail"=> $e]);
		}
		exit();
	}

	function setFailed($id){
		try{
			DB::table('bloodbank_storage')->where('id', $id)->update(['status'=>2]);
			echo json_encode(["title"=>"Success", "detail"=>" Success"]);
		}catch(Exception $e){
			echo json_encode(["title"=>"Error", "detail"=> $e]);
		}
		exit();
	}


	function updateSerologyLabResults(Request $req){
		$id = $req->get('patient_id');
		$set['bag_used'] 			= $req->get('bag_used');
		$set['bag_condition'] 		= $req->get('bag_condition');
		$set['final_bt'] 			= $req->get('final_blood_type');
		$set['anti_hiv_mtd'] 		= $req->get('anti_hiv_mtd');
		$set['anti_hiv_result'] 	= $req->get('anti_hiv_result');
		$set['hbsag_mtd'] 			= $req->get('hbsag_mtd');
		$set['hbssag_result'] 		= $req->get('hbsag_result');
		$set['anti_hcv_mtd'] 		= $req->get('anti_hcv_mtd');
		$set['anti_hcv_result']		= $req->get('anti_hcv_result');
		$set['syphilis_mtd'] 		= $req->get('syphilis_mtd');
		$set['syphilis_result']		= $req->get('syphilis_result');
		$set['malaria_mtd'] 		= $req->get('malaria_mtd');
		$set['malaria_result'] 		= $req->get('malaria_result');
		$set['date_released'] 		= date('Y-m-d', strtotime($req->get('date_released')));

		DB::table('bloodbank_storage_details')->where('bloodbank_storage_id', '=',$id)->update($set);

		$res = DB::table('initial_remarks_value')->where('bloodbank_storage_id', $id)->get();
		$data['hiv'] = $req->get('hiv');
		$data['hbs'] = $req->get('hbs');
		$data['hcv'] = $req->get('hcv');
		$data['syp'] = $req->get('syp');
		$data['mal'] = $req->get('mal');
		if(count($res)== 0){
			$data['bloodbank_storage_id'] = $id;
			
			DB::table('initial_remarks_value')->insert($data);
		}else{
			DB::table('initial_remarks_value')->where('bloodbank_storage_id', $id)->update($data);
		}
		return Redirect::to('serology');

	}

	function updateFinalLabResults(Request $req){
		$id 				= $req->get('patient_id2');
		if(!is_null($req->get('source'))){
			$set['source'] 		= $req->get('source');
		}
		
		if(!is_null($req->get('orig_lab_no'))){
			$set['orig_lab_no'] = $req->get('orig_lab_no');	
		}

		if(!is_null($req->get('sample_taken_from'))){
			$set['sample_taken_from'] = $req->get('sample_taken_from');	
		}

		if(!is_null($req->get('sample_taken_from'))){
			$set['sample_taken_from'] = $req->get('sample_taken_from');	
		}
		
		
		DB::table('bloodbank_storage_details')->where('bloodbank_storage_id', '=',$id)->update($set);
		return Redirect::to('serology');
	}
}
