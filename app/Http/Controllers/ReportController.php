<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use DB;
use PHPExcel;
use PHPExcel_IOFactory;
use Auth;
use PDF;

class ReportController extends Controller
{
    function generateReportReservation(){
    	$user = Auth::user();
    	return view('reports.blood_referral.index')->with([
            'user_info'=>$user,
            'side_active'=>'report_bloodreferral'
        ]);
    }

    function postGenerateReportReservation(Request $req){
    	DB::enableQueryLog();
    	$dates = $req->get('date');
    	$tmpDate = explode(" - ", $dates);
    	$from = date('Y-m-d', strtotime($tmpDate[0]));
    	$to = date('Y-m-d', strtotime($tmpDate[1]));

    	$res = DB::table('reservation')
    		->join('patient', 'reservation.patient_id', '=', 'patient.id')
    		->join('branch', 'reservation.branch_id', '=', 'branch.id')
    		->join('hospitals', 'reservation.hospital_id', '=', 'hospitals.id')
    		->join('blood_types', 'reservation.blood_type', '=', 'blood_types.id')
    		->whereBetween('reservation.date_created',[$from,$to])
    		->orderBy('reservation.date_created')
    		->get();
    	pre($res);

    }


    function postGenerateReferralReport(Request $req){
        $dates = $req->get('date');
        $tmpDate = explode(" - ", $dates);
        $from = date('Y-m-d', strtotime($tmpDate[0]));
        $to = date('Y-m-d', strtotime($tmpDate[1]));
        $res = DB::table('blood_referral as a')
            ->join('patient as b', 'a.patient_id', '=', 'b.id')
            ->join('hospitals as c', 'a.hospital_id', '=', 'c.id')
            ->select('a.*', 
                    DB::raw('CONCAT(b.last_name, ", ", b.first_name) as patient_name'),
                    'c.hospital_name'
                )
            ->whereBetween('a.date',[$from,$to])
            ->get();
        
        $res_activity = DB::table('activity_held as a')->select('date', 'id', 'no_of_blood_collected', 'ten_percent')->whereBetween('a.date',[$from,$to])->get();
        
        $accounted = 0;
        foreach($res_activity as $v){
            $accounted += $v->ten_percent >= 1 ? $v->ten_percent : 0;
        }

        $redeemed = 0;
        foreach($res as $v){
            $redeemed += $v->no_of_units;
        }

        return array(
            'res'=>$res,
            'res_activity'=>$res_activity,
            'accounted'=>$accounted,
            'redeemed'=>$redeemed,
            'remained'=>($accounted - $redeemed),
        );
    }

    function generateReportSerology(){
        $user = Auth::user();
        return view('reports.serology.index')->with([
            'user_info'=>$user,
            'side_active'=>'report_serology'
        ]);
    }

