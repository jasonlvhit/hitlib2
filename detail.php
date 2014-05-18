<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<title>哈尔滨工业大学图书馆</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<link rel="shortcut icon" href="/hitlib2/static/ico/favicon.png">
	<link href="/hitlib2/static/css/bootstrap.css" rel="stylesheet">
	<link href="/hitlib2/static/css/detail.css" rel="stylesheet">
</head>

<body>
<div class="navbar navbar-fixed-top" role="navigation">
		<div class="container">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="http://202.118.250.131/lib/opacAction.do?method=login" target="_blank">登陆</a></li>
				<li><a href="index.html">首页</a></li>
			</ul>
		</div>
</div>
	
<div class="container">

<?php
include_once('./simple_html_dom.php');

header("Content-Type:text/html;charset=utf-8");
$id = $_GET['id'];
$book_type = $_GET['book_type'];

//图书 589
//期刊 590
//论文 666

$url = "";
if($book_type == "589") {
	$url = "202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=searchBiblInfo&type=searchBiblInfo&id_bibl=".$id."&book_type=589&currpage=1";
}

if($book_type == "590") {
	$url = "202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=searchArriveInfo&type=searchArriveInfo&id_bibl=".$id."&year=".date('Y');
}

if($book_type == "666") {
	$url = "202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=searchPaperInfo&type=searchPaperInfo&id_bibl=".$id."&book_type=589&currpage=1";
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
$content = mb_convert_encoding($content,"UTF-8","GBK");

//curl_close($ch);
$html = new simple_html_dom();
$html->load($content);

if($book_type == "589") {
	$table = $html->find('table[id="bibl_info_table"]',0);
	$tbody = $table->last_child();

	echo $tbody->children(3);
	echo $tbody->children(5);
	for($i=4;$i<16;$i++) {
		echo $tbody->children($i);
	}
}
else {
	echo $content;
}
curl_close($ch);

?>

</div>
<div class="container">
	<footer class="footer">
		<div>Copyright &copy; 2014 Skyline75489.</div>
	</footer>
</div>

<script src="/hitlib2/static/js/jquery-2.1.1.min.js"></script>
<script src="/hitlib2/static/js/bootstrap.min.js"></script>
<script>
function showPosi(book_code){
	url="http://202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=showPosi&type=showPosi&book_code="+book_code;
	window.showModalDialog(url,"newwin","dialogHeight: 650px; dialogWidth: 950px; dialogTop: 200px; dialogLeft: 260px; edge: Raised; center: Yes; help: Yes; resizable: Yes; status: Yes;");
}


function showReserveInfo(bibl_id,book_code,yujiestore_id){     
    if(book_code == "yuyue"){
       $('htqk').value = "yy";
    }else{
       $('htqk').value = "yj";
    }
    
	url = "http://202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=searchReserveInfo&type=searchReserveInfo&id_bibl="+bibl_id+"&book_code="+book_code+"&yujiestore_id="+yujiestore_id;
	url = encodeURI(url);
	new Ajax.Request(url,{method: 'get', asynchronous: true ,onComplete:backReserveInfo});
 }
	 

</script>
</body>
</html>
<?php
$html->clear();
?>
