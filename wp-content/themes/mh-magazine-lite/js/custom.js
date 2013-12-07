jQuery(document).ready(function($){
	
	/***** Flexslider *****/
	
	$('#slider').flexslider({
		animation: "fade",
		controlNav: true,
		directionNav: false,
	});
	
	/***** prettyPhoto *****/
	
	$("a[rel^='prettyPhoto']").prettyPhoto({
		show_title: false,
		theme: 'light_square' /* pp_default / light_rounded / dark_rounded / light_square / dark_square / facebook */
	});
 
});