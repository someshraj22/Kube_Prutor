<?php

require_once('rb.php');

echo "Enter the mysql password: ";
$pwd = fgets(STDIN);
R::setup('mysql:host=172.17.0.4;port=3306;dbname=its', 'prutor', trim($pwd));
echo "Done setup ... ";

class Account
{
	public $name;
	public $email;
	public $roll;
	public $section;

	public function __construct($row) {
		$this->name = $row['name'];
		$this->email = $row['email'];
		$this->roll = $row['roll'];
		$this->section = $row['section'];
	}

	public function __toString()
    {
        return implode(",", $this->getCSV());
    }

    public function getCSV() {
    	return array($this->name, $this->email, $this->roll, $this->section);
    }

}

/* Select all assignments to be considered */
$query = "SELECT ac.name, ac.email, ac.roll, ac.section, a.event_name, a.question, a.max_marks, a.score
		  from assignment as a, account as ac
		  where a.user_id=ac.id and ac.type='STUDENT' and ac.enabled=1 and event_name like :event_name ";

echo $query."\n";
$assignList=R::getAssocRow($query, array(':event_name' => 'lab%'));
$numassign= count($assignList);
echo "NumAssign = ".$numassign."\n";

$subDict = array();
$accDict = array();		// email -> Account()
$eventDict = array();	// eventName -> Array(question -> maxMarks)
$marksDict = array();	// email -> Array(eventName -> score)

for($i=0;$i<$numassign;$i++){
	$row = $assignList[$i];
	$email = $row['email'];	$event_name = $row['event_name'];	$question = $row['question'];	$max_marks = $row['max_marks'];	$score = $row['score'];
	if(!isset($score)) $score=0;

	// set accDict
	if(!isset($accDict[$email]))
		$accDict[$email] = new Account($row);

	// set eventDict
	if(!isset($eventDict[$event_name]))
		$eventDict[$event_name] = array();
	if(!isset($eventDict[$event_name][$question]))
		$eventDict[$event_name][$question] = $max_marks;

	// set marksDict
	if(!isset($marksDict[$email]))
		$marksDict[$email] = array();
	if(!isset($marksDict[$email][$event_name]))
		$marksDict[$email][$event_name] = array();
	if(!isset($marksDict[$email][$event_name][$question]))
		$marksDict[$email][$event_name][$question] = $score;

}


$fileEvent = fopen("labMarksEvent_Absolute.csv", "w") or die("Unable to open file!");
$filePercent = fopen("labMarksEvent_Percent.csv", "w") or die("Unable to open file!");
$fileQ = fopen("labMarksQuestion_Absolute.csv", "w") or die("Unable to open file!");
fwrite($fileEvent, "name, email, roll, section"); 
fwrite($filePercent, "name, email, roll, section"); 
fwrite($fileQ, "name, email, roll, section");

// Write Titles
foreach($eventDict as $event_name => $qs) {
	$marksEvent = 0;
	
	foreach($qs as $question => $max_marks) {
		fwrite($fileQ, "," . $event_name ."_" . $question . "(" . $max_marks . ")" );
		$marksEvent += $max_marks;
	}
	fwrite($fileEvent, "," . $event_name . "(" . $marksEvent . ")");
	fwrite($filePercent, "," . $event_name . "(" . $marksEvent . ")");
}
fwrite($fileEvent, "\n"); fwrite($filePercent, "\n"); fwrite($fileQ, "\n");

// Write the marks
// Foreach student
foreach ($accDict as $email => $acc) {
	fwrite($fileEvent, $acc);	fwrite($filePercent, $acc);	fwrite($fileQ, $acc);

	// Foreach event
	foreach($eventDict as $event_name => $qs) {
		$marksStudent = 0; $marksEvent = 0;
		
		// Foreach question
		foreach($qs as $question => $max_marks) {
			$score = 0;
			if(!isset($marksDict[$email][$event_name]) || !isset($marksDict[$email][$event_name][$question])) {
				echo "Warning! " . $email . " doesn't have marks set for event:" . $event_name . ", Q:" . $question . "; 0 score assumed\n";
				$score = 0;
			}
			else $score = $marksDict[$email][$event_name][$question];

			fwrite($fileQ, "," . $score);
			$marksStudent += $score;
			$marksEvent += $max_marks;
		}
		fwrite($fileEvent, "," . $marksStudent);
		fwrite($filePercent, "," . round((100.0 * $marksStudent)/$marksEvent, 2));
	}

	fwrite($fileEvent, "\n");	fwrite($filePercent, "\n");	fwrite($fileQ, "\n");
}

fclose($fileEvent); fclose($filePercent); fclose($fileQ);
echo "\n\n Wrote #".count($accDict)." student's grades, for #".count($eventDict)." events \n\n";

/* */
?>
