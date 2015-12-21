<div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header">
                          <i class="fa fa-comment"></i> Комментарии пользователей
                        </h3>                   
                    </div>
                </div>  
               <!-- /.row -->
               
               
                               <div class="row">
                    <div class="col-lg-6">                      
                        <div class="table-responsive">
                 
                            <table id="companyes_table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Автор</th>  
                                        <th>email</th>                                       
                                        <th>Текст</th>
                                        <th>Дата добавления</th>
                                        <th>Статус</th>
                                        <th>Действие</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    {comments_list}
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
              
              
              
               <div class="modal fade" id="comment_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" id="edit_close" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h3 class="modal-title" id="myModalLabel">Редактирование комментария</h3>
          </div>
          <div class="modal-body">
        
     
              <form id="form_edit" role="form" target="rFrame" method="POST">

            <div class="row">					
             
              <div class="col-md-6">              
            
                    <div class="form-group">
                  <label class="control-label">
                    Текст комментария
                  </label>
                  <textarea style="max-width:550px; min-width:550px; min-height:150px; max-height:250px;" name="comment_text" id="comment_text" class="form-control" placeholder="Текст комментария.."  value=""></textarea>
                  
                </div>
              </div>
            </div>

            <div id="editcompany_error"></div>
            
            <div class="centered">
            <input name="form_uid" id='form_uid' type='hidden'/>
            <button id="edit_comment" class="btn btn-success btn-lg">Изменить</button>
            <button id="edit_closet" class="btn btn-danger btn-lg" data-dismiss="modal">Отмена</button>
            <img class="my_loader" src="{SITEURL}/img/admin/loader.gif" />
            </div>
          </form>
          </div>
         
            
          </div>
        </div>
      </div> 
              
              
    
               
</div>