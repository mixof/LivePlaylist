<div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">
                          <i class="fa fa-user "></i>  Пользователи
                        </h3>                   
                    </div>
                </div>  
               <!-- /.row -->
               
               
               <!-- Модальное окно EDIT -->
    <div class="modal fade" id="myModal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" id="edit_close" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="myModalLabel">Редактирование пользователя</h3>
          </div>
          <div class="modal-body">
        
      <!-- Форма редактирования в модальном окне -->
          <form id="form_edit" role="form" method="POST">

            <div class="row">					
             
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">
                    Имя
                  </label>
                  <input type="text" name="e_username" id="e_username" class="form-control" placeholder="Имя пользователя" value="">
                  
                </div>
                
                <div class="form-group">
                  <label class="control-label">
                    Email
                  </label>
                  <input type="email" name="e_email" id="e_email" class="form-control" placeholder="Например test@test.ru"  value="">
                  
                </div>               
                <div class="form-group">
                  <label class="control-label">
                    Новый Пароль
                  </label>
                  <input type="password" name="e_password" id="e_password" class="form-control" placeholder="Новый пароль"  value="">
                  
                </div>
                <div class="form-group">
                  <label class="control-label">
                    Role
                  </label></br>  
                   <label style="" class="radio-inline">
                    <input name="e_role" id="optionsRadiosInline" value="0" checked="" type="radio"/>
                   <img src="{SITEURL}/img/admin/user_icon.png" title="Пользователь" alt="Пользователь"/>
                  </label>               
                  <label style="" class="radio-inline">
                    <input name="e_role" id="optionsRadiosInline1" value="1" checked="" type="radio"/>
                    <img src="{SITEURL}/img/admin/admin_icon.png" title="Администратор" alt="Администратор"/>
                  </label>                  
                </div>
              </div>
            </div>

            <div id="edituser_error"></div>
            
            <div class="centered">
            <input name="form_uid" id='form_uid' type='hidden'/>
            <button id="edit_usr" class="btn btn-success btn-lg">Изменить</button>
            <button id="cancel_usr" class="btn btn-danger btn-lg"  data-dismiss="modal">Отмена</button>
            <img class="my_loader" src="{SITEURL}/img/admin/loader.gif" />
           
              
            </div>
          </form>
          
                <!-- Форма редактирования в модальном окне -->

          </div>
         
            
          </div>
        </div>
      </div> 



<!-- Модальное окно -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="myModalLabel">Добавление пользователя</h3>
          </div>
          <div class="modal-body">
        
      <!-- Форма редактирования в модальном окне -->
          <form id="form" role="form" method="POST">

            <div class="row">					
             
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">
                    Имя
                  </label>
                  <input type="text" name="username" id="username" class="form-control" placeholder="Имя пользователя" value="">
                  
                </div>
                
                <div class="form-group">
                  <label class="control-label">
                    Email
                  </label>
                  <input type="email" name="email" id="email" class="form-control" placeholder="Например test@test.ru"  value="">
                  
                </div>
                <div class="form-group">
                  <label class="control-label">
                    Логин
                  </label>
                  <input type="text" name="login" id="login" class="form-control" placeholder="Логин пользователя"  value="">
                  
                </div>
                <div class="form-group">
                  <label class="control-label">
                    Пароль
                  </label>
                  <input type="password" name="password" id="password" class="form-control" placeholder="Пароль пользователя"  value="">
                  
                </div>
                <div class="form-group">
                  <label class="control-label">
                    Role
                  </label></br>  
                   <label style="" class="radio-inline">
                    <input name="role" id="optionsRadiosInline1" value="0" checked="" type="radio"/>
                   <img src="{SITEURL}/img/admin/user_icon.png" title="Пользователь" alt="Пользователь"/>
                  </label>               
                  <label style="" class="radio-inline">
                    <input name="role" id="optionsRadiosInline1" value="1" checked="" type="radio"/>
                    <img src="{SITEURL}/img/admin/admin_icon.png" title="Администратор" alt="Администратор"/>
                  </label>	
                </div>
              </div>
            </div>

            <div id="adduser_error"></div>
            
            <div class="centered">
            <button id="subs" class="btn btn-success btn-lg">Добавить</button>
            <button id="cancel_usr" class="btn btn-danger btn-lg" data-dismiss="modal">Отмена</button>
            <img class="my_loader" src="{SITEURL}/img/admin/loader.gif" />
              
            </div>
          </form>
          
                <!-- Форма редактирования в модальном окне -->

          </div>
         
            
          </div>
        </div>
      </div>      
    <button id="show" class='btn btn-lg btn-primary'>Создать пользователя</button>
                           <div id="find_user"> <div class="form-group input-group">                          
                                <input id="search_field" class="form-control" type="text" placeholder="Логин пользователя" value=""/>
                                <span class="input-group-btn"><button id="search_btn" class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                            </div></div>
                <div class="row">
                    <div class="col-lg-6">
                        <h3>Список пользователей</h3>
                        <div class="table-responsive">
                 
                            <table id="users_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Логин</th>                                       
                                        <th>Имя</th>
                                        <th>e-mail</th>
                                        <th>Тип</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {users_list}
                                </tbody>                            
                            </table>
                                    <div class="wrapper-paging">
			      <ul>
				<li><a class="paging-back">&lt;</a></li>
				<li><a class="paging-this"><strong>1</strong> из <span>1</span></a></li>
				<li><a class="paging-next">&gt;</a></li>
			      </ul>
			    </div>
                        </div>
                    </div>                
                </div>
                <!-- /.row -->

            </div>