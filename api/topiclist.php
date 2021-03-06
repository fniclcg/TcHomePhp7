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

$type=$jsondata->type;
$token=$jsondata->token;

session_start();

$list = array();
if(!isDitributionMode($jsondata->bv)){
	exitJson(0,"",$list);
}

$uid=$_SESSION['openid'];
$houseid=$_SESSION['houseid'];

$page=0;
if($jsondata->page!=""){
	$page=$jsondata->page;
}
$limit=9;

$db = getDb();
//$sql = "select * from ".getTablePrefix()."_articles where `type` = $type and deleted=0 order by updatetime desc,createdate desc LIMIT ".$limit*$page.",$limit";
$sql = "select a.* 
		from ".getTablePrefix()."_articles a left join ".getTablePrefix()."_members b on a.authorid=b.openid
		where a.`type`=$type and a.deleted=0 
		order by 
			case 
			when b.houseid = $houseid then 0
			else 1 end
			asc, 
		a.updatetime desc,a.createdate desc LIMIT ".$limit*$page.",$limit";
if($jsondata->uid!=""){
	$uid=$jsondata->uid;
	$sql = "select * from ".getTablePrefix()."_articles where `type` <99 and authorid='$uid' and deleted=0 and masked=0 order by updatetime desc,createdate desc LIMIT ".$limit*$page.",$limit";
}else if($type==""){
	$sql = "select a.* 
			from ".getTablePrefix()."_articles a left join ".getTablePrefix()."_members b on a.authorid=b.openid
			where a.`type` <99 and a.`type` !=8 and a.deleted=0 
			order by 
				case when b.houseid = $houseid then 0
				else 1 end
				asc, 
			a.updatetime desc,a.createdate desc LIMIT ".$limit*$page.",$limit";
}
$res=mysqli_query($db,$sql) or die(mysqli_error($db));


while ($row = mysqli_fetch_assoc($res)) {

	$item=parseArticleSimpleItem($row);

    if(count($item['vids'])>0)$item['text']='[视频]'.$item['text'];

    $item['text']=mb_substr($item['text'], 0,60,"UTF-8");
    if(mb_strlen($item['text'],"UTF-8")>=60)$item['text']=$item['text']."...";

    $list[]=$item;
}

exitJson(0,"",$list);


?>