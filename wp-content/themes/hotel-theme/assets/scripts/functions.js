/*
*/
jQuery(document).ready(function(){
	"use strict";		
	jQuery('.toggle-menu').jPushMenu({closeOnClickLink: false});
  	jQuery('.dropdown-toggle').dropdown();
	jQuery(document).on( 'click', 'a.comment-reply-link', function( event ) {
    	jQuery('#respond-comment').hide();
	});
	jQuery(document).on( 'click', 'a#cancel-comment-reply-link', function( event ) {
    	jQuery('#respond-comment').show();
	});	
});
jQuery(document).ready(function() {
	"use strict";
	jQuery(".navigation ul ul") .parent('li') .addClass('parentIcon');
	jQuery(".navigation ul ul") .parent('li') .prepend( "<span class='responsive-btn'><i class='icon-plus8'></i></span>" );
	jQuery(".navigation ul a").click(function(e){
		var a = jQuery(window).width();
		var b = 1000
		if (a <= b) {
			if(jQuery(this).attr('href') == '' || jQuery(this).attr('href') == '#'){
				e.preventDefault();
			}
			var dropCheck = jQuery(this).siblings('.sub-dropdown');


			if(dropCheck.length != ''){
				if(jQuery(this).closest('li').hasClass('active')){
					jQuery(this).siblings('.responsive-btn').html("<i class='icon-plus8'></i>");
					jQuery(this).closest('li').removeClass('active');
					jQuery(this).siblings('ul').slideUp();
				}else{
					jQuery(this).closest('ul').find('.responsive-btn').html("<i class='icon-plus8'></i>");
					jQuery(this).siblings('.responsive-btn').html("<i class='icon-minus8'></i>");
					jQuery(this).closest('ul').find('>li').removeClass('active');
					jQuery(this).closest('li').addClass('active');
					jQuery(this).closest('ul').find('li').find('>.sub-dropdown').slideUp();
					jQuery(this).siblings('ul').slideDown();
				}
			}
		}
	});

	jQuery('.cs-click-menu').on('click', function(e) {
		"use strict";
		var a = jQuery(window).width();
		var b = 1000
		if (a <= b) {
			e.preventDefault();
			jQuery(this).next().slideToggle();
			jQuery(".navigation ul ul") .hide();
		}
	});
});
jQuery(window).resize(function() {
	"use strict";	
	var a = jQuery(window).width();
	var b = 1000
	if (a >= b) {
		jQuery(".navigation ul ul") .show();
	}else{
		jQuery(".navigation ul ul") .hide();
	}
});

jQuery(document).ready(function() {
	"use strict";	
	jQuery("[id^=map_canvas]").css("pointer-events", "none");
	var onMapMouseleaveHandler = function (event) {
		var that = $(this);
		
		that.on('click', onMapClickHandler);
		that.off('mouseleave', onMapMouseleaveHandler);
		jQuery("[id^=map_canvas]").css("pointer-events", "none");
	}
	
	var onMapClickHandler = function (event) {
		var that = jQuery(this);
		
		// Disable the click handler until the user leaves the map area
		that.off('click', onMapClickHandler);
		
		// Enable scrolling zoom
		that.find('[id^=map_canvas]').css("pointer-events", "auto");
		that.find('[id^=cs-map-location]').css("pointer-events", "auto");
		
		// Handle the mouse leave event
		that.on('mouseleave', onMapMouseleaveHandler);
	}
	
	// Enable map zooming with mouse scroll when the user clicks the map
	jQuery('.cs-map-section').on('click', onMapClickHandler);
	
	jQuery('input[name="s"]').attr('required', 'required');
		
});


/* ---------------------------------------------------------------------------
	* nice scroll for theme
 	* --------------------------------------------------------------------------- */
	function cs_nicescroll(){
		'use strict';	
		var nice = jQuery("html").niceScroll({mousescrollstep: "20",scrollspeed: "150",}); 
	}

