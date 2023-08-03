var windowWidth = jQuery(window).width();

if(windowWidth < 768){
	jQuery('.col1').find('.admin-navigtion').addClass('navigation-small');
}else{
	jQuery('.col1').find('.admin-navigtion').removeClass('navigation-small');
}

jQuery('.nav-button').on('click', function(e){
	e.preventDefault();
	var windowWidth = jQuery(window).width();
	if(windowWidth < 768){
		jQuery('.nav-button').closest('.admin-navigtion').addClass('navigation-small');
	}	
});

jQuery(window).resize(function(){
	var windowWidth = jQuery(window).width();

	if(windowWidth < 768){
		jQuery('.col1').find('.admin-navigtion').addClass('navigation-small');
		jQuery('.nav-button').on('click', function(e){
			e.preventDefault();
			jQuery(this).parents('.admin-navigtion').addClass('navigation-small');
		});
	}else{
		jQuery('.col1').find('.admin-navigtion').removeClass('navigation-small');
	}
});

jQuery(document).ready(function($) {
	
	$("#cs_rooms_data").removeClass('ui-sortable-handle');
	// Booking Calculations	
	$('[id^=cs_extra_feat_], [name=cs_payment_part], [name=cs_payment_gateway]').click(function() {
        cs_booking_pricing();
    });
	
	// Booking Form Validation
	$('.cs-check-tabs').on('click', function(e) {
		var cs_detail_tab = $('#cs-detail-tab');
		var cs_booking_form = $('#cs-booking-form');
		var cs_form_validity = 'invalid';
		$(":input[required]").each(function () {                     
			if (cs_booking_form[0].checkValidity()){                
				cs_form_validity = 'valid';
			}
		});
		
		if(cs_form_validity == 'invalid') {
			$('.cs-reservation-tabs .tabs-nav').find('li').removeClass('active');
			cs_detail_tab.parents('li').addClass('active');
			
			var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').attr('href');

			$('.cs-reservation-tabs .tabs-content').find('.tabs').hide();
			$('.cs-reservation-tabs .tabs-content').find(active).show();
		
			alert('Please fill your details first.');
			return false;
		}
	});
	
	// Checkbox
	$('label.cs-chekbox').on('click', function() {
		var checkbox = $(this).children('input[type=checkbox]');
		
		if(checkbox.is(":checked")) {
			$('#'+checkbox.attr('name')).val(checkbox.val())
		}
		else{
			$('#'+checkbox.attr('name')).val('')
		}
	});
	
});

/**
* Toggle Function
*/
function cs_toggle(id) {
	jQuery("#" + id).slideToggle("slow");
}

/**
* Update Title
*/
function update_title(id) {
	var val;
	val = jQuery('#address_name' + id).val();
	jQuery('#address_name' + id).html(val);
}

/**
* Delete Confirm Html popup
*/
var html_popup = "<div id='confirmOverlay' style='display:block'> \
								<div id='confirmBox'><div id='confirmText'>Are you sure to do this?</div> \
								<div id='confirmButtons'><div class='button confirm-yes'>Delete</div>\
								<div class='button confirm-no'>Cancel</div><br class='clear'></div></div></div>"
								

/**
* Delete Item
*/
jQuery(".btndeleteit").live("click", function() {
	
	jQuery(this).parents(".parentdelete").addClass("warning");
	jQuery(this).parent().append(html_popup);

	jQuery(".confirm-yes").click(function() {
		jQuery(this).parents(".parentdelete").fadeOut(400, function() {
			jQuery(this).remove();
		});
		
		jQuery(this).parents(".parentdelete").each(function(){
			var lengthitem = jQuery(this).parents(".dragarea").find(".parentdelete").size() - 1;
			jQuery(this).parents(".dragarea").find("input.textfld") .val(lengthitem);
		});

		jQuery("#confirmOverlay").remove();
		//count_widget--;
		//if (count_widget == 0) jQuery("#add_page_builder_item").removeClass("hasclass");
	
	});
	
	jQuery(".confirm-no").click(function() {
		jQuery(this).parents(".parentdelete").removeClass("warning");
		jQuery("#confirmOverlay").remove();
	});
	
	return false;
});




/**
* Get Rooms for Hotel
*/
function cs_get_rooms(admin_url,hotel_name){
	jQuery("#cs-booking-rooms").html('');
	jQuery('#cs-booking-rooms').after('<div id="loader-img-room" class="loader-img"><i class="icon-spin icon-spinner"></i></div>');
	jQuery.ajax({
		type: "POST",
		url: admin_url,
		data: 'hotel_name='+hotel_name+'&action=cs_get_hotel_rooms',
		success: function(response) {
            jQuery('#loader-img-room').remove();
            if(response == ''){
                response = '<option selected="selected">Nill</option>';
            }
			jQuery("#cs-booking-rooms").html(response);
		}
	});
}

/**
* Create Popup
*/
function cs_createpop(data, type) {
	var _structure = "<div id='cs-pbwp-outerlay'><div id='cs-widgets-list'></div></div>",
		$elem = jQuery('#cs-widgets-list');
	jQuery('body').addClass("cs-overflow");
	if (type == "csmedia") {
		$elem.append(data);
	}
	if (type == "filter") {
		jQuery('#' + data).wrap(_structure).delay(100).fadeIn(150);
		jQuery('#' + data).parent().addClass("wide-width");
	}
	if (type == "filterdrag") {
		jQuery('#' + data).wrap(_structure).delay(100).fadeIn(150);
	}

}

/**
* Remove Popup
*/
function cs_remove_overlay(id, text) {
	jQuery("#cs-widgets-list .loader").remove();
	var _elem1 = "<div id='cs-pbwp-outerlay'></div>",
		_elem2 = "<div id='cs-widgets-list'></div>";
	$elem = jQuery("#" + id);
	jQuery("#cs-widgets-list").unwrap(_elem1);
	if (text == "append" || text == "filterdrag") {
		$elem.hide().unwrap(_elem2);
	}
	if (text == "widgetitem") {
		$elem.hide().unwrap(_elem2);
		jQuery("body").append("<div id='cs-pbwp-outerlay'><div id='cs-widgets-list'></div></div>");
		return false;

	}
	if (text == "ajax-drag") {
		jQuery("#cs-widgets-list").remove();
	}
	jQuery("body").removeClass("cs-overflow");
}

/**
 * Media upload
 */
jQuery(document).ready(function() {
	var ww = jQuery('#post_id_reference').text();
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor_clone = function(html){
		imgurl = jQuery('a','<p>'+html+'</p>').attr('href');
		jQuery('#'+formfield).val(imgurl);
		tb_remove();
	}
	jQuery('input.uploadfile').click(function() {
		window.send_to_editor=window.send_to_editor_clone;
		formfield = jQuery(this).attr('name');
		tb_show('', 'media-upload.php?post_id=' + ww + '&type=image&TB_iframe=true');
		return false;
	});
});

/**
* Number Format
*/

function cs_number_format(num){
	return parseFloat(Math.round(num * 100) / 100).toFixed(2);
}

/**
* Change Package
*/
function cs_booking_pricing(){
	"use strict";
	
	// Booking Calculations
	var $ = jQuery;
	var cs_currency = '$';
	var cs_bkng_gross = 0;
	var cs_total_amount = 0;
	var cs_booking_fee = 0;
	var cs_booking_vat = 0;
	
	$("[id^=cs_extra_feat_]:checked").each(function() {
        var checked_features = jQuery(this).attr('data-price');
		cs_bkng_gross += parseFloat(checked_features);
    });
	
	cs_bkng_gross += parseFloat($('.cs-booking-gross').attr('data-gross'));
	
	$('.cs-booking-gross').html(cs_currency+cs_number_format(cs_bkng_gross));
		
	// Partial Amount Calculations
	var cs_partial_amount = 0;
	var cs_bkng_partial = $('#cs-partial-area strong').attr('data-partial');
	
	if(typeof(cs_bkng_partial) !== 'undefined'){
		
		if(cs_bkng_partial < 0){
			cs_bkng_partial = 1;
		}
		cs_partial_amount = (parseFloat(cs_bkng_gross)*parseInt(cs_bkng_partial))/100;
		
		$('#cs-partial-area strong').html(cs_currency+cs_number_format(cs_partial_amount));
	}
	
	// Default Gross Total in case of full Payment ON
	$('.cs-booking-total').html(cs_currency+cs_number_format(cs_bkng_gross));
	$('.cs-booking-total').attr('data-total', cs_number_format(cs_bkng_gross));
	
	// Partial Amount Calculations
	var cs_bkng_pay = $('[name=cs_payment_part]:checked').attr('id');
	
	if(typeof(cs_bkng_pay) !== 'undefined'){
		
		if(cs_bkng_pay == 'booking_part_pay'){
			$('.cs-booking-total').html(cs_currency+cs_number_format(cs_partial_amount));
			$('.cs-booking-total').attr('data-total', cs_number_format(cs_partial_amount));
			$('#cs-partial-area').show("slow");
		}
		else if(cs_bkng_pay == 'booking_full_pay'){
			$('.cs-booking-total').html(cs_currency+cs_number_format(cs_bkng_gross));
			$('.cs-booking-total').attr('data-total', cs_number_format(cs_bkng_gross));
			$('#cs-partial-area').hide("slow");
		}
	}
	
	cs_total_amount = $('.cs-booking-total').attr('data-total');
	
	// Fee Percentage
	var cs_fee_percent = $('[name=cs_payment_gateway]:checked').attr('data-fee');
	if(typeof(cs_fee_percent) !== 'undefined'){
		
		if(cs_fee_percent < 0){
			cs_fee_percent = 1;
		}
		
		cs_booking_fee = (cs_total_amount*cs_fee_percent)/100;
		$('.cs-booking-fee').attr('data-fee', cs_number_format(cs_booking_fee));
		$('.cs-booking-fee').html(cs_currency+cs_number_format(cs_booking_fee));
	}
		
	cs_booking_fee = $('.cs-booking-fee').attr('data-fee');

	if(typeof(cs_booking_fee) !== 'undefined' && cs_booking_fee > 0){
		cs_total_amount = parseFloat(cs_total_amount)+parseFloat(cs_booking_fee);
	}
	
	// VAT Percentage
	var cs_vat_percent = $('.cs-booking-vat').attr('data-percent');
	if(typeof(cs_vat_percent) !== 'undefined'){
		
		if(cs_vat_percent < 0){
			cs_vat_percent = 1;
		}
		
		cs_booking_vat = (cs_total_amount*cs_vat_percent)/100;
		$('.cs-booking-vat').attr('data-vat', cs_number_format(cs_booking_vat));
		$('.cs-booking-vat').html(cs_currency+cs_number_format(cs_booking_vat));
	}
		
	cs_booking_vat = $('.cs-booking-vat').attr('data-vat');

	if(typeof(cs_booking_vat) !== 'undefined' && cs_booking_vat > 0){
		cs_total_amount = parseFloat(cs_total_amount)+parseFloat(cs_booking_vat);
	}
	
	$('.cs-booking-grand').html(cs_currency+cs_number_format(cs_total_amount));
}

