<?php

require_once('rb.php');
$labName = "LabExam2A";
$deadline = "2015-11-19 23:59:59";

echo "Enter the mysql dbadmin password: ";
$pwd = fgets(STDIN);
try {
    R::setup('mysql:host=172.17.0.17;port=3306;dbname=its', 'prutor', trim($pwd));
    //    R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
}

/* Select all problems to be graded */
// $query = "SELECT a.id, a.event_id from assignment as a, event as e,account as acc  where a.event_id=e.id and acc.id=a.user_id and e.name = '".$labName."' and a.question in (1,2,3) and acc.section rlike 'b[0-9]+' ORDER BY a.id";
$query = "SELECT a.id, a.event_id from assignment as a, event as e,account as acc  where a.event_id=e.id and acc.id=a.user_id and e.name = '".$labName."' and a.question in (1,2,3) and acc.section rlike 'h[0-9]+' ORDER BY a.id";

$assignList=R::getAssocRow($query);


echo shuffle($assignList)."\n";

$numassign= count($assignList);
echo "NumAssign = ".$numassign."\n";

/* All TAs */
$query = "SELECT id  from account where `admin_role`='2'";

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
