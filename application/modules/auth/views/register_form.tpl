
<div class="titlediv"><h1>Регистрация аккаунта</h1></div>
<div class="myregform">
<fieldset class="set1">
<legend>Личная информация</legend>
<?php
   
echo ('<form method="post" action="{URL}/auth/register" id="reg_form">');

echo form_input(array('placeholder'=>'Фамилия', 'name'=>'firstname'));
echo form_input(array('placeholder'=>'Имя', 'name'=>'lastname'));
echo form_input(array('placeholder'=>'Email', 'name'=>'email_adress'));
?>



<legend>Данные авторизации</legend>
<?php
echo form_input(array('placeholder'=>'Логин', 'name'=>'username'));
echo form_input(array('placeholder'=>'Пароль', 'name'=>'password','type'=>'password'));
echo form_input(array('placeholder'=>'Повторите пароль', 'name'=>'password_confirm','type'=>'password'));


?>


<?php 
echo form_submit(array('name'=>'submit','class'=>'submitclass','value'=>'Зарегистрироваться'));
echo form_close();

?>
</div>

             
                    <div class="kite-container">
                      
                            <h3>Работа с сервисом | С чего начать</h3>
                        
                       <div class="whatsblock">
                            <img src="{URL}/img/kite-animation/icon-book.png"/> 
                             <div id="text">   
                            <h3>Регистрация</h3>
                            <p>Создайте свой аккаунт для работы с сервисом</p>
                            </div> 
                       </div>
                       <div class="whatsblock">
                             <img src="{URL}/img/kite-animation/icon-pencil.png"/>
                              <div id="text"> 
                               <h3>Создание компании</h3>
                            <p>Заполните информацию о своей компании</p>
                            </div> 
                       </div>
                       
                        <div class="whatsblock">
                             <img src="{URL}/img/kite-animation/icon-presentation.png"/>
                              <div id="text"> 
                               <h3>Создание плейлиста</h3>
                            <p>Создайте и заполните плейлист любимыми треками</p>
                            </div> 
                       </div>
                       
                            <div class="whatsblock">
                             <img src="{URL}/img/kite-animation/icon-lightbulb.png"/>
                              <div id="text"> 
                               <h3>Расскажите клиентам</h3>
                            <p>Предоставьте клиентам данные для подключения к вашему плейлисту</p>
                            </div> 
                       </div>
                       
                       
                            <div class="whatsblock">
                             <img src="{URL}/img/kite-animation/question-balloon.png"/>
                              <div id="text"> 
                               <h3>Следите за выбором клиентов</h3>
                            <p>Анализируйте выбор ваших клиентов и вносите необходимые изменения</p>
                            </div> 
                       </div>  
                                   
                    </div>
                    
                    <div class="clearance">
                   <?php echo validation_errors('<p class="myerror_msg">');?>
                   {MSG}
                    </div>
            