<!--form-register.php-->
<form id="form_register" method="post" action="<?php echo DIR_API; ?>user-register.php" style="margin: 20px 0;">
    <table>
        <tr>
            <td>Имя:</td>
            <td>
                <input type="text" name="name" placeholder="Ваше имя" form="form_register" value="">
            </td>
        <tr>
            <td>Логин:</td>
            <td>
                <input type="text" name="login" placeholder="Символы A-z, 0-9 и _" form="form_register" value="">
            </td>
        <tr>
            <td>e-mail:</td>
            <td>
                <input type="text" name="email" placeholder="e-mail" autocomplete="off" form="form_register" value="">
            </td>
        <tr>
            <td>Пароль:</td>
            <td>
                <input type="password" name="password1" placeholder="Минимум 8 символов" autocomplete="off" form="form_register">
            </td>
        <tr>
            <td>Подтвердите пароль:</td>
            <td>
                <input type="password" name="password2" placeholder="Пароль ещё раз" autocomplete="off" form="form_register">
            </td>
        </tr>
    </table>
    <input type="submit" value="Зарегистрироваться" class="form_register_submit" form="form_register">
</form>