    function postGenerateReportSerology(){
        try {
            $by = $_POST['by'];
            $dates = $_POST['date'];
            $tmpDate = explode(" - ", $dates);
            $from = date('Y-m-d', strtotime($tmpDate[0]));
            $to = date('Y-m-d', strtotime($tmpDate[1]));
            $where = 'b.'.$by;
            $filenames = 'Serology Report Filtered By ('.$by.') From '.$from.' to '.$to;
            $bloodbank_storage = DB::table('bloodbank_storage as a')
                                        ->leftJoin('bloodbank_storage_details as b', 'b.bloodbank_storage_id' , '=', 'a.id')
                                        ->join('blood_donors as c', 'c.id', '=', 'a.patient_id')
                                        ->leftJoin('blood_types as d', 'd.id', '=', 'a.blood_type')
                                        ->join('branch as e', 'a.branch_id', '=', 'e.id')
                                        ->select(DB::raw('CONCAT(c.last_name, "," , c.first_name) AS patient_name'),
                                                'a.id as bldbnk_id',
                                                'b.id as bldbnk_detail_id',
                                                'a.*',
                                                'b.*',
                                                'd.type as final_bt',
                                                'd.name AS donor_blood_type',                                       
                                                'e.branch_name')
                                        ->whereBetween($where,[$from,$to])
                                        ->orderBy('b.id', 'ASC')
                                        ->get();
            return $bloodbank_storage;

        } catch(\Exception $e){
            return $e->getMessage();
        }

        $objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objPHPExcel->load(__DIR__."/../../../templates/template_serology.xlsx"); // Empty Sheet
        // $objPHPExcel = PHPExcel_IOFactory::load();
        $objPHPExcel->setActiveSheetIndex(0);
        $cpd_start_col = 'A';
        $cpd_start_row = 10;
        $slr_start_col = 'BW';
        $slr_start_row = 10;
        $lri_start_col = 'AU';
        $lri_start_row = 10;
        $dmr_start_col = 'AA';
        $dmr_start_row = 10;
        $start_col = 'BW';
        $start_row = 10;
        $i = 1;

        foreach ($bloodbank_storage as $v) { 
            // first
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, $i);
            $cpd_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, $v->extraction_date);
            $cpd_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, $v->serial_no);
            $cpd_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, '');
            $cpd_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, $v->branch_name);
            $cpd_start_col++;

            $bt =$this->getBloodTypeById($v->final_bt);
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, substr($bt, 0, strlen($bt)-1));
            $cpd_start_col++;
            if(substr($bt, strlen($bt)-1) == '+'){
                $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, 'POS');
            }elseif((substr($bt, strlen($bt)-1) == '+')){
                $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, 'NEG');
            }else{
                $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, '');
            }            
            
            $cpd_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($cpd_start_col.$cpd_start_row, $v->bag_used);
            $cpd_start_col++;

            $cpd_start_col= 'A';

            $cpd_start_row++;

            // Daily Monitoring of Released Blood and Expiration Countdown
            $objPHPExcel->getActiveSheet()->setCellValue($dmr_start_col.$dmr_start_row, $v->serial_no);
            $dmr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($dmr_start_col.$dmr_start_row, $v->extraction_date);
            $dmr_start_col++;

            $dmr_start_col = 'AA';
            $dmr_start_row++;

            // LABORATORY RESULTS INPUT
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $i);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->serial_no);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->extraction_date);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->source);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, '');
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->branch_name);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->bag_used);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->bag_condition);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->bag_condition);
            $lri_start_col++;

            $bt =$this->getBloodTypeById($v->final_bt);
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, substr($bt, 0, strlen($bt)-1));
            $lri_start_col++;
            if(substr($bt, strlen($bt)-1) == '+'){
                $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, 'POS');
            }elseif((substr($bt, strlen($bt)-1) == '+')){
                $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, 'NEG');
            }else{
                $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, '');
            } 
            $lri_start_col++;

            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->anti_hiv_mtd);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->anti_hiv_result);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->hbsag_mtd);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->hbssag_result);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->anti_hcv_mtd);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->anti_hcv_result);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->syphilis_mtd);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->syphilis_result);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->malaria_mtd);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->malaria_result);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->anti_hiv);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->hbsag);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->anti_hcv);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->syphilis);
            $lri_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($lri_start_col.$lri_start_row, $v->malaria);
            $lri_start_col++;

            $lri_start_col = 'AU';

            $lri_start_row++;



            // last           
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->patient_name);
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->branch_name);
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $i);
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->serial_no);
            $slr_start_col++;
            $slr_start_col++;
            $bt =$this->getBloodTypeById($v->final_bt);
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, substr($bt, 0, strlen($bt)-1));
            $slr_start_col++;
            if(substr($bt, strlen($bt)-1) == '+'){
                $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, 'POS');
            }elseif((substr($bt, strlen($bt)-1) == '+')){
                $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, 'NEG');
            }else{
                $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, '');
            }
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->anti_hiv);
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->hbsag);
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->anti_hcv);
            $slr_start_col++;
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->syphilis);
            $slr_start_col++;
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->malaria);
            $slr_start_col++;
            if($v->status == 0){
                $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, 'Pending');    
            }elseif($v->status == 1){
                $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, 'Passed');
            }
            elseif($v->status == 2){
                $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, 'Failed');
            }
            
            $slr_start_col++;
            $slr_start_col++;
            $objPHPExcel->getActiveSheet()->setCellValue($slr_start_col.$start_row, $v->date_released);
            $slr_start_col++;
            $start_row++;
            $i++;
            $slr_start_col = 'BW';
        }

        $objPHPExcel->setActiveSheetIndex(0);

        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filenames.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    function getBloodTypeById($id){
        $res = DB::table('blood_types')->where('id', $id)->first();
        $name = ' ';
        if(count($res)>0){
            $name = $res->name;
        }
        return $name;
    }



    function getReportBloodDonor(){
        $available_year =  DB::select('SELECT YEAR(`donation_date`) AS years
                            FROM `blood_donors_history`
                            GROUP BY years
                            ORDER BY years');

        return view('reports.blood_donors.index')->with(['available_year'=> $available_year, 'side_active'=>'report_blood_donor']);
    }


    function postReportBloodDonor(Request $req){
        $user = Auth::user();
        $dates = $req->all();
        unset($dates['_token']);

        $start_date = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s',strtotime($dates['year']."-".$dates['month']."-01"))));
        $end_date = date('Y-m-t', strtotime($start_date)). " 23:59:59";
        $res = DB::table('blood_donors_history as a')
            ->select('b.last_name','b.first_name','b.middle_name','b.is_new', DB::raw("CONCAT(b.street,',', b.barangay,',',b.town_municipality,',',b.`province_city`) AS address"), 'b.cell_no', 'b.age','b.gender','a.*')
            ->join('blood_donors as b', 'a.donor_id', '=', 'b.id')            
            ->whereBetween('a.donation_date',[$start_date, $end_date])->get();

        return $res;

        $filenames = "test ".date('His');
        $objPHPExcel = PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objPHPExcel->load(__DIR__."/../../../templates/template_donors_register.xlsx"); // Empty Sheet
        // $objPHPExcel = PHPExcel_IOFactory::load();
        $objPHPExcel->setActiveSheetIndex(0);
        $start_col_accep = 'B';
        $start_row_accep = 11;
        $start_col_def = 'Y';
        $start_row_def = 11;
        $no_col = 'A';
        $no_start_row = 11;
        $date_year = date('F Y', strtotime($start_date));
        $objPHPExcel->getActiveSheet()->setCellValue("I1",  Auth::user()->name);  
        $objPHPExcel->getActiveSheet()->setCellValue("AF1",  Auth::user()->name);  
        $objPHPExcel->getActiveSheet()->setCellValue("I5",  $date_year);  
        $objPHPExcel->getActiveSheet()->setCellValue("AF5",  $date_year);  
        $i = 1;
        foreach ($res as $v) { 
            $objPHPExcel->getActiveSheet()->setCellValue($no_col.$no_start_row,  $i);           
            if($v->is_passed == 1){
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, date('M d', strtotime($v->donation_date)));
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, 'N/A');
                $start_col_accep++;
                if($v->is_new == 1){
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, "new");
                
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, "old");    
                }
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->source);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->category);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->donor_no_per_mbd);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->last_name);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->first_name);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->middle_name);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->address);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->cell_no);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->age);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->gender);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->abo);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->rh);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->serial_number);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->no_bag_collected);
                $start_col_accep++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, $v->no_of_times);
                $start_col_accep++;
                if($v->no_of_times >= 2)
                {
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, "YES");
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, "");
                }
                $start_col_accep++;
                if($v->no_of_times <= 3)
                {
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, "YES");
                    
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_accep.$start_row_accep, "");
                }
                $start_col_accep++;
                $start_col_accep = 'B';
                $start_row_accep++;
                $i++;
            }elseif($v->is_passed == 2){
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, date('M d', strtotime($v->donation_date)));
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, 'N/A');
                $start_col_def++;
                if($v->is_new == 1){
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, "new");
                }else{
                    $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, 'old');
                }
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->source);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->category);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->donor_no_per_mbd);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->last_name);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->first_name);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->middle_name);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->address);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->cell_no);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->age);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->gender);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->remarks);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->reason);
                $start_col_def++;
                $objPHPExcel->getActiveSheet()->setCellValue($start_col_def.$start_row_def, $v->suggestion);
                $start_col_def++;

                $start_col_def= 'Y';
                $start_row_def++;
                $i++;

            }            
            $no_start_row++;
            
            
        }

        $objPHPExcel->setActiveSheetIndex(0);

        
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filenames.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

    }

    /************************************************************************************/
    /*********************************** Bloodbank **************************************/
    /************************************************************************************/
    public function romanic_number($integer, $upcase = true) 
    { 
        $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
        $return = ''; 
        while($integer > 0) 
        { 
            foreach($table as $rom=>$arb) 
            { 
                if($integer >= $arb) 
                { 
                    $integer -= $arb; 
                    $return .= $rom; 
                    break; 
                } 
            } 
        }
        return $return; 
    } 

    function getReportBloodbank(){
        $user = Auth::user();
        return view('reports.blood_bank.index')->with([
            'user_info'=>$user,
            'side_active'=>'report_bloodbank'
        ]);
    }

    public function generatePDF(){
        ini_set('memory_limit', '-1');
        set_time_limit(500);
        $user = Auth::user();
        $dates = explode(" - ", $_POST["dateFinal"]);
        // create new PDF document
        PDF::setFooterCallback(function($pdf) {
            // Position at 15 mm from bottom
            $pdf->SetY(-15);
            // Set font
            $pdf->SetFont('helvetica', 'I', 8);
            // Page number
            $pdf->Cell(0, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        });

        PDF::SetTitle('PHILIPPINE RED CROSS');
        PDF::SetCreator(PDF_CREATOR);
        PDF::SetAuthor($user->name);
        PDF::SetSubject($_POST['reportType']);
        PDF::SetAutoPageBreak(TRUE, 12);
        PDF::SetFont('helvetica', '', 7);
        PDF::AddPage('L', 'A4');
        PDF::Image('img/logo.png', 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, 'C M', false, false);
        PDF::setCellPaddings(2,0,0,0);
        PDF::SetFont('helvetica','B', 7);
        PDF::Cell(0, 0, 'PHILIPPINE RED CROSS', 0, 1, 'L', 0, '', 0);
        PDF::setCellPaddings(17,0,0,0);
        PDF::SetFont('helvetica','', 7);
        PDF::Cell(0, 0, $_POST['reportType'], 0, 1, 'L', 0, '', 0);
        if(count(explode('-', $dates[0])) > 1){
            PDF::Cell(0, 0, 'From ' . date('F d, Y', strtotime($dates[0])) . ' to ' . date('F d, Y', strtotime($dates[1])), 0, 1, 'L', 0, '', 0);
        } else {
            PDF::Cell(0, 0, 'From ' . date('F', strtotime($dates[0])) . ' ' . $dates[1], 0, 1, 'L', 0, '', 0);
        }
        PDF::Cell(0, 0, 'Processed by ' . $user->name, 0, 1, 'L', 0, '', 0);
        PDF::Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
        PDF::Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
        PDF::writeHTML($_POST["htmlHandle"], true, false, true, false, '');
        PDF::Output('bloodbankreservation_' . date('Y-m-d') . '.pdf');
    }

    public function postReportBloodbank(Request $req) {
        $user = Auth::user();
        $dates = explode(" - ", $_POST["date"]);
        $selectedAvailabilities = $_POST['chkbx-availability'];
        if(($key = array_search("ALL", $selectedAvailabilities)) !== false) {
            unset($selectedAvailabilities[$key]);
        }
        $converted = "";
        for($x = 1; $x <= count($selectedAvailabilities); $x++){
            $converted .= '"' . $selectedAvailabilities[$x] . ($x == count($selectedAvailabilities) ? '"' : '",');
        }
        $inventory = DB::select('SELECT
                                        t1.id AS id,
                                        t3.branch_name,
                                        t1.time_reserved,
                                        IFNULL(t1.time_released, "NA") AS time_released,
                                        t1.availability AS `availability`,
                                        CONCAT(t2.last_name, ", ", t2.first_name) AS patient_name,
                                        t1.remarks,
                                        t4.hospital_name,
                                        t5.name AS blood_type
                                FROM reservation t1
                                        JOIN patient t2
                                            ON t1.patient_id = t2.id
                                        JOIN branch t3
                                            ON t1.branch_id = t3.id
                                        JOIN hospitals t4
                                            ON t1.hospital_id = t4.id
                                        JOIN blood_types t5
                                            ON t1.blood_type = t5.id
                                WHERE t1.status = 1 AND
                                      t1.availability IN (' . $converted . ') AND 
                                      t1.date_created BETWEEN ? AND ?', array($dates[0], $dates[1]));

        for($x = 0; $x < count($inventory); $x++) {
            $components = DB::select('CALL sp_reservation_get_component_list(?)', array($inventory[$x]->id));
            $inventory[$x]->components = $components;
        }

        return $inventory;

    }
    
}