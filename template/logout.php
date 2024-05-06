<?php session_start();?>
<!--logout.php-->
<div class="logout">
	<div class="logo">
		<a href="/">COSTINDEX</a>
	</div>
	<form id="form_logout" method="post" action="../api/user-logout.php">
	<table id="logout">
		<tr>
			<td>
				Пользователь: <?php echo $_SESSION['login']?>
			</td>
			<td>
				<input type="hidden" name="login" form="form_logout" value="<?php echo $_SESSION['login']?>">
				<input type="submit" name="submit" form="form_logout" value="Выйти" style="width: 50px;">
			</td>
		</tr>
	</table>
	</form>
</div>