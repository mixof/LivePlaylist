  $(document).ready( function() {
    
        
        // Активируем все чекбоксы
        //При клике на ссылку "Отметить все чекбоксы", активируем checkbox
        $("a[href='#select_all']").click( function() {		
 
           $('[type=checkbox]').prop("checked",true);
          //Если вам нужно отмечать и неактивные чекбоксы (disabled), то предыдущая строчка должна выглядеть так:  
          //$("#" + $(this).attr('rel') + " input:checkbox").attr('checked', true);  
            return false;
        });
        
        // Снимаем все отметки
        $("a[href='#select_none']").click( function() {
            $("[type=checkbox]").prop("checked",false);
              
             
            //Если должны обрабатываться неактивные чекбоксы, опять исключаем параметр :enabled
            //$("#" + $(this).attr('rel') + " input:checkbox").attr('checked', true);   
            return false;
        });
        
          $("a[href='#delete']").click( function() {
            var result=new Array();
            var check=$("[type=checkbox]:checked");
           check.each( function(ind) {
            
	       result.push($(this).val());
          
	       
	    });
       // alert(result);
       
       var del_data='tracks='+JSON.stringify(result);
        
        $.post("http://test1.ru/validation/deletetrack", del_data,  function(data){

	    
        
           var json = JSON.parse(data);
           var status = json['status'];
           
           
      
    if (status == 'error') {        
        
        var msg = json['msg'];       
        $(".pl_controls").after("<br><p>"+msg+"</p></br>").fadeOut(1000,function(){$(this).remove();});           
        
            
    }else if(status=='ok')
    {       
     
        $.each(result,function(ind) {
            
          // $("#lBlock").closest("div")
           var chk=$("[type=checkbox]").filter("[value="+result[ind]+"]");
           chk.closest("li").fadeOut(300, function(){
           $(this).remove();
          // var h=$(".jspPane").height();
          // $(".jspPane").height(h-34);
           $('.sm2-playlist-bd').jScrollPane();    
        });    
          
	       
	    });
                
    } 

	});
        
          return false;
          
          });
    });
    
    