<?php session_start();?>
<?php require 'header.php';?>
<?php $error = register_user($_POST['name'], $_POST['nick'], $_POST['reg_email'], $_POST['password1'], $_POST['password2']);?>
<?php if ($error == "Сообщение отправлено") header("Location: register_message_sent.php");?>
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
			<div class="form_reg">
				<form id="form_register" method="post" action="register.php" style="margin: 20px 0">
					<table>
						<tr><td>Имя:</td><td><input type="text" name="name" placeholder="Ваше имя" form="form_register" value="<?php echo $_POST['name'];?>"></td>
						<tr><td>Логин:</td><td><input type="text" name="nick" placeholder="Символы A-z, 0-9 и _" form="form_register" value="<?php echo $_POST['nick'];?>"></td>
						<tr><td>e-mail:</td><td><input type="text" name="reg_email" placeholder="e-mail" autocomplete="off" form="form_register" value="<?php echo $_POST['reg_email'];?>"></td>
						<tr><td>Пароль:</td><td><input type="password" name="password1" placeholder="Минимум 8 символов" autocomplete="off" form="form_register"></td>
						<tr><td>Подтвердите пароль:</td><td><input type="password" name="password2" placeholder="Пароль ещё раз" autocomplete="off" form="form_register"></td>
						</table>
						<?php echo '<p style="width: 300px; text-align: center;">'.$error.'</p>'; ?>
					<input type="submit" value="Зарегистрироваться" class="form_register_submit" form="form_register">
				</form>
			</div>
<?php require ('footer.php')?>