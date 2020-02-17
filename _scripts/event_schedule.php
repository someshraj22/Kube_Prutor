<?php

require_once('rb.php');

echo "Enter the mysql DBADMIN password: ";
$pwd = fgets(STDIN);
R::setup('mysql:host=172.17.0.93;port=3306;dbname=its', 'dbadmin', trim($pwd));

/************* Begin Mods *****************/

$event_name = "LAB-11";
$category = $event_name . " (DS)";

$slots = array(
	0=>array('A4','A5','A6','X1'), // Mon
	1=>array('A1','A2','A3','X1'), // Tue
	2=>array('A7','A8','A9','X1'), // Wed
	3=>array('A10','A11','A12','X1') // Thurs
);

$scheds = array(
	0=>array('2015-04-06 14:00:00','2015-04-06 17:10:00'), // Mon
	1=>array('2015-04-07 14:00:00','2015-04-07 17:10:00'), // Tue
	2=>array('2015-04-08 14:00:00','2015-04-08 17:10:00'), // Wed
	3=>array('2015-04-09 14:00:00','2015-04-09 17:10:00') // Thurs
);

/************* End Mods *****************/

// Event
$event = R::dispense('event');
$event->type = 'LAB';
$event->name = $event_name;
$id = R::store($event);

// Schedule and slot
for($day = 0; $day < 4; $day++) {	
	$schedule = R::dispense('schedule');
	$schedule->event_id = $id;
	$schedule->reference = "DAY " . ($day + 1);
	$schedule->time_start = $scheds[$day][0]; # $day
	$schedule->time_stop = $scheds[$day][1]; # $day
	$sid = R::store($schedule);

	// Slots
	$slotStr = "";
	foreach($slots[$day] as $sec) {
		$slot = R::dispense('slot');
		$slot->schedule_id = $sid;
		$slot->section = $sec;
		$slotStr = $slotStr . "'" . $sec . "',";
		R::store($slot);
	}

	// Assignments : p1vX for Mon, p2vX for Tue, ...
	$slotStr = trim($slotStr,", ");
	$pindex = $day + 1;
	$query = "INSERT INTO assignment (user_id,event_id,event_name,question,problem_id,max_marks) 
		SELECT a.id,:event,:event_name,p.id_v,p.id,:max_marks
		FROM account as a, problem as p 
		where p.category=:category and p.id_p=:pindex and a.section in (" . $slotStr . ")";

	echo $query."\n";
	R::exec($query, array(
		':event'=>$id,
		':event_name'=>$event->name,
		':category'=>$category,
		':pindex'=>$pindex, 
		':max_marks'=>20
	));
}

// Code
$query = "INSERT INTO code (assignment_id,user_id,contents,save_type,save_time) 
SELECT assignment.id,assignment.user_id,problem.template,0,'%s' 
FROM assignment INNER JOIN problem 
ON assignment.problem_id=problem.id 
WHERE problem.category=:category";

echo $query;
$query = sprintf($query, date("Y-m-d H:i:s"));

R::exec($query, array(
		':category'=>$category
	));

?>

