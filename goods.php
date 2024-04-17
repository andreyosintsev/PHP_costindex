<?php session_start();?>
<?php require 'header.php';?>
<?php add_goods($_POST['goods'], $_POST['cost'], $_POST['units']);?>
<?php $group=$_GET['group'];?>
<?php $filter=$_GET['filter'];?>
<?php $sortby=$_GET['sortby'];?>
<?php $sortdir=$_GET['sortdir'];?>
<!--goods.php-->
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
			<?php require 'abcindex.php'; ?>
			
			<header>
				<h1>Таблица товаров на</h1>
				<h2><time style="margin-top:5px" datetime="<?php db_newestdate('Y-m-d');?>"><?php db_newestdate('d.m.Y');?></time></h2>
			</header>
 
			<?php get_group_index(); ?>
 
			<?php if (isset($filter)) echo '<h2 style="margin: 10px 0 0 10px">'.$filter.'</h2>'; else echo '<h2 style="margin: 10px 0 0 10px">'.get_group_name($group).'</h2>';?>
			
			<?php get_popular($group); ?>
					
			<article>
				<div class="goods">
					<?php get_tablegoods($_GET['filter'], $sortby, $sortdir, $group) ?>
				</div>
			</article>

<?php require ('footer.php')?>