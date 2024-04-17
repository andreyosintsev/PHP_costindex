<?php session_start();?>
<?php require 'functions.php';?>
<?php runtime(); ?>
<?php login($_POST['login'], $_POST['pass']);?>
<!DOCTYPE html>
<html>
	<head>
		<title>Индекс потребительских цен</title>
		<meta charset="utf-8" />
		<meta name="description" content="Сервис отслеживания изменения уровня цен на потребительские товары повседневного спроса">
		<meta name='yandex-verification' content='67d5fc09920841b8' />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
			data.addColumn('number', 'Индекс');
			data.addRows([
					<?php
					$iter_in=0;
					$iter_out=0;
					$con=db_connect();
					$lastdays=date("c",strtotime("-24 weeks"));
					if ($_GET['lastdays']=='year') $lastdays=date("c",strtotime("-1 year"));
					if ($_GET['lastdays']=='6months') $lastdays=date("c",strtotime("-24 weeks"));
					if ($_GET['lastdays']=='month') $lastdays=date("c",strtotime("-4 weeks"));
					if ($_GET['lastdays']=='2years') $lastdays=date("c",strtotime("-2 year"));
					if ($_GET['lastdays']=='3months') $lastdays=date("c",strtotime("-12 weeks"));
					
					$sql=mysql_query("SELECT time_added, indexvalue FROM indexvalue WHERE time_added>'$lastdays' ORDER BY time_added ASC");
					while($row=mysql_fetch_array($sql)) {
						$key=date("Y-n-d",strtotime($row['time_added']));
						$days[$key]=$row['indexvalue'];
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
				'height':200,
				'legend':{'position':'none'},
				'titleTextStyle':{'fontName':'Georgia','fontSize':20,'bold':false},
				hAxis: {title: 'Дата'},
				vAxis: {title: 'Индекс, р.', textPosition: 'in'},
				chartArea: {width: '100%'},
			};
 
			// Instantiate and draw our chart, passing in some options.
			var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			chart.draw(data, options);
		}
		</script>
	</head>

<!--index.php-->
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
			<header>
				<h1>Индекс цен на <?php db_newestdate('d.m.Y');?></h1>
				<p><?php do_newestvalue();?></p>
				<p><?php do_changevalue();?></p>
				<time datetime="<?php db_newestdate('Y-m-d');?>"></time>
			</header>
 
			<article>
				<header>
					<h2>
						График изменения индекса потребительских цен
						<?php if ($_GET['lastdays']=='year') echo 'за год'; else
							if ($_GET['lastdays']=='6months') echo 'за полгода'; else
							if ($_GET['lastdays']=='month') echo 'за месяц'; else 
							if ($_GET['lastdays']=='2years') echo 'за два года'; else 
							if ($_GET['lastdays']=='3months') echo 'за квартал'; else 
							echo 'за полгода';							
						?>
					</h2>
				</header>
				<section>
					<div class="graph">
						<div id="chart_div" title="График изменения индекса за месяц" style="width: 100%; height: 200px;"></div>
						<table width="100%" style="border:0">
						<tr>
							<td width="20%"><a href="/?lastdays=month"<?php if ($_GET['lastdays']=='month') echo ' style="font-weight: bold"'?>>месяц</a></td>
							<td width="20%"><a href="/?lastdays=3months"<?php if ($_GET['lastdays']=='3months') echo ' style="font-weight: bold"'?>>квартал</a></td>
							<td width="20%"><a href="/?lastdays=6months"<?php if (($_GET['lastdays']=='6months')  or ($_GET['lastdays']=='')) echo ' style="font-weight: bold"'?>>полгода</a></td>
							<td width="20%"><a href="/?lastdays=year"<?php if ($_GET['lastdays']=='year') echo ' style="font-weight: bold"'?>>год</a></td>
							<td width="20%"><a href="/?lastdays=2years"<?php if ($_GET['lastdays']=='2years') echo ' style="font-weight: bold"'?>>два года</a></td>
						</td>
						</table>
					</div>
				</section>
				<section>
				<p>Из средств массовой информации каждый день поступают сведения, что цены постоянно растут. Этот ресурс создан для того, чтобы на конкретных значениях цен определить, как изменяются цены на потребительские товары день за днем.</p>
				
				<p>Индекс представляет собой среднее арифметическое цен на товары, представленные в рейтинге. Пересчет индекса осуществляется каждый день. Обновление цен производится по мере возможности.
				К учету взяты цены на товары популярной торговой сети магазинов с товарами на каждый день на основе реальных кассовых чеков.</p>
				</section>
			</article>
			
			<div id="vk_comments"></div>
			<script type="text/javascript">
				VK.Widgets.Comments("vk_comments", {limit: 10, attach: "*"});
			</script>


<?php require ('footer.php');?>