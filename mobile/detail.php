<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<title>哈尔滨工业大学图书馆</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
	<link rel="shortcut icon" href="/hitlib2/static/ico/favicon.png">
	<link href="/hitlib2/static/css/bootstrap.css" rel="stylesheet">
	<link href="/hitlib2/static/css/mobile_detail.css" rel="stylesheet">
</head>

<body>
	<div class="container">

		<?php
		include_once('../simple_html_dom.php');

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

		?>

		<ul class="list-unstyled">
			<?php
			if($book_type == "589") {
				$table = $html->find('table[id="bibl_info_table"]',0);
				$tbody = $table->last_child();
				foreach($tbody->find('tr[class=tableRegion]') as $tr )
				{
					echo "<li>";
					if($tr->id)
						continue;
					foreach( $tr->find('td') as $td)
					{
						//去掉链接和图片
						if($td->find('a') || $td->find('img'))
							continue;
						//去掉“相关信息”和“其他信息”，这两项基本上都是空的
						if($td->plaintext == "相关信息：" || $td->plaintext == "其他信息：")
							continue;
						//书名等信息
						if($td->align) {
							$input = $td->find('input',0);
							if($input->type="text" && $input->value) {
								$value = $input->value;
								$value = str_replace(" /","",$value);
								$value = str_replace(" =","",$value);
								echo $value."<br>";
							}
						}
						//馆藏信息
						else {
							if($td->parent->parent->children[1]->first_child()->class) {														
								echo $td->plaintext;
								if($td->plaintext) {
									echo "<br>";
								}
							}
							//图书简介，作者信息，目录信息
							else { 
								echo $td->plaintext;	
							}
						}
					}
					echo "</li>";
				}
			}
			else if ($book_type == "590") {
				$table = $html->find('table[id="arrive_info_table"]',0);
				$tbody = $table->last_child();
				$table2 = $tbody->find('table[class="tableRegion"]',0);
				foreach($table2->find('tr') as $tr )
				{
					echo "<li>";
					foreach( $tr->find('td') as $td)
					{
						if($td->width)
							continue;
						echo $td->plaintext."&nbsp";
					}
					echo "</li>";
				}
			}
			
			else if($book_type == "666") {
				$table = $html->find('table[id="paper_info_table"]',0);
				$tbody = $table->last_child();
				$table2 = $tbody->find('table[class="tableRegion"]',0);
				foreach($table2->find('tr') as $tr )
				{
					echo "<li>";
					if($tr->id)
						continue;
					foreach( $tr->find('td') as $td)
					{
						echo $td->plaintext;
					}
					echo "</li>";
				}
			}
			curl_close($ch);
			?>
		</ul>



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
