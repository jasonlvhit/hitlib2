<?php
include_once('../simple_html_dom.php');

header("Content-Type:text/html;charset=utf-8");
$rcardNo = $_POST["rcardNo"];
$pwd = $_POST["pwd"];
$registerName = $_POST["registerName"];

$rcardNo = urlencode($rcardNo);
$pwd = urlencode($pwd);
$registerName = urlencode($registerName);

$url = "http://202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=login&registerName=".$registerName."&rcardNo=".$rcardNo."&pwd=".$pwd;
//echo $url;
$content = file_get_contents($url);
$msg = explode("oooOOoo",$content);
//var_dump($msg);
if(!$msg) {
	echo "<script>alert('网络忙或系统正在维护中，请稍后再试！');history.back();</script>";
	exit;
}
$msgType = $msg[0];

$cardid = trim($msg[1]);

if($msgType == "err_type"){
	echo "<script>alert('此注册读者证为无效状态！');history.back()</script>";
}
else if($msgType == "err_date"){
	echo "<script>alert('此注册读者证已过期！');history.back()</script>";
}
else if($msgType == "err_reg_name"){
	echo "<script>alert('此注册号不存在！');history.back()</script>";
}
else if($msgType == "err_card_no"){
	echo "<script>alert('读者证号不存在！');history.back()</script>";
} 
else if($msgType == "err_reg_pwd"||$msgType == "err_card_pwd"){
	echo "<script>alert('登陆密码错误！');history.back()</script>";
}

else if($msgType == "pass1") {
	$url = "http://202.118.250.131/lib/opacAction.do?method=init&seq=301";

}
else if($msgType == "pass2") {
	
}

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

$html = new simple_html_dom();
$html->load($content);

?>

<!DOCTYPE html>
<html>
	<head>
		<title>我的图书馆</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
		<link rel="shortcut icon" href="/hitlib2/static/ico/favicon.png">
		<link href="/hitlib2/static/css/bootstrap.css" rel="stylesheet">
		<link href="/hitlib2/static/css/mobile_detail.css" rel="stylesheet">
</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-10">
				<ul class="list-unstyled">
				<?php
				foreach( $html->find('table[bgcolor=#587E92]') as $table)
				{
					//if($table->next_sibling()->bgcolor="#CCCCCC") {
					//	continue;
					//}
					//echo $table->prev_sibling()->bgcolor;
					$count = 1;
					echo "<li>";
					echo "<table cellspacing=\"1\" cellpadding=\"1\" style=\"border-spacing:1;border-color:gray;\" border=\"0\">";
					foreach( $table->find('td') as $td )
					{
						if(!$count%2) {
							echo "<tr>";
						}
						$count++;
						echo "<td>";
						$text = $td->plaintext;
						$text = str_replace("[续借]","",$text);
						echo $text;
						echo "</td>";
						
						//else {
						//	echo "<a class=\"button\" href=\"./reloan.php?id=.". $arr[1] . "\">续借</a>";
						//}
						if($count %2) {
							echo "</tr>";
						}
					}

					$a = $table->find('a',0);
					if($a) {
						preg_match("/javascript:reloan\(\'([0-9]*?)\'/",$a->href,$arr);
						//var_dump($arr);
						echo "<td colspan=\"2\"><a class=\"button\" href=\"./reloan.php?cardid=".$cardid . "&book_id=".$arr[1]."\">续借</a></td>";
					}
					echo "</table></li>";
				}
				
				?>
				</div>
			</div>
		</div>
	<div class="container">
	<footer class="footer">
		<div>Copyright &copy; 2014 Skyline75489.</div>
	</footer>
</div>

<script src="/hitlib2/static/js/jquery-2.1.1.min.js"></script>
<script src="/hitlib2/static/js/bootstrap.min.js"></script>
	</body>
</html>
<?php
$html->clear();
?>