/* ---------------------------------------------------------------------------
   * Search Toggle Function
   * --------------------------------------------------------------------------- */
  jQuery('.cs-search form').hide();
  	jQuery("a.cs_searchbtn").click(function(){
  		jQuery('.cs-search form').hide();
    	jQuery(".cs-search form").fadeToggle();
   });
   jQuery('html').click(function() {
   	jQuery(".cs-search form").fadeOut();
   });
  jQuery('.cs-search').click(function(event){
       event.stopPropagation();
   });


/* ---------------------------------------------------------------------------
 * Hover on Section Function
 * --------------------------------------------------------------------------- */
	jQuery(document).ready(function(){
		"use strict";		  
    	jQuery(".blog-box").hover(function(){
     		jQuery(this).find(".bloginfo-sec").stop().animate({bottom:0}, 500);},
      		function() {
        		jQuery(this).find('.bloginfo-sec').stop().animate({bottom:-67}, 500);
    		});
  	});
 /* ---------------------------------------------------------------------------
 * Masonry view script start here
 * --------------------------------------------------------------------------- */
function cs_masonry_func(){
	'use strict';	
	setTimeout(function(){  
	  var container = jQuery(".mas-isotope").imagesLoaded(function() {
		  container.isotope()
		});
		jQuery(window).resize(function() {
		  setTimeout(function() {
			jQuery(".mas-isotope").isotope()
		  }, 600)
		});
	}, 3000);
}

/* ---------------------------------------------------------------------------
 * Music Top Strip
 * --------------------------------------------------------------------------- */
jQuery(document).ready(function() {
	"use strict";
	jQuery('.music').on('click', function(e){
		 // e.preventDefault();
		  var active = jQuery(this).parent('li').hasClass('active');
		
		  if(active){
			jQuery(this).parent('li').removeClass('active');
			jQuery(this).find('span').html('on');
			jQuery(this).siblings('i').removeClass();
			jQuery(this).siblings('i').addClass('icon-volume-off');
			document.getElementById('audio').pause();
		  }else{
			jQuery(this).parent('li').addClass('active');
			jQuery(this).find('span').html('off');
			jQuery(this).siblings('i').removeClass();
			jQuery(this).siblings('i').addClass('icon-volume-up');
			document.getElementById('audio').play();
		  }
	});
});
 
/* ---------------------------------------------------------------------------
 * Footer Back To Top Function
 * --------------------------------------------------------------------------- */
	jQuery(document).ready(function(){
		"use strict";	
		//Click event to scroll to top
		jQuery('#backtop').click(function(){
			jQuery('html, body').animate({scrollTop : 0},800);
			return false;
		});
		
	});
	

/* ---------------------------------------------------------------------------
 * Tool Tip
 * --------------------------------------------------------------------------- */
jQuery(function () {
	"use strict";		
  	jQuery('[data-toggle="tooltip"]').tooltip()
})	

/* ---------------------------------------------------------------------------
 * Music Toggle
 * --------------------------------------------------------------------------- */
function cs_music_toggle(admin_url){
	'use strict';
	var dataString='post_id=post_id&action=cs_music_toggle';
	jQuery.ajax({
		type:"POST",
		url: admin_url,
		data:dataString, 
		success:function(response){
			
		}
	});
	return false;
}

/* ---------------------------------------------------------------------------
* Parallex Function
* --------------------------------------------------------------------------- */
function cs_parallax_func(){
	"use strict";
	// Cache the Window object     
	jQuery('section.parallex-bg[data-type="background"]').each(function(){
		var $bgobj = jQuery(this); // assigning the object
		jQuery(window).scroll(function() {
			// Scroll the background at var speed
			// the yPos is a negative value because we're scrolling it UP!								
			var yPos = -(jQuery(window).scrollTop() / $bgobj.data('speed')); 
			// Put together our final background position
			var coords = '50% '+ yPos + 'px';
			// Move the background
			$bgobj.css({ backgroundPosition: coords });
		}); // window scroll Ends
	});
}

