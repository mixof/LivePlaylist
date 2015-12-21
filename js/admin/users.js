$(function() {    
    $(".wrapper-paging").show();
  TABLE.paginate('#users_table', 10);
    
    $('#show').button().click(function() {
       $('#myModal').modal('show');
    });
    
    $('#subs').button().click(function() {
    add_form();
    
     return false;
    });
    
    
      $('#edit_usr').button().click(function() {
    
       edit_form();    
       
     return false;
    });
    
    
     $('.close').button().click(function() {
        
       full_close_modal("myModal","adduser_error");
    });
    
      $('#edit_close').button().click(function() {
        
       full_close_modal("myModal_edit","edituser_error");
    });
    
     $("tbody").on('click',"a[href='#delete']", function() {
        
        var parrent=$(this).closest('tr');
        var index=$(this).closest("td").children('input').attr('value');
        bootbox.confirm("Удалить пользователя безвозвратно?", function(result) {
    if (result) {
      delete_user(index,parrent);      
      
    }
});
  
 
      
       
      return false;
    });
    
   
    
                        
    
     $("#search_btn").click(function() {
           
           var login= $('#search_field').val();
           
             search_click(login);       
    
    });
    
    
    trArr = $("#users_table tr:not(:first)");

	townArr = $("td:nth-child(1n+1)", trArr);
	 
	$("#search_field").keyup(function() {

	    if(!this.value)

	        return trArr.show()

	    trArr.hide();

	    townArr.filter(":contains("+this.value+")").parent("tr").show();

	});
    
    
    
        
     $("tbody").on('click',"a[href='#edit']", function() {
  
       $('#myModal_edit').modal('show');
      var parent=$(this).parents('tr');
       var user_id;  
       $(parent).find('td').each(function(i,val){
        
      
        var value=$(val).html();  
        
          
     
        if(i==1) $('#e_username').val(value);
        if(i==2) $('#e_email').val(value);
        if(i==3) {
            if($(value).attr('alt')=='Администратор') $('#optionsRadiosInline1').prop("checked", true);            
            else  $('#optionsRadiosInline').prop("checked", true);
        }
        if(i==4) { user_id=$(this).children('#e_uid').val(); $('#form_uid').val(user_id);}
       });
   
       
      return false;
    });
    
    
    function search_click(value)
    {
        trArr = $("#users_table tr:not(:first)");

	    townArr = $("td:nth-child(1n+1)", trArr);
	 
     if(!value) return trArr.show();
      
       trArr.hide();

	    townArr.filter(":contains("+value+")").parent("tr").show();
     
    }
    
     
   
    function full_close_modal(modal_id,error_block)
    {
       $('#'+modal_id).modal('hide');
       $('#'+error_block).hide("fast"); 
    }
    
    
    function delete_user(user_id,parrent)
    {
      
        
      $.post("/admin/users",'act=del&id='+user_id, function(){},'json')
       .success(function(data) {
        
        if(data['status']='success')
        {
          $(parrent).remove();                           
            
        }else if(data['status']='error')
        
           
         parrent.css('background-color','rgba(251, 19, 19, 0.25)');
           
                    
        })
       .error(function() { parrent.css('background-color','rgba(251, 19, 19, 0.25)');}); 
       
 
        
    }    
      
    
    function edit_form()
    {
         var msg = $('#form_edit').serialize();
      msg+="&act=edit";
        $('.my_loader').show();        
        $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/admin/users',
          data: msg,
          success: function(data) {
           
          if(data['status']=='error')
          {          
            $('#edituser_error').addClass("alert alert-danger");
            $('#edituser_error').html(data['msg']);              
            $('#edituser_error').show();
                  
          }else if(data['status']=='success')
          {
            $('#edituser_error').removeClass('alert alert-danger').addClass("alert alert-success");            
            $('#edituser_error').html(data['msg']);              
            $('#edituser_error').show();            
           setTimeout (function(){
              full_close_modal("myModal_edit","edituser_error");
              var user_img='<img src="/img/admin/user_icon.png" title="Пользователь" alt="Пользователь"/>';
            if(data['role']!=0)user_img='<img src="/img/admin/admin_icon.png" title="Администратор" alt="Администратор"/>';
              
          var rw=update_row(data['id']);        
                 
          
          $(rw).closest('tr').find('td').each(function(i,val){
           
            if(i==1)$(this).html(data['username']);
            if(i==2)$(this).html(data['email']);
            if(i==3)$(this).html(user_img);
            
            }); 
          
            }, 500);

          
          } 
          
          $('.my_loader').hide();   
            
          },
          error:  function(xhr, str){
           
            $('#edituser_error').addClass("alert alert-danger");
            $('#edituser_error').html('Возникла ошибка: ' + xhr.responseCode);  
            $('#edituser_error').show( "fast" ); 
            $('.my_loader').hide("fast");
            
            }
        });
    
    }
    
    function update_row(user_id)
    {
       return $("#e_uid[value='"+user_id+"']");
    }
    
     function add_form() {
      var msg = $('#form').serialize();
      msg+="&act=add";
        $('.my_loader').show();        
        $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/admin/users',
          data: msg,
          success: function(data) {
           
          if(data['status']=='error')
          {          
            $('#adduser_error').addClass("alert alert-danger");
            $('#adduser_error').html(data['msg']);              
            $('#adduser_error').show();
                  
          }else if(data['status']=='success')
          {
            $('#adduser_error').removeClass('alert alert-danger').addClass("alert alert-success");            
            $('#adduser_error').html(data['msg']);              
            $('#adduser_error').show();            
           setTimeout (function(){
              full_close_modal("myModal","adduser_error");
              var user_img='<img src="/img/admin/user_icon.png" title="Пользователь" alt="Пользователь"/>';
            if(data['role']!='0')user_img='<img src="/img/admin/admin_icon.png" title="Администратор" alt="Администратор"/>';
              $("#users_table").append("<tr><td>"+data['login']+"</td><td>"+data['username']+"</td><td>"+data['email']+"</td><td>"+user_img+"</td><td><div class='doing'><a href='#edit'><img src='/img/admin/edit.png' title='Изменить' alt='Изменить'/></a><a href='#delete'><img src='/img/admin/delete.png' title='Удалить' alt='Удалить'/></a></div><input id='e_uid' name='u_id' type='hidden' value="+data['id']+"></td></tr>");
            }, 500);

           
          } 
          
          $('.my_loader').hide();   
            
          },
          error:  function(xhr, str){
           
            $('#adduser_error').addClass("alert alert-danger");
            $('#adduser_error').html('Возникла ошибка: ' + xhr.responseCode);  
            $('#adduser_error').show( "fast" ); 
            $('.my_loader').hide("fast");
            
            }
        });
 
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
