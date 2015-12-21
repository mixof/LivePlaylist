$(document).ready(function(){
var company=get_cookie("my_company_id");
	$('#delete').click(function(){
		
	
		
		$.confirm({
			'title'		: 'Удаление компании',
			'message'	: 'Внимание! <br />Компания и все её трэки будут удалены безвозвратно!',
			'buttons'	: {
				'Удалить'	: {
					'class'	: 'blue',
					'action': function(){
					 
					var img=$('#img-logo').attr('src').replace(/^.*[\\\/]/, '');   

                   $.post("cabinet/company/"+company, {image: img, company: company, del:'y' }, function(data){
                    
                     window.location.href = "/cabinet";
                  
                       });
				
               
                	}
				},
				'я передумал..'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	
    return false;	
	});
	
});

 
function get_cookie ( cookie_name )
{
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
 
  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}