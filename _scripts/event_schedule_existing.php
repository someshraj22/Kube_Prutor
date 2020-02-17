<?php

require_once('rb.php');

echo "Enter the mysql password: ";
$pwd = fgets(STDIN);
R::setup('mysql:host=172.17.0.3;port=3306;dbname=its', 'prutor', trim($pwd));
//R::debug(true);

/************* Begin Mods *****************/

$event_id = 9;
$event_name = "GL1";
$category = "GL1";
$pindices = array(5,6,7,8); # The "id_p" of problem table which needs to be assigned
$marks = 20; # The marks for the problems: FIXED as of now.
# The IDs of various users (account table primary key) to whom this lab needs to be scheduled
$rolls = array("'tueguest2', 'tueguest3', 'tueguest4', 'tueguest5'");

/************* End Mods *****************/

// Event
$rollsStr = implode(",", $rolls);
for($p=0; $p < count($pindices); $p++){ 
	
	$query = "INSERT INTO assignment (user_id,event_id,event_name,question,problem_id,max_marks) 
		SELECT a.id,:event,:event_name,:ques,p.id,:max_marks
		FROM account as a, problem as p 
		where p.category=:category and p.id_p=:pindex and p.id_v=1 and a.roll in (" . $rollsStr . ")";

	echo $query."\n";
	R::exec($query, array(
		':event'=>$event_id,
		':event_name'=>$event_name,
		':category'=>$category,
		':pindex'=>$pindices[$p], 
		':max_marks'=>$marks,
		':ques'=>$p+1, // $p starts at 0, question starts at 1
	));

// Code
	$query = "INSERT INTO code (assignment_id,user_id,contents,save_type,save_time) 
		SELECT assignment.id,assignment.user_id,problem.template,0,'%s' 
		FROM assignment, problem, account
		WHERE assignment.problem_id=problem.id and assignment.user_id=account.id and  
		problem.category=:category and problem.id_p=:pindex and 
		account.roll in (" . $rollsStr . ") and assignment.event_id=:event";

	echo $query;
	$query = sprintf($query, date("Y-m-d H:i:s"));

	R::exec($query, array(
		':event'=>$event_id,
		':category'=>$category,
		':pindex'=>$pindices[$p],
	));
}
?>