/* ---------------------------------------------------------------------------
* Mailchimp Function
* --------------------------------------------------------------------------- */
function cs_mailchimp_submit(theme_url,counter,admin_url){
	'use strict';
	$ = jQuery;
	$('#btn_newsletter_'+counter).hide();
	$('#process_'+counter).html('<div id="process_newsletter_'+counter+'"><i class="icon-refresh icon-spin"></i></div>');
	$.ajax({
		type:'POST', 
		url: admin_url,
		data:$('#mcform_'+counter).serialize()+'&action=cs_mailchimp', 
		success: function(response) {
			$('#mcform_'+counter).get(0).reset();
			$('#newsletter_mess_'+counter).fadeIn(600);
			$('#newsletter_mess_'+counter).html(response);
			$('#btn_newsletter_'+counter).fadeIn(600);
			$('#process_'+counter).html('');
		}
	});
}
/* ---------------------------------------------------------------------------
	* skills Function
 	* --------------------------------------------------------------------------- */
function cs_skill_bar(){
	"use strict";	 
	jQuery('.skillbar').each(function($) {
		jQuery(this).waypoint(function(direction) {
			jQuery(this).find('.skillbar-bar').animate({
				width: jQuery(this).attr('data-percent')
			}, 2000);
		}, {
			offset: "100%",
			triggerOnce: true
		});
	});

}

/* ---------------------------------------------------------------------------
	* skills Function
 	* --------------------------------------------------------------------------- */
 
function cs_testimonial_shortcode(id){
	"use strict";	 
	jQuery("#cs-testimonial-"+id).flexslider({
		animation: 'fade',
		slideshow: true,
		controlNav: true,
		directionNav: true,
		slideshowSpeed: 7000,
		animationDuration: 600,
		prevText:"<em class='icon-arrow-left9'></em>",
		nextText:"<em class='icon-arrow-right9'></em>",
		start: function(slider) {
			jQuery('.cs-testimonial').fadeIn();
		}
	});
}
/* ---------------------------------------------------------------------------
	* skills Function
 	* --------------------------------------------------------------------------- */

function cs_services(id){
	"use strict";	
	jQuery("#cs-flexslider-"+id).flexslider({
		animation: 'slide',
		minItems: 3,
		prevText:"<em class='icon-arrow-left9'></em>",
		nextText:"<em class='icon-arrow-right9'></em>",
	
	});
}
/* ---------------------------------------------------------------------------
	 * Banner ads Click Counter 
	 * --------------------------------------------------------------------------- */
function cs_banner_click_count_plus(ajax_url, id){
	'use strict';
	var dataString='code_id='+id+'&action=cs_banner_click_count_plus';
	jQuery.ajax({
		type:"POST",
		url: ajax_url,
		data:dataString, 
		success:function(response){
			if(response != 'error'){
				jQuery("#cs_banner_clicks"+id).removeAttr("onclick");
			}
		}
	});
	return false;
}
/* ---------------------------------------------------------------------------
	 * Map Styles
	 * --------------------------------------------------------------------------- */
