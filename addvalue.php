<?php session_start();?>
<?php require 'header.php';?>
<?php add_costs($_POST['goods'], $_POST['cost']);?>
<?php del_costs($_GET['delete']);?>
<?php update_costs();?>
<?php add_goods($_POST['groups'], $_POST['good_name'], $_POST['cost'], $_POST['units']);?>
<!--addvalue.php-->
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
				<h1>Новый чек за</h1>
				<time datetime="<?php echo date('Y-m-d');?>"><?php echo date('d.m.Y');?></time>
			</header>
			
			<div class="goods">
					<?php get_tablecosts($_SESSION['login']); ?>
			</div>
			
			<?php if (!empty($_SESSION['login']) and !empty($_SESSION['id'])) {
				require ('addvalueform.php');}				
			?>
			
			<div class="pagelinkitem"><a href="addnewgood.php">Добавить новый товар</a></div>
			
<?php require ('footer.php')?>