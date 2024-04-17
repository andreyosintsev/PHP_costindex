<?php session_start();?>
<?php require 'functions.php';?>
<?php $idx=$_GET['idx'];?>
<!DOCTYPE html>
<html>
	<head>
		<title>Цена <?php echo get_good($idx, false);?> | Индекс потребительских цен</title>
		<meta charset="utf-8" />
		<meta name="description" content="Сервис отслеживания изменения уровня цен на потребительские товары повседневного спроса">
		<meta name='yandex-verification' content='67d5fc09920841b8' />
		<link rel="stylesheet" href="http://costindex.ru/style.css">
		<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	
		<script type="text/javascript" src="//vk.com/js/api/openapi.js?115"></script>
		<script type="text/javascript">
			VK.init({apiId: 4548704, onlyWidgets: true});
		</script>
		
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
 
		// Load the Visualization API library and the piechart library.
		google.load('visualization', '1.0', {'packages':['corechart']});
		google.setOnLoadCallback(drawChart);
		// ... draw the chart...
 
		function drawChart() {
 
			//Create the data table.
					
			var data = new google.visualization.DataTable();
			data.addColumn('date', 'Дата');
			data.addColumn('number', 'Цена');
			data.addRows([
				<?php
					$iter_in=0;
					$iter_out=0;
					$con=db_connect();
					$days=array();
					$sql=mysql_query("SELECT date, cost FROM costs WHERE goods='$idx' ORDER BY date ASC");
					while($row=mysql_fetch_array($sql)) {
						$key=date("Y-n-d",strtotime($row['date']));
						$days[$key]=$row['cost'];
						$iter_in=$iter_in+1;
					}
					db_disconnect($con);
					foreach($days as $day=>$count) {
						$date=strtotime($day)*1000;
						echo "[new Date($date), $count]";
						$iter_out=$iter_out+1;
						if ($iter_out<$iter_in) echo ",\n"; else echo "\n";
				}?>
				]);
			        
			var options = {'title':'',
				'width':706,
				'height':200,
				'legend':{'position':'none'},
				'titleTextStyle':{'fontName':'Georgia','fontSize':20,'bold':false},
				hAxis: {title: 'Дата'},
				vAxis: {title: 'Цена, р.', textPosition: 'in'},
				chartArea: {width: '100%'},
			};
 
			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
		</script>
	</head>
<!--good.php-->
	<body>
		<div class="banner_top">
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- costindex.ru - хедер 728х90 -->
			<ins class="adsbygoogle"
				style="display:inline-block;width:728px;height:90px"
				data-ad-client="ca-pub-4019665621332188"
				data-ad-slot="2557863983"></ins>
			<script>
				(adsbygoogle = window.adsbygoogle || []).push({});
			</script>	
		</div>
		
		<?php if (!empty($_SESSION['login']) and !empty($_SESSION['id'])) 
			require ('logged.php'); else require ('login.php');
		?>
		
		<div class="wrapper">
			<nav>
				<span class="group">
					<a href="http://costindex.ru/goods.php">Товары</a> > <a href="http://costindex.ru/goods.php?group=<?php echo get_group($idx); ?>"><?php echo get_group_name(get_group($idx)); ?></a>
				</span>
				<?php get_edit_link();?>
			</nav>
			<header>
				<h1><?php echo get_good($idx);?></h1>
			</header>
			<section>
				<div class="thumb">
					<div class="thumb_img">
						<?php if (get_thumb($idx)!='nophoto.jpg') echo '<img src="/thumbs/'.get_thumb($idx).'" alt="'.get_good($idx).'" title="'.get_good($idx).'">'; else {
							if (!empty($_SESSION['login']) and !empty($_SESSION['id'])) echo '<a href="#" title="Добавить изображение"><img src="/thumbs/addphoto.jpg"></a>'; else echo '<img src="/thumbs/nophoto.jpg" title="Изображение пока отсутствует">';}?>
					</div>
				
<script type="text/javascript">(function(w,doc) {
if (!w.__utlWdgt ) {
    w.__utlWdgt = true;
    var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';
    s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
    s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';
    var h=d[g]('body')[0];
    h.appendChild(s);
}})(window,document);
</script>
<div data-mobile-view="false" data-share-size="20" data-like-text-enable="false" data-background-alpha="0.0" data-pid="1371463" data-mode="share" data-background-color="#ffffff" data-share-shape="rectangle" data-share-counter-size="12" data-icon-color="#ffffff" data-mobile-sn-ids="fb.vk.tw.wh.ok.gp." data-text-color="#000000" data-buttons-color="#FFFFFF" data-counter-background-color="#ffffff" data-share-counter-type="common" data-orientation="horizontal" data-following-enable="false" data-sn-ids="fb.vk.tw.ok.gp.lj." data-preview-mobile="false" data-selection-enable="false" data-exclude-show-more="false" data-share-style="1" data-counter-background-alpha="1.0" data-top-button="false" class="uptolike-buttons" ></div>
				</div>
				<div class="desc">
					<div class="desc_price"><?php echo get_price($idx).' р.';?></div>
					<div class="desc_price_minmax"><?php echo get_price_min($idx).' - '.get_price_max($idx).' р.';?></div>
					<div class="desc_params"><?php get_params($idx);?></div>
					<h3>Изменение цены</h3>
					<div class="desc_pricechange"><?php echo get_tablepricechange($idx);?></div>
				</div>
				<div class="clear">
			</section>
			<section>
					<div class="graph">
						<div id="chart_div" title="График изменения цены на товар" style="width: 706px; height: 200px;"></div>
					</div>
			</section>
			<section>
					<div class="related">
						<?php get_related($idx); ?>					
					</div>
			</section>			
			<div id="vk_comments"></div>
			<script type="text/javascript">
				VK.Widgets.Comments("vk_comments", {limit: 10, width: "706", attach: "*"});
			</script>
<?php require ('footer.php')?>