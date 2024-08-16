$(function() {
  "use strict";

  var nav_offset_top = $('header').height() + 50; 
    /*-------------------------------------------------------------------------------
	  Navbar 
	-------------------------------------------------------------------------------*/

	//* Navbar Fixed  
    function navbarFixed(){
        if ( $('.header_area').length ){ 
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();   
                if (scroll >= nav_offset_top ) {
                    $(".header_area").addClass("navbar_fixed");
					$(".sv_start_your_search").show();
					$(".sv_header_text").hide();
					
					$(".svstartdate").attr("id","startDate");
					$(".svenddate").attr("id","endDate");
					
					$('.dark_logo').addClass('d-sm-block');
					$('.light_logo').hide();
                    $(".sv_search_icon").addClass("icon_fixed");

                } else {
					$(".sv_start_your_search").hide();
					$(".sv_header_text").show();

                    $(".header_area").removeClass("navbar_fixed");

					$(".svstartdate").attr("id","");
					$(".svenddate").attr("id","");
					
					$('.dark_logo').removeClass('d-sm-block');
					$('.light_logo').show();
                    $(".sv_search_icon").removeClass("icon_fixed");
                }
            });
        };
    };
    navbarFixed();


    /*-------------------------------------------------------------------------------
	  testimonial slider
	-------------------------------------------------------------------------------*/
    if ($('.testimonial').length) {
        $('.testimonial').owlCarousel({
            loop: true,
            margin: 30,
            items: 5,
            nav: false,
            dots: true,
            responsiveClass: true,
            slideSpeed: 300,
            paginationSpeed: 500,
            responsive: {
                0: {
                    items: 1
                }
            }
        })
    }

});


// List Grid Control
$(document).ready(function () {
    $('#list').click(function (event) { 
        event.preventDefault(); 
        $('#products .item').addClass('list-group-item');
        $('#list-tag').removeClass('justify-content-center');
        $('#list').removeClass('inactive-list');
        $('#grid').addClass('inactive-list');
     });

    $('#grid').click(function (event) { 
        event.preventDefault(); 
        $('#products .item').removeClass('list-group-item'); 
        $('#products .item').addClass('grid-group-item');
        $('#list-tag').addClass('justify-content-center');
        $('#grid').removeClass('inactive-list');
        $('#list').addClass('inactive-list');
     });

     $('#reviewIcon').click(function (event) { 
     var a=   $('#collapseReviews').hasClass( "show" );
     if(a) {
            $('#reviewArrow').removeClass( "fa-angle-down" );
            $('#reviewArrow').addClass( "fa-angle-right" );
        } else {
            $('#reviewArrow').removeClass( "fa-angle-right" );
            $('#reviewArrow').addClass( "fa-angle-down" );
        }
     });
     
    $('body').on('click', '.sv_support, .footer-close', function() {
         $('.sv_footer_popup').toggle("slow");
    });
      
   

});