var counter_extra_feature = 0;
function add_extra_feature_to_list(admin_url, plugin_url) {
	counter_extra_feature++;
	var dataString = 'counter_extra_feature=' + counter_extra_feature +
		'&cs_extra_feature_title=' + jQuery("#cs_extra_feature_title").val() +
		'&cs_extra_feature_price=' + jQuery("#cs_extra_feature_price").val() +
		'&cs_extra_feature_type=' + jQuery("#cs_extra_feature_type").val() +
		'&cs_extra_feature_guests=' + jQuery("#cs_extra_feature_guests").val() +
		'&cs_extra_feature_fchange=' + jQuery("#cs_extra_feature_fchange").val() +
		'&cs_extra_feature_desc=' + jQuery("#cs_extra_feature_desc").val() +
		'&action=cs_add_extra_feature_to_list';
	jQuery(".feature-loader").html("<img src='" + plugin_url + "/assets/images/ajax-loader.gif' />");
	jQuery.ajax({
		type: "POST",
		url: admin_url,
		data: dataString,
		success: function(response) {
			jQuery("#total_extra_features").append(response);
			jQuery(".feature-loader").html("");
			cs_remove_overlay('add_extra_feature_title', 'append');
			jQuery("#cs_extra_feature_title").val("Title");
			jQuery("#cs_extra_feature_price").val("");
			jQuery("#cs_extra_feature_type").val("");
			jQuery("#cs_extra_feature_guests").val("");
			jQuery("#cs_extra_feature_desc").val("");
		}
	});
	return false;
}

var counter_feats = 0;
function add_feats_to_list(admin_url, plugin_url) {
	counter_feats++;
	var dataString = 'counter_feats=' + counter_feats +
		'&cs_feats_title=' + jQuery("#cs_feats_title").val() +
		'&cs_feats_image=' + jQuery("#cs_feats_image").val() +
		'&cs_feats_desc=' + jQuery("#cs_feats_desc").val() +
		'&action=cs_add_feats_to_list';
	jQuery(".feature-loader").html("<img src='" + plugin_url + "/assets/images/ajax-loader.gif' />");
	jQuery.ajax({
		type: "POST",
		url: admin_url,
		data: dataString,
		success: function(response) {
			jQuery("#total_feats").append(response);
			jQuery(".feature-loader").html("");
			cs_remove_overlay('add_feats_title', 'append');
			jQuery("#cs_feats_title").val("Title");
			jQuery("#cs_feats_image").val("");
			jQuery("#cs_feats_desc").val("");
		}
	});
	return false;
}

var counter_dyn_reviews = 0;
function add_dyn_reviews_to_list(admin_url, plugin_url) {
	counter_dyn_reviews++;
	var dataString = 'counter_dyn_reviews=' + counter_dyn_reviews +
		'&cs_dyn_reviews_title=' + jQuery("#cs_dyn_reviews_title").val() +
		'&action=cs_add_dyn_reviews_to_list';
	jQuery(".feature-loader").html("<img src='" + plugin_url + "/assets/images/ajax-loader.gif' />");
	jQuery.ajax({
		type: "POST",
		url: admin_url,
		data: dataString,
		success: function(response) {
			jQuery("#total_dyn_reviews").append(response);
			jQuery(".feature-loader").html("");
			cs_remove_overlay('add_dyn_reviews_title', 'append');
			jQuery("#cs_dyn_reviews_title").val("Title");
		}
	});
	return false;
}



/**
 * @Gallery
 *
 */
jQuery( function($){
	// Product gallery file uploads
	var gallery_frame;

	jQuery('.add_gallery_data').on( 'click', 'a', function( event ) {
		var $el = $(this);
		
		var get_id 		   	   = $el.parents('.add_gallery_data').data('id');
		var rand_id 		   = $el.parents('.add_gallery_data').data('rand_id');
		
		var cs_plugin_url 	   = $("#cs_plugin_url").val();
		var $gallery_images    = $('#gallery_container ul.gallery_images');
		var attachment_ids 	   = $('#'+get_id).val();

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( gallery_frame ) {
			gallery_frame.open();
			return;
		}

		// Create the media frame.
		gallery_frame = wp.media.frames.room_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable : 'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		gallery_frame.on( 'select', function() {

			var selection = gallery_frame.state().get('selection');

			selection.map( function( attachment ) {

				attachment = attachment.toJSON();
				
				if( attachment.type == 'image' ) {
					var gallery_url	= '<img src="' + attachment.url + '" />';
				} else if( attachment.type == 'audio' ) {
					var gallery_url	= '<i class="icon-documents"></i>';
				} else if( attachment.type == 'video' ) {
					var gallery_url	= '<i class="icon-video-camera"></i>';
				} else{
					var gallery_url	= '<i class="icon-documents"></i>';
				}
				
				if ( attachment.id ) {
					attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

					$gallery_images.append('\
						<li class="image" data-attachment_id="' + attachment.id + '">\
							'+gallery_url+'\
							<div class="actions">\
								<span><a href="javascript:;" class="delete" title="' + $el.data('delete') + '"><i class="icon-times"></i></a></span>\
							</div>\
						</li>');
					}

				});

				var gallery = []; // more efficient than new Array()
				jQuery('#gallery_sortable_'+rand_id+' li').each(function(){
					var data_value	= jQuery.trim( jQuery(this).data('attachment_id'));
					 gallery.push(jQuery(this).data('attachment_id'));
				});
				
				jQuery("#"+get_id).val(gallery.toString());
			});

			// Finally, open the modal.
			gallery_frame.open();
		});
		
});

/**
 * @Sorting
 *
 */
 
function cs_gallery_sorting(id,random_id){
	var gallery = []; // more efficient than new Array()
	jQuery('#gallery_sortable_'+random_id+' li').each(function(){
		var data_value	= jQuery.trim( jQuery(this).data('attachment_id'));
		 gallery.push(jQuery(this).data('attachment_id'));
	});
	
	jQuery("#"+id).val(gallery.toString());
}

/**
 * @Attachments
 *
 */
jQuery( function($){
	// Product gallery file uploads
	var directory_gallery_frame;
	var $cs_attachments_ids 	= $('#cs_room_file_attach');
	var $directory_attachments  = $('#file_attachment_container ul.cs_attachments_list');

	jQuery('.add_file_attachmnets').on( 'click', 'input', function( event ) {
		var $el = $(this);
		
		var file_icon_url = jQuery("#file_icon_url").val();
		var attachment_ids = $cs_attachments_ids.val();

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( directory_gallery_frame ) {
			directory_gallery_frame.open();
			return;
		}

		// Create the media frame.
		directory_gallery_frame = wp.media.frames.directory_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data('choose'),
			button: {
				text: $el.data('update'),
			},
			states : [
				new wp.media.controller.Library({
					title: $el.data('choose'),
					filterable : 'all',
					multiple: true,
				})
			]
		});

		// When an image is selected, run a callback.
		directory_gallery_frame.on( 'select', function() {
			var selection = directory_gallery_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

					$directory_attachments.append('\
						<li class="cs-file-list" data-attachment_id="' + attachment.id + '">\
							<img src="' + file_icon_url + '" />\
							<div class="actions">\
								<span><a href="#" class="delete" title="' + $el.data('delete') + '"><i class="icon-times"></i></a></span>\
							</div>\
						</li>');
					}
				});
				$cs_attachments_ids.val( attachment_ids );
			});

			// Finally, open the modal.
			directory_gallery_frame.open();
		});

		// Remove images
		$('#file_attachment_container').on( 'click', 'a.delete', function() {
			$(this).closest('li.cs-file-list').remove();
			return false;
		});
});


/*--------------------------------------------------------------
 * Plugin Option Saving
 *-------------------------------------------------------------*/
function plugin_option_save(admin_url){
	jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
	function newValues() {
	  var serializedValues = jQuery("#plugin-options input,#plugin-options select,#plugin-options textarea").serialize()+'&action=plugin_option_save';
	  return serializedValues;
	}
	var serializedReturn = newValues();
	 jQuery.ajax({
		type:"POST",
		url: admin_url,
		data:serializedReturn, 
		success:function(response){
			
			jQuery(".loading_div").hide();
			jQuery(".form-msg .innermsg").html(response);
			jQuery(".form-msg").show();
			jQuery(".outerwrapp-layer").delay(100).fadeOut(100)
			window.location.reload(true);
			slideout();
		}
	});
	//return false;
}
		
function cs_pl_backup_generate( admin_url ){
	"use strict";
	jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
	
	var dataString = 'action=cs_pl_opt_backup_generate';
	jQuery.ajax({
		type:"POST",
		url: admin_url,
		data:dataString, 
		success:function(response){
			
			jQuery(".loading_div").hide();
			jQuery(".form-msg .innermsg").html(response);
			jQuery(".form-msg").show();
			jQuery(".outerwrapp-layer").delay(100).fadeOut(100);
			window.location.reload(true);
			slideout();
		}
	});
	//return false;
}

jQuery(document).on('click', '#cs-p-backup-restore', function(){
	
	jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
	
	var admin_url = jQuery('.cs-import-areaa').data('ajaxurl');
	var file_name = jQuery(this).data('file');
	
	var dataString = 'file_name='+file_name+'&action=cs_pl_backup_file_restore';
	
	jQuery.ajax({
		type:"POST",
		url: admin_url,
		data:dataString, 
		success:function(response){
			
			jQuery(".loading_div").hide();
			jQuery(".form-msg .innermsg").html(response);
			jQuery(".form-msg").show();
			jQuery(".outerwrapp-layer").delay(2000).fadeOut(100);
			window.location.reload(true);
			slideout();
		}
	});
	//return false;
});
		
function price_option_save(admin_url,id){
	cs_remove_overlay('cs_'+id+'_popup','append');
	
	jQuery(".outerwrapp-layer,.loading_div").fadeIn(100);
	function newValues() {
	 	var serializedValues = jQuery("#cs_get_prices input,#cs_pricing_result input, #cs-booking-pricing input,#cs-booking-pricing select,#cs-booking-pricing textarea").serialize()+'&action=price_option_save';
	  return serializedValues;
	}
	
	/*jQuery("td.price-action").each(function(){
		var $this = jQuery(this); // This is the jquery object of the input, do what you will
		var currency	= $this.data('currency');
		var new_val	= $this.find('input').val();
		$this.find('small').html(currency+new_val);
		$this.find('input').hide();
		$this.find('small').show();
	});*/
		
	var serializedReturn = newValues();
	 jQuery.ajax({
		type:"POST",
		url: admin_url,
		data:serializedReturn, 
		success:function(response){
			
			jQuery(".loading_div").hide();
			jQuery(".form-msg .innermsg").html(response);
			jQuery(".form-msg").show();
			jQuery(".outerwrapp-layer").delay(100).fadeOut(100)
			//window.location.reload(true);
			slideout();
		}
	});
	//return false;
}

