<?php

function getCompilerFeedback($output) {

	$lines = array_filter(explode("\n", $output));

	$errors = array();

	/**
	 * Each valid message consists of 4 parts:
	 * [0] Line number
	 * [1] Column number
	 * [2] Message type (error/warning)
	 * [3] Message text
	*/
	foreach($lines as $line) {
		$line = trim(substr($line, strpos($line, ':') + 1));
		if(is_numeric(substr($line, 0, 1))) {
			$parts = explode(':', $line);
			if(count($parts) === 4) array_push($errors, $parts);
		}
	}

	$feedback = array();

	// TODO omitting those errors whose templates aren't present
	foreach($errors as $error) {
		
		$line = trim($error[0]);
		$position = trim($error[1]);
		$type = trim($error[2]);
		$message = trim($error[3]);
			
		if($type === 'note') $type = 'info';
			
		$analytic = getErrorHash($message);
		$rows = R::getAssocRow("SELECT type,feedback FROM ext_feedback WHERE hash=?", array($analytic['hash']), 5);
			
		if(count($rows)) {

			$parseType = intval($rows[0]['type']);
			$feedbackMessage = $rows[0]['feedback'];

			if($parseType === 0) {
				foreach(array_keys($analytic['vars']) as $var) {
					$feedbackMessage = str_replace($var, $analytic['vars'][$var], $feedbackMessage);
				}
			} else {
				$v8 = new V8Js();
				$definitions = '';
				foreach(array_keys($analytic['vars']) as $var) {
					$definitions = $definitions . 'var ' . substr($var, 1) . '="' . $analytic['vars'][$var] . '";';
					$feedbackMessage = str_replace($var, substr($var, 1), $feedbackMessage);
				}
				$feedbackMessage = $definitions . $feedbackMessage;
				try {
					ob_start();
					$v8->executeString($feedbackMessage);
					$feedbackMessage = ob_get_clean();
				} catch(Exception $e) {
					ob_get_clean();
				}
			}
		} else
			$feedbackMessage = $message;
			
		array_push($feedback, array(
			'type'=>$type,
			'line'=>$line,
			'position'=>$position,
			'feedback'=>$feedbackMessage
		));
	}

	return $feedback;
}

function getErrorHash($error) {

	$reg_quotes = "/'([a-zA-Z0-9 ;:_,`=#@\$\|\!\"<>%\{\}&\.\/\+\-\(\)\^\*\[\]\\\]+)'|\"([a-zA-Z0-9 _\+\-\*\/]+)\"/";
	$vars = array();

	preg_match($reg_quotes, $error, $matches);

	if(count($matches)) {
		$set = array();
		$index = 1;
		do {
			array_push($set, $matches[1]);
			$error = str_replace($matches[0], ':X' . $index, $error);
			$vars[':X' . $index] = substr($matches[0], 1, strlen($matches[0]) - 2);
			preg_match($reg_quotes, $error, $matches);
			$index++;
		} while(count($matches));
	}

	$hash = md5($error);

	return array(
		'hash'=>$hash,
		'vars'=>$vars
	);
}

function hashErrors() {

	$reg_quotes = "/'([a-zA-Z0-9 ;:_,`=#@\$\|\!\"<>%\{\}&\.\/\+\-\(\)\^\*\[\]\\\]+)'|\"([a-zA-Z0-9 _\+\-\*\/]+)\"/";

	do {
		$processed = array();
		$rows = R::getAssocRow("SELECT id,code_id,type,message,line FROM compilation_error WHERE id NOT IN (SELECT error_id FROM ext_syntax)
					 ORDER BY id LIMIT " . $this->CHUNK_SIZE);
		foreach($rows as $row) {
			$error = $row['message'];
			preg_match($reg_quotes, $error, $matches);
			if(count($matches)) {
				$set = array();
				$index = 1;
				do {
					array_push($set, $matches[1]);
					$error = str_replace($matches[0], ':X' . $index, $error);
					preg_match($reg_quotes, $error, $matches);
					$index++;
				} while(count($matches));
			}
			$hash = md5($error);
			$query = sprintf("INSERT INTO ext_syntax (code_id,type,error_id,error,hash,line,message) VALUES ('%s','%s','%s','%s','%s','%s','%s')",
					$row['code_id'], $row['type'], $row['id'], addslashes($error), $hash, $row['line'], addslashes($row['message']));
			array_push($processed, $query);
		}
		if(count($processed)) R::exec(implode(";", $processed));
	} while(count($rows));

}

?>