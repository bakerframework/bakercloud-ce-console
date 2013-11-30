/* ===================================================
 * bounce.js v2.1
 * ========================================================== */
 
$(document).ready(function(){

    /* Animated Skills Bar
    * ====================== */  
	$('.progress-skills').each(function(){
		var t = $(this),
		label = t.attr('data-label');
        percent = t.attr('data-percent') + '%';
        t.find('.bar').text(label + ' ' + '(' + percent + ')').animate({width:percent});
      });

    /* Fluid Resposive Video Embeds
	 * https://github.com/davatron5000/FitVids.js
    * ====================== */   	
	$(".js-video").fitVids();

    /* Input Validation
	 * https://github.com/ReactiveRaven/jqBootstrapValidation
    * ====================== */  	
	$("input").not("[type=submit]").jqBootstrapValidation();
	
    /* Scroll Effect for Alt. Homepage
	* ====================== */	 
	function scrollEffect() {
		scrollPos = $(this).scrollTop();
        $('#landingSlide').css({'background-position': 'center ' + (200 + (scrollPos/4)) + "px"});
    }
    $(window).scroll(function() {
		scrollEffect();
    }); 	

    /* Smooth Scroll to Top
    * ====================== */
	$("#totop").click(function () {
		$("html, body").animate({
			scrollTop: 0
	    }, 300);
		return false;
	});	

});