<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use DB;
    use Illuminate\Support\Facades\Input;

    class BloodBankController extends Controller
    {
        public function index(){
            $user = \Auth::user();
            if(!acl_roles($user->user_type, 'bloodbank')){
                return view('errors.403');
            }
            $branches = DB::table('branch')->get();
            $components = DB::table('component')->get();
            $hospitals = DB::table('hospitals')->get();
            $blood_types = DB::table('blood_types')->get();
            return view('bloodbank.index')->with([
                'user_info'=>$user,
                'side_active'=>'bloodbank',
                'branches'=>$branches,
                'hospitals'=>$hospitals,
                'components'=>$components,
                'blood_types'=>$blood_types
            ]);
        }

        public function getAvailableSerology(){
            $serology = array();
            try{
                if(Input::get("serology_id")){
                    $serology = DB::select('SELECT
                                                    t1.id AS id,
                                                    t2.serial_no AS serial_no
                                                FROM bloodbank_storage t1
                                                JOIN bloodbank_storage_details t2
                                                    ON t1.id = t2.bloodbank_storage_id
                                                WHERE t1.`status` = 1 OR t1.id = ?', array(Input::get("serology_id")));
                } else {
                    $serology = DB::select('SELECT
                                                    t1.id AS id,
                                                    t2.serial_no AS serial_no
                                                FROM bloodbank_storage t1
                                                JOIN bloodbank_storage_details t2
                                                    ON t1.id = t2.bloodbank_storage_id
                                                WHERE t1.`status` = 1');
                }
            } catch(\Exception $e){
                $e->getMessage();
            }
            return $serology;
        }

        public function getCensus(){
            $user = \Auth::user();
            try {
                if($_GET['tab'] != "STORAGE"){
                    return DB::select('CALL sp_reservation_get_census_two(?, ?, ?, ?, ?)', array($_GET['tab'], $_GET['bloodTypeId'], $_GET['componentId'], $_GET['indexX'], $_GET['indexY']));
                } else {
                    return DB::select('SELECT IFNULL(SUM(st2.qty), "-") AS qty, ' .
                                                    '"' . $_GET['indexX'] . '" AS i_indexX, ' .
                                                    '"' . $_GET['indexY'] . '" AS i_indexY, ' .
                                                    '"' . $_GET['tab'] . '" AS i_availability ' .
                                            'FROM bloodbank_storage st1 ' .
                                            'JOIN bloodbank_storage_components st2 ' .
                                                'ON st1.id = st2.inventory_id ' .
                                            'WHERE st1.blood_type = ? ' .
                                            'AND st2.component_id = ? ' .
                                            'AND st1.`status` = 1', array($_GET['bloodTypeId'], $_GET['componentId']));
                }
            } catch(\Exception $e) {
                $e->getMessage();
            }
        }

        public function getComponentList(){
            try {
                $responseJson = array(
                    array("bloodTypes" => DB::table('blood_types')->get()),
                    array("components" => DB::table('component')->get()),
                );
                return $responseJson;
            } catch(\Exception $e) {
                $e->getMessage();
            }
        }

        public function insertReservation(){
            try {
                $reservation["patient_id"] = 0;
                $reservation["blood_type"] = 0;
                $reservation["serology"] = Input::get("serology");
                $reservation["diagnosis"] = Input::get("diagnosis");
                $reservation["branch_id"] = Input::get("branch_id");
                $reservation["hospital_id"] = Input::get("hospital_id");
                $reservation["remarks"] = Input::get("remarks");
                $reservation["availability"] = "RESERVED";
                $reservation["time_reserved"] = DB::raw("now()");
                $reservation["date_created"] = DB::raw("now()");
                $component = Input::get("components");
                $mode = Input::get("patient_mode");

                if($mode == "EXISTING"){ // IF PATIENT SELECTED IS EXISTING
                    $reservation["patient_id"] = Input::get("patient_id");
                    $reservation["blood_type"] = DB::select('SELECT blood_type FROM patient WHERE id = ?', array($reservation["patient_id"]))[0]->blood_type;
                } else { // IF PATIENT IS NEW
                    $reservation["blood_type"] = Input::get("blood_type");
                    $patient["last_name"] = strtoupper(Input::get("lastname"));
                    $patient["first_name"] = strtoupper(Input::get("firstname"));
                    $patient["middle_name"] = strtoupper(Input::get("middlename"));
                    $patient["gender"] = Input::get("gender");
                    $patient["age"] = Input::get("age");
                    $patient["blood_type"] = $reservation["blood_type"];
                    $patient["is_approved"] = 1;
                    $reservation["patient_id"] = DB::table('patient')->insertGetId($patient);
                }

                // INSERT RESERVATION
                $reservation_entry_id = DB::table('reservation')->insertGetId($reservation);
                for($x = 0; $x < count($component); $x++){
                    unset($component[$x]["component_label"]);
                    unset($component[$x]["isNew"]);
                    $component[$x]["reservation_entry_id"] = $reservation_entry_id;
                    try {
                        DB::table("reservation_component")->insert($component[$x]);
                    } catch (\Exception $e) {
                        return $e->getMessage();
                    }
                }

                // SET SELECTED SEROLOGY STATUS TO 3 = RELEASED/RESERVED
                DB::table('bloodbank_storage')
                    ->where('id', $reservation["serology"])
                    ->update(['status' => 3]);
            } catch(\Exception $e){
                return $e->getMessage();
            }
        }

        public function updateReservation(){
            $reservation["serology"] = Input::get("serology");
            $reservation["diagnosis"] = Input::get("diagnosis");
            $reservation["patient_id"] = Input::get("patient_id");
            $reservation["blood_type"] = 0;
            $reservation["branch_id"] = Input::get("branch_id");
            $reservation["hospital_id"] = Input::get("hospital_id");
            $reservation["remarks"] = Input::get("remarks");
            $reservation["availability"] = "RESERVED";
            $component = Input::get("components");
            $componentDelete = Input::get("componentsDelete");

            try {
                $idToRevert = DB::select('SELECT serology FROM reservation WHERE id = ?', array(Input::get("id")))[0]->serology;
                DB::table('bloodbank_storage')
                    ->where('id', $idToRevert)
                    ->update(['status' => 1]);
            } catch (\Exception $e){
                return $e->getMessage();
            }

            try {
                $reservation["blood_type"] = DB::select('SELECT blood_type FROM patient WHERE id = ?', array($reservation["patient_id"]))[0]->blood_type;
            } catch (\Exception $e){
                return $e->getMessage();
            }

            try {
                DB::table('bloodbank_storage')
                    ->where('id', $reservation["serology"])
                    ->update(['status' => 3]);
            } catch (\Exception $e){
                return $e->getMessage();
            }

            try {
                /* update reservation details */
                DB::table('reservation')
                    ->where('id', Input::get("id"))
                    ->update($reservation);

                /* delete components */
                if(count($componentDelete) > 0){
                    for($x = 0; $x < count($componentDelete); $x++){
                        DB::table('reservation_component')
                            ->where('id', '=', $componentDelete[$x]['id'])
                            ->delete();
                    }
                }

                /* insert components */
                if(count($component) > 0){
                    for($x = 0; $x < count($component); $x++){
                        unset($component[$x]["component_label"]);
                        unset($component[$x]["isNew"]);
                        $component[$x]["reservation_entry_id"] = Input::get("id");
                        DB::table("reservation_component")
                            ->insert($component[$x]);
                    }
                }

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        public function deleteReservation(){
            try {
                DB::table('bloodbank_storage')
                    ->where('id', Input::get("serology"))
                    ->update(['status' => 1]);
            } catch (\Exception $e){
                return $e->getMessage();
            }
            try {
                DB::table('reservation')
                    ->where('id', Input::get("id"))
                    ->update(['status' => 0]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        public function releaseReservation(){
            try {
                DB::table('reservation')
                    ->where('id', Input::get("id"))
                    ->update(['availability' => "RELEASED", 'time_released' => DB::raw('NOW()')]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        public function cancelReservation(){
            try {
                DB::table('bloodbank_storage')
                    ->where('id', Input::get("serology"))
                    ->update(['status' => 1]);
            } catch (\Exception $e){
                return $e->getMessage();
            }
            try {
                DB::table('reservation')
                    ->where('id', Input::get("id"))
                    ->update(['availability' => "CANCELLED"]);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        public function getInventoryTable(){

            try {
                $requestData = $_REQUEST;

                $columns = array( 
                    0 => 't3.branch_name', 
                    1 => 't1.time_reserved',
                    2 => 't1.time_released',
                    3 => 't2.last_name',
                    4 => 't5.name',
                    5 => 't1.availability'
                );

                try {
                    $totalRows = intval(DB::select('SELECT COUNT(id) AS count FROM reservation WHERE status = 1')[0]->count);
                } catch(\Exception $e){
                    return $e->getMessage();
                }

                try {
                    $totalFiltered = DB::select('SELECT COUNT(t1.id) AS count
                                    FROM reservation t1
                                            JOIN patient t2
                                                ON t1.patient_id = t2.id
                                            JOIN branch t3
                                                ON t1.branch_id = t3.id
                                            JOIN hospitals t4
                                                ON t1.hospital_id = t4.id
                                            JOIN blood_types t5
                                                ON t1.blood_type = t5.id
                                    WHERE t1.status = 1
                                            AND (
                                                    t3.branch_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t2.first_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t2.last_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t2.middle_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t1.availability LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t4.hospital_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                            )')[0]->count;
                } catch(\Exception $e){
                    return $e->getMessage();
                }

                try {
                    $inventory = DB::select('SELECT t1.id AS id,
                                        t1.diagnosis,
                                        t1.branch_id,
                                        t3.branch_name,
                                        t1.time_reserved,
                                        IFNULL(t1.time_released, "NA") AS time_released,
                                        t1.patient_id,
                                        CONCAT(t2.last_name, ", ", t2.first_name) AS patient_name,
                                        t1.availability AS `availability`,
                                        t1.remarks,
                                        t1.hospital_id,
                                        t4.hospital_name,
                                        t1.blood_type AS blood_type_id,
                                        t5.name AS blood_type,
                                        t1.date_created
                                    FROM reservation t1
                                            JOIN patient t2
                                                ON t1.patient_id = t2.id
                                            JOIN branch t3
                                                ON t1.branch_id = t3.id
                                            JOIN hospitals t4
                                                ON t1.hospital_id = t4.id
                                            JOIN blood_types t5
                                                ON t1.blood_type = t5.id
                                    WHERE t1.status = 1 
                                            AND (
                                                    t3.branch_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t2.first_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t2.last_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t2.middle_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t1.availability LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                                    OR t4.hospital_name LIKE CONCAT("%", "' . $requestData['search']['value'] . '", "%")
                                ) ORDER BY ' . $columns[$requestData['order'][0]['column']] . ' ' . $requestData['order'][0]['dir'] . ' LIMIT ' . $requestData['length'] . ' OFFSET ' . $requestData['start']);
                } catch(\Exception $e){
                    return $e->getMessage();
                }

                $data = array();
                for($x = 0; $x < count($inventory); $x++){
                    $nestedData=array(); 
                    $nestedData[] = $inventory[$x]->branch_name;
                    $nestedData[] = $inventory[$x]->time_reserved;
                    $nestedData[] = $inventory[$x]->time_released;
                    $nestedData[] = $inventory[$x]->patient_name;
                    $nestedData[] = $inventory[$x]->blood_type;
                    $nestedData[] = $inventory[$x]->availability;
                    $nestedData[] = "";
                    $nestedData[] = "";
                    $nestedData[] = "";
                    $nestedData[] = $inventory[$x]->diagnosis;
                    $nestedData[] = $inventory[$x]->hospital_name;
                    $nestedData[] = $inventory[$x]->date_created;
                    $nestedData[] = $inventory[$x]->id;
                    $nestedData[] = $inventory[$x]->remarks;
                    try {
                        $nestedData[] = DB::select('CALL sp_reservation_get_component_list(?)', array($inventory[$x]->id));
                    } catch(\Exception $e){
                        return $e->getMessage();
                    }
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

        public function getInventory(){
            $user = \Auth::user();
            try {
                $inputId = $_GET["reservation_id"];
                $inventory = DB::select('SELECT
                                                t1.serology AS serology,
                                                t1.diagnosis AS diagnosis,
                                                t1.id AS id,
                                                t1.branch_id,
                                                t3.branch_name,
                                                t1.time_reserved,
                                                IFNULL(t1.time_released, "NA") AS time_released,
                                                t1.availability,
                                                t1.patient_id,
                                                CONCAT(t2.last_name, ", ", t2.first_name) AS patient_name,
                                                t1.remarks,
                                                t1.hospital_id,
                                                t4.hospital_name,
                                                t1.blood_type AS blood_type_id,
                                                t5.name AS blood_type,
                                                t1.date_created
                                           FROM reservation t1
                                           JOIN patient t2
                                             ON t1.patient_id = t2.id
                                           JOIN branch t3
                                             ON t1.branch_id = t3.id
                                           JOIN hospitals t4
                                             ON t1.hospital_id = t4.id
                                           JOIN blood_types t5
                                             ON t1.blood_type = t5.id
                                          WHERE t1.id = ?
                                            AND t1.`status` = 1',
                                        array($inputId)
                                        );
                $inventory[0]->component_list = DB::select('CALL sp_reservation_get_component_list(?)', array($inventory[0]->id));
                return $inventory;
            } catch(\Exception $e){
                return $e->getMessage();
            }
        }

        public function getBloodbankRows(){
            try {
                return DB::select('SELECT COUNT(id) AS totalRows FROM reservation WHERE status = 1')[0]->totalRows;
            } catch(\Exception $e) {
                return $e->getMessage();
            }
        }
    }
