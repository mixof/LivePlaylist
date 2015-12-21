$(".reg_form_input input").focus(function(){
$(this).attr("class","input_edit");
$("#mess_"+$(this).attr("id")).html('');
                              })
$("#reg_form input").blur(function(){
$(this).attr("class","input_start");
check_ajax($(this).attr("id"))
                              })
                              
$("#repassword").keyup(function(){
    if ($("#password").val()!=''&&$("#repassword").val()!='')
    check_ajax('repassword');
})  

$("#password").keyup(function(){
    if ($("#password").val()!=''&&$("#repassword").val()!='')
    check_ajax('repassword');
}) 

$("#captcha").keyup(function(){
    check_captcha();    
}) 
                         
function check_ajax(input)
{
    is_sending();
    var data_val = $("#"+input).val();
    ok=0;
    extra='';
    if (input=='repassword')
    {
        extra="&password1="+$("#password").val();
    }
    $.ajax({
      url: "{SITEURL}/ajax/registry.php?"+input+extra,
      type: "POST",
      data: ({"dataname": data_val}),
      dataType: "json",
      timeout: 10000,
      beforeSend: function(){
      $("#load_"+input).html('<img src="{SITEURL}/images/ajax-loader.gif">');  
      },
      success: function(data){
        $("#load_"+input).html('');
        $("#mess_"+input).html(data.m);
        if (data.ok==1)
        $("#"+input).attr("class","input_ok");
        else $("#"+input).attr("class","input_error");
        is_sending();
      },
      error: function(xhr, status){
      $("#load_"+input).html('');   
      }
    });
    
    if (input=='password')
    {
        check_ajax("repassword");
    }
}

function is_sending()
{
    var show_btn=0;
    if ($("#username").attr("class")=="input_ok")
    if ($("#email").attr("class")=="input_ok")
    if ($("#password").attr("class")=="input_ok")
    if ($("#repassword").attr("class")=="input_ok")
    if ($("#captcha").attr("class")=="input_ok")
    {
        show_btn=1;
    }
    if (show_btn) $(".register_btn").fadeIn("slow", "linear"); 
    else $(".register_btn").fadeOut("slow", "linear"); 
}

function check_captcha()
{
    cval=$("#captcha").val();
    if (cval.length==6)
    check_ajax('captcha');
}