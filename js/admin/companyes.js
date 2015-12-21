 $(function() {  
    
   $(".wrapper-paging").show();
  TABLE.paginate('#companyes_table', 10);
  
    trArr = $("#companyes_table tr:not(:first)");

	townArr = $("td:nth-child(1n+1)", trArr);
	 
	$("#search_field").keyup(function() {

	    if(!this.value)

	        return trArr.show()

	    trArr.hide();

	    townArr.filter(":contains("+this.value+")").parent("tr").show();

	});
    
    
       
 
      $('#edit_close').button().click(function() {
        
       full_close_modal("myModal_tracks","editcompany_error");
    });
    
    
         $('#edit_closet').button().click(function() {
        
       full_close_modal("myModal_tracks","editcompany_error");
       return false;
    });
    
    
    function search_click(value)
    {
        trArr = $("#companyes_table tr:not(:first)");

	    townArr = $("td:nth-child(1n+1)", trArr);
	 
     if(!value) return trArr.show();
      
       trArr.hide();

	    townArr.filter(":contains("+value+")").parent("tr").show();
     
    }
    
    
     $("#search_btn").click(function() {
           
           var login= $('#search_field').val();
           
             search_click(login);       
    
    });
    
    
      $("tbody").on('click',"a[href='#edit']", function() {
  
       $('#myModal_tracks').modal('show');
       
       
       
   var parent=$(this).parents('tr');
       var user_id;  
       $(parent).find('td').each(function(i,val){
        
      
       var value=$(val).html();  
        
        
       if(i==0) 
       {    
        var uri=$(value).attr('src');
        var name = uri.substr(uri.lastIndexOf("/") + 1);
       $('#logo').val(name);       
     $('#image_preview').attr('src',uri);
       }
       
      
       if(i==2) $('#c_name').val(value);
       if(i==3) $('#description').val(value);
       if(i==4) $('#c_adress').val(value);
       if(i==5) $('#c_phone').val(value);
       if(i==7) {
      
       var company_id=$(this).find('#c_uid').val();
       $('#form_uid').val(company_id);
       
       }
      /*
        if(i==2) $('#e_email').val(value);
        if(i==3) {
            if($(value).attr('alt')=='Администратор') $('#optionsRadiosInline1').prop("checked", true);            
            else  $('#optionsRadiosInline').prop("checked", true);
        }
        if(i==4) { user_id=$(this).children('#e_uid').val(); $('#form_uid').val(user_id);}*/
       });
  
       
       return false;
       
       
       });
       
       
       
   $('#edit_company').button().click(function() {
    
    
     edit_company();
     
       
     return false;
    });
       
       
        function update_row(user_id)
    {
       return $("#c_uid[value='"+user_id+"']");
    }
       
       
       function edit_company()
       {
        
        var msg = $('#form_edit').serialize();
      msg+="&act=edit";
        $('.my_loader').show();        
        $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/admin/companyes',
          data: msg,
          success: function(data) {
           
          if(data['status']=='error')
          {          
            $('#editcompany_error').addClass("alert alert-danger");
            $('#editcompany_error').html(data['msg']);              
            $('#editcompany_error').show();
                  
          }else if(data['status']=='success')
          {
            $('#editcompany_error').removeClass('alert alert-danger').addClass("alert alert-success");            
            $('#editcompany_error').html(data['msg']);              
            $('#editcompany_error').show();            
           setTimeout (function(){
            
              full_close_modal("myModal_tracks","editcompany_error");
            
              
          var rw=update_row(data['id']);        
                 
          
          $(rw).closest('tr').find('td').each(function(i,val){
           
                  
            if(i==2) $(this).html(data['name']);
       if(i==3){ $(this).html(data['description']);}
       if(i==4) $(this).html(data['adress']);
       if(i==5) $(this).html(data['phone']);
       
          
            }); 
          
            }, 500);

          
          } 
          
          $('.my_loader').hide();   
            
          },
          error:  function(xhr, str){
           
            $('#editcompany_error').addClass("alert alert-danger");
            $('#editcompany_error').html('Возникла ошибка: ' + xhr.responseCode);  
            $('#editcompany_error').show( "fast" ); 
            $('.my_loader').hide("fast");
            
            }
        });
       }
       
       $('.span4').change(function() {
  var input = $(this)[0];
  var id = $(this).attr('id');
  if ( input.files && input.files[0] ) {
    if ( input.files[0].type.match('image.*') ) {
      var reader = new FileReader();
      reader.onload = function(e) { $('#'+id).attr('src', e.target.result);}
      reader.readAsDataURL(input.files[0]);
    }
  }
});


  $("tbody").on('click',"a[href='#delete']", function() {
    
        var parrent=$(this).closest("tr");
        var index=$(this).closest("td").children('#c_uid').attr('value');
        var uri=$(parrent).find('img').attr('src');
        var name = uri.substr(uri.lastIndexOf("/") + 1);
        bootbox.confirm("Удалить компанию безвозвратно?", function(result) {
    if (result) {
      
      
          if(delete_company(index,name))
         {
            
            
           
            
         $(parrent).remove();
          
         }else
          {
          
          parrent.css('background-color','rgba(251, 19, 19, 0.25)');
               
         }
    }
});

return false;
});


function delete_company(company_id, image)
    {
        
     return $.post("/admin/companyes",'act=del&id='+company_id+'&img='+image, function(){},'json')
       .success(function(data) {
        
        if(data['status']=='success')
        {
         
           return true; 
                         
            
        }else if(data['status']=='error')
        
        
          
             
             return false;
                    
        })
       .error(function() { alert("Ошибка удаления"); return false;}); 
        
    }    

 function full_close_modal(modal_id,error_block)
    {
       $('#'+modal_id).modal('hide');
       $('#'+error_block).hide("fast"); 
    }

  
  });
  
  var TABLE = {};

TABLE.paginate = function(table, pageLength) {
  // 1. Set up paging information
  var $table = $(table);
  var $rows = $table.find('tbody > tr');
  var numPages = Math.ceil($rows.length / pageLength) - 1;
  var current = 0;
  
  // 2. Set up the navigation controls
  var $nav = $table.parents('.table-responsive').find('.wrapper-paging ul');
  var $back = $nav.find('li:first-child a');
  var $next = $nav.find('li:last-child a');
  
  $nav.find('a.paging-this strong').text(current + 1);
  $nav.find('a.paging-this span').text(numPages + 1);
  $back
    .addClass('paging-disabled')
    .click(function() {
      pagination('<');
    });
  $next.click(function() {
    pagination('>');
  });
  
  // 3. Show initial rows
  $rows
    .hide()
    .slice(0,pageLength)
    .show();
    
  pagination = function (direction) { // 4. Move previous and next  
    
    var reveal = function (current) { // 5. Reveal the correct rows
      $back.removeClass('paging-disabled');
      $next.removeClass('paging-disabled');
      
      $rows
        .hide()
        .slice(current * pageLength, current * pageLength + pageLength)
        .show();
        
      $nav.find('a.paging-this strong').text(current + 1);
    };
    
    if (direction == "<") { // previous
      if (current > 1) {
	(current -= 1);
      }
      else if (current == 1) {
	(current -= 1);
	$back.addClass("paging-disabled");
      }
    } else { // next
      if (current < numPages - 1) {
	current += 1;
      }
      else if (current == numPages - 1) {
	current += 1;
	$next.addClass("paging-disabled");
      }
    }
    reveal(current);
  }
}

