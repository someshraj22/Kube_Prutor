<?php

// ---- Begin Mods ----
// Please change these lines appropriately, and the ip address of database
//
$eventName = "GL4";
$deadline = "2016-03-05 23:59:59";
$filenameMapping = "final_csv_gl4.csv";
$checkSubmitted = 1;

// ---- End   Mods ----

require_once('rb.php');

/* DB connection */
echo "Enter the mysql root password: ";
$pwd = fgets(STDIN);
try {
    R::setup('mysql:host=172.17.0.3;port=3306;dbname=its', 'prutor', trim($pwd));
    //    R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
}


// Read CSV
function readCSV($filenameMapping){ // problem, taRoll
        $problemTaArr = array();
        $problemList = array();
        $file = fopen($filenameMapping, "r");
        if(!$file) die($filenameMapping . " doesn't exist in curr dir");

        while(! feof($file))
        {
                $row = fgetcsv($file);

                $problem = trim($row[0]); //  problem, taRoll
                $taRoll = trim($row[1]);
                if ($problem == "") continue;
                if (! in_array($problem, $problemList)) {
                        $problemTaArr[$problem] = array();
                        array_push($problemList, $problem);
                }
                array_push($problemTaArr[$problem], $taRoll);
        }
        fclose($file);
        return $problemTaArr;
}

$problemTaArr = readCSV($filenameMapping);
echo "TA => Students \n ";
print_r($problemTaArr);

/* Foreach Problem => TAs */
foreach($problemTaArr as $problem => $taRolls){
        $taRollsStr = implode(",", $taRolls);

        /* Select all problems to be graded */
        $query = "SELECT a.id, a.event_id from assignment as a, event as e,account as acc
                          where a.event_id=e.id and acc.id=a.user_id and acc.type='STUDENT'
                          and a.is_submitted=:checkSubmitted and a.problem_id='" . $problem . "'
                          and e.name =:eventName ORDER BY a.problem_id";
        //echo $query;

        $assignList=R::getAssocRow($query , array(
                        ':eventName'=>$eventName,
                        ':checkSubmitted'=>$checkSubmitted
                ));


        //echo shuffle($assignList)."\n";
        $numassign= count($assignList);
        $numtas = count($taRolls);

        /* Fetch the TAs */
        $query = "SELECT id from account where roll in (" .$taRollsStr. ") order by id";

        echo $query;

        $talist=R::getAssocRow($query, array(
                        // ':rolls' => $taRolls
                ));

        /* DEBUG: Show the TAs */
        for($i=0; $i<$numtas; $i++) {
                $taID = $talist[$i]['id']; // Pick first TA (assuming Roll is unique anyways)
                print "TA-roll=[$taRolls[$i]], TA-ID=[$taID] => NumAssign=[$numassign] \n";
       }
        /* Assign the problem for grade */
       $j = 0;
        for($i=0; $i<$numassign; $i++) {
                $query = "INSERT INTO task (assignee, deadline, reference,type,event_id,is_complete)
                                  VALUES (:assignee, :deadline, :assignID, 'GRADING', :event_id, 0 )";
                $taID = $talist[$j]['id'];
                $j = ($j + 1)%$numtas;
                //echo $query;
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
 
