<?php

require_once 'bootstrap.php';

function getErrorClasses() {
	$query = "SELECT hash,error,type,COUNT(id) AS frequency FROM ext_syntax GROUP BY hash ORDER BY frequency DESC";
	$rows = R::getAssocRow($query);
	return $rows;
}

function getClassInstances($hash) {
	$query = "SELECT assignment_id,code_id,message FROM ext_syntax INNER JOIN code ON code.id=code_id WHERE hash=?";
	$rows = R::getAssocRow($query, array($hash));
	return $rows;
}

function getSingleInstance($code_id) {
	$query = "SELECT line,(SELECT contents FROM code WHERE id=code_id) AS code FROM ext_syntax WHERE code_id=?";
	$rows = R::getAssocRow($query, array($code_id));
	return $rows[0];
}

?>