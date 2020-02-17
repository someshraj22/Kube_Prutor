<?php

require_once('rb.php');
//$labCategory = "Practice (OldLabExams-2)";
//$practiceCategory = "ZPRAC1-15-16-LE2";
$labCategory = "UGL4";
$practiceCategory = "PRACTICE-UGL4";

echo "Enter the mysql dbadmin password: ";
$pwd = fgets(STDIN);
try {
    R::setup('mysql:host=172.17.0.3;port=3306;dbname=its', 'prutor', trim($pwd));
    //    R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
}

// Import lab problems as new practice problems
$query = "INSERT INTO  `problem` (  `title` ,  `category` ,  `statement` ,  `solution` ,  `template` ,  `id_p` ,  `id_v` ,  `id_d`, `env` )  SELECT title, '".$practiceCategory."', statement, solution, template, id_p, id_v, id_d, env FROM  `problem` WHERE category LIKE '".$labCategory."'";

echo $query;
R::exec($query);
echo "\nProblems copied\n";

// Import testcases from lab problems to corres. practice problems
//$query = "INSERT INTO `test_case` (problem_id,input,output,visibility,type) SELECT newp.id,t.input,t.output,t.visibility,t.type FROM test_case as t,problem as p, problem as newp WHERE t.problem_id=p.id and p.category like '".$labCategory."' and newp.category like '".$practiceCategory."' and p.id<>newp.id and p.solution=newp.solution";
// make all test cases visible.
$query = "INSERT INTO `test_case` (problem_id,input,output,visibility,type) SELECT newp.id,t.input,t.output,1,t.type FROM test_case as t,problem as p, problem as newp WHERE t.problem_id=p.id and p.category like '".$labCategory."' and newp.category like '".$practiceCategory."' and p.id<>newp.id and p.solution=newp.solution";

echo $query;
R::exec($query);
echo "\nTests copied\n";
?>
