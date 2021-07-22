<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;

class ForecastingController extends Controller
{
    public function index(){
    	$user = \Auth::user();
    	// $patient = DB::table('patient')->get();
    	return view('forecast.index')->with(['user_info'=>$user,'side_active'=>'forecast']);
    }

    public function generateData(){
        $response = array();
        $patients = DB::table('patient')->select('id')->get();
        $donors = DB::table('blood_donors')->select('id')->get();
        $branches = DB::table('branch')->select('id')->get();
        $hospitals = DB::table('hospitals')->select('id')->get();
        $bloodTypes = DB::table('blood_types')->select('id')->get();
        $components = DB::table('component')->select('id')->get();

        
            for($x = 0; $x < 10; $x++){
                $dateToInput = "";
                /* ASCENDING DATE */
                // try{
                //     $latestDate = DB::table('bloodbank_storage')
                //                     ->select(DB::raw('DATE(date_created) AS date_created'))
                //                     // ->whereRaw('YEAR(date_created) = "2016" AND id != 16')
                //                     ->orderBy('date_created', 'DESC')
                //                     ->limit(1)
                //                     ->get()[0]
                //                     ->date_created;
                //     $dateToInput = date('Y-m-d H:i:s', strtotime($latestDate . " +" . mt_rand(0, 1) . " days"));    
                // } catch(\Exception $e) {
                //     $dateToInput = date('2015-01-01 00:00:00');
                // }

                /* RANDOM DATE */
                $rYear = mt_rand(2015, 2017);
                $rMonth = mt_rand(1, 12);
                $dateToInput = $rYear . "-" . $rMonth . "-" . mt_rand(1, cal_days_in_month(CAL_GREGORIAN, $rMonth, $rYear));
                $timesInDay = mt_rand(1, 5);
                for($z = 0; $z < $timesInDay; $z++){
                    try{
                        $bloodbank_storage_id = DB::table('bloodbank_storage')->insertGetId(
                            [
                                'patient_id' => $donors[mt_rand(0, (count($donors) - 1))]->id,
                                'branch_id' => $branches[mt_rand(0, (count($branches) - 1))]->id,
                                'hospital_id' => $hospitals[mt_rand(0, (count($hospitals) - 1))]->id,
                                'blood_type' => $bloodTypes[mt_rand(0, (count($bloodTypes) - 1))]->id,
                                'time_reserved' => date("Y-m-d H:i:s"),
                                'date_created' => $dateToInput,
                                'status' => 0
                            ]
                        );
                    } catch(\Exception $e) {
                        return "2: " . $e->getMessage();
                    }

                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $serial_no = '';
                    for ($i = 0; $i < 10; $i++) {
                        $serial_no .= $characters[rand(0, $charactersLength - 1)];
                    }

                    try{
                        DB::table('bloodbank_storage_details')->insert(
                            [
                                'serial_no' => $serial_no,
                                'bloodbank_storage_id' => $bloodbank_storage_id,
                                'extraction_date' => date("Y-m-d H:i:s"),
                                'anti_hiv' => mt_rand(0, 1),
                                'hbsag' => mt_rand(0, 1),
                                'anti_hcv' => mt_rand(0, 1),
                                'syphilis' => mt_rand(0, 1),
                                'malaria' => mt_rand(0, 1)
                            ]
                        );
                    } catch(\Exception $e) {
                        return "3: " . $e->getMessage();
                    }

                    // try {
                    //     $id = DB::table('reservation')->insertGetId(
                    //         [
                    //             'patient_id' => $patients[mt_rand(0, (count($patients) - 1))]->id,
                    //             'branch_id' => $branches[mt_rand(0, (count($branches) - 1))]->id,
                    //             'hospital_id' => $hospitals[mt_rand(0, (count($hospitals) - 1))]->id,
                    //             'blood_type' => $bloodTypes[mt_rand(0, (count($bloodTypes) - 1))]->id,
                    //             'serology' => $bloodbank_storage_id,
                    //             'time_reserved' => date("Y-m-d, H:i:s"),
                    //             'time_released' => date("Y-m-d, H:i:s"),
                    //             'availability' => "RELEASED",
                    //             'date_created' => $dateToInput,
                    //             'status' => 1
                    //         ]
                    //     );
                    // } catch(\Exception $e) {
                    //     return "4: " . $e->getMessage();
                    // }

                    // $maxComponents = mt_rand(1, 5);
                    // for($y = 0; $y < $maxComponents; $y++){
                    //     try{
                    //         DB::table('reservation_component')->insert(
                    //             [
                    //                 'reservation_entry_id' => $id,
                    //                 'component_id' => $components[mt_rand(0, (count($components) - 1))]->id,
                    //                 'qty' => mt_rand(1, 3)
                    //             ]
                    //         );
                    //     } catch(\Exception $e) {
                    //         return "5: " . $e->getMessage();
                    //     }
                    // }
                }
            }
            return "Inserted on:" + $dateToInput;
    }

