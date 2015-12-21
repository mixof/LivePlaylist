// JavaScript Document
jQuery(document).ready(function($) {

    // Kite Animation
    $('.question-balloon').transition({scale: 1}, 500, 'ease');
    $('.kite-path').transition({width: '600px', delay: 500}, 3000, 'linear');
    $('.kite').transition({opacity: 1, x: 100, y: -50, delay: 300, rotate: '50deg'}, 300, 'linear')
            .transition({x: 200, y: -20, rotate: '80deg'}, 350, 'linear')
            .transition({x: 300, y: 0, rotate: '20deg'}, 350, 'ease-in')
            .transition({x: 450, y: -80, rotate: '20deg'}, 600, 'linear')
            .transition({x: 500, y: -100, rotate: '40deg'}, 250, 'linear')
            .transition({x: 530, y: -100, rotate: '60deg'}, 250, 'linear')
            .transition({x: 550, y: -90, rotate: '25deg'}, 300, 'linear')
            .transition({x: 570, y: -85, rotate: '10deg'}, 300, 'linear');;
            
    $('.milestone1').transition({opacity: 1, delay: 1000}, 500, 'linear');
    $('.kite-icon-book').transition({opacity: 1, delay: 1200}, 1000, 'linear');
    $('.milestone2').transition({opacity: 1, delay: 1500}, 500, 'linear');
    $('.kite-icon-pencil').transition({opacity: 1, delay: 1700}, 1000, 'linear');
    $('.milestone3').transition({opacity: 1, delay: 2200}, 500, 'linear');
    $('.kite-icon-lightbulb').transition({opacity: 1, delay: 2400}, 1000, 'linear');
    $('.milestone4').transition({opacity: 1, delay: 2700}, 500, 'linear');
    $('.kite-icon-presentation').transition({opacity: 1, delay: 2900}, 1000, 'linear');
    $('.kite-end').transition({opacity: 1, delay: 3300}, 1000, 'linear');



});


