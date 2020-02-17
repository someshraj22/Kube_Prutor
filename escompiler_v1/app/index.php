<?php

require_once 'bootstrap.php';

// Serve a request.

if(isset($_REQUEST['output']))
	$compiler_output = $_REQUEST['output'];
else if(isset($_REQUEST['test']))
	$compiler_output = file_get_contents('sample.txt');
else
	die('You need to provide the "output" parameter.');

$feedback = getCompilerFeedback($compiler_output);

echo json_encode($feedback);

?>