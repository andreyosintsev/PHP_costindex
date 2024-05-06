<!--login.php-->
<div class="login">
	<div class="logo">
		<a href="/">COSTINDEX</a>
	</div>
	<a href="<?php echo DIR_PAGES . 'register.php'; ?>" class="register">Регистрация</a>
	<form id="form_login" method="post" action="../api/user-login.php">
	<table id="login">
		<tr>
			<td>
				<input name="login" type="text" form="form_login" style="width: 100px; margin-right: 5px;" value="Логин" onfocus='if (this.value === "Логин") this.value=""' onblur='if (this.value === "") this.value="Логин"'>
			</td>
			<td>
				<input name="pass" type="password" form="form_login" style="width: 100px; margin-right: 5px;" value="Пароль" onfocus='if (this.value === "Пароль") this.value=""' onblur='if (this.value === "") this.value="Пароль"'>
			</td>
			<td>
				<input type="submit" name="submit" form="form_login" value="Войти" style="width: 50px;">
			</td>
		</tr>
	</table>
	</form>
</div>