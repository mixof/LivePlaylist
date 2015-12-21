
<div id="login_form">

	<h1>Авторизация</h1>
    <?php 
	echo ('<form method="post" action="{URL}/auth/login">');
	echo form_input(array('placeholder'=>'Логин', 'name'=>'userlogin'));
	echo form_password(array('placeholder'=>'Пароль', 'name'=>'userpass'));
	echo form_submit('submit', 'Войти');
	echo ('<a href="{URL}/auth/register">Зарегистрироваться</a>');
	echo form_close();
	?>

</div><!-- end login_form-->
{LOGMSG}
