<?php
    session_start();
    require 'functions.php';

    $group   = $_GET['group'];
    $filter  = $_GET['filter'];
    $sortby  = $_GET['sortby'];
    $sortdir = $_GET['sortdir'];
?>

<?php require 'template/header.php'; ?>

	<body>
		<?php include('template/ads-top.php'); ?>
		
		<?php 
			if (!empty($_SESSION['login']) && !empty($_SESSION['id'])) 
			require ('template/logged.php'); else require ('template/login.php');
		?>
		
		<div class="wrapper">
			<?php require 'template/abccontents.php'; ?>
			
			<header>
				<h1>Таблица товаров на</h1>
				<h2><time datetime="<?php get_date_newest('Y-m-d');?>"><?php get_date_newest('d.m.Y');?></time></h2>
			</header>
 
			<?php get_group_contents(); ?>
 
			<?php
				if (isset($filter))
					echo '<h2 style="margin: 10px 0 0 10px">'. $filter .'</h2>'; 
				else 
					echo '<h2 style="margin: 10px 0 0 10px">'. get_group_name($group) .'</h2>';
			?>
			
			<?php get_popular($group); ?>
					
			<article>
				<div class="goods">
					<?php get_goods_table($group, $filter, $sortby, $sortdir) ?>
				</div>
			</article>

<?php require 'template/footer.php'; ?>