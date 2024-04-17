<?php session_start();?>
<?php require 'header.php';?>
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
			<header>
				<h1>Добавить новый товар</h1>
			</header>
			<?php if (!empty($_SESSION['login']) and !empty($_SESSION['id'])) {
				require ('addgoodform.php');}				
			?>				
<?php require ('footer.php')?>