    public function getForecast(){
        $chartPattern = $_GET["chart_pattern"];
        $dateInput = $_GET["date_input"];
        $reason = isset($_GET["reason"]) ? $_GET["reason"] : "";
        $reasonsArray = array(
            "anti_hiv",
            "hbsag",
            "anti_hcv",
            "syphilis",
            "malaria"
        );
        try {
            $resultsArray = array();
            $labelsArray = array();
            $reasonsArray = array();

            $today = date('Y-m-01');
            $end = date('Y-m-01', strtotime($dateInput));
            $diff = abs(strtotime($end) - strtotime($today));
            $predict = $chartPattern == 12 ? floor($diff / (30*60*60*24)) : ceil($diff / (365*60*60*24));
            for($x = 1; $x <= $predict; $x++){
                $label = "";
                switch($chartPattern){
                    case "12":
                        $label = date('F', strtotime($today . " +" . $x . " month"));
                        break;
                    case "1";
                        $label = date('Y', strtotime($today . " +" . $x . " year"));
                        break;
                }
                array_push($labelsArray, $label);
            }

            // generate forecast
            if($_GET["blood_type"] == 0){
                for($z = 1; $z < 9; $z++){
                    array_push($resultsArray, $this->generateForecast($z, $chartPattern, $predict, $reason));
                }
            } else {
                array_push($resultsArray, $this->generateForecast($_GET["blood_type"], $chartPattern, $predict, $reason));
            }

            // set fillers
            $highestLength = 0;
            for($x = 0; $x < count($resultsArray); $x++){
                $highestLength = count($resultsArray[$x]) > $highestLength ? count($resultsArray[$x]) : $highestLength;
            }
            
            for($x = 0; $x < count($resultsArray); $x++){
                $diff = ($highestLength - count($resultsArray[$x]));
                for($y = 0; $y < $diff; $y++){
                    array_push($resultsArray[$x], 0.1);
                }
            }

            $jsonResponse = array(
                'labels' => $labelsArray,
                'data' => $resultsArray,
            );

            return $jsonResponse;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getForecastReason(){
        try{
            $chartPattern = $_GET["chart_pattern"];
            $reason = isset($_GET["reason"]) ? $_GET["reason"] : "";
            $bloodTypeId = $_GET["blood_type"];
            $today = date('Y-m-01');
            $end = date('Y-m-01', strtotime($_GET["date_input"]));
            $diff = abs(strtotime($end) - strtotime($today));
            $predict = $chartPattern == 12 ? floor($diff / (30*60*60*24)) : ceil($diff / (365*60*60*24));
            $reasonsArray = array(
                "anti_hiv",
                "hbsag",
                "anti_hcv",
                "syphilis",
                "malaria"
            );
            $resultsArray = array();
            $filledData = array();
            $highestReason = 0;
            $highestReasonName = "";
            for($y = 0; $y < count($reasonsArray); $y++){
                $results = DB::select('CALL sp_forecast_get_sakit(?, ?, ?)', array($bloodTypeId, $chartPattern, $reasonsArray[$y]));
                if(count($results) > 1){
                    for($x = 0; $x < count($results); $x++) {
                        array_push($filledData, $results[$x]->count);
                    }
                    $vForecast = new CMathForecasting($filledData, 1);
                    $vResult = $vForecast->predict($predict);
                } else {
                    $vResult = array();
                }
                if(count($vResult) > 0){
                    if(ceil($vResult[$predict - 1]) > $highestReason){
                        $highestReason = ceil($vResult[$predict - 1]);
                        $highestReasonName = $reasonsArray[$y];
                    }
                } else {
                    $highestReasonName = "NULL";
                }
                //array_push($resultsArray, $results);
            }
            array_push($resultsArray, $highestReasonName . "-" . ($bloodTypeId - 1));
            return $resultsArray;
        } catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function generateForecast($bloodTypeId, $chartPattern, $predict, $reason){
        $filledData = array();
        $vResult = array();
        if($reason != ""){
            $result = DB::select('CALL sp_forecast_get_monthly(?, ?, ?)', array($bloodTypeId, $chartPattern, $reason));
        } else {
            $sql = $chartPattern == 12 ? 'CALL sp_forecast_get_monthly(?, ?)' : 'CALL sp_forecast_get_yearly(?, ?)';
            $result = DB::select($sql, array($bloodTypeId, $chartPattern));
        }
        if(count($result) > 1){
            // set data and filler data
            for($x = 0; $x < count($result); $x++) {
                // set data
                array_push($filledData, $result[$x]->count);
            }
            $vForecast = new CMathForecasting($filledData, $chartPattern);
            $vResult = $vForecast->predict($predict);
        }

        return $vResult;
    }
}

class CRegressionLinear {
    private $mDatas; // input data, array of (x1,y1);(x2,y2);... pairs, or could just be a time-series (x1,x2,x3,...)
    /** constructor */
    function __construct($pDatas) {
      $this->mDatas = $pDatas;
    }
    /** compute the coeff, equation source: http://people.hofstra.edu/faculty/Stefan_Waner/RealWorld/calctopic1/regression.html */
    function calculate() {
      $n = count($this->mDatas);
      $vSumXX = $vSumXY = $vSumX = $vSumY = 0;
      //var_dump($this->mDatas);
      $vCnt = 0; // for time-series, start at t=0
      foreach ($this->mDatas AS $vOne) {
        if (is_array($vOne)) { // x,y pair
          list($x,$y) = $vOne;
        } else { // time-series
          $x = $vCnt; $y = $vOne;
        } // fi
        $vSumXY += $x*$y;
        $vSumXX += $x*$x;
        $vSumX += $x;
        $vSumY += $y;
        $vCnt++;
      } // rof
      $vTop=($n*$vSumXY - $vSumX*$vSumY);
      $vBottom = ($n*$vSumXX - $vSumX*$vSumX);
      $a = $vBottom != 0 ? $vTop/$vBottom:0;
      $b = ($vSumY - $a*$vSumX)/$n;
      //var_dump($a,$b);
      return array($a,$b);
    }
    /** given x, return the prediction y */
    function predict($x) {
      list($a,$b) = $this->calculate();
      $y = $a*$x+$b;
      return $y;
    }
  }

  class CMathForecasting {
     private $mPastDatas; // array of data (eg: x1,x2,x3,x4...)
     private $mNumSeasons; // the number of seasons to consider (eg: 4 quarters/year, 7 days/week, 24 hours/day)
     /** constructor */
     function __construct($pPastDatas, $pNumSeasons) {
        $this->mPastDatas = $pPastDatas;
        $this->mNumSeasons = $pNumSeasons;
     }
    /** compute the n-season moving average */
     function computeSMA() {
        $vSMA = array();
        for ($i=0;$i<count($this->mPastDatas);$i++) {
           if ($i + $this->mNumSeasons-1 >= count($this -> mPastDatas)) { // out of bound, done
              break;
           } // fi
           $vSum = 0;
           for ($j=0;$j<$this->mNumSeasons;$j++) {
               $vSum += $this->mPastDatas[$i+$j];
//				$vSum += $this->mPastDatas[$i+$j];
           } // rof
           $vSMA[] = $vSum/$this->mNumSeasons;
        } // rof
        return $vSMA;
     }
     /** compute centered moving average from the n-season moving average */
     function computeCMA($pSMA) {
        $vCMA = array();
        for ($i=0;$i<count($pSMA);$i++) {
           if ($i+1>=count($pSMA)) { // out of bound, done
              break;
           } // fi
           $vCMA[] = ($pSMA[$i]+$pSMA[$i+1])/2;
        } // rof
        return $vCMA;
     }
     /** season irregularity */
     function computeNoises($pCMA) {
        $vNoises = array();
        for ($i=0;$i<count($pCMA);$i++) {
           $vStarting = floor($this->mNumSeasons/2);
           $vNoises[] = $this->mPastDatas[$i+$vStarting]/$pCMA[$i];
        } // rof
        return $vNoises;
     }
     /** comment */
     function computeSeasonIndices($pNoises) {
        $vIndices = array();
        for ($i=0;$i<$this->mNumSeasons;$i++) {
           $vSum = array();
           for ($j=$i;$j<count($pNoises);$j+=$this->mNumSeasons) {
              $vSum[] = $pNoises[$j];
           } // rof
           $vStarting = (floor($this->mNumSeasons/2)+$i)%$this->mNumSeasons;
           $vIndices[$vStarting] = array_sum($vSum)/count($vSum);
        } // rof
        ksort($vIndices);
        // also adjust these season indices
        $vSum = array_sum($vIndices);
        for ($i=0;$i<count($vIndices);$i++) {
           $vIndices[$i] = $vIndices[$i]*$this->mNumSeasons/$vSum;
        } // rof
        return $vIndices;
     }
     /** comment */
     function computeDeSeasonalized($pSeasonIndex) {
        $vDatas = array();
        for ($i=0;$i<count($this->mPastDatas);$i++) {
           $vDatas[] = $this->mPastDatas[$i]/$pSeasonIndex[$i%$this->mNumSeasons];
           $_SESSION["err1"] = $this->mPastDatas[$i];
           $_SESSION["err2"] = $pSeasonIndex[$i%$this->mNumSeasons];
        } // rof
        return $vDatas;
     }
     /** how many future periods to predict */
     function predict($pNumFuturePeriods) {
        $vSMA = $this->computeSMA();
        if ($this->mNumSeasons%2==0) { // even
           $vCMA = $this->computeCMA($vSMA);
        } else { // odd, nSMA=CMA
           $vCMA = $vSMA;
        } // fi
        $vNoises = $this->computeNoises($vCMA);
        $vIndices = $this->computeSeasonIndices($vNoises);
        $vDeSeasonalized = $this->computeDeSeasonalized($vIndices);
        // perform regression to get the trend line
        $vRegression = new CRegressionLinear($vDeSeasonalized);
        list($vXVar,$vIntercept) = $vRegression->calculate();
        $vForecast = array();
        for ($i=0;$i<$pNumFuturePeriods;$i++) {
           $vForecast[] = $vIntercept + $vXVar * (count($this->mPastDatas)+$i);
        } // rof
        // have to re-seasonalized these values
        for ($i=0;$i<count($vForecast);$i++) {
           $vForecast[$i] = round($vForecast[$i]*$vIndices[(count($this->mPastDatas)+$i)%$this->mNumSeasons], 2);
        } // rof
        return $vForecast;
    }
  }

  // previous forecasting

  /*
  $firstDateDayOfWeek = date("w", strtotime($result[0]->date)) == 0 ? 8 : date("w", strtotime($result[0]->date));
            $lastDateDayOfWeek = date("w", strtotime($result[count($result) - 1]->date)) == 0 ? 8 : date("w", strtotime($result[count($result) - 1]->date));
            
            // set prelim filler dates
            if($firstDateDayOfWeek != 1){
                for($x = 1; $x < $firstDateDayOfWeek; $x++){
                    array_push($filledData, 0.1);
                }
            }

                
            // set dates and filler dates
            for($x = 0; $x < count($result); $x++){
                array_push($filledData, $result[$x]->count);
                
                if(($x + 1) < count($result)){ // kung meron pa kasunod
                    $dateDiff = (date_diff(date_create($result[$x]->date), date_create($result[$x + 1]->date))->format('%d days') - 1);
                    if($dateDiff > 0){
                        for($y = 0; $y < $dateDiff; $y++){
                            array_push($filledData, 0.1);
                        }
                    }
                }
            }
            // set post filler dates
            if($lastDateDayOfWeek != 8){
                for($x = $lastDateDayOfWeek; $x < 7; $x++){
                    array_push($filledData, 0.1);
                }
            }
            
            $vForecast = new CMathForecasting($filledData, $chartPattern); 
            $vResult = $vForecast->predict($chartPattern);
   */

   
                // if(array_key_exists(($x + 1), $result)) { // kung meron pa kasunod
                //     $diff = abs(strtotime($result[$x + 1]->date) - strtotime($result[$x]->date));
                //     $years = floor($diff / (365*60*60*24));
                //     $quarters = floor($diff / (90*60*60*24));
                //     $months = floor($diff / (30*60*60*24));
                //     $weeks = floor($diff / (7*60*60*24));
                //     $days = floor($diff / (60*60*24));

                //     // set filler data
                //     if($days > 0){
                //         switch($chartPattern){
                //             case "1":
                //                 for($y = 1; $y < $days; $y++) {
                //                     array_push($filledData, 0.1);
                //                 }
                //                 break;
                //             case "7":
                //                 for($y = 1; $y < $weeks; $y++) {
                //                     array_push($filledData, 0.1);
                //                 }
                //                 break;       
                //         }
                //     }
                // }