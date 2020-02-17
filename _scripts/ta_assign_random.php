<?php

require_once('rb.php');
/* Configure these two parameters for each lab task assignment */
$labName = "GL2";
$deadline = "2016-02-07 23:59:59";

/* This will change whenever rdb container restarts, and changes its ip */
$rdb_ip = "172.17.0.3";

/**** You need not have to change anything below *************************/

echo "Enter the mysql dbadmin password: ";
$pwd = fgets(STDIN);
try {
	$hoststr = "mysql:host=".$rdb_ip.";port=3306;dbname=its"; 
    R::setup($hoststr, 'prutor', trim($pwd));
    //    R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
}

/* Select all problems to be graded */
$query = "SELECT a.id, a.event_id from assignment as a, event as e,account as acc  where a.event_id=e.id and acc.id=a.user_id and e.name = '".$labName."' and a.question in (1,2,3,4) and acc.section rlike 's[1-4]' ORDER BY a.id";
//$query = "SELECT t.reference, t.event_id FROM `task` as t, `account` as a WHERE a.roll in (153050089, 153050098) and a.id = t.assignee";


$assignList=R::getAssocRow($query);


echo shuffle($assignList)."\n";

$numassign= count($assignList);
echo "NumAssign = ".$numassign."\n";

/* All TAs */
// $query = "SELECT id  from account where `admin_role`='2'";
/* Select  TAs by roll number */
$taRolls = "'143050001', '143050096', '143050082', '143050094', '153050005', '153050025', '153050038', '153050059', '153050030', '153050051', '153050074', '153050066', '153050073', '153050084', '153050086', '153050079', '153050043', '153050092', '153050093', '143050090'";

$query = "SELECT id  from account where `roll` in (" .$taRolls. ")";

$talist=R::getAssocRow($query);

$numta=count($talist);
$perta=($numassign/$numta);
$start = 1.0;
$stop = $perta;
echo "NumTAs = ".$numta."\n";
echo "perTA = ".$perta."\n";

for($x=0;$x<$numta;$x++){
	$startInt = (int)$start;
	$stopInt = (int)$stop;

	echo $talist[$x]['id']." : ".$startInt." -> ".$stopInt.", ".$assignList[$startInt - 1]['id']." -> ".$assignList[$stopInt - 1]['id']."\n";
	// echo $talist[$x]['id']." : ".$startInt." -> ".$stopInt.", ".$assignList[$startInt - 1]['reference']." -> ".$assignList[$stopInt - 1]['reference']."\n";

	for($i=$startInt; $i<=$stopInt; $i++) {
		$query = "INSERT INTO task (assignee, deadline, reference,type,event_id) 
				  VALUES (:assignee, '$deadline', :assignID, 'GRADING', :event_id )";

		// echo $query;
	        // For each question
		
		R::exec($query, array(
			':assignee'=>$talist[$x]['id'],
			':assignID'=>$assignList[$i - 1]['id'], // Indexing starts from 0. Hence the i-1
			':event_id'=>$assignList[$i - 1]['event_id']
		));
 	}

	$start = $stop + 1.0;
	if($x == ($numta -2) ) $stop = $numassign;
	else	$stop = $stop + $perta;
}
/*

*/
?>
