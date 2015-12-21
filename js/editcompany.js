 
    function call() {
      var msg   = $('myForm').serialize();
        $.ajax({
          type: 'POST',
          url: 'http://test1.ru/cabinet/editcompany/'+get_cookie('my_company_id'),
          data: msg,
          success: function(data) {
            alert(data);
          },
          error:  function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
 
    }
    
    
    function get_cookie ( cookie_name )
{
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
 
  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}
     