/*--------------------------------------------------------------
 * Plugin Reset Option
 *-------------------------------------------------------------*/
function cs_rest_plugin_options(admin_url, plugin_url){
	//jQuery(".loading_div").show('');
	var var_confirm = confirm("You current plugin options will be replaced with the default plugin activation options.");
	if ( var_confirm == true ){
		var dataString = 'action=plugin_option_rest_all';
		jQuery.ajax({
			type:"POST",
			url: admin_url+"/admin-ajax.php",
			data: dataString,
			success:function(response){
				jQuery(".form-msg").show();
				jQuery(".form-msg").html(response);
				jQuery(".loading_div").hide();
				window.location.reload(true);
				slideout();
			}
		});
	}
	//return false;
}

/*--------------------------------------------------------------
 * Plugin Demo Option
 *-------------------------------------------------------------*/
function cs_demo_plugin_options(admin_url, plugin_url){

	var var_confirm = confirm("You current plugin options will be replaced with the demo plugin activation options.");
	if ( var_confirm == true ){
		var dataString = 'action=plugin_option_demo_ready';
		jQuery.ajax({
			type:"POST",
			url: admin_url+"/admin-ajax.php",
			data: dataString,
			success:function(response){
				jQuery(".form-msg").show();
				jQuery(".form-msg").html(response);
				jQuery(".loading_div").hide();
				window.location.reload(true);
				slideout();
			}
		});
	}
	//return false;
}

/*--------------------------------------------------------------
 * Plugin Reset Option
 *-------------------------------------------------------------*/
 function cs_get_currencies( code ){
	 
	if( code != '' ){
		var dataString = 'code=' + code +
		'&action=cs_get_currency_symbol';
		var plugin_url	= jQuery("#cs_plugin_url").val();
		jQuery("#currency_sign").parent('.to-field').append("<span><i style='color:#fe9909;' class='icon-spinner8 icon-spin'></i></span>");
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: dataString,
			success: function(response) {
				jQuery("#currency_sign").val(response);
				jQuery("#currency_sign").parent('.to-field').find('span').remove();
			}
		});
	}
	
	return false;
}

jQuery(document).ready(function() {	 
	jQuery("#cs_currency_type").change(function(e) {
		cs_get_currencies(this.value);
    });
});

/* ---------------------------------------------------------------------------
 * Accomodation Toggle Function
 * --------------------------------------------------------------------------- */
	jQuery(".plain-info").find('.cslist-info').hide();
	jQuery('.info-toggle').on('click', function(e){
		e.preventDefault();
		var datalink = jQuery(this).attr('id');
		var active = jQuery(this).hasClass('active');
		if(active){
			jQuery(this).removeClass('active');
			jQuery(this).parents(".plain-info").find('.cslist-info').toggle( "slow");
			jQuery(this).find('i').removeClass().addClass('icon-plus8');
		}else{
			jQuery(this).addClass('active');
			jQuery(this).parents(".plain-info").find('.cslist-info').toggle( "slow");
			jQuery(this).find('i').removeClass().addClass('icon-minus8');
		}
	});

/* ---------------------------------------------------------------------------
 * Rooms Ajax Listing & Filteration
 * --------------------------------------------------------------------------- */
	jQuery('.cs-rooms-filter').on('click', function(e){
		function newValues() {
		  var serializedValues = jQuery("#plugin-options input").serialize()+'&action=cs_rooms_listing_ajax';
		  return serializedValues;
		}
		var serializedReturn = newValues();
		
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: dataString,
			success: function(response) {
				jQuery("#currency_sign").val(response);
				jQuery("#currency_sign").parent('.to-field').find('span').remove();
			}
		});
	});

/* ---------------------------------------------------------------------------
 * Add/Remove Rooms
 * --------------------------------------------------------------------------- */
jQuery(document).ready(function() {	
	jQuery("#cs_room_num").change(function(){
		counter	= 0;
		var rooms_prefix_dummy = jQuery("#cs_rooms_prefix").val();
		var rooms_prefix 	   = jQuery("#cs_rooms_prefix").val();
		if( rooms_prefix == '' ) {
			rooms_prefix	= rooms_prefix_dummy;
		}
		
		var rooms_data_total = jQuery("#rooms-holder").data('total');
		var rooms_data  = jQuery("#rooms-holder").data('rooms').split(",");
		var keys_data   = jQuery("#rooms-holder").data('keys').split(",");
		var status_data = jQuery("#rooms-holder").data('status').split(",");
		var reason_data = jQuery("#rooms-holder").data('reason').split("|||");
		
		var count = jQuery("#rooms-holder ul li").size();
		var requested = jQuery("#cs_room_num").val();

		if (requested > count) {
			
			for(i=count; i<requested; i++) {
			 counter++;
			 var str		= uniqID(5);
			 var del_cap	= '';
			 var status		= '';
			 
			 if( rooms_data_total > 0 && i < rooms_data_total ) {
			 	
				if( rooms_data[i] ) {
					var uniq_name	= rooms_data[i];
					var uniq_status	= status_data[i];
					var uniq_key	= keys_data[i];
					var uniq_reason	= reason_data[i];
					var status	= 'readonly="readonly"';
					var del_cap	= '<a href="javascript:;" class="delete-capcaity-room"><i class="icon-trash4"></i></a>';
				} else{
					var uniq_name	= rooms_prefix+(i+1);
				}
			 } else{
			 	var uniq_name	= rooms_prefix+(i+1);
				var uniq_status	= 'active';
				var uniq_key	= i;
				var uniq_reason	= '';
			 }
			 
			 
			 var $ctrl = '<li><input type="text" '+status+' class="rooms_meta" value="'+uniq_name+'" name="cs_room_meta[]" id="room_meta"/><input type="hidden" value="'+uniq_key+'" name="cs_room_key[]" id="room_meta"/><input type="hidden" value="'+uniq_status+'" name="cs_room_status[]" /><input type="hidden" value="'+uniq_reason+'" name="cs_room_reason[]" /><i class="icon-arrows-alt"></i>'+del_cap+'<span class="name-checking"></span></li>'       
				jQuery("#rooms-holder ul").append($ctrl);
				cs_check_availabilty();
			}
		} else if ( jQuery.trim(requested) == '' ){
			jQuery("#rooms-holder ul").html('');
		} else if (requested < count) {
			var x = requested - 1;
			jQuery("#rooms-holder ul li:gt(" + x + ")").remove();
		}
	});
	
	jQuery( "#cs_rooms_data" ).sortable();
	
	
	
	function uniqID(length) {

			var charstoformid = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
            if (! length) {
                length = Math.floor(Math.random() * charstoformid.length);
            }
            
			var uniqid = '';
            for (var i = 0; i < length; i++) {
                uniqid += charstoformid[Math.floor(Math.random() * charstoformid.length)];
            }
			
			// one last step is to check if this ID is already taken by an element before 
            if(jQuery("#"+uniqid).length == 0)
                return uniqid;
            else
                return uniqID(5)
      }
});

/**
 * Check Availabilty
 */ 

function cs_check_availabilty() {
	
	jQuery('input#room_meta').keyup( function(e) { 
	 //then give it a second to see if the user is finished
	var timer;
	var name = jQuery(this).val();
	var serializedValues = jQuery("form").serialize();
	$this	= jQuery( this );
	var dataString = 'name=' + name + 
					 '&form_field_names=' + serializedValues + 
					 '&action=cs_check_name_availabilty'
	
		clearInterval(timer);  //clear any interval on key up
		timer = setTimeout(function() { //then give it a second to see if the user is finished
			$this.parent('li').find('.name-checking').html('<i class="icon-spinner8 icon-spin"></i>');	
			jQuery.ajax({
				type:"POST",
				url: ajaxurl,
				data: dataString,
				dataType: 'json',
				success:function(response){		
					if ( response.type == 'success' ) {
						$this.parent('li').find('.name-checking').html(response.message);
						jQuery('input[type="submit"]').removeAttr('disabled');
						/*jQuery("#cs_rooms_data li").each(function(){
							var $this	= jQuery(this);
							if( $this.find('i.icon-times') ){
								jQuery('input[type="submit"]').attr('disabled','disabled');
							}
						});*/
						
					} else if ( response.type == 'error' ) {
						$this.parent('li').find('.name-checking').html(response.message);
						jQuery('input[type="submit"]').attr('disabled','disabled');
					} 
				}
			});
		},3000);		
	});
}

/* ---------------------------------------------------------------------------
	* Add reviews
 	* --------------------------------------------------------------------------- */
	function cs_reviews_submission(admin_url){
		'use strict';
		
		var email	= jQuery("#reviewer_email").val();
		var subject	= jQuery("#reviews_title").val();
		var name	= jQuery("#reviewer_name").val();
		var description	= jQuery("#reviews_description").val();
		
		if( email =='' || subject =='' || name =='' || description =='' ){
			alert('Please fill all fields.');
			return false;
		}
		
		if( !validateEmail(email)) { 
			alert('Please enter a valid email address.');
			return false;
		}
		
		jQuery(".review-message-type").html('');
		jQuery("#loading").html('<i class="icon-spinner8 icon-spin"></i>');
		jQuery.ajax({
			type:"POST",
			url: admin_url,
			dataType: "json",
			data:jQuery('#cs-reviews-form').serialize()+'&action=cs_add_reviews', 
			success:function(response){
				jQuery("#loading").html('');
				jQuery(".review-message-type").html(response.message);
				jQuery(".review-message-type").show();
			}
		});
		
		return false;
	}
	
	function validateEmail($email) {
	  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	  return emailReg.test( $email );
	}
/* ---------------------------------------------------------------------------
	* Get Rooms
 	* --------------------------------------------------------------------------- */
	jQuery(document).ready(function() {	
		jQuery("#cs_get_rooms").change(function(){
			'use strict';
			
			var room_id	= jQuery(this).val();
			//jQuery('.cs-update-message').hide();
			jQuery(".review-message-type").html('');
			jQuery('#cs-loader').html('<i class="icon-spinner8 icon-spin"></i>');
			jQuery.ajax({
				type:"POST",
				url: ajaxurl,
				dataType: "json",
				data:'room_id='+room_id+'&action=cs_get_rooms', 
				success:function(response){
					jQuery(".rooms-data-wrapper").html(response.data);
					jQuery('#cs-loader').html('');
				}
			});
			
			return false;
		});
	});
	
