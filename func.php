<?php
/*---------------------------CSV FUNCTIONS----------------------------*/
function addUserToCSV($pn){
	if (!userExists($pn)){
		$fp = fopen('log.csv', 'a');
		$list = array (
		    array($pn,'0',date("m/d/y"),"n/a","confirming")
		);

		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}
		fclose($fp);
	}
	else{
		echo "User exists in database already.";
	}
}
function confirmUser($pn){
	$fp = fopen('log.csv', 'r+');
	$list = getLogArray();
	
	if(userExists($pn)){
		for($i=0; $i<sizeof($list); $i++){
			if($list[$i]==$pn){
				$list[$i+1]="1";
				$list[$i+3]=date("m/d/y");
				$list[$i+4]="confirmed";
			}
		}
	}
	for($i = 0; $i<sizeof($list); $i+=5){
		fwrite($fp,$list[$i].",".$list[$i+1].",".$list[$i+2].",".$list[$i+3].",".$list[$i+4]."\n");
	}
	fclose($fp);
}
function getState($pn){
	$list = getLogArray();
	
	if(userExists($pn)){
		for($i=0; $i<sizeof($list); $i++){
			if($list[$i]==$pn){
				return $list[$i+4];
			}
		}
	}
}
function setState($pn,$state){
	$fp = fopen('log.csv', 'r+');
	$list = getLogArray();
	
	for($i=0; $i<sizeof($list); $i++){
		if($list[$i]==$pn){
			$list[$i+4]=$state;
		}
	}
	for($i = 0; $i<sizeof($list); $i+=5){
		fwrite($fp,$list[$i].",".$list[$i+1].",".$list[$i+2].",".$list[$i+3].",".$list[$i+4]."\n");
	}
	fclose($fp);
}
function userExists($pn){
	if(in_array($pn,getLogArray())){
		return true;
	}
	else{
		return false;
	}
}
function isConfirmed($pn){
	$list = getLogArray();
	
	if(userExists($pn)){
		for($i=0; $i<sizeof($list); $i++){
			if($list[$i]==$pn){
				if($list[$i+1]=="1"){
					return true;
				}
				else{
					return false;
				}
			}
		}
	}
	else{
		return false;
	}
}
function getLogArray(){
	$fp = fopen('log.csv', 'r+');
	$theArray = Array();
	while (($line = fgetcsv($fp)) !== false) {
		$theArray = array_merge($theArray,$line);
	}
	fclose($fp);
	return $theArray;
}
function showLog(){
	echo "<h2>The Log</h2><br /><table>\n\n";
	$f = fopen("log.csv", "r");
	while (($line = fgetcsv($f)) !== false) {
	        echo "<tr>";
	        foreach ($line as $cell) {
	                echo "<td>" . htmlspecialchars($cell) . "</td>";
	        }
	        echo "<tr>\n";
	}
	fclose($f);
	echo "\n</table>";
}

/*-------------------------DATABASE FUNCTIONS-------------------------*/
function addUser($pn){
	connectDB("IDAS");
	$query = 'INSERT INTO users (phone_number) VALUES ('.$pn.')';
	mysql_query($query) or die("bad query");
	closeDB();
	
}
function connectDB($db){
	$un="idas";
	$pass="idaspass";
	mysql_connect("floodidas.dyndns.info",$un,$pass);
	mysql_select_db($db) or die( "Unable to select database");
}
function closeDB(){
	mysql_close();
}
/*--------------------------------------------------------------------*/
?>