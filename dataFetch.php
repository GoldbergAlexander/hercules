<?php 
require_once 'dbconnect.php';
include 'security.php';

/* dataFetch()
	$detail -- Year View, Month View, or Day View
		Some data is only reachable at certain levels
	$startDate -- Point to Start -- Inlcusive
	$endDate -- Point to End -- Inclusive
		Date:
			- Dates must be given as valid date objects
				- They will be reduced or expanded as required
	$location -- if not given the data will not be grouped by location and totals will be given
		- if Given it should be in text as shown in the location table
	$data -- the type of data to return
		- this will mostly be revenue, however, for daily data it could be anything entered
	
*/
//Error check not done
function dataFetch($detail, $startDate, $endDate, $location, $data){
	global $con;
	$results = array(); // Return Variable
	
	if($detail == "day"){
		if($location != NULL){
			//Prepare
			if(!$stmt = $con->prepare("
				SELECT `Date`, SUM(CheckCount + CashCount + CardUnit)
				AS Revenue
				FROM DailyRevenueEntry
				WHERE `Date` BETWEEN ? AND ?
				AND DailyRevEntry_idLocation = 
					(SELECT idLocation
					FROM Location
					WHERE `Name` = ?)
				GROUP BY `Date`					
				")){
				echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
			}
			//Bind
			if(!$stmt->bind_param("sss",$startDate,$endDate,$location)){
				echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if(!$stmt->execute()){
				echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Bind Result
			if(!$stmt->bind_result($date,$revenue)){
				echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
			}
			while($stmt->fetch()){
				$arr = array(
				'label' => $date,
				'value' => $revenue);
				array_push($results,$arr); // Push the data to the results
			}
			$stmt->close();

		}else{	
			//Prepare
			if(!$stmt = $con->prepare("
				SELECT `Date`, SUM(CheckCount + CashCount + CardUnit)
				AS Revenue
				FROM DailyRevenueEntry
				WHERE `Date` BETWEEN ? AND ?
				GROUP BY `Date`					
				")){
				echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
			}
			//Bind
			if(!$stmt->bind_param("ss",$startDate,$endDate)){
				echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if(!$stmt->execute()){
				echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Bind Result
			if(!$stmt->bind_result($date,$revenue)){
				echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
			}
			while($stmt->fetch()){
				$arr = array(
				'label' => $date,
				'value' => $revenue);
				array_push($results,$arr); // Push the data to the results
			}
			$stmt->close();
			}
	}else if($detail == "month"){		
		
		$startYear = date('Y',strtotime($startDate));
		$endYear = date('Y',strtotime($endDate));
		
		$startMonth = date('n',strtotime($startDate));
		$startMonthMinusOne = $startMonth;
		
		$endMonth = date('n',strtotime($endDate));
		$endMonthPlusOne = $endMonth + 2;

		if($location != NULL){
			//Prepare
			if(!$stmt = $con->prepare("
				SELECT `Year`,`Month`, SUM(Revenue)
				AS Revenue
				FROM RevenueMonthly
				WHERE ((`Year` > ? AND `Year` < ?) 
				OR (`Year` = ? AND `Month` > ?)
				OR (`Year` = ? AND `Month` < ?))  
				AND RevMonth_idLocation =
					(SELECT idLocation
					FROM Location
					WHERE `Name` = ?)
				GROUP BY `Year`,`Month`					
				")){
				echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
			}
			//Bind
			if(!$stmt->bind_param("iiiiiis",
					$startYear,$endYear,
					$startYear,$startMonthMinusOne,
					$endYear,$endMonthPlusOne,
					$location)){
				echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if(!$stmt->execute()){
				echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Bind Result
			if(!$stmt->bind_result($year,$month,$revenue)){
				echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
			}
			while($stmt->fetch()){
				$arr = array(
				'label' => date("M-Y",mktime(0,0,0,$month,0,$year)),
				'value' => $revenue);
				array_push($results,$arr); // Push the data to the results
			}
			$stmt->close();

		}else{	
			//Prepare
			if(!$stmt = $con->prepare("
				SELECT `Year`,`Month`, SUM(Revenue)
				AS Revenue
				FROM RevenueMonthly
				WHERE (`Year` > ? AND `Year` < ?) 
				OR (`Year` = ? AND `Month` > ?)
				OR (`Year` = ? AND `Month` < ?)  
				GROUP BY `Year`,`Month`					
				")){
				echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
			}
			//Bind
			//Bind
			if(!$stmt->bind_param("iiiiii",
					$startYear,$endYear,
					$startYear,$startMonthMinusOne,
					$endYear,$endMonthPlusOne)){

				echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if(!$stmt->execute()){
				echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Bind Result
			if(!$stmt->bind_result($year,$month,$revenue)){
				echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
			}
			while($stmt->fetch()){
				$arr = array(
				'label' => date("M-Y",mktime(0,0,0,$month,0,$year)),
				'value' => $revenue);
				array_push($results,$arr); // Push the data to the results
			}
			$stmt->close();
		}
	}else if($detail == "year"){
		$startYear = date('Y',strtotime($startDate));
		$endYear = date('Y',strtotime($endDate));


		if($location != NULL){
			//Prepare
			if(!$stmt = $con->prepare("
				SELECT `Year`, SUM(Revenue)
				AS Revenue
				FROM RevenueYearly
				WHERE `Year` BETWEEN ? AND ?
				AND RevYear_idLocation =
					(SELECT idLocation
					FROM Location
					WHERE `Name` = ?)
				GROUP BY `Year`					
				")){
				echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
			}
			//Bind
			if(!$stmt->bind_param("sss",$startYear,$endYear,$location)){
				echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if(!$stmt->execute()){
				echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Bind Result
			if(!$stmt->bind_result($year,$revenue)){
				echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
			}
			while($stmt->fetch()){
				$arr = array(
				'label' => date("Y",mktime(0,0,0,0,0,$year)),
				'value' => $revenue);
				array_push($results,$arr); // Push the data to the results
			}
			$stmt->close();

		}else{	
			//Prepare
			if(!$stmt = $con->prepare("
				SELECT `Year`, SUM(Revenue)
				AS Revenue
				FROM RevenueYearly
				WHERE `Year` BETWEEN ? AND ?
				GROUP BY `Year`				
				")){
				echo "Prepare Failed: (" . $con->errno . ") " . $con->error;
			}
			//Bind
			if(!$stmt->bind_param("ss",$startYear,$endYear)){
				echo "Bind Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Execute
			if(!$stmt->execute()){
				echo "Execute Failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			//Bind Result
			if(!$stmt->bind_result($year,$revenue)){
				echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
			}
			while($stmt->fetch()){
				$arr = array(
				'label' => date("Y",mktime(0,0,0,0,0,$year)),
				'value' => $revenue);
				array_push($results,$arr); // Push the data to the results
			}
			$stmt->close();
		}
	}
	return $results;	
}
?>
