$(document).ready(function(){
	// FAQ TOGGLER
	jQuery('div.toggler:not(.open)').hide();
	jQuery('.toggle').click(function(){
		jQuery(this).toggleClass("active").next().slideToggle("fast");
	});

	// LOGIN TOGGLER
	jQuery('.toggle1').click(function(event){
		event.preventDefault();
		
		// Set the effect type
		var effect = 'slide';
	 
		// Set the options for the effect type chosen
		var options = { direction: 'right' };
	 
		// Set the duration
		var duration = 500;
	 	
		// Execute the effect on desired element
		$('.toggler1').toggle(effect, options, duration);
	});

	// REVIEWS ROTATION
	jQuery('ul.reviews').cycle({
		fx: 'fade',
		timeout:       8000,
		speed:         1000,
		before: onAfter
	});
	function onAfter(curr, next, opts, fwd){
		//get the height of the current slide
		//uncomment this line to turn on hight adjustment 
		//var ht = jQuery(this).height();
		//set the container's height to that of the current slide
		jQuery(this).parent().animate(); //replace animate with this to turn on hight adjustment// .animate({height: ht})
	}

	// PEOPLE ROTATION
	jQuery('ul.people').cycle({
		fx: 'fade',
		timeout:       8000,
		speed:         1000,
		before: onAfter
	});
	function onAfter(curr, next, opts, fwd){
		//get the height of the current slide
		//uncomment this line to turn on hight adjustment 
		//var ht = jQuery(this).height();
		//set the container's height to that of the current slide
		jQuery(this).parent().animate(); //replace animate with this to turn on hight adjustment// .animate({height: ht})
	}
});