function cs_update_rooms(){
	jQuery('.cs-block-action').on('click', 'a', function(e){
		var $this			= jQuery(this);
		var data_key		= jQuery(this).data('key');
		var data_reference	= jQuery(this).data('reference');
		var reason			= $this.parents('.rooms-data').find('input').val();
		var data_status		= $this.data('status');

		$this.parents('.cs-block-action').find('.cs-spinner').html('<i class="icon-spinner8 icon-spin"></i>');
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'data_key='+data_key+'&room_id='+data_reference+'&reason='+reason+'&status='+data_status+'&action=cs_update_rooms', 
			success:function(response){
				$this.next('.cs-spinner').html('');
				if ( response.type == 'success' ) {
					jQuery('.cs-update-message p').html(response.message);
					jQuery('.cs-update-message').show();
					$this.removeClass('in-active');
					$this.addClass(response.status);
					$this.text(response.status);
					$this.parents('.cs-block-action').find('.cs-spinner').html('');
				} else if ( response.type == 'error' ) {
					jQuery('.cs-update-message p').html(response.message);
					jQuery('.cs-update-message').show();
					$this.parents('.cs-block-action').find('.cs-spinner').html('');
				} 
				
			}
		});
		
		return false;
		
	});
	
	jQuery('.cs-block-reason').on('click', 'a.edit-reason', function(e){
		var $this	= jQuery(this);
		console.log($this);
		$this.parent('.cs-block-reason').find('p').hide();
		$this.parent('.cs-block-reason').find('input').show();
		$this.hide();
		$this.next('.edit-reason-update').show();
		
	});
	
	jQuery('.cs-block-reason').on('click', 'a.edit-reason-update', function(e){
		var $this	= jQuery(this);
		$this.parent('.cs-block-reason').find('input').hide();
		var reason	= $this.parent('.cs-block-reason').find('input').val();
		$this.parent('.cs-block-reason').find('p').html(reason).show();
		$this.hide();
		$this.prev('.edit-reason').show();
		
		var data_key		= $this.data('key');
		var data_reference	= $this.data('reference');
		var data_status		= $this.data('status');
		
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'data_key='+data_key+'&room_id='+data_reference+'&reason='+reason+'&status='+data_status+'&action=cs_update_rooms', 
			success:function(response){
				
			}
		});
		
		return false;
		
	});
}

/* ---------------------------------------------------------------------------
	* Get Rooms For Booking
 	* --------------------------------------------------------------------------- */
	jQuery(document).ready(function() {	
		jQuery("#tab-booking-settings").on('click','#cs_search_room', function(){
			'use strict';
			var $this	=  jQuery(this);
			var date_from	= jQuery("#cs_check_in_date").val();
			var date_to		= jQuery("#cs_check_out_date").val();
			var total_days	= jQuery("#cs_booking_num_days").val();
			var cs_hotel_id	= jQuery("#cs_hotel_id").val();
			var no_of_rooms	= jQuery("#cs-booking-rooms").val();
			
			// Empty Old Data
			jQuery("#cs_bkng_gross_total").val('');
			jQuery('#cs_bkng_tax').val('');
			jQuery('#cs_bkng_grand_total').val('');
			jQuery('#cs_bkng_remaining').val('');
			jQuery('#cs_bkng_advance').val('');
			jQuery('#wrapper_room_extras').html('');
						
			var total_adults = [];
			var total_childs = [];
			
			jQuery(".reservation-inner select#cs_max_adults").each(function(){
			 	var data = jQuery(this); // This is the jquery object of the input, do what you will
				var val	= data.val();
    			total_adults.push(val);
			});
			
			jQuery(".reservation-inner select#cs_max_childs").each(function(){
			 	var data = jQuery(this); // This is the jquery object of the input, do what you will
				var val	= data.val();
    			total_childs.push(val);
			});
			
			//console.log(values);

			if( date_from == '' ) {
				alert('Check in date is required');
				return false;
			} else if( date_to == '' ) {
				alert('Check in date is required');
				return false;
			} else if( total_days == '' ) {
				alert('Total no of days required.');
				return false;
			}
			
			jQuery(".review-message-type").html('');
			jQuery(".wrapper_room_detail").html('');
			
			$this.parent('.input-sec').append('<i class="icon-spinner8 icon-spin"></i>');
			jQuery.ajax({
				type:"POST",
				url: ajaxurl,
				dataType: "json",
				data:'no_of_rooms='+no_of_rooms+'&cs_hotel_id='+cs_hotel_id+'&date_from='+date_from+'&date_to='+date_to+'&total_days='+total_days+'&total_adults='+total_adults+'&total_childs='+total_childs+'&action=cs_get_available_rooms', 
				success:function(response){
					if( response.type == 'success' ) {
						jQuery("#wrapper_room_availability").html(response.output);
						jQuery("#wrapper_room_detail").html(response.output_rooms);
						jQuery("#wrapper_room_extras").html('');
						jQuery("#wrapper_room_availability").show();
						$this.parent('.input-sec').find('i').remove();
					} else{
						jQuery("#wrapper_room_availability").html(response.message);
					}
					
				}
			});
			
			return false;
		});
	});

/* ---------------------------------------------------------------------------
* Check Room Availabilty
* --------------------------------------------------------------------------- */
function cs_select_room(){	
	jQuery(".bk-room-availabilty").on('change','#cs-select-room', function(){
		
		var $this	= jQuery(this);
		
		var capacity			= $this.val();
		var room_type			= $this.data('room_type');
		var cs_booking_id		= jQuery("#cs_booking_id").val();
		var reference			= $this.data('reference');
		var capacity			= $this.val();
		var total_days			= jQuery("#cs_booking_num_days").val();
		
		var current_room		= jQuery('.wrapper_room_detail').find('.cs-current-room').data('key');
		
		if( capacity > 0 && capacity !='' ) {
			$this.parents('.bk-room-availabilty').find('figure').append('<i class="icon-spinner8 icon-spin"></i>');
			jQuery.ajax({
				type:"POST",
				url: ajaxurl,
				dataType: "json",
				data:'current_room='+current_room+'&cs_booking_id='+cs_booking_id+'&total_days='+total_days+'&room_type='+room_type+'&capacity='+capacity+'&action=cs_get_room_detail', 
				success:function(response){
					if( response.type == 'success' ) {
						
						jQuery("#wrapper_room_detail span.message").remove();
						var room_no	 = jQuery('.wrapper_room_detail').find('div.cs-current-room').next('div').data('id');
						jQuery('.wrapper_room_detail').find('.cs-current-room').html(response.output);
						jQuery("#wrapper_room_availability").html(response.selection_done);
						jQuery('.wrapper_room_detail').find('.cs-current-room').removeClass('cs-current-room').next('div').addClass('cs-current-room');
						jQuery("#wrapper_room_availability").show();
						
						if( response.status == 'completed' ) {
							jQuery("#wrapper_room_availability").html('');
							jQuery("#wrapper_room_availability").hide();
							jQuery("#wrapper_room_extras").html(response.selection_done);
						}
					
						//jQuery("#wrapper_room_extras").html(response.extras);
						jQuery("#cs_bkng_gross_total").val(response.price);
						jQuery('#cs_bkng_tax').val(response.vat);
						jQuery('#cs_bkng_grand_total').val(response.grand_total);
						jQuery('#cs_bkng_remaining').val(response.remaining);
						jQuery('#cs_bkng_advance').val(response.advance);
						
					} else{
						jQuery("#wrapper_room_detail span.message").remove();
						jQuery("#wrapper_room_detail").append('<span class="message">'+response.message+'</span>');
						$this.parents('.bk-room-availabilty').find('i').remove();
					}
				}
			});
		}
		
		return false;
			
	});
}

/* ---------------------------------------------------------------------------
* Check Room Extras
* --------------------------------------------------------------------------- */
function cs_room_extras(){	

	// Extras Show Price ON Guests
	jQuery(".wrapper_room_extras").on('change','#cs-total-guests', function(){
		
		var $this				= jQuery(this);
		var extra_id			= $this.data('extra_id');
		var guests				= $this.val();
		var nights_input		= $this.parents('.total-area').find('.cs-total-nights').length;
		var cs_extras_price		= $this.parents('li.extras-list').data('price');
		var cs_currency			= jQuery('#cs_currency_type').val();
		
		if( nights_input ){
           var nights	= $this.parents('.total-area').find('.cs-total-nights').val();
        } else{
            var nights	= '';
        }
		
		var total_price	= cs_extras_price * guests;
		if( nights > 0 ) {
			total_price	= total_price * nights;
		}
		
		$this.parents('.total-area').find('span.price').html(cs_currency + total_price.toFixed(2));
		$this.parents('.total-area').find('.cs_extras_price').val(total_price.toFixed(2));
		
		// Gross Calculation
		cs_gross_calculations();
		return false;
			
	});
	
	// Extras Show Price ON Nights
	jQuery(".wrapper_room_extras").on('change','#cs-total-nights', function(){
		
		var $this				= jQuery(this);
		var extra_id			= $this.data('extra_id');
		var nights				= $this.val();
		var guests_input		= $this.parents('.total-area').find('.cs-total-guests').length;
		var cs_extras_price		= $this.parents('li.extras-list').data('price');
		var cs_currency			= jQuery('#cs_currency_type').val();
		
		if( guests_input ){
           var guests	= $this.parents('.total-area').find('.cs-total-guests').val();
        } else{
            var guests	= '';
        }
		
		var total_price	= cs_extras_price * guests;
		if( nights > 0 ) {
			total_price	= total_price * nights;
		}
		
		$this.parents('.total-area').find('span.price').html(cs_currency + total_price.toFixed(2));
		$this.parents('.total-area').find('.cs_extras_price').val(total_price.toFixed(2));
		
		// Gross Calculation
		cs_gross_calculations();
		return false;
			
	});

/* ---------------------------------------------------------------------------
* Gross Calculations On Extras
* --------------------------------------------------------------------------- */
	jQuery('.wrapper_room_extras').on('change', 'input[type="checkbox"]', function(e) {
		var total_price	= 0;
		var gross_price	= jQuery('.final_price').data('gross');
		
		jQuery(this).parents('.extras-list').find('select').prop('disabled', 'disabled');
		
		jQuery(".cs-extras-check:checked").each(function() {
			var $this	= jQuery(this);
			$this.parents('.extras-list').find('select').prop('disabled', false);
			var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
			if( price && price !='undefined' ) {
				total_price = parseFloat(price) + parseFloat(total_price);	
			}
		});
		
		total_price = parseFloat(gross_price) + parseFloat(total_price);

		//VAT Calculation
		var vat	= jQuery('.reservation-inner').data('vat');
		var vat_switch	= jQuery('.reservation-inner').data('vat_switch');
		if( vat_switch == 'on' ) {
			var vat_price	= ( total_price / 100 ) * vat;
		} else{
			var vat_price	= 0;
		}
		
		var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );
		var advance		= jQuery('#cs_bkng_advance').val();
		
		jQuery('#cs_bkng_gross_total').val(total_price.toFixed(2));
		jQuery('#cs_bkng_tax').val(vat_price.toFixed(2));
		jQuery('#cs_bkng_grand_total').val(grand_total.toFixed(2));
		jQuery('#cs_bkng_remaining').val((grand_total - advance).toFixed(2));
	
	});
}

