<?php session_start();?>
<!--logged.php-->
		<div class="logout">
			<div class="logo">
				<a href="http://costindex.ru/">COSTINDEX</a>
			</div>
			<form id="form_logout" method="post" action="index.php">
			<table id="logout">
			<tr>
				<td>
					Пользователь: <?php echo $_SESSION['login']?>
				</td>
				<td>
					<input type="hidden" name="login" form="form_logout" value="logout">
					<input type="submit" name="submit" form="form_logout" value="Выйти" style="width: 50px;">
				</td>
			</tr>
			</table>
			</form>
		</div>