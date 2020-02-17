<?php

# ------- Begin of Parameters mod --------

# MySql Setup Parameters
$ip = '172.17.0.3';
$port = '3306';
$uname = 'prutor';
$filenameCSV = 'accounts.csv';

# ------ End of Parameters mod ---------

# Setup MySql
require_once('rb.php');
echo "Enter the mysql dbadmin password: ";
$pwd = fgets(STDIN);
try {
    R::setup('mysql:host=' . $ip . ';port=' . $port . ';dbname=its', $uname, trim($pwd));
    //    R::debug(true);
} catch ( PDOException $exception ) {
    echo "Error in connection: ".$exception->getCode()."\n" ;
    echo $exception->getMessage( ). "\n";
    die();
}


# Account class definition
class Account {
	public $email, $name, $section, $roll, $role; // Type: 0(student), 1(Tutor), 2(TA)

	function __construct($table) {
		$this->email = $table[0];
		$this->name = $table[1];
		$this->section = $table[2];
		$this->roll = $table[3];
		$this->role = intval($table[4]);
	}

	function getCSV() {
		$arr = array($this->email, $this->name, $this->section, $this->roll, $this->role);
		return $arr;
	}

	function __toString() {
		return implode(",", $this->getCSV());
	}
}


# Fetch the users list from csv
$accounts = array();
$file = fopen($filenameCSV,"r");

while(! feof($file))
{
	$row = fgetcsv($file);
	if(count($row)>0 and trim($row[0]) != '') {
		$account = new Account($row);
		echo $account . '\n';

		# Insert into DB
		$query = "INSERT INTO account(id, email, password, name, section, type, admin_role, auth_type, roll, enabled, hash) 
				  VALUES (UUID(), :email, '', :name, :section, :type, :adminRole, :auth_type, :roll, :enabled, :hash)";
 
 		$type = 'STUDENT'; // default role is of student. 
 		$role = NULL;
		if($account->role == 1 or $account->role == 2) { # Admin role
				$type = 'ADMIN';
				$role = $account->role;
		}
        	# For LDAP based authentication
		R::exec($query, array(
				':email'=>$account->email, ':name'=>$account->name, ':section'=>$account->section, ':roll'=>$account->roll,
				':type'=>$type, ':adminRole'=>$role, ':auth_type'=>0, ':enabled'=>1, ':hash'=>NULL
			));
		# For explicit password
		# password can be set by visiting link: http://<prutor-url>/setnewpassword/<roll-number>
		#R::exec($query, array(
		#		':email'=>$account->email, ':name'=>$account->name, ':section'=>$account->section, ':roll'=>$account->roll,
		#		':type'=>$type, ':adminRole'=>$role, ':auth_type'=>NULL, ':enabled'=>0, ':hash'=>$account->roll
		#	));
	}
	
}
fclose($file);

?>
