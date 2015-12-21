
var ww = document.body.clientWidth;

$(document).ready(function() {
	$(".top-navigation > .nav li a").each(function() {
		if ($(this).next().length > 0) {
			$(this).addClass("parent");
		};
	})
	
	$(".top-navigation > .toggleMenu").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("active");
		$(".top-navigation > .nav").toggle();
	});
	adjustMenu();
})

$(window).bind('resize orientationchange', function() {
	ww = document.body.clientWidth;
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 768) {
		$(".top-navigation > .toggleMenu").css("display", "inline-block");
		if (!$(".top-navigation > .toggleMenu").hasClass("active")) {
			$(".top-navigation > .nav").hide();
		} else {
			$(".top-navigation > .nav").show();
		}
		$(".top-navigation > .nav li").unbind('mouseenter mouseleave');
		$(".top-navigation > .nav li a.parent").unbind('click').bind('click', function(e) {
			// must be attached to anchor element to prevent bubbling
			e.preventDefault();
			$(this).parent("li").toggleClass("hover");
		});
	} 
	else if (ww >= 768) {
		$(".top-navigation > .toggleMenu").css("display", "none");
		$(".top-navigation > .nav").show();
		$(".top-navigation > .nav li").removeClass("hover");
		$(".top-navigation > .nav li a").unbind('click');
		$(".top-navigation > .nav li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {
		 	// must be attached to li so that mouseleave is not triggered when hover over submenu
		 	$(this).toggleClass('hover');
		});
	}
}

