 $(function() {  
    
       $(".wrapper-paging").show();
  TABLE.paginate('#companyes_table', 10);
  
  
    $("tbody").on('click',"a[href='#delete']", function() {
    
        var parrent=$(this).closest("tr");
        var index=$(this).closest("td").children('#com_id').attr('value');
            
        bootbox.confirm("Удалить комментарий безвозвратно?", function(result) {
            
    if (result) delete_comment(index, parrent);
            
         
    
});

return false;
});


 $("tbody").on('click',"a[href='#edit']", function() {
    
        var parrent=$(this).closest("tr");
        var index=$(this).closest("td").children('#com_id').attr('value');
            
        $('#comment_edit').modal('show')
        
        $('#form_uid').val(index);
        
        
      
       $(parrent).find('td').each(function(i,val){
       
      
       var value=$(val).html();         
             
       if(i==2)  $('#comment_text').val(value);
     
      
       });

  return false;

});


 $('#edit_comment').button().click(function() {
    
    
     edit_comment();
     
       
     return false;
    });
    
    
    
     function edit_comment()
       {
        
        var msg = $('#form_edit').serialize();
      msg+="&act=edit";
        $('.my_loader').show();        
        $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/admin/comments',
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
            
              full_close_modal("comment_edit","editcompany_error");
            
              
          var rw=update_row(data['id']);        
                 
          
          $(rw).closest('tr').find('td').each(function(i,val){
           
                  
            if(i==2) $(this).html(data['text']);
       
       
          
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
       
       

    $("tbody").on('click',"a[href='#accept']", function() {
    
        var parrent=$(this).closest("tr");
        var index=$(this).closest("td").children('#com_id').attr('value');
            
        bootbox.confirm("Опубликовать комментарий?", function(result) {
            
    if (result) accept_comment(index, parrent);
            
         
    
});

return false;

});


  function update_row(comment_id)
    {
       return $("#com_id[value='"+comment_id+"']");
    }

function delete_comment(comment_id,parrent)
{    
    $.post("/admin/comments",'act=del&id='+comment_id, function(){},'json')
       .success(function(data) {
        
        if(data['status']=='success')
        {
         
           $(parrent).remove();
            
        }else if(data['status']=='error')
           
           parrent.css('background-color','rgba(251, 19, 19, 0.25)');
        })
       .error(function() {   parrent.css('background-color','rgba(251, 19, 19, 0.25)');}); 
}
   
function accept_comment(comment_id,parrent)
{    
     $.post("/admin/comments",'act=accept&id='+comment_id, function(){},'json')
       .success(function(data) {
        
        if(data['status']=='success')
        {
            
         $(parrent).find('.dropdown-menu').hide();   
         $(parrent).find('td').each(function(i,val){
           
                  
          if(i==4) $(this).html('<img src="/img/ok.png" alt="Опубликован" title="Опубликован"></img>');
          if(i==5) $(this).html("<div class='dropdown'><a id='dLabel' role='button' data-toggle='dropdown' data-target='#' href='#'><i class='fa fa-navicon'></i></a><ul class='dropdown-menu' role='menu' aria-labelledby='dLabel'><li><a href='#edit'><img src='/img/admin/edit.png' title='Изменить' alt='Изменить'/> Изменить</a></li><li><a href='#delete'><img src='/img/admin/delete.png' title='Удалить' alt='Удалить'/> Удалить</a></li></ul></div><input name='com_id' id='com_id' type='hidden' value="+comment_id+">");  
                    
          
       
          
            }); 
           
            
        }else if(data['status']=='error')
           
           parrent.css('background-color','rgba(251, 19, 19, 0.25)');
        })
       .error(function() {   parrent.css('background-color','rgba(251, 19, 19, 0.25)');}); 
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