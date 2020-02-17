<?php

require_once('rb.php');

echo "Enter the mysql dbadmin password: ";
$pwd = fgets(STDIN);
	
try {
    R::setup('mysql:host=172.17.0.3;port=3306;dbname=its', 'prutor', trim($pwd));
        #R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
}

/* All TAs */

/*
$taAssignList = array( # M/C - http://karkare.cse.iitk.ac.in/ - DB: 172.17.0.5
	array( # Session 1
		array('Aakash Paul', 'Pulkit Kariryaa', 'Abhishek Rose'), # Q1 - 6
		array('AISHWARYA GUPTA', 'PRIYANK AGARWAL', 'Pranav Bisht'), # Q2 - 6
		array('Ajay Singh', 'Pranjul Ahuja', 'AKANSHA AGGARWAL', 'Vijay Pal Jat'), # Q3 - 6
	),
	array( # Session 2
		array('amit nagarkoti', 'Safal Pandita', 'KAVITA BALUTIA'), # Q1 - 6
		array('Ankit Kumar', 'Piyush Kumar', 'chigullapally Sriharsha'), # Q2 - 6
		array('Chandan Kumar Pandey', 'Nishit Majithia', 'Mhamane Swapnil Gangadhar'), # Q3 - 6
	)
); */




$taAssignList = array( # M/C - Ziyaan - http://172.27.20.189/ - DB: 172.17.0.3
	array( # Session 1
		array('Debjeet Majumdar', 'Utsav Singh', 'Kartik Kale'), # Q1 - 6
		array('Donthu Vamsi Krishna', 'Samik Some', 'Rohit Gupta'), # Q2 - 6
		array('GAURAV MAMGAIN', 'Rohit Sehgal', 'PRABUDDHA CHAKRABORTY'),  # Q3 - 6
	),
	array( # Session 2
		array('Ashish Dwivedi', 'RITIKA', 'Mayuri Gangurde'), # Q1 - 6
		array('Krishnaprasad P', 'Richa Singh', 'RAJENDRA KUMAR'), # Q2 - 6
		array('Lalchand Pandia', 'Rakshit Sharma', 'Sabreen Syed'), # Q3 - 6
	)
);


for($session=1; $session<=2; $session++) {
	echo "\n\n_______________________ Session-". $session ." ____________________\n";

	for($question=1; $question<=3; $question++) {
		/* Select all problems to be graded */
		$query = "";
		if($session == 1)
			$query = "SELECT a.id from assignment as a, account as acc  where acc.id=a.user_id and a.event_id=182 and a.question=:question and acc.section rlike 'B0[1-6]' ORDER BY a.id";
		else 
			$query = "SELECT a.id from assignment as a, account as acc  where acc.id=a.user_id and a.event_id=182 and a.question=:question and acc.section rlike '(B0[7-9])|(B1[0-2])' ORDER BY a.id";
		$assignList=R::getAssocRow($query, array(
			':question'=>$question,
		));

		$numassign= count($assignList);
		echo "\n---------------------------------\n";
		echo "Question-". $question ." NumAssign = ".$numassign."\n";
		echo 'Shuffle = '.shuffle($assignList)."\n";

		$tas = $taAssignList[$session-1][$question-1];
		$query = "SELECT id  from account where admin_role=2  and name in ('" . implode("','", $tas) ."')";
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
						  VALUES (:assignee, '2015-11-13 23:59:59', :assignID, 'GRADING',182)";

				R::exec($query, array(
					':assignee'=>$talist[$x]['id'],
					':assignID'=>$assignList[$i - 1]['id'] // Indexing starts from 0. Hence the i-1
				));
			}
			
			$start = $stop + 1.0;
			if($x == ($numta -2) ) $stop = $numassign;
			else	$stop = $stop + $perta;
		}
	}

}


?>
