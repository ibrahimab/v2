// Overrides and addons
// http://burodmg.nl
// 2015 - HG

//main menu
var $cont = $('#nav-container');
var $body = $('body');

$(document).scroll(function() {
    var $scrl = $(this).scrollTop();
    if ($scrl>0){
        $cont.addClass('small');
        $body.addClass('smallfix');
    } else {
        $cont.removeClass('small');
        $body.removeClass('smallfix');
    }
});
//'boek nu' change position on small devices
$(document).ready(function() {
    var $window = $(window);
    function checkWidth() {
        var windowsize = $window.width();
        if (windowsize < 641) {
            // If the window is smaller than 641px (40em) wide then move 'boek nu' box
            var $target = $("#details-for-mobile");
            var $container = $("#accom-main-box-container");//origin
            var $content = $container.html();
            $target.append($content);
            $($container).remove()
        }
    }
    // Execute on load
    checkWidth();
    // Bind event listener
    $(window).resize(checkWidth);
});
//main menu - drop down
$('#dropdown-saved').click(function() {
    var drpdwnW = $(this).outerWidth() - 1;
    $('#dropdown-saved-list a').css('width', drpdwnW + 'px' );
});
//explain horizontal scroll
$('.table-explain .button').click(function() {
   $('.table-explain').hide();
    return false;
});
//like heart
// $('.heart a').click(function() {
//     $('#nav-main #dropdown-saved ').animate({color: '#d50d3b', borderColor: '#d50d3b' }, 500).delay( 1000 ).animate({color: '#1a3761', borderColor: '#d9d9d9' }, 500);
//     $('#nav-main #dropdown-container').addClass('active');
//     $(this).toggleClass('active');
//     $(this).toggleClass('added');
//     $('#nav-main #link-saved a').animate({color: '#d50d3b', borderColor: '#d50d3b'}, 500).delay( 1000 ).animate({color: '#1a3761', borderColor: '#d9d9d9'}, 500);
//     $('#nav-main #link-saved a').addClass('active');
//     return false;
// });
//open close divs
$('#search-filter-results h2 a').click(function() {//close ONE filter
    $(this).closest('h2').toggleClass('closed');
    var getParent = "#" + $(this).closest("div").attr("id");
    $(getParent + ' div').slideToggle();
    return false;
});
$('#search-filter .title').click(function() {//close ALL filters
    if ($(this).hasClass('closed')){
        $('#search-filter .title').removeClass('closed');
        $('#search-filter .search-filter-default .fields').slideDown();
        $('#search-filter .search-filter-default h2').removeClass('closed');        
    }else{
        $('#search-filter .title').addClass('closed');
        $('#search-filter .search-filter-default .fields').slideUp();
        $('#search-filter .search-filter-default h2').addClass('closed');  
    }
    return false;
});
$('.readmore a').click(function() {//open close div (class=readmore)
    var el = $(this);
    var state = el.data('state');

    if (state === 'open') {
        el.find('span').text(el.data('open-title'));
    } else {
        el.find('span').text(el.data('close-title'));
    }
    el.data('state', (el.data('state') === 'open' ? 'close' : 'open'));
    var getParent =  "." + el.parents('div:eq(1)').attr('class');
    $(getParent+ ' .readmore a').toggleClass('open');
    $(getParent+ ' .hide').slideToggle('slow');
    return false;
});
$(document).ready(function(){

    $('body').addClass('has-js');
    $('.styled_label input:checked').parent('label.label_check').addClass('c_on');
    $('.styled_label input:checked').parent('label.label_radio').addClass('r_on');

    $('body').on('click', '.styled_label', function(event) {

        event.preventDefault();

        var element = $(this);
        var input   = element.children('label').children('input');
        input.prop('checked', !input.prop('checked'));

        if (input.data('group') !== null) {
            $('.styled_label [data-group="' + input.data('group') + '"]').not(input).prop('checked', false);
        }

        $('.styled_label label.label_check').removeClass('c_on');
        $('.styled_label input:checked').parent('label.label_check').addClass('c_on');

        $('.styled_label label.label_radio').removeClass('r_on');
        $('.styled_label input:checked').parent('label.label_radio').addClass('r_on');
    });

    window.resetStyledInput = function() {

        $('.styled_label input:checked').prop('checked', false).parent('label.label_check').removeClass('c_on');
        $('.styled_label input:checked').prop('checked', false).parent('label.label_radio').removeClass('r_on');
    };
});
//go to next month - 'boek nu' table
$( "table.responsive" ).append($('#types-nav'));
var scrollPos = 0;
 $('#types .next').click(function(e) {
    e.preventDefault();
    scrollPos = scrollPos + 321;
    $("#types .scrollable").animate({ scrollLeft: scrollPos }, 1000);
});
 $('#types .prev').click(function(e) {
    e.preventDefault();
    if (scrollPos > 320) {
        scrollPos = scrollPos - 321;
        $("#types .scrollable").animate({ scrollLeft: scrollPos }, 1000);
    }
});
//go to div from link with class goto
$(".goto").click(function(e) {
    e.preventDefault();
    var getTarget =  $(this).attr('href');
    $('html, body').animate({
        scrollTop: $(getTarget).offset().top - 180
    }, 2000);
    return false;
});
//go up button
jQuery(document).ready(function() {
    var offset = 220;
    var duration = 500;
    jQuery(window).scroll(function() {
        if (jQuery(this).scrollTop() > offset) {
            jQuery('.go-up').fadeIn(duration);
        } else {
            jQuery('.go-up').fadeOut(duration);
        }
    });
    jQuery('.go-up').click(function(e) {
        e.preventDefault();
        jQuery('html, body').animate({scrollTop: 0}, duration);
        return false;
    })
});

jQuery(document).foundation();
jQuery(function() {

    $('input').placeholder({customClass:'my-placeholder'});

    $('.accom-slides').slick({

        lazyLoad: 'ondemand',
        slidesToShow: 1,
        slidesToScroll: 1
    });
});