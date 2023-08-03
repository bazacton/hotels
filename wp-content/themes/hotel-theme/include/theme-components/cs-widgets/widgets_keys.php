<?php
/*
 * File: Widget Keys
 */

if(!function_exists('cs_activate_widget')){	
	function cs_activate_widget(){		
		$sidebars_widgets = get_option('sidebars_widgets');
		
		// Footer Contact Us
		$footer_contactinfo = array();
		$footer_contactinfo[1] = array(
			'title'		=> 'Contact Us', 
			'address'	=> 'Patricia C. Amedee 4401 Waldeck Street Grapevine Nashville, TX 76051',
			'phone'		=> '00 500 500 500',
			'fax'		=> '123 500 500 500',
			'email'		=> 'london@chimpstudio.co.uk',
			'link'		=> 'chimpstudio.co.uk',
		);						
		$footer_contactinfo['_multiwidget'] = '1';
		update_option('widget_contactinfo',$footer_contactinfo);
		$footer_contactinfo = get_option('widget_contactinfo');
		krsort($footer_contactinfo);
		foreach($footer_contactinfo as $key1=>$val1){
			$footer_contactinfo_key = $key1;
			if(is_int($footer_contactinfo_key)){
				break;
			}
		}	
		
		
		if( class_exists('wp_hotel_booking') ) {
			$search_widget_key	= '';
			$search_widget = array();
			$search_widget[1] = array(
				'title'		=> 'Search Room', 
			);						
			$search_widget['_multiwidget'] = '1';
			update_option('widget_room_search',$search_widget);
			$search_widget = get_option('widget_room_search');
			krsort($search_widget);
			foreach($search_widget as $key1=>$val1){
				$search_widget_key = $key1;
				if(is_int($search_widget_key)){
					break;
				}
			}	
		}
		
		// Footer Cats
		$footer_cats = array();
		$footer_cats[1] = array(
					"title"		=> 'Featured Categories',
					"dropdown" 	=> '',
					"count"		=> '',
					"hierarchical" => ''
				);					
		$footer_cats['_multiwidget'] = '1';
		update_option('widget_categories',$footer_cats);
		$footer_cats = get_option('widget_categories');
		krsort($footer_cats);
		foreach($footer_cats as $key1=>$val1){
			$footer_cats_key = $key1;
			if(is_int($footer_cats_key)){
				break;
			}
		}	
			
		 // Footer Latest Posts
		$footer_recent_post = array();
		$footer_recent_post[1] = array(
			"title"		=> 'Recent Blogs',
			"select_category" 	=> '',
			"showcount" => '3',
			"thumb" => false
			);					
		$footer_recent_post['_multiwidget'] = '1';
		update_option('widget_recentposts',$footer_recent_post);
		$footer_recent_post = get_option('widget_recentposts');
		krsort($footer_recent_post);
		foreach($footer_recent_post as $key1=>$val1){
			$footer_recent_post_key = $key1;
			if(is_int($footer_recent_post_key)){
				break;
			}
		}	
	
		// Footer contact Form 
		$footer_form = array();
		$footer_form[1] = array(
			"title"				=> 'contact us',
			"contact_email" 	=> get_bloginfo('admin_email'),
			"contact_succ_msg"	=> 'Message Sent Successfully.',
			);					
		$footer_form['_multiwidget'] = '1';
		update_option('widget_cs_contact_msg',$footer_form);
		$footer_form = get_option('widget_cs_contact_msg');
		krsort($footer_form);
		foreach($footer_form as $key1=>$val1){
			$footer_form_key = $key1;
			if(is_int($footer_form_key)){
				break;
			}
		}
	
		// Widget Search	
		$search = array();
		$search[1] = array(
			"title" => '',
		);	
		$search['_multiwidget'] = '1';
		update_option('widget_search',$search);
		$search = get_option('widget_search');
		krsort($search);
		foreach($search as $key1=>$val1){
			$search_key = $key1;
			if(is_int($search_key)){
				break;
			}
		}
		
		// Blog Cats
		$blog_cats = array();
		$blog_cats = get_option('widget_categories');
		$blog_cats[2] = array(
					"title"		=> 'Blog Categories',
					"dropdown" 	=> '',
					"count"		=> '',
					"hierarchical" => ''
				);					
		$blog_cats['_multiwidget'] = '1';
		update_option('widget_categories',$blog_cats);
		$blog_cats = get_option('widget_categories');
		krsort($blog_cats);
		foreach($blog_cats as $key1=>$val1){
			$blog_cats_key = $key1;
			if(is_int($blog_cats_key)){
				break;
			}
		}
		
		// Tags
		$tag_cloud = array();
		$tag_cloud[1] = array(	
			"title"		=>	'Tag Cloud',
			"taxonomy" => 'post_tag',
		);	
		$tag_cloud['_multiwidget'] = '1';
		update_option('widget_tag_cloud',$tag_cloud);
		$tag_cloud = get_option('widget_tag_cloud');
		krsort($tag_cloud);
		foreach($tag_cloud as $key1=>$val1){
			$tag_cloud_key = $key1;
			if(is_int($tag_cloud_key)){
				break;
			}
		}
		
		// Blog Services Text
		$blog_text = array();
		$blog_text = get_option('widget_text');
		$blog_text[1] = array(
			'title' => '',
			'text' => '[cs_services cs_service_type="modern" cs_service_border_right="no" cs_service_icon_type="image" cs_service_bg_image="http://hotels.chimpgroup.com/wp-content/uploads/widget-ser-11.png" service_bg_imageDkgdvY79Vv="Browse" service_icon_size="icon-2x" cs_service_postion_modern="left" cs_service_postion_classic="left" cs_service_title="Itlaian Resturant"]Stay as productive on the road as you are in your office with our fantastic services.[/cs_services][cs_services cs_service_type="modern" cs_service_border_right="no" cs_service_icon_type="image" cs_service_bg_image="http://hotels.chimpgroup.com/wp-content/uploads/widget-ser-21.png" service_bg_image5Wf2PZa4Ud="Browse" service_icon_size="icon-2x" cs_service_postion_modern="left" cs_service_postion_classic="left" cs_service_title="Catering services"]Stay as productive on the road as you are in your office with our fantastic services.[/cs_services][cs_services cs_service_type="modern" cs_service_border_right="no" cs_service_icon_type="image" cs_service_bg_image="http://hotels.chimpgroup.com/wp-content/uploads/widget-ser-31.png" service_bg_image4V1FDFLNlF="Browse" service_icon_size="icon-2x" cs_service_postion_modern="left" cs_service_postion_classic="left" cs_service_title="Wireless internet"]Stay as productive on the road as you are in your office with our fantastic services.[/cs_services]',
		);						
		$blog_text['_multiwidget'] = '1';
		update_option('widget_text',$blog_text);
		$blog_text = get_option('widget_text');
		krsort($blog_text);
		foreach($blog_text as $key1=>$val1){
			$blog_text_key = $key1;
			if(is_int($blog_text_key)){
				break;
			}
		}
		
		// Text
		$text = array();
		$text = get_option('widget_text');
		$text[2] = array(
			'title' => '',
			'text' => '<img src="'.get_template_directory_uri().'/assets/images/contact-us.jpg" alt="contact us" />',
		);						
		$text['_multiwidget'] = '1';
		update_option('widget_text',$text);
		$text = get_option('widget_text');
		krsort($text);
		foreach($text as $key1=>$val1){
			$text_key = $key1;
			if(is_int($text_key)){
				break;
			}
		}
		
		
				// Text
		$text = array();
		$text = get_option('widget_text');
		$text[2] = array(
			'title' => '',
			'text' => '[cs_testimonials column_size="1/1" cs_testimonial_section_title="TESTIMONIALS" testimonial_style="modren-slider" cs_testimonial_text_align="left"][testimonial_item testimonial_author="Alex"
testimonial_company="Google"
testimonial_img="http://hotels.chimpgroup.com/luxuryresort/wp-content/uploads/user-1.jpg"] And oh the guinea owing erroneously furtively far the festive more and so this alas during therefore far one honorable less and imitative less let deliberately some darn.[/testimonial_item][testimonial_item testimonial_author="ROSS DANIEL" testimonial_company="Google In" testimonial_img="http://hotels.chimpgroup.com/luxuryresort/wp-content/uploads/user-2.jpg"] And oh the guinea owing erroneously furtively far the festive more and so this alas during therefore far one honorable less and imitative less let deliberately some darn. [/testimonial_item][testimonial_item testimonial_author="DANIEL ROSS" testimonial_company="Networking" testimonial_img="http://hotels.chimpgroup.com/luxuryresort/wp-content/uploads/user-3.jpg"] And oh the guinea owing erroneously furtively far the festive more and so this alas during therefore far one honorable less and imitative less let deliberately some darn. [/testimonial_item][/cs_testimonials]',
		);						
		$text['_multiwidget'] = '1';
		update_option('widget_text',$text);
		$text = get_option('widget_text');
		krsort($text);
		foreach($text as $key1=>$val1){
			$text_keys = $key1;
			if(is_int($text_keys)){
				break;
			}
		}
		
		// Facebook
		$facebook_module = array();
		$facebook_module[1] = array(
			"title"			=> 'Facebook',
			"pageurl"		=> "https://www.facebook.com/envato",
			"showfaces"		=> "on",
			"showstream"	=> "",
			"likebox_height" => "265",
			"fb_bg_color" => "#ffffff",
			);						
		$facebook_module['_multiwidget'] = '1';
		update_option('widget_facebook_module',$facebook_module);
		$facebook_module = get_option('widget_facebook_module');
		krsort($facebook_module);
		foreach($facebook_module as $key1=>$val1) {
			$facebook_module_key = $key1;
			if(is_int($facebook_module_key)) {
				break;
			}
		}		
	 
	 	// Twitter
		$cs_twitter_widget = array();
		$cs_twitter_widget[1] = array(
			"title"		=>	'Twitter',
			"username" 	=>	"envato",
			"numoftweets" => "3",
			);						
		$cs_twitter_widget['_multiwidget'] = '1';
		update_option('widget_cs_twitter_widget',$cs_twitter_widget);
		$cs_twitter_widget = get_option('widget_cs_twitter_widget');
		krsort($cs_twitter_widget);
		foreach($cs_twitter_widget as $key1=>$val1){
			$cs_twitter_widget_key = $key1;
			if(is_int($cs_twitter_widget_key)){
				break;
			}
		}	
			
		// Add widgets in sidebars
		$sidebars_widgets['blogs_sidebar']		= array("search-$search_key", "categories-$blog_cats_key", "recentposts-$footer_recent_post_key", "contactinfo-$footer_contactinfo_key", "text-$blog_text_key", "tag_cloud-$tag_cloud_key");
		$sidebars_widgets['contact']			= array("text-$text_key", "cs_twitter_widget-$cs_twitter_widget_key", "facebook_module-$facebook_module_key");
		$sidebars_widgets['event_listings']		= array("search-$search_key", "contactinfo-$footer_contactinfo_key", "tag_cloud-$tag_cloud_key");
		$sidebars_widgets['event_detail']		= array("search-$search_key", "contactinfo-$footer_contactinfo_key", "text-$blog_text_key", "tag_cloud-$tag_cloud_key");
		$sidebars_widgets['footer-widget-1']	= array("contactinfo-$footer_contactinfo_key", "categories-$footer_cats_key", "recentposts-$footer_recent_post_key", "cs_contact_msg-$footer_form_key");
			$sidebars_widgets['home_luxuryresort']			= array("text-$text_key","contactinfo-$footer_contactinfo_key");
		if( class_exists('wp_hotel_booking') ) {
			$sidebars_widgets['room-detail-sidebar']	= array("room_search-$search_widget_key","contactinfo-$footer_contactinfo_key");
			$sidebars_widgets['rooms_sidebar']			= array("room_search-$search_widget_key","contactinfo-$footer_contactinfo_key");
		}
				
		update_option('sidebars_widgets',$sidebars_widgets); //save widget informations	
	}
}