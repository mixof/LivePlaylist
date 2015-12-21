<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<title>Вход в админ панель</title>
	<link rel="stylesheet" href="{SITEURL}/css/admin/admin_auth.css"/>

	<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>


  <section class="container">
    <div class="login">
      <h1>Вход в панель управления</h1>
      <form method="post" action="{URL}/admin">
        <p><input type="text" name="login" value="" placeholder="Логин"></p>
        <p><input type="password" name="password" value="" placeholder="Пароль"></p>
     
       <p class="submit"><a href="/"><img src="{SITEURL}/img/logo-big-default.png"/></a> <input type="submit" name="commit" value="Войти"></p>
      </form>
    </div>

{validation}
    
  </section>
</body>
</html>