/* ---------------------------------------------------------------------------
* Gross Calculations
* --------------------------------------------------------------------------- */
function cs_gross_calculations(){
	var total_price	= 0;
	var gross_price	= jQuery('.final_price').data('gross');
	
	
	jQuery(".cs-extras-check:checked").each(function() {
		var $this	= jQuery(this);
		var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
		if( price && price !='undefined' ) {
			total_price = parseFloat(price) + parseFloat(total_price);	
		}
	});
	
	total_price = parseFloat(gross_price) + parseFloat(total_price);
	
	//VAT Calculation
	var vat	= jQuery('.reservation-inner').data('vat');
	var vat_switch	= jQuery('.reservation-inner').data('vat_switch');
	if( vat_switch == 'on' ) {
		var vat_price	= ( total_price / 100 ) * vat;
	} else{
		var vat_price	= 0;
	}
	
	var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );
	var advance		= jQuery('#cs_bkng_advance').val();

		
	jQuery('#cs_bkng_tax').val(vat_price.toFixed(2));	
	jQuery('#cs_bkng_grand_total').val(grand_total.toFixed(2));
	jQuery('#cs_bkng_remaining').val((grand_total - advance).toFixed(2));
	jQuery('#cs_bkng_gross_total').val(total_price.toFixed(2));
}

/* ---------------------------------------------------------------------------
* Add Transaction
* --------------------------------------------------------------------------- */
jQuery(document).ready(function() {	
	jQuery('#cs_add_transaction').on('click', function(e) {
		var $this	= jQuery(this);
		var cs_trans_id				= jQuery("#cs_trans_id").val();
		var cs_booking_id			= jQuery("#cs_booking_id").val();
		var cs_trans_email			= jQuery("#cs_trans_email").val();
		var cs_trans_first_name		= jQuery('#cs_trans_first_name').val();
		var cs_trans_last_name		= jQuery("#cs_trans_last_name").val();
		var cs_trans_address		= jQuery("#cs_trans_address").val();
		var cs_trans_amount			= jQuery("#cs_trans_amount").val();
		var cs_trans_gateway		= jQuery("#cs_trans_gateway").val();
		var cs_trans_status			= jQuery("#cs_trans_status").val();
		
		if( cs_trans_id == '' || cs_booking_id == '' || cs_trans_email == '' || cs_trans_first_name == '' || cs_trans_last_name == '' ||  cs_trans_address == ''  ||  cs_trans_last_name == ''  ||  cs_trans_amount == '' ||  cs_trans_gateway == '' ||  cs_trans_status == ''  ) {
			alert('All the fields is required');
			return false;
		}
		
		jQuery('.message-wrap').hide();
		$this.parent('.input-sec').append('<i class="icon-spinner8 icon-spin"></i>');
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'cs_trans_id='+cs_trans_id+
				 '&cs_booking_id='+cs_booking_id+
				 '&cs_trans_email='+cs_trans_email+
				 '&cs_trans_first_name='+cs_trans_first_name+
				 '&cs_trans_last_name='+cs_trans_last_name+
				 '&cs_trans_address='+cs_trans_address+
				 '&cs_trans_amount='+cs_trans_amount+
				 '&cs_trans_gateway='+cs_trans_gateway+
				 '&cs_trans_status='+cs_trans_status+
				 '&action=cs_add_transaction', 
			success:function(response){
				if( response.type == 'success' ) {
					jQuery('#cs_transactions_data tbody').append(response.data);
					$this.parent('.input-sec').find('i').remove();
					jQuery("#cs_trans_id").val('');
					jQuery("#cs_booking_id").val('');
					jQuery("#cs_trans_email").val('');
					jQuery('#cs_trans_first_name').val('');
					jQuery("#cs_trans_last_name").val('');
					jQuery("#cs_trans_address").val('');
					jQuery("#cs_trans_amount").val('');
					jQuery("#cs_trans_gateway").val('');
					jQuery("#cs_trans_status").val('');
					jQuery(".message-wrap").hide();
					jQuery('.cs-message').html(response.message);
					jQuery('.cs-message').removeClass('error').addClass('updated');
					jQuery('.message-wrap').show();
					jQuery('#cs_transactions_data').find('.dataTables_empty').parent('tr').remove();
					cs_remove_overlay('cs_transactions_pop','append');
		
				} else{
					jQuery('.cs-message').removeClass('updated').addClass('error');
					jQuery('.cs-message').html(response.message);
					jQuery('.message-wrap').show();
					$this.parent('.input-sec').find('i').remove();
				}
			}
		});
		
		return false;
			
	});
});
/* ---------------------------------------------------------------------------
* Add Customer
* --------------------------------------------------------------------------- */
jQuery(document).ready(function() {	
	jQuery('#cs_add_customer').on('click', function(e) {
		var $this	= jQuery(this);
		var cs_customer_first_name	= jQuery("#cs_add_customer_first_name").val();
		var cs_customer_last_name	= jQuery("#cs_add_customer_last_name").val();
		var cs_customer_email		= jQuery('#cs_add_customer_email').val();
		var cs_customer_address		= jQuery("#cs_add_customer_address").val();
		var cs_customer_name		= jQuery("#cs_add_cstmr_name option:selected").text();
		var cs_customer_phone		= jQuery("#cs_add_customer_phone").val();
		var cs_customer_city		= jQuery("#cs_add_customer_city").val();
		var cs_customer_country		= jQuery("#cs_add_customer_country").val();
		
		if( cs_customer_first_name == '' || cs_customer_last_name == '' || cs_customer_email == '' || cs_customer_address == ''  ||  cs_customer_phone == ''  ||  cs_customer_city == '' ||  cs_customer_country == ''  ) {
			alert('All the fields is required');
			return false;
		}else  if( !validateEmail(cs_customer_email)) { 
			alert('Please enter a valid email address');
			return false;
		}
		
		jQuery('.message-wrap').hide();
		$this.parent('.input-sec').append('<i class="icon-spinner8 icon-spin"></i>');
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'&cs_customer_first_name='+cs_customer_first_name+
				 '&cs_customer_last_name='+cs_customer_last_name+
				 '&cs_customer_email='+cs_customer_email+
				 '&cs_customer_address='+cs_customer_address+
				 '&cs_customer_name='+cs_customer_name+
				 '&cs_customer_phone='+cs_customer_phone+
				 '&cs_customer_city='+cs_customer_city+
				 '&cs_customer_country='+cs_customer_country+
				 '&action=cs_add_customer', 
			success:function(response){
				if( response.type == 'success' ) {
					jQuery('#cs_custmr_data tbody').append(response.data);
					$this.parent('.input-sec').find('i').remove();
					jQuery("#cs_customer_first_name").val('');
					jQuery("#cs_customer_last_name").val('');
					jQuery('#cs_customer_email').val('');
					jQuery("#cs_customer_address").val('');
					jQuery("#cs_customer_name").val('');
					jQuery("#cs_customer_phone").val('');
					jQuery("#cs_customer_city").val('');
					jQuery("#cs_customer_country").val('');
					$this.parents('.cs-popup-content').find('.message-wrap').hide();
					jQuery('.cs-message').removeClass('error').addClass('updated');
					jQuery('.message-wrap').show();
					jQuery('#cs_transactions_data').find('.dataTables_empty').parent('tr').remove();
					cs_remove_overlay('cs_customer_pop','append');
					cs_remove_customer();
		
				} else{
					jQuery('.cs-message').removeClass('updated').addClass('error');
					jQuery('.cs-message').html(response.message);
					jQuery('.message-wrap').show();
					$this.parent('.input-sec').find('i').remove();
				}
			}
		});
		
		return false;
			
	});
});
/* ---------------------------------------------------------------------------
* Filters
* --------------------------------------------------------------------------- */
jQuery(document).ready(function() {	
	jQuery("#cs_transactions_data").on('change','.cs_filter_gateways', function(){
		var $this	= jQuery(this);
		var status	= jQuery(this).val();
		var transaction_id	= $this.data('key');
		$this.parent('td').append('<i class="icon-spinner8 icon-spin"></i>');
		$this.parent('td select').hide();
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'status='+status+'&transaction_id='+transaction_id+'&action=cs_update_transaction_status', 
			success:function(response){
				$this.parent('td').find('i').remove();
				$this.parent('td select').show();
			}
		});
		
		return false;
	});
	
	// Delete Capacity Room
	jQuery('#cs_rooms_data').on('click', 'a.delete-capcaity-room', function(e){
		var $this	= jQuery(this);
		var length	= jQuery('#cs_rooms_data li').length - 1;

		if( length == '' ) {
			length	= 0;
		}
		
		$this.parent('li').remove();
		jQuery('#cs_room_num').prop('selectedIndex',length);
	});
});

