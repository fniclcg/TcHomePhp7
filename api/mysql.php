<?php
date_default_timezone_set('Asia/Shanghai');

function connDB($dbConf)
{
	$conn = mysqli_connect($dbConf['host'], $dbConf['user'], $dbConf['pass'], 'MiniCom');

	if ($conn) {
		mysqli_query($conn, 'set names \'utf8\';');
		return $conn;
	}
	return false;
}

function getDb()
{
	$db1 = array(
		'host' => '118.184.219.119:3306',
		'user' => 'root',
		'pass' => 'lcg!QAZ2wsx',
		'DB_CHARSET'=> 'utf8mb4'
	);
	
	$db = connDB($db1);
	
	//mysqli_select_db($db, 'MiniCom');

	mysqli_query($db, 'set names \'utf8mb4\'');
	
	return $db;
}

function getTablePrefix(){
	return 'bj_sjs_jybhy';
}

function getAppId(){
	return 'wxe689a0c6f3c13bb7';
}

function getAppSecret(){
	return '84d2efff871b50c6d9906f9d979ac105';
}