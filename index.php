<?php 
	session_start();
	require 'functions.php';
	runtime();

	$login = $_POST['login'];
	$pass = $_POST['pass'];

	login($login, $pass);
?>

	<?php require 'template/header.php'; ?>

	<script src="https://www.google.com/jsapi"></script>
	<script src="scripts/chart.js"></script>
	<script>
		google.load('visualization', '1.0', {'packages':['corechart']});
		google.setOnLoadCallback(() => drawChart(
			[		
				<?php graph_index_fill($_GET['lastdays']); ?>
			], 
			'chart_div',
            'Индекс')
		);
	</script>
	<body>

	<?php include('template/ads-top.php'); ?>
		
	<?php if (!empty($_SESSION['login']) and !empty($_SESSION['id'])) 
		require ('template/logged.php'); else require ('template/login.php');
	?>
		
	<div class="wrapper">
		<header>
			<h1>Индекс цен на <?php get_date_newest('d.m.Y');?></h1>
			<p><?php get_index_newest();?></p>
			<p><?php get_index_change();?></p>
			<time datetime="<?php get_date_newest('Y-m-d');?>"></time>
		</header>

		<article>
			<header>

				<?php
					$time_interval = convert_date_interval_to_string($_GET['lastdays']);

					function get_style($time_interval, $interval) {
						if ($time_interval == $interval) echo 'style="font-weight: bold"';
					}
				?>

				<h2>
					График изменения индекса потребительских цен за	<?php echo $time_interval; ?>
				</h2>
			</header>
			<section>
				<div class="graph">
					<div id="chart_div" title="График изменения индекса за <?php echo $time_interval; ?>"></div>
					<table width="100%" style="border:0">
					<tr>
						<td width="20%">
							<a href="/?lastdays=month"   <?php get_style($time_interval, 'month'); ?> >месяц</a>
						</td>
						<td width="20%">
							<a href="/?lastdays=3months" <?php get_style($time_interval, '3months'); ?> >квартал</a>
						</td>
						<td width="20%">
							<a href="/?lastdays=6months" <?php get_style($time_interval, '6months'); ?> >полгода</a>
						</td>
						<td width="20%">
							<a href="/?lastdays=year"    <?php get_style($time_interval, 'year'); ?> >год</a>
						</td>
						<td width="20%">
							<a href="/?lastdays=2years"  <?php get_style($time_interval, '2years'); ?> >два года</a>
						</td>
					</tr>
					</table>
				</div>
			</section>
			<section>
				<p>Из средств массовой информации каждый день поступают сведения, что цены постоянно растут. Этот ресурс создан для того, чтобы на конкретных значениях цен определить, как изменяются цены на потребительские товары день за днем.</p>
				
				<p><strong>Индекс - среднее арифметическое цен на товары, представленные в рейтинге.</strong></p>
				
				<p>Пересчет индекса осуществляется каждый день. Обновление цен производится по мере возможности.
				К учету взяты цены на товары популярной торговой сети магазинов с товарами на каждый день на основе реальных кассовых чеков.</p>
			</section>
		</article>
		
		<?php include 'template/comments.php';?>

<?php require 'template/footer.php'; ?>