/* ---------------------------------------------------------------------------
* Add Hotel
* --------------------------------------------------------------------------- */
jQuery(document).ready(function() {	
	jQuery(".cs-popup-content").on('click','.hotel-action-btn', function(){
	
		var $this	= jQuery(this);
		var key		= $this.data('key');
		var id		= $this.data('id');

		var $container				= jQuery(this).parents('.cs-popup-content');
		var cs_hotel_name			= $container.find("#cs_hotel_name").val();
		var cs_hotel_street_adres	= $container.find("#cs_hotel_street_adres").val();
		var cs_hotel_city			= $container.find('#cs_hotel_city').val();
		var cs_hotel_state			= $container.find("#cs_hotel_state").val();
		var cs_hotel_country		= $container.find("#cs_hotel_country").val();
		var cs_hotel_postal			= $container.find("#cs_hotel_postal").val();
		var cs_hotel_phone			= $container.find("#cs_hotel_phone").val();
		var cs_hotel_fax			= $container.find("#cs_hotel_fax").val();
		var cs_hotel_email			= $container.find("#cs_hotel_email").val();
		var cs_hotel_stars				= $container.find("#cs_hotel_stars").val();
		var cs_hotel_booking_email		= $container.find("#cs_hotel_booking_email").val();
		var cs_hotel_maps				= $container.find("#cs_hotel_maps").val();
		
		if( cs_hotel_name == '' ||  cs_hotel_booking_email == '') {
			alert('All the fields is required');
			return false;
		} else  if( !validateEmail(cs_hotel_booking_email)) { 
			alert('Please enter a valid email address');
			return false;
		}
		
		jQuery('.message-wrap').hide();
		$this.parents('#add_hotel_to_btn').append('<i class="icon-spinner8 icon-spin"></i>');
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'&cs_hotel_name='+cs_hotel_name+
				 '&cs_hotel_street_adres='+cs_hotel_street_adres+
				 '&cs_hotel_city='+cs_hotel_city+
				 '&cs_hotel_state='+cs_hotel_state+
				 '&cs_hotel_country='+cs_hotel_country+
				 '&cs_hotel_postal='+cs_hotel_postal+
				 '&cs_hotel_phone='+cs_hotel_phone+
				 '&cs_hotel_fax='+cs_hotel_fax+
				 '&cs_hotel_email='+cs_hotel_email+
				 '&cs_hotel_stars='+cs_hotel_stars+
				 '&cs_hotel_booking_email='+cs_hotel_booking_email+
				 '&cs_hotel_maps='+cs_hotel_maps+
				 '&id='+id+
				 '&key='+key+
				 '&action=cs_add_hotels', 
			success:function(response){
				if( response.type == 'success' ) {
					jQuery('#cs_hotels_data tbody').append(response.data);
					$this.parent('#add_hotel_to_btn').find('i').remove();

					if( key == 'add' ) {
						$container.find("#cs_hotel_name").val('');
						$container.find("#cs_hotel_street_adres").val('');
						$container.find('#cs_hotel_state').val('');
						$container.find("#cs_hotel_city").val('');
						$container.find("#cs_hotel_country").val('');
						$container.find("#cs_hotel_postal").val('');
						$container.find("#cs_hotel_phone").val('');
						$container.find("#cs_hotel_fax").val('');
						$container.find("#cs_hotel_email").val('');
						$container.find("#cs_hotel_stars").val('');
						$container.find("#cs_hotel_maps").val('');
						$container.find(".message-wrap").hide();
						jQuery('#cs_hotels_data').find('.dataTables_empty').parent('tr').remove();
						cs_remove_overlay('cs_hotel_pop','append');
						jQuery('.cs-message').remove();
					}
					
					jQuery('.cs-message').html(response.message);
					jQuery('.cs-message').removeClass('error').addClass('updated');
					jQuery('.message-wrap').show();
					
					if( key == 'update' ) {
						window.location.reload(true);
					}
		
				} else{
					jQuery('.cs-message').removeClass('updated').addClass('error');
					jQuery('.cs-message').html(response.message);
					jQuery('.message-wrap').show();
					$this.parents('.input-sec').find('i').remove();
				}
			}
		});
		
		return false;
			
	});
});
function cs_delete_hotel(){
	jQuery('.hotel-action').on('click','.hotel-delete', function(e) {
		var $this	= jQuery(this);
		var key	= $this.parent('.hotel-action').data('key');
		
		jQuery('.message-wrap').hide();
		$this.parent('.hotel-action').append('<i class="icon-spinner8 icon-spin"></i>');
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'&key='+key+
				 '&action=cs_remove_hotels', 
			success:function(response){
				if( response.type == 'success' ) {
					jQuery('.cs-update-message p').html(response.message);
					jQuery('.cs-update-message').show();
					$this.parents('.hotel-detail').remove();
					$this.parent('.hotel-action').find('i').remove();
				} else{
					$this.parent('.hotel-action').find('i').remove();
					jQuery('.cs-update-message p').html(response.message);
					jQuery('.cs-update-message').show();
				}
			}
		});
		
		return false;
			
	});
}
/*-------------------------------------------------------------------------------
 * 
 * Booking Functions Front End
 *
 -------------------------------------------------------------------------------*/
 

/* ---------------------------------------------------------------------------
* Search Widget
* --------------------------------------------------------------------------- */
jQuery(document).ready(function($) {						
	jQuery(".rooms-options-data").on('click','#seach_room_btn', function(){
		var _this	= jQuery(this);
		var _container		= _this.parents('#search-wraper').find('.booking-members');
		var checkin_date	= _this.parents('#search-wraper').find('#check-in-date').val();
		var checkout_date	= _this.parents('#search-wraper').find('#check-out-date').val();
		var flag	= 'true';
		
		jQuery(_container).each(function() {
			if (jQuery.trim(jQuery(this).val()) == '') {
				flag	= 'false';
				return false; // Terminate the .each loop
			}
		});

		if( checkin_date == '' || checkin_date == 'DD/MM/YYYY' || checkout_date == '' || checkout_date == 'DD/MM/YYYY' || flag == 'false'){
			alert('Please fill all the fields');
			return false;
		}
		
		_this.parents('.reservation-inner').find("#room-seach").submit();            
		return true; // return false to cancel form action
		
	});
});

/* ---------------------------------------------------------------------------
* Edit Search
* --------------------------------------------------------------------------- */
jQuery(document).ready(function($) {						
	jQuery(".reservation-search").on('click','#cs-edit-search', function(){
		var $this	= jQuery(this);
		$this.parents('.reservation-search').hide();
		jQuery( '.reservation-form' ).show();
	});
	
	jQuery(".page-sidebar").on('click','#cs-edit-search', function(){
		var $this	= jQuery(this);
		$this.parents('.reservation-search').hide();
		jQuery( '.reservation-form' ).show();
	});
});

/* ---------------------------------------------------------------------------
* Post Data for reservation
* --------------------------------------------------------------------------- */
function cs_check_room_availabilty(){
	jQuery(".short-info").on('change','.cs_room_capacity', function(){
		var postFormStr	= '';
		var $this		= jQuery(this);
		var room		= $this.data('room');
		var reference	= $this.data('reference');
		var capacity	= $this.val();
		var ajaxurl		= jQuery('.cs-accomodation').data('admin_url');
		var current_room		= jQuery('.booking-rooms').find('.cs-current-room').data('key');

		if( capacity > 0 && capacity !='' ) {
			cs_set_loader();
			
			jQuery.ajax({
				type:"POST",
				url: ajaxurl,
				dataType: "json",
				data:'room='+room+'&capacity='+capacity+'&current_room='+current_room+'&action=cs_check_availabilty', 
				success:function(response){
					var room_no	 = jQuery('.rooms-list').find('li.cs-current-room').next('li').data('id');
	
					jQuery('.rooms-list').find('.cs-current-room').html(response.selected_room);	
					jQuery('.rooms-list').find('.cs-current-room').removeClass('cs-current-room').next('li').addClass('cs-current-room');
					jQuery('.cs-accomodation').html(response.selection_done);
					
					jQuery('.select-number').html(room_no);
					jQuery('.cs-current-no').html(room_no);
					jQuery('.room-adults').html(response.total_adults);
					jQuery('.room-childs').html(response.total_childs);	
					jQuery('.cs-process-outer').remove();
					cs_price_calculations();
					
					if( response.status == 'completed' ) {
						jQuery('.select-heading').remove();
						jQuery('.tabs-nav').show();
						jQuery('.change-room').remove();
						cs_process_booking();
					}
					//jQuery('.reservation-search').show();
					//jQuery('.reservation-form').hide();
	
					//Booking Detail
					/*jQuery('.cs-reservation-tabs').find('li:first-child').addClass('active');
					jQuery('.tabs-nav').show();
					jQuery('.cs-process-wrap').show();
					cs_make_payment();
					cs_process_booking();*/
				}
			});

		}
		return false;
		
	});
	
}

/* ---------------------------------------------------------------------------
* Price Calculation
* --------------------------------------------------------------------------- */
function cs_price_calculations(){
	var total_price = 0;
	
	var cs_currency	= jQuery('.cs-gross-calculation').data('currency');
	jQuery(".booking-rooms li div").each(function() {
		var data_price	= jQuery(this).data('price');
		if( data_price && data_price !='undefined' ){
			total_price	+= parseFloat( data_price );
		}
	});

	total_price	= parseFloat( total_price );
	
	//VAT Calculation
	var vat	= jQuery('.cs-gross-calculation').data('vat');
	var vat_switch	= jQuery('.cs-gross-calculation').data('vat_switch');
	if( vat_switch == 'on' ) {
		var vat_price	= ( total_price / 100 ) * vat;
	} else{
		var vat_price	= 0;
	}

	var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );
	
	jQuery('.price-box h1').html(cs_currency+grand_total.toFixed(2));
	jQuery('.vat-wrap span').html(cs_currency+vat_price.toFixed(2));
	jQuery('.total-price-wrap span').html(cs_currency+total_price.toFixed(2));
	jQuery('.extras').show();
	jQuery('.price-wrapp').show();
}

jQuery(document).ready(function($) {
	jQuery(".rooms-list").on('click','#change-room', function(){
		var postFormStr	= '';
		var $this			= jQuery(this);
		var change_room		= $this.data('change_room');
		var key				= $this.parents('li').data('key');
		var id				= $this.parents('li').data('id');
		var ajaxurl			= jQuery('.cs-accomodation').data('admin_url');
		cs_set_loader();
		
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data:'key='+key+'&id='+id+'&change_room='+change_room+'&action=cs_trash_data', 
			success:function(response){
				jQuery('.cs-accomodation').html(response.output);
				$this.parents('li').addClass('cs-current-room').next('li').removeClass('cs-current-room');
				$this.parents('li').html(response.selected_room)
				jQuery('.select-number').html(id);
				jQuery('.cs-current-no').html(id);
				jQuery('.room-adults').html(response.total_adults);
				jQuery('.room-childs').html(response.total_childs);	
				
				cs_price_calculations();
				cs_check_room_availabilty();
				jQuery('.cs-process-outer').remove();	
			}
		});
		
		return false;
		
	});
});


