<?php
include_once('../simple_html_dom.php');

header("Content-Type:text/html;charset=utf-8");
$_title = $_GET["title"];
$_pabookType = $_GET["pabookType"];
$_title = trim($_title);
$pabookType = urlencode($_pabookType);
$title = urlencode($_title);
//第一页
$smcx_p = $_GET['smcx_p'];

//pabookType
//图书 589
//期刊 590
//论文 666
$url = "202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=smcx&type=smcx&method1=1&retrieveLib=2&title=".$title."&pabookType=".$pabookType."&smcx_p=".$smcx_p;
//echo $url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
//echo $content;

$content = mb_convert_encoding($content,"UTF-8","GBK");

//使用Simple_Html_DOM解析网页
$html = new simple_html_dom();
$html->load($content);

$table = $html->find('table[id="smcx_table"]',0);
$tbody = $table->last_child();

//echo $content;
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<title>哈尔滨工业大学图书馆</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<link rel="shortcut icon" href="/hitlib2/static/ico/favicon.png">
	<link href="/hitlib2/static/css/bootstrap.css" rel="stylesheet">
	<link href="/hitlib2/static/css/mobile_result.css" rel="stylesheet">
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-4">
			<a href="/hitlib2/mobile/index.html"><img style="width: 100%;" id="searchlogo" src="/hitlib2/static/pic/my_logo.png" alt="Logo" class="img-rounded"></a>
			<form id="searchbox" action="result.php" method="get" class="form-horizontal" role="form" onsubmit="return validate_form(this);">
				<div class="form-group">
					<div class="col-xs-9">
						<input type="name" class="form-control" name="title" value="<?php echo $_title;?>" placeholder="请输入名称搜索">
						
						<label class="radio-inline">
							<input type="radio" <?php if($pabookType=="589") echo 'checked="true"';?> name="pabookType" id="inlineRadio1" value="589">书目
						</label>
						<label class="radio-inline">
							<input type="radio" <?php if($pabookType=="590") echo 'checked="true"';?> name="pabookType" id="inlineRadio2" value="590">期刊
						</label>
						<label class="radio-inline">
							<input type="radio" <?php if($pabookType=="666") echo 'checked="true"';?> name="pabookType" id="inlineRadio3" value="666">论文
						</label>
						
					</div>
					<div class="col-xs-3">
					<button type="submit" class="form-control btn btn-default">搜索</button>
				</div>
				</div>
			</form>
			
		</div>
	</div>
		<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-5" id="result">
			<ul class="list-unstyled">
					   	<?php
					   	$book_count = 0;
					    foreach( $tbody->find('tr') as $tr )
						{
							echo "<li class=\"resitem\">";
							//先找到链接，得到书的id
							$a = $tr->find('a',0);
							preg_match("/showDetail\(\'([0-9]*?)\'/",$a->onclick,$arr);
							echo "<a style=\"font-size:18px\" href=\"./detail.php?id=" . "$arr[1]"."&book_type=" .$pabookType ."\">";
							$count = 0;
							foreach($tr->find('td') as $td )
							{
								//去除各种奇葩字符
								$text = $td->plaintext;
								$text = str_replace(chr(30),"&nbsp",$text);
								$text = str_replace("=","",$text);
								$text = str_replace("/","",$text);
								$keywords = explode(" " ,$_title);
								//var_dump($keywords);
								foreach ($keywords as $keyword) {
									$keyword = trim($keyword);
									$pos = stripos($text, $keyword);
									if($pos !== false) {
										$len = strlen($keyword);
										$keyword = substr($text, $pos,$len);
									}
									$text = str_replace($keyword, "<em>$keyword</em>", $text);
								}
								$count++;
								//第一项是标题
								if($count == 1) {
									echo $text."</a><br>";
								}
								$a = $td->find('a',0);
								if($a) {
									
								}
								else {
									echo $text.",";
								}
							}
							echo "</li>";
							$book_count++;
						}
					    ?>
				  	</ul>
				  	<ul class="pager">
				  	<?php
						
				  		if($smcx_p >1 ) {
					  		echo "<li><a class=\"button\"  href=\"" . $_SERVER['PHP_SELF'] . "?title=".$title."&pabookType=".$pabookType."&smcx_p=".($smcx_p-1). "\">上一页</a><li>";
				  		}
				  		if($table->find('a[href="javascript:document.forms.smcx.smcx_p.value=\'2\';buildTable(\'smcx\')"]') || $smcx_p >1 && $book_count==10) {
					  		$smcx_p += 1;
					  		echo "<li><a class=\"button\"  href=\"" . $_SERVER['PHP_SELF'] . "?title=".$title."&pabookType=".$pabookType."&smcx_p=".$smcx_p . "\">下一页</a><li>";
				  		}
			
				  	?>
				  	</ul>
				  	
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
<script>
function validate_required(field,alerttxt)
{
	with (field)
	  	{
		  	if (value==null||value=="")
		    	{return false}
		  	else {return true}
		}
}

function validate_form(thisform)
{
	with (thisform)
	  	{
	  	if (validate_required(title)==false)
	    	{alert("请输入检索值！");return false}
    	}
}
</script>
</body>
</html>
<?php
$html->clear();
?>
