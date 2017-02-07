<?php 
require_once '/var/www/html/database/dbconnect.php';
require_once '/var/www/html/security/security.php';
secure();
/* dataFetch()
	$detail -- Year View, Month View, or Day View
		Some data is only reachable at certain levels
	$startDate -- Point to Start -- Inlcusive
	$endDate -- Point to End -- Inclusive
		Date:
			- Dates must be given as valid date objects
				- They will be reduced or expanded as require_onced
	$location -- if not given the data will not be grouped by location and totals will be given
		- if Given it should be in text as shown in the location table
	$data -- the type of data to return
		- this will mostly be revenue, however, for daily data it could be anything entered
	
*/
//Error check not done
function dataFetch($detail, $startDate, $endDate, $location, $data){
	global $con;
	$results = array(); // Return Variable
	$location = "%" . $location . "%";
	if($detail == "day"){
		//Dailies On Hold
		//Prepare
		if(!$stmt = $con->prepare("
			SELECT Name,`Date`, SUM(CashCount + CheckCount + CardUnit)
			AS Revenue
			FROM RevenueDaily
			INNER JOIN Location
			ON RevDaily_idLocation = idLocation
			WHERE (`Date` BETWEEN ? AND ?)
			AND Name LIKE ?
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
		if(!$stmt->bind_result($name,$date,$revenue)){
			echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
		}
		while($stmt->fetch()){
			$arr = array(
			'label' => $date,
			'value' => $revenue);
			array_push($results,$arr); // Push the data to the results
		}
		$stmt->close();

	}else if($detail == "month"){		
		
		$startYear = date('Y',strtotime($startDate));
		$endYear = date('Y',strtotime($endDate));
		
		$startMonth = date('n',strtotime($startDate));
		$startMonthMinusOne = $startMonth;
		
		$endMonth = date('n',strtotime($endDate));
		$endMonthPlusOne = $endMonth + 2;

		//Prepare
		if(!$stmt = $con->prepare("
			SELECT Name,`Year`,`Month`, SUM(Revenue)
			AS Revenue
			FROM RevenueMonthly
			INNER JOIN Location
			ON RevMonth_idLocation = idLocation
			WHERE ((`Year` > ? AND `Year` < ?) 
			OR (`Year` = ? AND `Month` > ?)
			OR (`Year` = ? AND `Month` < ?))  
			AND Name LIKE ?
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
		if(!$stmt->bind_result($name,$year,$month,$revenue)){
			echo "Bind Result Failed: (" .$stmt->errno . ") " . $stmt->error;
		}
		while($stmt->fetch()){
			$arr = array(
			'label' => date("M-Y",mktime(0,0,0,$month,0,$year)),
			'value' => $revenue);
			array_push($results,$arr); // Push the data to the results
		}
		$stmt->close();

	}else if($detail == "year"){
		$startYear = date('Y',strtotime($startDate));
		$endYear = date('Y',strtotime($endDate));

		//Prepare
		if(!$stmt = $con->prepare("
			SELECT Name,`Year`, SUM(Revenue)
			AS Revenue
			FROM RevenueYearly
			INNER JOIN Location
			ON RevYear_idLocation = idLocation
			WHERE (`Year` BETWEEN ? AND ?)
			AND Name LIKE ?
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
		if(!$stmt->bind_result($name,$year,$revenue)){
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
	return $results;	
}
?>