/* ---------------------------------------------------------------------------
* Gross Calculations On Extras
* --------------------------------------------------------------------------- */
function cs_gross_calculation(){	
	
	jQuery('.cs-check-list').on('change', 'input[type="checkbox"]', function(e) {
			
			var delete_id	= jQuery(this).parents('.extras-list').find('.cs-extras-check').val();
			jQuery('extra-'+delete_id).remove();
			
			if( jQuery('.booking-extras li').length < 2 ) {
				jQuery('.booking-extras').hide();
			}
			
			var total_price = 0;
			var cs_currency			= jQuery('.cs-gross-calculation').data('currency');
			var gross_price			= jQuery('.cs-gross-calculation').data('price');
			var full_pay			= jQuery('.cs-gross-calculation').data('full_pay');
			var advance				= jQuery('.cs-gross-calculation').data('advance');
			
			jQuery(".booking-rooms li div").each(function() {
				var data_price	= jQuery(this).data('price');
				if( data_price && data_price !='undefined' ){
					total_price	+= parseFloat( data_price );
				}
			});
			
			jQuery(this).parents('.extras-list').find('select').prop('disabled', 'disabled');
			
			jQuery(".booking-extras").html('');
			jQuery(".cs-extras-check:checked").each(function() {
				var $this	= jQuery(this);
				$this.parents('.extras-list').find('select').prop('disabled', false);
				var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
				
				if( price && price !='undefined' ) {
					total_price = parseFloat(price) + parseFloat(total_price);	
				}
				
				// Create Html
				var id	= $this.parents('.extras-list').find('.cs-extras-check').val();
				var label	= $this.parents('.extras-list').find('label div').html();
				
				
				var t_guest		= $this.parents('.extras-list').find('#cs-total-guests').val();
				var t_nights	= $this.parents('.extras-list').find('#cs-total-nights').val();
				
				var extras_data	= '';
				if( t_guest && t_guest !='' ) {
					extras_data	+= parseInt( t_guest) +' Guests x ';
				}
				
				if( t_nights && t_nights !='' ) {
					extras_data	+= parseInt( t_nights ) +' Nights';
				}

				var extra_html	= '';
				var extra_price	= '';
				if( price && price !='undefined' ) {
					extra_price	= cs_currency+price;
				} else{
					extra_price	= 'Free';
				}
				
				extra_html	+= '<li class="extra-'+id+'">'
								+ '<p> '+label+' <small>'+extras_data+'</small></p>'
								+ '<span>'+extra_price+'</span>'
								+ '</li>';
				
				jQuery(".booking-extras").append(extra_html);
				jQuery('.booking-extras').show();
			});
			
			total_price	= parseFloat( total_price );
			
			//VAT Calculation
			var vat	= jQuery('.cs-gross-calculation').data('vat');
			var vat_switch	= jQuery('.cs-gross-calculation').data('vat_switch');
			if( vat_switch == 'on' ) {
				var vat_price	= ( total_price / 100 ) * vat;
			} else{
				var vat_price	= 0;
			}
			
			var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );
			
			jQuery('.price-box h1').html(cs_currency+grand_total.toFixed(2));
			jQuery('.vat-wrap span').html(cs_currency+vat_price.toFixed(2));
			jQuery('.total-price-wrap span').html(cs_currency+total_price.toFixed(2));
			jQuery('.extras').show();
			jQuery('.price-wrapp').show();

		});
		
		// Gateway Calculation
		jQuery('#cs-gateway-wrap').on('click', 'input[type="radio"]', function(e) {
			
			var $this	= jQuery(this);
			var total_price			= 0.00;
			var due_total			= 0.00;
			var pay_now				= 0.00;
			var advance_total		= 0.00;
			
			var cs_currency			= jQuery('.cs-gross-calculation').data('currency');
			var gross_price			= jQuery('.cs-gross-calculation').data('price');
			var full_pay			= jQuery('.cs-gross-calculation').data('full_pay');
			var advance				= jQuery('.cs-gross-calculation').data('advance');
			
			jQuery(".booking-rooms li div").each(function() {
				var data_price	= jQuery(this).data('price');
				if( data_price && data_price !='undefined' ){
					total_price	+= parseFloat( data_price );
				}
			});
			
			
			jQuery(".cs-extras-check:checked").each(function() {
				var $this	= jQuery(this);
				$this.parents('.extras-list').find('select').prop('disabled', false);
				var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
				if( price && price !='undefined' ) {
					total_price = parseFloat(price) + parseFloat(total_price);	
				}
						
			});
			
			total_price	= parseFloat( total_price );
	
			//VAT Calculation
			var vat	= jQuery('.cs-gross-calculation').data('vat');
			var vat_switch	= jQuery('.cs-gross-calculation').data('vat_switch');
			if( vat_switch == 'on' ) {
				var vat_price	= ( total_price / 100 ) * vat;
			} else{
				var vat_price	= 0;
			}
			
			var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );
			
			if( advance !='' ) {
				var advance_total	= ( grand_total / 100 ) * advance;
				due_total				= grand_total - advance_total;
				var pay_now				= advance_total;
			} else{
				grand_total		= grand_total;
			}  
			
			jQuery('.cs-deposit-amount span').html(cs_currency+advance_total.toFixed(2));
			jQuery('.cs-total-amount span').html(cs_currency+grand_total.toFixed(2));
			jQuery('.total-price-wrap span').html(cs_currency+total_price.toFixed(2));
			jQuery('.price-box h1').html(cs_currency+grand_total.toFixed(2));

		});
		
		// Set Payment Type
		jQuery('.radio-box').on('change', 'input[type="radio"]', function(e) {
			var $this	= jQuery(this);
			var cs_type	= $this.val();
			if( cs_type == 'full' ) {
				jQuery('.cs-deposit-amount').hide();
				jQuery('.cs-arrival-data').hide();
			} else{
				jQuery('.cs-deposit-amount').show();
				jQuery('.cs-arrival-data').show();
			}
		});
	
		// Extras Show Price ON Guest
		jQuery(".cs-check-list").on('change','#cs-total-guests', function(){
			
			var $this				= jQuery(this);
			var extra_id			= $this.data('extra_id');
			var guests				= $this.val();
			var nights_input		= $this.parents('.extras-list').find('.cs-total-nights').length;
			var cs_extras_price		= $this.parents('li.extras-list').data('price');
			var cs_currency			= jQuery('.cs-gross-calculation').data('currency');
			
			if( nights_input ){
			   var nights	= $this.parents('.extras-list').find('.cs-total-nights').val();
			} else{
				var nights	= '';
			}
			
			var total_price	= cs_extras_price * guests;
			if( nights > 0 ) {
				total_price	= total_price * nights;
			}
			
			$this.parents('.extras-list').find('small').html(cs_currency + total_price.toFixed(2));
			$this.parents('.extras-list').find('.cs_extras_price').val(total_price.toFixed(2));
			
			// Payment Calculation
			cs_payment_calculations();
			return false;
				
	});
	
	// Extras Show Price ON Nights
	jQuery(".cs-check-list").on('change','#cs-total-nights', function(){
			
			var $this				= jQuery(this);
			var extra_id			= $this.data('extra_id');
			var nights				= $this.val();
			var guests_input		= $this.parents('.extras-list').find('.cs-total-guests').length;
			var cs_extras_price		= $this.parents('li.extras-list').data('price');
			var cs_currency			= jQuery('.cs-gross-calculation').data('currency');
			
			if( guests_input ){
				var guests	= $this.parents('.extras-list').find('.cs-total-guests').val();
			} else{
				var guests	= '';
			}
			
			var extra_t_price	= cs_extras_price * guests;
			if( nights > 0 ) {
				extra_t_price	= cs_extras_price * nights;
			}
			
			$this.parents('.extras-list').find('small').html(cs_currency + extra_t_price.toFixed(2));
			$this.parents('.extras-list').find('.cs_extras_price').val(extra_t_price.toFixed(2));
			
			// Payment Calculation
			cs_payment_calculations();
			return false;
				
	});
}
	
/* ---------------------------------------------------------------------------
* Gross Calculations
* --------------------------------------------------------------------------- */
function cs_payment_calculations(){
	var total_price = 0;
	var cs_currency			= jQuery('.cs-gross-calculation').data('currency');
	var gross_price			= jQuery('.cs-gross-calculation').data('price');
	var full_pay			= jQuery('.cs-gross-calculation').data('full_pay');
	var advance				= jQuery('.cs-gross-calculation').data('advance');
			
	jQuery(".booking-rooms li div").each(function() {
		var data_price	= jQuery(this).data('price');
		if( data_price && data_price !='undefined' ){
			total_price	+= parseFloat( data_price );
		}
	});
	
	jQuery(this).parents('.extras-list').find('select').prop('disabled', 'disabled');
	
	jQuery(".booking-extras").html('');
	jQuery(".cs-extras-check:checked").each(function() {
		var $this	= jQuery(this);
		$this.parents('.extras-list').find('select').prop('disabled', false);
		var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
		if( price && price !='undefined' ) {
			total_price = parseFloat(price) + parseFloat(total_price);	
		}
		
		// Create Html
		var id		= $this.parents('.extras-list').find('.cs-extras-check').val();
		var label	= $this.parents('.extras-list').find('label div').html();
		
		
		var t_guest		= $this.parents('.extras-list').find('#cs-total-guests').val();
		var t_nights	= $this.parents('.extras-list').find('#cs-total-nights').val();
		
		var extras_data	= '';
		
		if( t_guest && t_guest !='' ) {
			extras_data	+= parseInt( t_guest) +' Guests x ';
		}
		
		if( t_nights && t_nights !='' ) {
			extras_data	+= parseInt( t_nights ) +' Nights';
		}

		var extra_html	= '';
		var extra_price	= '';
		if( price && price !='undefined' ) {
			extra_price	= cs_currency+price;
		} else{
			extra_price	= 'Free';
		}
		
		extra_html	+= '<li class="extra-'+id+'">'
						+ '<p> '+label+' <small>'+extras_data+'</small></p>'
						+ '<span>'+extra_price+'</span>'
						+ '</li>';
		
		jQuery(".booking-extras").append(extra_html);
				
	});
	
	total_price	= parseFloat( total_price );

	//VAT Calculation
	var vat	= jQuery('.cs-gross-calculation').data('vat');
	var vat_switch	= jQuery('.cs-gross-calculation').data('vat_switch');
	if( vat_switch == 'on' ) {
		var vat_price	= ( total_price / 100 ) * vat;
	} else{
		var vat_price	= 0;
	}
	
	jQuery('.total-price-wrap span').html(cs_currency+total_price.toFixed(2));
	jQuery('.price-box h1').html(cs_currency+total_price.toFixed(2));
	jQuery('.vat-wrap span').html(cs_currency+vat_price.toFixed(2));
}

/* ---------------------------------------------------------------------------
* Confirm Booking
* --------------------------------------------------------------------------- */
function cs_make_payment(){

		var postFormStr	= '';
		var ajaxurl			= jQuery('.cs-accomodation').data('admin_url');
		var gateway			= jQuery('input[name=cs_payment_gateway]:checked').val();
		
		// Loader On Body
		cs_set_loader();
		
		var total_price	= 0.00;
		jQuery(".booking-rooms li div").each(function() {
			var data_price	= jQuery(this).data('price');
			if( data_price && data_price !='undefined' ){
				total_price	+= parseFloat( data_price );
			}
		});
		
		
		jQuery(".cs-extras-check:checked").each(function() {
			var $this	= jQuery(this);
			$this.parents('.extras-list').find('select').prop('disabled', false);
			var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
			if( price && price !='undefined' ) {
				total_price = parseFloat(price) + parseFloat(total_price);	
			}
					
		});
		
		total_price	= parseFloat( total_price );
		
		//VAT Calculation
		var vat	= jQuery('.cs-gross-calculation').data('vat');
		var vat_switch	= jQuery('.cs-gross-calculation').data('vat_switch');
		if( vat_switch == 'on' ) {
			var vat_price	= ( total_price / 100 ) * vat;
		} else{
			var vat_price	= 0;
		}
		
		var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );

		var serializedReturn = 'gateway='+gateway+'&gross_price='+total_price+'&vat_price='+vat_price+'&grand_total='+grand_total+'&'+jQuery('#booking_form').serialize()+'&action=cs_add_booking';
		
		jQuery.ajax({
			type:"POST",
			url: ajaxurl,
			dataType: "json",
			data: serializedReturn, 
			success:function(response){
				if( response.type == 'error' ) {
					jQuery('.cs-notification').html(response.message);
					jQuery('.cs-process-outer').remove();
				} else{
					if( response.gateway == 'transfer' ) {
						jQuery('.cs-bank-transfer').remove();
						jQuery('#sidebar-extras-area').remove();
						jQuery('#sidebar-price-area').remove();
						jQuery('.rooms-list').html( response.form );
						jQuery('.search-summery').remove();
						// Show Confirmation
						var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').data('id');
						jQuery('.tabs-nav > .active').next('li').addClass('active').prev('li').removeClass('active');
						jQuery('.tabs-nav > .active').next('li').find('a').trigger('click');
						var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').data('id');
						jQuery('.cs-reservation-tabs .tabs-content').find('.tabs').hide();
						jQuery('.cs-reservation-tabs .tabs-content').find("#"+active).show();
								
						jQuery('.cs-process-outer').remove();
					} else{
						jQuery('body').append(response.form);
					}
				}
			}
		});
		
		return false;

}

