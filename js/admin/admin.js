var url=document.location.href;
var segments = url.split( '/' ); 
var action=null;
action = segments[4];

$.each($(".side-nav a"),function(){ 
    
if(this.href.indexOf(action)!=-1){$(this).parent('li').addClass('active');}

});