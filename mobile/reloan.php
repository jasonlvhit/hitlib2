<?php
include_once('./simple_html_dom.php');

header("Content-Type:text/html;charset=utf-8");
$cardid = $_GET['cardid'];
$book_code = $_GET['book_id'];

$cardid = trim($cardid);
$book_code = trim($book_code);

$url="http://202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=reloan&book_code=" . $book_code . "&tab=tb1";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$header[]= 'Host: '.'202.118.250.131';  
$header[]= 'Connection: Keep-Alive ';  
$header[]= 'Cookie: '.'cardid='.$cardid.';  ';

curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
$content = curl_exec($ch);

//echo $content;
curl_close($ch);

$msg = explode("oooOOoo",$content);
//var_dump($msg);
$msgType = $msg[0];

switch ( $msgType )
{
	case "0" :
		echo "<script>alert('续借成功！');history.back()</script>";
		break;
	case "-1":
		echo "<script>alert('无此读者证号！');history.back()</script>";	
		break;
	case "-2":
		echo "<script>alert('此读者证已过期！');history.back()</script>";
		break;
	case "-3":
		echo "<script>alert('读者欠款未处理！');history.back()</script>";
		break;
	case "-4":
		echo "<script>alert('您有过期未还文献，不能续借！');history.back()</script>";
		break;
	case "-5":
		echo "<script>alert('未达到可续借时间间隔！');history.back()</script>";
		break;
	case "-6":
		echo "<script>alert('超出可续借次数！');history.back()</script>";
		break;
	default:
		echo "<script>alert('续借失败！原因未知');history.back()</script>";
		break;
}
?>