function cs_map_select_style(style){
	"use strict";
	var styles = '';
	if(style == 'style-1'){
		var styles = [
						{
							"featureType": "administrative",
							"elementType": "all",
							"stylers": [
								{
									"visibility": "on"
								},
								{
									"lightness": 33
								}
							]
						},
						{
							"featureType": "landscape",
							"elementType": "all",
							"stylers": [
								{
									"color": "#f2e5d4"
								}
							]
						},
						{
							"featureType": "poi.park",
							"elementType": "geometry",
							"stylers": [
								{
									"color": "#c5dac6"
								}
							]
						},
						{
							"featureType": "poi.park",
							"elementType": "labels",
							"stylers": [
								{
									"visibility": "on"
								},
								{
									"lightness": 20
								}
							]
						},
						{
							"featureType": "road",
							"elementType": "all",
							"stylers": [
								{
									"lightness": 20
								}
							]
						},
						{
							"featureType": "road.highway",
							"elementType": "geometry",
							"stylers": [
								{
									"color": "#c5c6c6"
								}
							]
						},
						{
							"featureType": "road.arterial",
							"elementType": "geometry",
							"stylers": [
								{
									"color": "#e4d7c6"
								}
							]
						},
						{
							"featureType": "road.local",
							"elementType": "geometry",
							"stylers": [
								{
									"color": "#fbfaf7"
								}
							]
						},
						{
							"featureType": "water",
							"elementType": "all",
							"stylers": [
								{
									"visibility": "on"
								},
								{
									"color": "#acbcc9"
								}
							]
						}
					];
	}
	else if(style == 'style-2'){
		var styles = [
						{
							"featureType": "landscape",
							"stylers": [
								{
									"hue": "#FFBB00"
								},
								{
									"saturation": 43.400000000000006
								},
								{
									"lightness": 37.599999999999994
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "road.highway",
							"stylers": [
								{
									"hue": "#FFC200"
								},
								{
									"saturation": -61.8
								},
								{
									"lightness": 45.599999999999994
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "road.arterial",
							"stylers": [
								{
									"hue": "#FF0300"
								},
								{
									"saturation": -100
								},
								{
									"lightness": 51.19999999999999
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "road.local",
							"stylers": [
								{
									"hue": "#FF0300"
								},
								{
									"saturation": -100
								},
								{
									"lightness": 52
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "water",
							"stylers": [
								{
									"hue": "#0078FF"
								},
								{
									"saturation": -13.200000000000003
								},
								{
									"lightness": 2.4000000000000057
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "poi",
							"stylers": [
								{
									"hue": "#00FF6A"
								},
								{
									"saturation": -1.0989010989011234
								},
								{
									"lightness": 11.200000000000017
								},
								{
									"gamma": 1
								}
							]
						}
					];
	}
	else if(style == 'style-3'){
		var styles = [
						{
							"featureType": "administrative",
							"elementType": "labels.text.fill",
							"stylers": [
								{
									"color": "#444444"
								}
							]
						},
						{
							"featureType": "landscape",
							"elementType": "all",
							"stylers": [
								{
									"color": "#f2f2f2"
								}
							]
						},
						{
							"featureType": "poi",
							"elementType": "all",
							"stylers": [
								{
									"visibility": "off"
								}
							]
						},
						{
							"featureType": "road",
							"elementType": "all",
							"stylers": [
								{
									"saturation": -100
								},
								{
									"lightness": 45
								}
							]
						},
						{
							"featureType": "road.highway",
							"elementType": "all",
							"stylers": [
								{
									"visibility": "simplified"
								}
							]
						},
						{
							"featureType": "road.arterial",
							"elementType": "labels.icon",
							"stylers": [
								{
									"visibility": "off"
								}
							]
						},
						{
							"featureType": "transit",
							"elementType": "all",
							"stylers": [
								{
									"visibility": "off"
								}
							]
						},
						{
							"featureType": "water",
							"elementType": "all",
							"stylers": [
								{
									"color": "#46bcec"
								},
								{
									"visibility": "on"
								}
							]
						}
					];
	}
	return styles;
}


/* ---------------------------------------------------------------------------
	* Form Validation Function
 * --------------------------------------------------------------------------- */
function cs_form_validation(form_id, cs_type){
	"use strict";
	var name_field = jQuery('#frm'+form_id+' input[name="contact_name"]');
	var email_field = jQuery('#frm'+form_id+' input[name="contact_email"]');
	var subject_field = jQuery('#frm'+form_id+' input[name="subject"]');
	var message_field = jQuery('#frm'+form_id+' textarea[name="contact_msg"]');
	
	var name = name_field.val();
	var email = email_field.val();
	var subject = subject_field.val();
	var message = message_field.val();
	var email_pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
	var name_err_msg, email_err_msg, subject_err_msg, msg_err_msg;
	
	var cs_error_form = true;
	if( name == ''){
		name_err_msg = '<p>Please Fill in Name</p>';
		name_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		name_err_msg = '';
		name_field.removeClass('cs-error');
	}
	if(email == ''){
		email_err_msg = "<p>Please Enter Email.</p>";
		email_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		email_err_msg = '';
		email_field.removeClass('cs-error');
	}
	if(email != ''){
		if(!email_pattern.test(email)){
			email_err_msg = "<p>Please Enter Valid Email.</p>";
			email_field.addClass('cs-error');
			cs_error_form = false;
		}
		else{
			email_err_msg = '';
			email_field.removeClass('cs-error');
		}
	}
	if( subject == ''){
		subject_err_msg = '<p>Please Fill in Subject</p>';
		subject_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		subject_err_msg = '';
		subject_field.removeClass('cs-error');
	}
	if( message == ''){
		msg_err_msg = '<p>Please Fill in Message</p>';
		message_field.addClass('cs-error');
		cs_error_form = false;
	}
	else{
		msg_err_msg = '';
		message_field.removeClass('cs-error');
	}
	if(cs_error_form == true){
		if( cs_type == 'widget' ) {
			
			var admin_url = jQuery('#frm'+form_id).data('ajaxurl');
			var succ_msg = jQuery('#frm'+form_id).data('sucmsg');
			var err_msg = jQuery('#frm'+form_id).data('errmsg');
			
			jQuery("#loading_div"+form_id).html('<i class="icon-spinner8 icon-spin"></i>');
			jQuery("#loading_div"+form_id).show();
			jQuery("#message"+form_id).html('');
			var datastring = jQuery('#frm'+form_id).serialize() +"&cs_contact_succ_msg="+succ_msg+"&cs_contact_error_msg="+err_msg+"&action=cs_contact_form_submit";
			jQuery.ajax({
				type:'POST', 
				url: admin_url,
				data: datastring, 
				dataType: "json",
				success: function(response) {
					
					if (response.type == 'error'){
						jQuery("#loading_div"+form_id).html('');
						jQuery("#loading_div"+form_id).hide();
						jQuery("#message"+form_id).addClass('error_mess');
						jQuery("#message"+form_id).show();
						jQuery("#message"+form_id).html(response.message);
					} else if (response.type == 'success'){
						jQuery("#frm"+form_id).slideUp();
						jQuery("#loading_div"+form_id).html('');
						jQuery("#loading_div"+form_id).hide();
						jQuery("#message"+form_id).addClass('succ_mess');
						jQuery("#message"+form_id).show();
						jQuery("#message"+form_id).html(response.message);
					}                        
				}
			});
			
		} else {
			cs_contact_frm_submit(form_id);
		}
	}else{
		// do nothing 
	}
}

/* ---------------------------------------------------------------------------
  * Textarea Focus Function's
  * --------------------------------------------------------------------------- */
  jQuery(document).ready(function($){
	  "use strict";
		jQuery('input,textarea').focus(function(){
		   jQuery(this).data('placeholder',jQuery(this).attr('placeholder'))
		   jQuery(this).attr('placeholder','');
		});
		jQuery('input,textarea').blur(function(){
		   jQuery(this).attr('placeholder',jQuery(this).data('placeholder'));
		});
	});



/* ---------------------------------------------------------------------------
	 * Post like Counter 
	 * --------------------------------------------------------------------------- */
function cs_post_likes_count(admin_url, id){
	"use strict";
	var dataString='post_id=' + id + '&action=cs_post_likes_count';
	jQuery("#post_likes"+id).html('<i class="icon-spinner8 fa-spin"></i>');
	jQuery.ajax({
		type:"POST",
		url: admin_url,
		data:dataString, 
		success:function(response){
			if(response != 'error'){
				jQuery("#post_likes"+id).html(response);
				jQuery("#post_likes"+id).removeAttr("onclick");
			} else {
				jQuery("#post_likes"+id).html(' There is an error.');
			}
			
		}
	});
	return false;
}

/* ---------------------------------------------------------------------------
	 * Show Map Function
	 * --------------------------------------------------------------------------- */
	
function cs_show_map(id, add,lat, long, zoom, home_url, path) {
	"use strict";					
	var a = jQuery("div.post-"+id).find("[id^=map]").length;
	if (a > 1) {
			jQuery("#event-"+id).slideToggle();
		} else {
			jQuery("article.post-"+id) .find("a.map-marker i").hide();
			jQuery("article.post-"+id) .find("a.map-marker").append('<i class="icon-spinner"></i>');
			var dataString = 'post_id=' + id + '&add=' + add + '&lat=' + lat + '&long=' + long + '&zoom='+ zoom;
			jQuery.ajax({
				type:"POST",
				url: path+"/templates/events/event_map_ajax.php",
				data:dataString, 
				success:function(response){
					jQuery("article.post-"+id) .find("a.map-marker i").show();
					jQuery("article.post-"+id) .find("a.map-marker .icon-spinner").hide();
					jQuery("div.post-"+id).toggleClass("event-map");
					jQuery("div.post-"+id).show();
					jQuery("#map_canvas"+id).html(response);
				
				}
	});
		}
}

/* ---------------------------------------------------------------------------
  * Responsive Video Function
  * --------------------------------------------------------------------------- */

  jQuery(document).ready(function($) {
	"use strict";	
    jQuery(".main-section").fitVids();
  });

(function(e){"use strict";e.fn.fitVids=function(t){var n={customSelector:null,ignore:null};if(!document.getElementById("fit-vids-style")){var r=document.head||document.getElementsByTagName("head")[0];var i=".fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}";var s=document.createElement("div");s.innerHTML='<p>x</p><style id="fit-vids-style">'+i+"</style>";r.appendChild(s.childNodes[1])}if(t){e.extend(n,t)}return this.each(function(){var t=['iframe[src*="player.vimeo.com"]','iframe[src*="youtube.com"]','iframe[src*="youtube-nocookie.com"]','iframe[src*="kickstarter.com"][src*="video.html"]',"object","embed"];if(n.customSelector){t.push(n.customSelector)}var r=".fitvidsignore";if(n.ignore){r=r+", "+n.ignore}var i=e(this).find(t.join(","));i=i.not("object object");i=i.not(r);i.each(function(){var t=e(this);if(t.parents(r).length>0){return}if(this.tagName.toLowerCase()==="embed"&&t.parent("object").length||t.parent(".fluid-width-video-wrapper").length){return}if(!t.css("height")&&!t.css("width")&&(isNaN(t.attr("height"))||isNaN(t.attr("width")))){t.attr("height",9);t.attr("width",16)}var n=this.tagName.toLowerCase()==="object"||t.attr("height")&&!isNaN(parseInt(t.attr("height"),10))?parseInt(t.attr("height"),10):t.height(),i=!isNaN(parseInt(t.attr("width"),10))?parseInt(t.attr("width"),10):t.width(),s=n/i;if(!t.attr("id")){var o="fitvid"+Math.floor(Math.random()*999999);t.attr("id",o)}t.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",s*100+"%");t.removeAttr("height").removeAttr("width")})})}})(window.jQuery||window.Zepto)

/* ---------------------------------------------------------------------------
  * Page Loader Function
  * --------------------------------------------------------------------------- */

// jQuery(window).load(function(){
// 	jQuery('.overlay-load').fadeOut();
// 	// jQuery('body').css({"overflow":"auto"});
// });