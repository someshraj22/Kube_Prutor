<?php

require_once('rb.php');

echo "Enter the mysql DBADMIN password: ";
$pwd = fgets(STDIN);
R::setup('mysql:host=172.17.0.17;port=3306;dbname=its', 'prutor', trim($pwd));
echo "Done setup ... ";

class Code
{
	public $code;
	public $codeID;
	public $userID;
	public $rollID;
	public $quesID;

	public function __construct($cID, $uID, $rID, $qID) {
		$this->codeID = $cID;
		$this->userID = $uID;
		$this->rollID = $rID;
		$this->quesID = $qID;
	}

	public function __toString()
    {
        return 'Code('.$this->codeID . ', ' . $this->rollID . ', ' . $this->quesID . ')';
    }
}

$labEvent = "LE1";

/* Select all problems to be graded */
$query = "SELECT *, c.id as code_id from assignment as a, account as ac, code as c 
		  where a.user_id=ac.id and a.submission = c.id and  event_name = :labEvent 
		  and question in (1,2,3,4,5) and ac.section rlike 'B[0-9]+'";
echo $query."\n";
$subList=R::getAssocRow($query, array(':labEvent' => $labEvent));
$numassign= count($subList);
echo "NumAssign = ".$numassign."\n";

$subDict = array();
for($i=0;$i<$numassign;$i++){
	$submission = $subList[$i];
	$quesID = $submission['problem_id'];
	$codeID = $submission['submission'];
	$rollID = $submission['roll'];
	$userID = $submission['user_id'];
	if (!isset($subDict[$quesID])) {
		$subDict[$quesID] = array();
	}

	$subDict[$quesID][$codeID] = new Code($codeID, $userID, $rollID, $quesID);
}
//print_r($subDict);

$query = "SELECT * from problem";
echo $query."\n";
$probList=R::getAssocRow($query, array());
$numProbs= count($probList);
$probDict = array();
for($i=0; $i < $numProbs; $i++){
	$probID = $probList[$i]['id'];
	$probDict[$probID] = $probList[$i];
}
//print_r($probDict);

$query = "SELECT * from account";
echo $query."\n";
$userList=R::getAssocRow($query, array());
$numUsers= count($userList);
$userDict = array();
for($i=0; $i < $numUsers; $i++){
	$userID = $userList[$i]['id'];
	$userDict[$userID] = $userList[$i];
}

$count = 0;
//print_r($userDict);
$subCount = count($subDict);
foreach ($subDict as $quesID => $codeDict) {
	echo "ProbID = " . $quesID . " : ";
	$template = base64_decode($probDict[$quesID]['template']);
	if (!file_exists($quesID)) {
	    mkdir($quesID, 0777, true);
	    $myfile = fopen($quesID."/template.tmp", "w") or die("Unable to open file!");
		fwrite($myfile, $template);
		fclose($myfile);
	}

	$query = "SELECT id, code.contents from code where id in (";
	$numCode = count($codeDict);
	foreach($codeDict as $codeID => $codeObj){

		$query = $query.$codeID.",";
	}

	$query = rtrim($query, ',').")";
	//echo $query . "\n";
	$codeList=R::getAssocRow($query, array());
	echo count($codeList) . "\n";
	//print_r($codeList);
	for($x=0;$x<count($codeList);$x++){
		$codeID = $codeList[$x]['id'];
		$codeObj = $codeDict[$codeID];
		//print_r($codeObj);
		$code = base64_decode ($codeList[$x]['contents']);
		$userID = $codeObj->userID;
		$roll = $userDict[$userID]['roll'];

		$myfile = fopen($quesID.'/'.$roll.".c", "w") or die("Unable to open file!");
		fwrite($myfile, $code);
		fclose($myfile);
		

		$count += 1;
	}
}

echo 'Wrote '.$count." # submissions into files\n";
?>
