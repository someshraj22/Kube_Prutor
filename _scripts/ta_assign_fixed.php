<?php

// ---- Begin Mods ----

$eventName = "LAB-1";
$deadline = "2016-01-17 23:59:59";
$filenameMapping = "taStudent.csv";
$checkSubmitted = 1;

// ---- End   Mods ----

require_once('rb.php');

/* DB connection */
echo "Enter the mysql root password: ";
$pwd = fgets(STDIN);
try {
    R::setup('mysql:host=172.17.0.4;port=3306;dbname=its', 'prutor', trim($pwd));
    //    R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
}


// Read CSV
function readCSV($filenameMapping){ // studentRoll, taRoll
	$taStudentArr = array();
	$talist = array();

	$file = fopen($filenameMapping, "r");
	if(!$file) die($filenameMapping . " doesn't exist in curr dir");

	while(! feof($file))
	{
		$row = fgetcsv($file);
		$studRoll = trim($row[0]); // studentRoll, taRoll
		$taRoll = trim($row[1]);

		if(! in_array($taRoll, $talist)) {
			array_push($talist, $taRoll);
			$taStudentArr[$taRoll] = array();
		}
		array_push($taStudentArr[$taRoll], $studRoll);
		
	}
	fclose($file);
	return $taStudentArr;
}

$taStudentArr = readCSV($filenameMapping);
echo "TA => Students \n ";
print_r($taStudentArr);

/* Foreach TA => Students */
foreach($taStudentArr as $taRoll => $studRolls){
	$studRollsStr = implode(",", $studRolls);

	/* Select all problems to be graded */
	$query = "SELECT a.id, a.event_id from assignment as a, event as e,account as acc  
			  where a.event_id=e.id and acc.id=a.user_id and acc.type='STUDENT' 		
			  and a.is_submitted=:checkSubmitted and acc.roll in (" . $studRollsStr . ")
			  and e.name =:eventName ORDER BY a.problem_id";

	$assignList=R::getAssocRow($query , array(
			':eventName'=>$eventName,
			':checkSubmitted'=>$checkSubmitted
		));

	
	//echo shuffle($assignList)."\n";
	$numassign= count($assignList);
	

	/* Fetch the TAs */
	$query = "SELECT id from account where roll=:roll
	          order by id";

	$talist=R::getAssocRow($query, array(
			':roll' => $taRoll
		));
	$taID = $talist[0]['id']; // Pick first TA (assuming Roll is unique anyways)
	print "TA-roll=[$taRoll], TA-ID=[$taID] => NumAssign=[$numassign] \n";

	/* Assign the problem for grade */
	for($i=0; $i<$numassign; $i++) {
		$query = "INSERT INTO task (assignee, deadline, reference,type,event_id) 
				  VALUES (:assignee, :deadline, :assignID, 'GRADING', :event_id )";

		// echo $query;
	        // For each question
		
		R::exec($query, array(
			':assignee'=>$taID,
			':assignID'=>$assignList[$i]['id'], // Indexing starts from 0. Hence the i-1
			':event_id'=>$assignList[$i]['event_id'],
			':deadline'=>$deadline
		));
 	}
}

/*

*/
?>
