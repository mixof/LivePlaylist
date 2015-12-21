 <script> $(document).ready(init); function init(){ $(function(){ $(".jspPane").sortable({axis: 'y'}); }); }</script>
 
 <div class="block-controls">
                                  <h3>{company_name}</h3>
                                </div>
 <div class="block-controls" id="cc">
                          <a id="delete" href="#">Удалить</a>   <a id="edit" href="{SITEURL}/cabinet/editcompany/{this_company}">Редактировать</a>   
                                </div>
                          
   <div class="blog-author">
                                    <div class="image-with-transparent-border pull-left">
                                
                                        <img id="img-logo" src="{SITEURL}/img/companyes/{company_logo}" alt="{company_name}" class="img-circle" />
                                    </div>                                    
                                    <h4>Описание:</h4>
                                    <span class="muted">
                                       {company_description}
                                    </span>
                                  
                                    
                                </div>
                                  <div class="company_contact">
                                    <p>
                                    <b>Адрес:</b>  {company_adress}
                                    </p>
                                    
                                     <p>
                                    <b>Телефон:</b> {company_phone}
                                    </p>
                                    
                                    <p>
                                    <b>Категория:</b>  {company_category}
                                    </p>
                                    </div>
                                    
                                <div class="clear"></div>                               
                                  <div id="network_options" class="panel panel-default">
    <div class="panel-heading">    
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                 <div class="block-header">
                             <h4 class="panel-title"><i class="icon-cogs icon-2x pull-left"></i>Настройки подключения</h4>
                                </div>
              </a>
        
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
      <div class="alert alert-warning">
    
      Используйте данную информацию для подключения в мобильном приложении.
      </div>
       <div class="pull-left">
       <h4 >Данные для подключения:</h4><br />
       <b>id компании</b>: <i>{this_company}</i><br />
       <b>Кодовое слово</b>: <a href="#" name="code_word" id="code_word" url="{SITEURL}/cabinet/company/{this_company}"><i>{code_word}</i></a>
       </div>
                            <div class="pull-right">
                                  {qr_code}
                            </div>
                                  
                           
                        </div>          
      
    </div>
  </div> <br />
                                
                                 <div class="block-header">
                                  <h3>Плейлист</h3>
                                </div>
                               
                                <div class="pl_controls"> <a href="{SITEURL}/cabinet/upload/{this_company}" class="butonka"><i class="icon-plus"></i> Добавить трек</a> <a href="#delete" class="butonka"><i class="icon-trash"></i> Удалить отмеченые</a> <a href="#select_all" class="butonka"><i class="icon-check-sign"></i> Выделить все</a> <a href="#select_none" class="butonka"><i class="icon-remove"></i> Снять выделение</a> <a href="#refresh" class="butonka"><i class="icon-refresh"></i> Обновить список</a></div>
                          
                            <br />
 
         
                            
{TRACKS}

