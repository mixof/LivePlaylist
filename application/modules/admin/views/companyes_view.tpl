<div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">
                          <i class="fa fa-home "></i> Компании
                        </h3>                   
                    </div>
                </div>  
               <!-- /.row -->
               
               
               
                      
               <!-- Модальное окно tracks -->
    <div class="modal fade" id="myModal_tracks" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" id="edit_close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="myModalLabel">Редактирование компании</h3>
          </div>
          <div class="modal-body">
        
     
              <form id="form_edit" role="form" target="rFrame" method="POST" type="multipart/form-data">

            <div class="row">					
             
              <div class="col-md-6">
              
               <div class="form-group">                 
                   
                   <img src="" title="Фото компании" style="width:140px; height:140px;" id="image_preview" alt="Фото компании"/>
                     <br />
                                    <span class="text-error">Максимальный размер 2мб.</span>
                                       <br /> <br />
                                        <input type="file" name="userfile" class="span4" id="image_preview" accept="image/*" />
                                        <input type="hidden" name="logo" id="logo" value=""/>              
                              
                </div>

                <div class="form-group">
                  <label class="control-label">
                   Название
                  </label>
                  <input type="text" name="c_name" id="c_name" class="form-control" placeholder="название" value=""/>
                  
                </div>
                
                <div class="form-group">
                  <label class="control-label">
                    Телефон
                  </label>
                  <input type="text" name="c_phone" id="c_phone" class="form-control" placeholder="телефон"  value=""/>
                  
                </div>               
                <div class="form-group">
                  <label class="control-label">
                    Адресс
                  </label>
                  <input type="text" name="c_adress" id="c_adress" class="form-control" placeholder="адресс"  value=""/>
                  
                </div>
                    <div class="form-group">
                  <label class="control-label">
                    Описание
                  </label>
                  <textarea style="max-width:550px; min-width:550px;" name="description" id="description" class="form-control" placeholder="Описание"  value=""></textarea>
                  
                </div>
              </div>
            </div>

            <div id="editcompany_error"></div>
            
            <div class="centered">
            <input name="form_uid" id='form_uid' type='hidden'/>
            <button id="edit_company" class="btn btn-success btn-lg">Изменить</button>
            <button id="edit_closet" class="btn btn-danger btn-lg">Отмена</button>
            <img class="my_loader" src="{SITEURL}/img/admin/loader.gif" />
            </div>
          </form>
          </div>
         
            
          </div>
        </div>
      </div> 

              
               
               
               
               
        

                           <div id="find_user"> <div class="form-group input-group">                          
                                <input id="search_field" class="form-control" type="text" placeholder="поиск.." value=""/>
                                <span class="input-group-btn"><button id="search_btn" class="btn btn-default" type="button"><i class="fa fa-search"></i></button></span>
                            </div></div>
                <div class="row">
                    <div class="col-lg-6">
                        <h3>Список компаний</h3>
                        <div class="table-responsive">
                 
                            <table id="companyes_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Логотип</th>  
                                        <th>Категория</th>                                       
                                        <th>Название</th>
                                        <th>Описание</th>
                                        <th>Адрес</th>
                                        <th>Телефон</th>
                                        <th>Владелец</th>
                                        <th>Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {companyes_list}
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