/* ---------------------------------------------------------------------------
* Window Loader
* --------------------------------------------------------------------------- */
function cs_set_loader(){
	jQuery('.cs-accomodation').append('<div class="cs-process-outer"><div class="process-loader"><i class="icon-spinner8 icon-spin"></i></div></div>');
}

function cs_process_booking() {
	jQuery(".cs-process-wrap").on('click','a', function(){

		var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').data('id');
		console.log(active);
		if( typeof( active ) === 'undefined' ){
			/*jQuery('.cs-reservation-tabs').find('li:first-child').addClass('active');
			
			cs_set_loader();
			var serializedReturn = 'action=cs_booking_detail';
			var ajaxurl		= jQuery('.cs-accomodation').data('admin_url');
			jQuery.ajax({
				type:"POST",
				url: ajaxurl,
				dataType: "json",
				data: serializedReturn, 
				success:function(response){
					jQuery('.tabs-nav').show();
					jQuery('.cs-process-wrap').show();
					jQuery('.cs-accomodation').html(response.reservation_detail);
					jQuery('.cs-process-outer').remove();
					cs_make_payment();
				}
			});
			
			return false;*/
		} else {
		
			var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').data('id');
			if( active == 'tab1' ) {
				cs_grand_calculation();
				var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').data('id');
				jQuery('.tabs-nav > .active').next('li').addClass('active').prev('li').removeClass('active');
				jQuery('.tabs-nav > .active').next('li').find('a').trigger('click');
				var active = jQuery('.cs-reservation-tabs').find('.tabs-nav .active a').data('id');
				jQuery('.cs-reservation-tabs .tabs-content').find('.tabs').hide();
				jQuery('.cs-reservation-tabs .tabs-content').find("#"+active).show();
			
			}else if( active == 'tab2' ) {
				var cs_f_name			= jQuery('#cs_f_name').val();
				var cs_l_name			= jQuery('#cs_l_name').val();
				var cs_email			= jQuery('#cs_email').val();
				
				if( cs_f_name =='' || cs_l_name == '' || cs_email == '' ) { 
					alert('Please fill all the required fields');
					return false;
				} else if( !validateEmail(cs_email)) { 
					alert('Please enter a valid email address');
					return false;
				}
				cs_make_payment();
			} 
			
			
			
		}
		
	 });
}

/* ---------------------------------------------------------------------------
* Gateway Fee Calculation
* --------------------------------------------------------------------------- */
function cs_grand_calculation(){
	var total_price = 0;
	var advance_total		= 0.00;
	var cs_currency			= jQuery('.cs-gross-calculation').data('currency');
	var gross_price			= jQuery('.cs-gross-calculation').data('price');
	var full_pay			= jQuery('.cs-gross-calculation').data('full_pay');
	var advance				= jQuery('.cs-gross-calculation').data('advance');
	var fee					= jQuery('.cs-gross-calculation').data('gateway_fee');
			
	jQuery(".booking-rooms li div").each(function() {
		var data_price	= jQuery(this).data('price');
		if( data_price && data_price !='undefined' ){
			total_price	+= parseFloat( data_price );
		}
	});
	
	
	jQuery(".cs-extras-check:checked").each(function() {
		var $this	= jQuery(this);
		$this.parents('.extras-list').find('select').prop('disabled', false);
		var price	= $this.parents('.extras-list').find('.cs_extras_price').val();
		if( price && price !='undefined' ) {
			total_price = parseFloat(price) + parseFloat(total_price);	
		}
				
	});
	
	total_price	= parseFloat( total_price );
	
	//VAT Calculation
	var vat	= jQuery('.cs-gross-calculation').data('vat');
	var vat_switch	= jQuery('.cs-gross-calculation').data('vat_switch');
	if( vat_switch == 'on' ) {
		var vat_price	= ( total_price / 100 ) * vat;
	} else{
		var vat_price	= 0;
	}
	
	var grand_total	= parseFloat( vat_price ) + parseFloat( total_price );
	
	if( advance !='' ) {
		var advance_total	= ( grand_total / 100 ) * advance;
		due_total				= grand_total - advance_total;
		var pay_now				= advance_total;
	} else{
		grand_total		= grand_total;
	}  

	
	jQuery('.cs-deposit-amount span').html(cs_currency+advance_total.toFixed(2));
	jQuery('.cs-total-amount span').html(cs_currency+grand_total.toFixed(2));
	jQuery('.total-price-wrap span').html(cs_currency+total_price.toFixed(2));
	jQuery('.price-box h1').html(cs_currency+grand_total.toFixed(2));
}


/* ---------------------------------------------------------------------------
	* Search Rooms Form
 	* --------------------------------------------------------------------------- */
	function cs_widget_form(){
		jQuery(".cs-search-room-elm").on('click','#seach_room_btn', function(){
			'use strict';
			var url			= jQuery('.cs-search-room-elm').data('action');
			
			var date_from	= jQuery('#check-in-date').val();
			var date_to	= jQuery('#check-out-date').val();
			var adults	= jQuery('#cs_max_adults').val();
			var childs	= jQuery('#cs_max_childs').val();
			var no_of_rooms	= jQuery('#no_of_rooms').val();
			
			var form	= '<form action="'+url+'" method="post" id="cs-search-room" style="display="none">';
			form	+= '<input type="text" name="date_from" id="check-in-date" value="'+ date_from +'">';
			form	+= '<input type="text" name="date_to" id="check-out-date" value="'+date_to+'">';
			form	+= '<input type="text" name="adults" id="adults" value="'+adults+'">';
			form	+= '<input type="text" name="childs" id="childs" value="'+childs+'">';
			form	+= '<input type="text" name="no_of_rooms" id="no_of_rooms" value="'+no_of_rooms+'">';
			form	+= '</form>';
			jQuery('body').append(form);
			jQuery( '#cs-search-room' ).submit();
		});
	}
	

/* ---------------------------------------------------------------------------
* Datepicker Calender
* --------------------------------------------------------------------------- */
/*function cs_widget_datepicker(){
	 var startDate = new Date();
	 var FromEndDate = new Date();
	 var ToEndDate = new Date();
	 ToEndDate.setDate(ToEndDate.getDate()+365);
	 
	 jQuery('.cs-check-in input').datepicker({
		weekStart: 1,
		startDate: new Date(),
		format: 'dd/mm/yyyy',
		autoclose: true
	}).on('changeDate', function(selected){
		startDate = new Date(selected.date.valueOf());
		startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
		jQuery('.cs-check-out input').datepicker('setStartDate', startDate);
	});
	
	jQuery('.cs-check-out input')
		.datepicker({
			
			weekStart: 1,
			startDate: new Date(),
			format: 'dd/mm/yyyy',
			autoclose: true
		}).on('changeDate', function(selected){
			FromEndDate = new Date(selected.date.valueOf());
			FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
			jQuery('.cs-check-in input').datepicker('setEndDate', FromEndDate);
	});
}*/

function cs_widget_datepicker(current_date){
	jQuery('.cs-check-in input').datetimepicker({
		format: 'd-m-Y H:i',
		timepicker: true,
		minDate:current_date,
		onSelectDate: function (selectedDate) {
			jQuery(".cs-check-out input").datetimepicker({minDate: selectedDate});
		}
	});
	
	jQuery('.cs-check-out input').datetimepicker({
		format: 'd-m-Y H:i',
		timepicker: true,
		minDate:current_date,
		onSelectDate: function (selectedDate) {
			jQuery(".cs-check-in input").datetimepicker({maxDate: selectedDate});
		}
	});
}

jQuery(document).ready(function($) {
	jQuery('.reservation-inner').on('change', '#cs-booking-rooms', function(e) {
		var $this	= jQuery(this);
		var no_of_room	= $this.val();
	 	var adults	= $this.parents('#search-wraper').data('adults');
		var childs	= $this.parents('#search-wraper').data('childs');

		var parentStatus = jQuery(this).parents('.rooms').siblings('.sub-rooms');
		
		var adult_options	= '';
		for ( ad = 1; ad <=adults; ad++ ){
			adult_options	+= '<option value="'+ad+'">'+ad+' Adult(s)</option>';
		}
		
		var child_options	= '';
		for ( ch = 0; ch <= adults; ch++ ){
			child_options	+= '<option value="'+ch+'">'+ch+' Child(s)</option>';
		}

		if(parentStatus.length != ''){
			$this.parents('.reservation-form').find('.select-options').remove();

			for( i = no_of_room; i > 0; i--){
				var rooms=i;
				jQuery('.sub-rooms li').hide();
				jQuery(this).closest('ul').find('.sub-rooms ul').prepend('<li class="select-options"><h5>Room '+rooms+'</h5><div class="cs-capacity-wrap"><div class="select-area"><span class="select-style"><select id="cs_max_adults" class="booking-members" name="adults[]">'+adult_options+'</select></span></div><div class="select-area"><span class="select-style-foure"><select class="booking-members" id="cs_max_childs" name="childs[]">'+child_options+'</select></span></div></div></li>');
			}
			jQuery('.sub-rooms li').delay(300).slideDown();
		}else{
			$this.parents('.reservation-form').find('.select-options').remove();
			
			for( i = no_of_room; i > 0; i--){
				var rooms=i;
				if(no_of_room == 1){
					jQuery(this).closest('ul').find('.rooms').after('<li class="select-options"><div class="cs-capacity-wrap"><div class="select-area"><span class="select-style"><select id="cs_max_adults" class="booking-members" name="adults[]">'+adult_options+'</select></span></div><div class="select-area"><span class="select-style-foure"><select id="cs_max_childs" class="booking-members" name="childs[]">'+child_options+'</select></span></div></div></li>');
				}else{
					jQuery(this).closest('ul').find('.rooms').after('<li class="select-options"><h5>Room '+rooms+'</h5><div class="cs-capacity-wrap"><div class="select-area"><span class="select-style"><select id="cs_max_adults" class="booking-members" name="adults[]">'+adult_options+'</select></span></div><div class="select-area"><span class="select-style-foure"><select class="booking-members" id="cs_max_childs" name="childs[]">'+child_options+'</select></span></div></div></li>');
				}
			}
			jQuery('.select-options').slice(1).slideUp();
			jQuery('.select-options').delay(300).slideDown();
		}
	
	});
});