<?php
include_once('simple_html_dom.php');

header("Content-Type:text/html;charset=utf-8");
$title = $_GET["title"];
$title2 = $_GET["title2"];
$author = $_GET["author"];
$subject = $_GET["subject"];
$subject2 = $_GET["subject2"];
$classNo = $_GET["classNo"];
$ISBN = $_GET["ISBN"];
$callNo = $_GET["callNo"];

$title = urlencode($title);
$title2 = urlencode($title2);
//第一页
$smcx_p = $_GET['smcx_p'];

//图书查询 retrieveLib=2
//期刊 3
//论文 0
$url = "202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=smcx&type=smcx&method1=1&retrieveLib=2&title=".$title."&titleSecond=".$title2."&author=".urlencode($author)."&subject=".urlencode($subject)."&subjectSecond=".urlencode($subject2)."&classNo=".urlencode($classNo)."&ISBN=".urlencode($ISBN)."&callNo=".urlencode($callNo)."&smcx_p=".$smcx_p;
//echo $url;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);

$content = str_replace(chr(30),"&nbsp",$content);

$content = mb_convert_encoding($content,"UTF-8","GBK");

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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./static/ico/favicon.png">

    <title>哈工大图书馆</title>

    <!-- Bootstrap core CSS -->
    <link href="./static/css/bootstrap.css" rel="stylesheet">
    <link href="./static/css/non-responsive.css" rel="stylesheet">
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./index.html">哈工大图书馆</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="./index.html">首页</a></li>
            <li class="active"><a href="./smcx.html">书目查询</a></li>
            <li><a href="./lwcx.html">论文查询</a></li>
            <li><a href="./qccx.html">期刊查询</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="./login.html">登陆</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
		 <div class="page-header">
			  		<h1>查询结果</h1>
		</div>
		<table class="table table-condensed table-hover">
			<tr>
				<th>题名</th>
				<th>责任者</th>
				<th>出版项</th>
				<th>出版年</th>
				<th>页码</th>
				<th>索取号</th>
			</tr>
			<?php
			   	$book_count = 0;
		    	foreach( $tbody->find('tr') as $tr )
				{
					echo "<tr onclick=\"document.location='";
					$a = $tr->find('a',0);
					if($a) {
						preg_match("/showDetail\(\'([0-9]*?)\'/",$a->onclick,$arr);
						echo "./detail.php?id=" . "$arr[1]";
					}
					echo "';\">";
					foreach($tr->find('td') as $td )
					{
						echo "<td>";
						if($td->find('a',0))
						{
							continue;
						}
						//去掉一些奇怪的字符
						$text = $td->plaintext;
						$text = str_replace(" /","",$text);
						$text = str_replace(" =","",$text);
						echo $text;
						echo "</td>";
					}
					echo "</tr></a>";
					$book_count++;
				}
			?>
		</table>
	
		<ul class="pager">
		<?php
			
	  		if($smcx_p >1 ) {
		  		echo "<li><a href=\"" . $_SERVER['PHP_SELF'] . "?title=".$title."&title2=".$title2."&author=".urlencode($author)."&subject=".urlencode($subject)."&subject2=".urlencode($subject2)."&classNo=".urlencode($classNo)."&ISBN=".urlencode($ISBN)."&callNo=".urlencode($callNo)."&smcx_p=".($smcx_p-1). "\">上一页</a></li>";
	  		}
	  		if($table->find('a[href="javascript:document.forms.smcx.smcx_p.value=\'2\';buildTable(\'smcx\')"]') || $smcx_p >1 && $book_count==10) {
		  		$smcx_p += 1;
		  		echo "<li><a href=\"" . $_SERVER['PHP_SELF'] . "?title=".$title."&title2=".$title2."&author=".urlencode($author)."&subject=".urlencode($subject)."&subject2=".urlencode($subject2)."&classNo=".urlencode($classNo)."&ISBN=".urlencode($ISBN)."&callNo=".urlencode($callNo)."&smcx_p=".$smcx_p . "\">下一页</a></li>";
	  		}
		?>
		</ul>
				  	
	</div>
    <script src="./static/js/jquery-2.1.1.min.js"></script>
    <script src="./static/js/bootstrap.min.js"></script>
  </body>
</html>

