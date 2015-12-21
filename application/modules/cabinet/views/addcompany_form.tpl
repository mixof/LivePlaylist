
   <div >
                                    <h3>Создание компании</h3>
                                </div>
                                  
                            <?php echo validation_errors('<p class="myerror_msg2">');?>
                             {myerror_msg}
                            <form method="post" action="#"  enctype="multipart/form-data" class="contact-form">
                              <div style="float:left;position: reslative; ">
                                   <label class="control-label">Логотип:</label>
                                       <img src="{SITEURL}/img/companyes/no_company_logo.png" alt="image" id="image_preview" class="img-circle"/>
                                       
                                           <div class="controls controls-row">
                                              
                                    <div class="span4">
                                      <br />
                                    <span class="text-error">Максимальный размер 2мб.</span>
                                       <br /> <br />
                                        <input type="file" name="userfile" class="span4" id="image_preview" accept="image/*"/>
                                       <script type="text/javascript">
$('.span4').change(function() {
  var input = $(this)[0];
  var id = $(this).attr('id');
  if ( input.files && input.files[0] ) {
    if ( input.files[0].type.match('image.*') ) {
      var reader = new FileReader();
      reader.onload = function(e) { $('#'+id).attr('src', e.target.result); }
      reader.readAsDataURL(input.files[0]);
    }
  }
});
</script>
                                    </div>
                                  
                                    
                                </div>    
                                    </div>
                                <div class="controls controls-row">
                                    <div class="span4"  style="float:left;">
                                        <label class="control-label">Название: <span class="text-error">*</span></label>
                                        <input name="title" class="span4" type="text" placeholder="Название компании"/>
                                    </div>  
                                    
                                <div class="controls controls-row">
                                    <div class="span4"  style="float:left;">
                                        <label class="control-label">Категория: <span class="text-error">*</span></label>
                                        
                                     <select name="category" id="cat" size = "1">

                                   {categoryes_value}

</select> 
                                    </div>
                                </div>  
                                
                                  <div class="controls controls-row">
                                    <div class="span4">
                                        <label class="control-label">Адрес: <span class="text-error">*</span></label>
                                        <input name="adres" class="span4" type="text" placeholder="Адрес"/>
                                    </div>
                                </div>    
                                     
                                   
                                          <div class="controls controls-row" >
                                    <div class="span4">
                                        <label class="control-label">Телефон:</label>
                                        <input name="phone" class="span4" type="text" placeholder="Телефон"/>
                                    </div>
                                </div>                        
  
                                </div>   
                                         
                                                   
                             
                                <div class="controls" style="float:left;">                               
                                  <label class="control-label">Описание: <span class="text-error">*</span></label>
                                    <textarea name="description" class="span8" id="mytextarea" placeholder="Ваш текст.."></textarea>
          <script type='text/javascript'>
tinymce.init({
    selector: 'textarea',
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
});
</script>
          </div>
 
                                
                                <div class="controls">
                                    <button type="submit" class="btn btn-primary pull-left span2">Добавить</button>
                                </div>
                            </form>
                           
                            