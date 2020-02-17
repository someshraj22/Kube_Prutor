<?php

require_once 'bootstrap.php';
require_once 'ui/data.php';

$action = $_REQUEST['action'];

header('Content-Type: application/json');

if($action === 'instances') {
	$data = getClassInstances($_REQUEST['hash']);
	echo json_encode($data);
} else {
	echo json_encode("bad query");
}

?>