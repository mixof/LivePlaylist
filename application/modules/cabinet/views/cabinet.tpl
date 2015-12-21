<section>
        <div class="sub-header">
                <div class="container">
                    <ul class="breadcrumb">
                    
                    </ul>
                    <div class="row">
                        <div class="span12">
                            <div class="title-page">
                                <h3>Личный кабинет</h3>
                                <p>
                                 Настройка компаний и списков воспроизведения.
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="blog-medium-page block">
                <div class="container">
                    <div class="row">
                        <div class="span4">
                            <div class="side-category" data-step="3" data-intro="<i class='icon-home icon-2x pull-left'></i> Здесь расположен список ваших компаний. Добавьте свою первую компанию для начала работы с сервисом." data-position='right'>
                                <div class="block-header">
                                    <h3>Мои компании</h3>
                                </div>     
                                  <div class="block-header">                         
                                 
                                    <a href="/cabinet/addcompany"> <i class="icon-plus-sign"></i>Добавить компанию</a>
                                    </div> 
                                <ul class="ul-side-category">
                                <br />
                                 {user_companyes}                                
                                </ul>
                            </div>
                            
                            <div class="side-category">
                                <div class="block-header">
                                    <h3>Прочее</h3>
                                </div>     
                                  <div class="block-header" data-step="4" data-intro="<i class='icon-comments icon-2x pull-left'></i>Пишите свои отывы о сервисе и они обязательно появятся на главной странице, после того как пройдут проверку администратором." data-position='top'>                         
                                 
                                    <a href="#add_comment" id="add_comment"> <i class="icon-comment"></i>Оставить отзыв</a>
                                    </div> 
                            </div>
                        
                        </div>
                        <div class="span8">                              
                        {404}
                         {ADDCOMPANY_FORM}
                         {COMPANY}                      
                        </div>
                    </div>
                </div>
            </div>     
        </section>
     
        <div id="form-content" class="modal hide fade in" style="display: none;">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3>Оставить отзыв:</h3>
    </div>
    <div class="modal-body">
        <form class="contact" id="contact" name="contact">           
            <label class="label" for="message">Текст отзыва:</label></br>
            <textarea style="max-width:97%; min-width:97%; min-height:250px; max-height:350px;" name="message" placeholder="Текст вашего отзыва.." class="input-xlarge"></textarea>
        </form>
         <div id="editcompany_error"></div>
    </div>
    <div class="modal-footer">
        <img class="my_loader" src="{SITEURL}/img/admin/loader.gif" />
        <button class="btn btn-success" id="submit_comment">Отправить</button>
        <a href="#" class="btn btn-danger btn-lg" data-dismiss="modal" >Отмена</a>
    </div>
</div>
                             
     