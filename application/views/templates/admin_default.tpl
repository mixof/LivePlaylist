<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>

    <title>Панель управления LivePlaylist</title>

    <!-- Bootstrap Core CSS -->
    <link href="{SITEURL}/css/admin/bootstrap.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="{SITEURL}/css/admin/sb-admin.css" rel="stylesheet"/>


    <!-- Custom Fonts -->
      <link href="{SITEURL}/css/admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    {ADD_CSS}
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  

   

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand2" href="/"><img src="{SITEURL}/img/admin/logo-admin-default.png" /></a>
                <a class="navbar-brand" href="/">Вернуться на сайт</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
               {new_comments}                  
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>{admin_login}<b class="caret"></b></a>
                    <ul class="dropdown-menu">                        
                        <li>
                            <a href="{SITEURL}/admin/logout"><i class="fa fa-fw fa-power-off"></i> Выход</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="{SITEURL}/admin/stat"><i class="fa fa-fw fa-dashboard"></i> Статистика</a>
                    </li>
                    <li>
                        <a href="{SITEURL}/admin/users"><i class="fa fa-user"></i> Пользователи</a>
                    </li>
                    <li>
                        <a href="{SITEURL}/admin/companyes"><i class="fa fa-home"></i> Компании</a>
                    </li>  
                     
                     <li>
                        <a href="{SITEURL}/admin/comments"><i class="fa fa-comments"></i> Комментарии</a>
                    </li>                      
                                     
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">
              {CONTENT}
              {404}

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="{SITEURL}/js/vendor/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{SITEURL}/js/vendor/bootstrap.js"></script>

    <!-- Morris Charts JavaScript 
    <script src="{SITEURL}/js/admin/plugins/morris/raphael.min.js"></script>
    <script src="{SITEURL}/js/admin/plugins/morris/morris.min.js"></script>
    <script src="{SITEURL}/js/admin/plugins/morris/morris-data.js"></script>-->
    {ADD_JS}    
   

</body>

</html>
