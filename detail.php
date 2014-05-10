<?php
include_once('./simple_html_dom.php');

header("Content-Type:text/html;charset=utf-8");
$id = $_GET['id'];
//echo $id;
//图书 589
//期刊 590
//论文 666
$url = "202.118.250.131/lib/opacAction.do?method=DoAjax&dispatch=searchBiblInfo&type=searchBiblInfo&id_bibl=".$id."&book_type=589&currpage=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$content = curl_exec($ch);
$content = mb_convert_encoding($content,"UTF-8","GBK");
//echo $content;
//curl_close($ch);
$html = new simple_html_dom();
$html->load($content);

$table = $html->find('table[id="bibl_info_table"]',0);
$tbody = $table->last_child();
//html->clear();
curl_close($ch);
//var_dump($tbody);

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
			  		<h1>书目信息</h1>
		 </div>
		 <table class="table">
		<?php
			//这个信息页面的DOM树太tm难抓了，通篇都是一样的标签，连个class，id什么的都没有
			foreach($tbody->find('tr[class=tableRegion]') as $tr )
			{
				echo "<li class=\"list-group-item\">";
				if($tr->id)
					continue;
				foreach( $tr->find('td') as $td)
				{
					//去掉链接和图片
					if($td->find('a') || $td->find('img'))
						continue;
					//书名等信息
					if($td->align) {
						$input = $td->find('input',0);
						if($input->type="text") {
							echo $input->value."</br>";
						}
					}
					//馆藏信息
					else {
						if($td->parent->parent->children[1]->first_child()->class) {						
								echo $td->plaintext;
								if($td->plaintext) {
									echo "</br>";
								}
							}
						//图书简介，作者信息，目录信息
						else { 
							//if($td->plaintext) {
								echo $td->plaintext;
							//}
						}
					}
				}
				echo "</li>";
			}
		?>
		</ul>

	</div>
    <script src="./static/js/jquery-2.1.1.min.js"></script>
    <script src="./static/js/bootstrap.min.js"></script>
  </body>
</html>
