<?php
// ini_set('display_errors',1); //错误信息 
// ini_set('display_startup_errors',1); //php启动错误信息 
// error_reporting(-1); 
header("Content-type:text/html;charset=utf-8");
include_once 'mysql.php';
include_once 'functions.php';
include_once 'sqlutils.php';

$postdata=file_get_contents("php://input");

$jsondata=json_decode($postdata);

$token=$jsondata->token;

session_start();

$page=0;
if($jsondata->page!=""){
	$page=$jsondata->page;
}
$limit=6;

$db = getDb();
$sql = "select * from ".getTablePrefix()."_articles where `type` = 102 and deleted=0 order by updatetime desc LIMIT ".$limit*$page.",$limit";
if($uid!=""){
	$sql = "select * from ".getTablePrefix()."_articles where `type` = 102 and deleted=0 and authorid='$uid' order by updatetime desc LIMIT ".$limit*$page.",$limit";
}else if($keyword!=""){
	$sql = "select * from ".getTablePrefix()."_articles where `type` = 102 and deleted=0 and `title` like '%$keyword%' order by updatetime desc LIMIT ".$limit*$page.",$limit";
}
$res=mysqli_query($db,$sql) or die(mysqli_error($db));

$list = array();
while ($row = mysqli_fetch_assoc($res)) {

	$list[]=parsePKItem($row);
}

exitJson(0,"",$list);


?>