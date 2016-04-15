<div class = "login_info">
<h3>Вход для пользователя</h3>
    <form action="" method="post" name="login_form" onsubmit="return validateLoginForm()">
    <table class="login">
        <tr>
            <td><img src="../../images/user.png"/></td>
            <td><input type="text" name="user_login" autofocus></td>
            <td><p id="login_validation_message"></p></td><!--Рамка выпирает из-за неё-->
        </tr>
        <tr>
            <td><img src="../../images/lock.png"/></td>
            <td><input type="password" name="user_password"></td>
            <td><p id="password_validation_message"></p></td>
        </tr>
        <th colspan="2" style="text-align: right">
            <input type="submit" value="ВОЙТИ" name="login_go"
                   style="width: 150px; height: 30px;"></th>
    </table>
</form>
</div>

<?php
extract($data);
if (isset($data['status'])) {
    if ($data['status'] == "access_granted") {
        ?>
        <p style="color:green">Авторизация прошла успешно.</p>
    <?php } elseif ($data['status'] == "access_denied") { ?>
        <p style="color:red">Логин и/или пароль введены неверно.</p>
    <?php } elseif ($data['status'] == "error") { ?>
        <p style="color:red">Ошибка при работе с базой данных, обратитесь к администратору</p>
        <?php
    }
}
?>
