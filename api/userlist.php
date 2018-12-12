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
$uid=$_SESSION['openid'];

$db = getDb();
$sql="select count(id) from ".getTablePrefix()."_members where area=1";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));
$area1=mysqli_fetch_row($res);

$sql="select count(id) from ".getTablePrefix()."_members where area=2";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));
$area2=mysqli_fetch_row($res);

$sql="select count(id) from ".getTablePrefix()."_members where area=3";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));
$area3=mysqli_fetch_row($res);

$sql="select count(id) from ".getTablePrefix()."_members where area=4";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));
$area4=mysqli_fetch_row($res);

$sql="select count(id) from ".getTablePrefix()."_members where area=5";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));
$area5=mysqli_fetch_row($res);

$sql="select count(id) from ".getTablePrefix()."_members where area=0";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));
$area0=mysqli_fetch_row($res);

$result= array(array('name'=>'燕堤西街7号院','count' => $area1[0]),
			   array('name'=>'燕堤西街6号院','count' => $area2[0]),
			   array('name'=>'燕堤南路1号院','count' => $area3[0]),
			   array('name'=>'燕堤南路2号院','count' => $area4[0]),
			   array('name'=>'燕堤中街6号院','count' => $area5[0]),
			   array('name'=>'未填写院区','count' => $area0[0]));

$sql = "select * from ".getTablePrefix()."_members order by lastlogin desc LIMIT 30";
$res=mysqli_query($db,$sql) or die(mysqli_error($db));


$loginlist = array();
while ($row = mysqli_fetch_assoc($res)) {
	
	$loginlist[] = getUserSimpleInfo($row['openid']);
}

exitJson(0,"",array('arearank' => $result, 'loginlist'=>$loginlist));


?>