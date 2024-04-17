<?php session_start();?>
<?php require 'header.php';?>
<?php $confirmed=confirm_user($_GET['login'], $_GET['number']);?>
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
				<h1>Регистрация пользователя</h1>
			</header>
			<section>
				<?php if ($confirmed=='confirmed') echo '<p style="margin:100px 20px; text-align: center;">Регистрация успешно завершена.<br><br> Теперь вы можете воспользоваться своими <b>логином</b> и <b>паролем для доступа</b> на сайт</p>.'; else echo '<p style="margin:100px 20px; text-align: center; color:#FF0000">Ошибка подтверждения регистрации<br><br> Пожалуйста, пройдите регистрацию заново.</p>';?>
			</section>
			
<?php require ('footer.php')?>