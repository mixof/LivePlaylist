// JavaScript Document
jQuery(document).ready(function($) {
    
     $(".track_rate").tooltip();

 $('#add_comment').button().click(function() {
     $('#contact').trigger( 'reset' );
       $('#editcompany_error').hide();  
       $('#form-content').modal('show');
       return false;
    });
    
    
     $('#submit_comment').button().click(function() {
   
        add_comment();
       return false;
    });
    
    
     function add_comment()
       {
        
        var msg = $('#contact').serialize();
      msg+="&act=add_comment";
        $('.my_loader').show();        
        $.ajax({
          dataType: 'json',
          type: 'POST',
          url: '/cabinet',
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
            
            $('#form-content').modal('hide');
           $('#editcompany_error').removeClass('alert alert-danger')
           $('#editcompany_error').hide();                        
           $('#contact').trigger( 'reset' );
          
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
       
    // Header Search form Button effects
    var $search_input = $(".header-search-form-input");
    var search_width = $search_input.width();
    var $search_button = $(".header-search-form-button");
    $(".header-search-form-button i").click(function() {

        var button_icon = $search_button.find("i").attr("class");
        if (button_icon == "icon-search") {
            $search_button.animate({"height": "82px"}, 300, function() {
                $search_button.find("i").removeClass("icon-search").addClass("icon-remove");
                $search_input.show().css({
                    width: 0
                }).animate({
                    width: search_width
                }, 300);
            });
        }
        else if (button_icon == "icon-remove") {
            $search_input.stop().animate({width: 0}, 100, function() {
                $search_input.hide();
                $search_button.animate({"height": "42px"}, 200, function() {
                    $search_button.find("i").removeClass("icon-remove").addClass("icon-search");
                });
            });
        }
    });
    $search_input.blur(function() { // Hide search form, when it loses the focus
        $search_input.stop().animate({width: 0}, 100, function() {
            $search_input.hide();
            $search_button.animate({"height": "42px"}, 200, function() {
                $search_button.find("i").removeClass("icon-remove").addClass("icon-search");
            });
        });
    });

    // Magnific Popup Initialization
    $('.bubble-popup').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom'
    });

    // Calendar Grid Plugin init
    Grid.init();

    // jPages paginated blocks
    var $holder = $("body").find(".holder");
    if (!$holder.length) {
        $("body").append("<div class='holder'></div>");
    }
    $("div.holder").jPages({
        containerID: "recent-works",
        previous: ".block-recent-works a[data-role='prev']",
        next: ".block-recent-works a[data-role='next']",
        animation: "fadeInRight",
        perPage: 4
    });
    $("div.holder").jPages({
        containerID: "why-choose-us",
        previous: ".block-why-choose-us a[data-role='prev']",
        next: ".block-why-choose-us a[data-role='next']",
        animation: "flipInY",
        perPage: 4
    });
    $("div.holder").jPages({
        containerID: "twitter-feed",
        previous: ".block-twitter-feed a[data-role='prev']",
        next: ".block-twitter-feed a[data-role='next']",
        animation: "fadeInRight",
        perPage: 1
    });
    $("div.holder").jPages({
        containerID: "filtered-menu",
        previous: ".food-category-filtered-menu a[data-role='prev']",
        next: ".food-category-filtered-menu a[data-role='next']",
        animation: "flipInY",
        perPage: 4
    });
    $("div.holder").jPages({
        containerID: "latest-posts",
        previous: ".block-latest-posts a[data-role='prev']",
        next: ".block-latest-posts a[data-role='next']",
        animation: "fadeInRight",
        perPage: 4
    });
    $("div.holder").jPages({
        containerID: "what-clients-say",
        previous: ".wcs a[data-role='prev']",
        next: ".wcs a[data-role='next']",
        animation: "fadeInRight",
        perPage: 1
    });
    $("div.holder").jPages({
        containerID: "what-clients-say2",
        previous: ".block-what-clients-say a[data-role='prev']",
        next: ".block-what-clients-say a[data-role='next']",
        animation: "fadeInRight",
        perPage: 2
    });
    $("div.holder").jPages({
        containerID: "event-schedule",
        previous: ".block-event-schedule a[data-role='prev']",
        next: ".block-event-schedule a[data-role='next']",
        animation: "fadeInUp",
        perPage: 4
    });


    // Toggle Box functions
    $(".toggle-box-header").click(function() {
        var $obj = $(this);
        if ($obj.hasClass("expanded")) {
            $obj.removeClass("expanded");
            $obj.next("div").slideUp();
        }
        else {
            $obj.addClass("expanded");
            $obj.next("div").slideDown("slow");
        }
    });

    // Restaurant Food Categories Dropdown
    var $div = $(".food-categories-dropdown");
    var $categories = $div.find(".food-category");
    var height = $div.height();
    $div.hide().css({height: 0});
    $categories.fadeOut("fast");

    $(".food-categories-header").click(function() {
        var $icon = $(this).find("i");
        if ($div.is(':visible')) {
            $div.animate({height: 0}, {duration: 300, complete: function() {
                    $div.hide();
                    $(".food-categories-header").removeClass("active");
                    $icon.removeClass("icon-caret-up").addClass("icon-caret-down");
                }
            });
            $categories.fadeOut("fast");
        } else {
            $(".food-categories-header").addClass("active");
            $icon.removeClass("icon-caret-down").addClass("icon-caret-up");
            $div.show().animate({height: height}, {duration: 500});
            $categories.each(function(i, el) {
                $(el).delay(50 + (i * 50)).hide().fadeIn("slow");
            });
        }
    });

    /** 
     * Testimonials arrow adjustment
     * 
     * First of all, we will move arrow to top when page is loaded, if the window width is smaller than 767px (tablets & mobiles)
     */
    var $testimonialsLeftSided = $(".text-testimonial-left");
    var $testimonialsRightSided = $(".text-testimonial-right");
    if ($(window).width() < 767) {
        $testimonialsLeftSided.removeClass("text-testimonial-left").addClass("text-testimonial-top");
        $testimonialsRightSided.removeClass("text-testimonial-right").addClass("text-testimonial-top");
    }
    /*
     * Secondly, we will do the same operation when window width becomes smaller than 767px,
     * and vice versa when window width becomes greater than 767px
     */
    $(window).resize(function() {
        if ($(window).width() < 767) {
            $testimonialsLeftSided.removeClass("text-testimonial-left").addClass("text-testimonial-top");
            $testimonialsRightSided.removeClass("text-testimonial-right").addClass("text-testimonial-top");
        }
        else {
            $testimonialsLeftSided.removeClass("text-testimonial-top").addClass("text-testimonial-left");
            $testimonialsRightSided.removeClass("text-testimonial-top").addClass("text-testimonial-right");
        }
    });

    $('.bxslider').bxSlider({pagerCustom: '.bxpager'});

    $(".flexisel").flexisel({
        visibleItems: 5,
        animationSpeed: 1000,
        autoPlay: true,
        autoPlaySpeed: 3000,
        pauseOnHover: true,
        enableResponsiveBreakpoints: true,
        responsiveBreakpoints: {
            portrait: {
                changePoint: 480,
                visibleItems: 1
            },
            landscape: {
                changePoint: 640,
                visibleItems: 2
            },
            tablet: {
                changePoint: 768,
                visibleItems: 3
            }
        }
    });

    $('#da-slider').cslider({
        autoplay: true,
        bgincrement: 450
    });
    var o = {
        init: function() {
            var $arcs = $('.arc');
            $arcs.each(function(i) {
                o.diagram(i);
            });
        },
        random: function(l, u) {
            return Math.floor((Math.random() * (u - l + 1)) + l);
        },
        diagram: function(id) {
            $("#skills").append("<div id='diagram" + id + "' class='skill-diagram'></div>");
            var r = Raphael("diagram" + id, 180, 180),
                    rad = 35,
                    defaultText = '',
                    speed = 250;

            r.circle(90, 90, 55).attr({stroke: 'none', fill: '#fff'});



            r.customAttributes.arc = function(value, color, rad) {
                var v = 3.6 * value,
                        alpha = v == 360 ? 359.99 : v,
                        random = o.random(91, 240),
                        a = (random - alpha) * Math.PI / 180,
                        b = random * Math.PI / 180,
                        sx = 90 + rad * Math.cos(b),
                        sy = 90 - rad * Math.sin(b),
                        x = 90 + rad * Math.cos(a),
                        y = 90 - rad * Math.sin(a),
                        path = [['M', sx, sy], ['A', rad, rad, 0, +(alpha > 180), 1, x, y]];
                return {path: path, stroke: color}
            }

            var t = $('.service-skill .arc:eq(' + id + ')'),
                    color = t.find('.color').val(),
                    value = t.find('.percent').val(),
                    text = t.find('.text').text();
            console.log(text);
            var title = r.text(90, 90, defaultText).attr({
                font: '18px RobotoCondensed',
                fill: color
            }).toFront();
            title.attr({text: text + '\n \n' + value + '%'});
            rad += 30;
            var z = r.path().attr({arc: [value, color, rad], 'stroke-width': 20});

            z.mouseover(function() {
                this.animate({'stroke-width': 30, opacity: .75}, 1000, 'elastic');
                if (Raphael.type != 'VML') //solves IE problem
                    this.toFront();
                title.stop().animate({opacity: 0}, speed, '>', function() {
                    this.attr({text: text + '\n' + value + '%'}).animate({opacity: 1}, speed, '<');
                });
            }).mouseout(function() {
                this.stop().animate({'stroke-width': 20, opacity: 1}, speed * 4, 'elastic');

            });

        }
    }

    $(function() {
        o.init();
    });

    // Clone portfolio items to get a second collection for Quicksand plugin
    var $portfolioClone = $(".portfolio").clone();

    // Attempt to call Quicksand on every click event handler
    $(".filter a").click(function(e) {
        $(".filter li").removeClass("current");

        // Get the class attribute value of the clicked link
        var $filterClass = $(this).parent().attr("class");

        if ($filterClass == "all") {
            var $filteredPortfolio = $portfolioClone.find("li");
        } else {
            var $filteredPortfolio = $portfolioClone.find("li[data-type~=" + $filterClass + "]");
        }

        // Call quicksand
        $(".portfolio").quicksand($filteredPortfolio, {
            duration: 400,
            easing: 'swing'
        }, function() {

            $('.bubble-popup').magnificPopup({
                type: 'image',
                mainClass: 'mfp-with-zoom'
            });

        });


        $(this).parent().addClass("current");

        // Prevent the browser jump to the link anchor
        e.preventDefault();
    })

    // Waypoints
    /*$(".block-latest-posts").waypoint(function() {
     $(this).find("li:not(.jp-hidden)").each(function(i, el) {
     $(el).removeClass().addClass("span3 animated jp-invisible");
     setInterval(function() {
     $(el).removeClass("jp-invisible").addClass("bounceInRight");
     }, 200 + (i * 200));
     });
     $(this).find("li:not(.jp-hidden)").removeClass("bounceInRight");
     }, {
     offset: '100%',
     context: window,
     triggerOnce: true
     });*/

  $("#qr_code").popover({
    title: 'QR код',
    content: 'Используйте данный код для подключения в мобильном приложении LivePlaylist.',
    trigger: 'hover',
    placement: 'left'    
  });
  
    $("#code_word").popover({
    title: 'Кодовое слово',
    content: 'Защитит вашу компанию от не желанных гостей. Максимальный размер до 15 символов.',
    trigger: 'hover',
    placement: 'right'    
  });
  
      $.fn.editable.defaults.mode = 'inline';
      
      $('#code_word').editable({  
            ajaxOptions: {
        
        dataType: 'json'
    },
           pk: 1,        
           url: $('#code_word').attr('url'),          
           title: 'Enter username', 
            type: 'text',   
            inputclass:'editable_input',   
                validate: function(value) {
        if($.trim(value) == '') {
            return 'Кодовое слово должно быть заполнено!';
        }
        if($.trim(value).length>15) {
            return 'Слишком длинное слово!';
        }
    },
        
       success: function(response, newValue) {
        if(response.status=="ok"){
           var d = new Date();
            $("#qr_code").attr("src",response.msg+d.getTime());
        }else
        if(response.status=="error")return response.msg;
         
    },
    
    error: function(data) {
 
return "Не удалось соедениться с сервером..";       
    
}
    
    
      });




});


