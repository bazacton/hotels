<?php
/**
 * The template Theme Colors
 */

if ( ! function_exists( 'cs_theme_colors' ) ) {
    function cs_theme_colors(){
        global $post,$cs_theme_options;
        $cs_theme_color = $cs_theme_options['cs_theme_color'];
        $sub_header_border_color = isset($cs_theme_options['cs_sub_header_border_color']) ? $cs_theme_options['cs_sub_header_border_color'] : '';
        $main_header_border_color = isset($cs_theme_options['cs_header_border_color']) ? $cs_theme_options['cs_header_border_color'] : '';        
        $page_header_style = '';
        $page_header_border_colr = '';
        $page_subheader_border_color = '';
        
		if(is_page() || is_single()){
            $cs_post_type = get_post_type($post->ID);
            switch($cs_post_type){
				case 'product':
					$post_type_meta = 'product';
					break;
                default:
                    $post_type_meta = 'cs_full_data';
            }          
			$cs_page_bulider = get_post_meta($post->ID, "$post_type_meta", true);  
            if(isset($cs_page_bulider) and $cs_page_bulider <> ''){
				$page_header_style = isset($cs_page_bulider['cs_header_banner_style']) ? $cs_page_bulider['cs_header_banner_style'] : '';
				$page_header_border_colr = isset($cs_page_bulider['cs_page_main_header_border_color']) ? $cs_page_bulider['cs_page_main_header_border_color'] : '';
				$page_subheader_border_color = isset($cs_page_bulider['cs_page_subheader_border_color']) ? $cs_page_bulider['cs_page_subheader_border_color'] : '';
			}
		}
?>
<style type="text/css">
/*!
* Theme Color File */

/*!
* Theme Color */
.cscolor,.cs-hovercolor:hover,.breadcrumb-sec .breadcrumbs ul li.active, .breadcrumb-sec .breadcrumbs ul li.active span,.cs-accomodation article:hover .accomodation-info h5 a,.accomodation-tabs .nav li.active a,.twitter_widget article p a, .cs-banner .flexslider li figure .caption .textinfo p,
.cs-services:hover h5, .events-timeline .post-options li span, .cs-error-msg, .footer-nav ul li a:hover, #copyright .social-media ul li a:hover i, .left-side p a:hover,
.widget_calendar thead th,/* Blog */ .pagination li a.active, .pagination li a:hover, .post-options li.categories a:hover, .blog-grid article:hover h5 a,  .blog-large article:hover h2 a,
.cs-blog article:hover h4 a, .widget_recent ul li:hover h6 a, .prev-next-post article.prev:hover h4 a, .prev-next-post article.next:hover h4 a,.cs-team .social-media ul li a:hover, .cs-counter .cs-text a, .post-options li a:hover, .accomodation-tabs .nav > li:hover > a, .panel-group.cs-default.simple .panel-heading a:before,
.cs-list ul li a:hover,.cs-iconlist li a:hover, .cs-reservation-tabs .tabs-nav li.active a, .search-summery span:before, .process-loader, .reservation-search .cs-bank-transfer h2, .undo-search a i, .rooms-list .text span.price, .search-summery li span, .cs-check-list .check-price small, .rooms-list .text span.sidebar-total, .rooms-list .text span.sidebar-discount-total, .extras ul li span, .pypal-sec input[type="checkbox"]:checked + label::before, .pypal-sec input[type="radio"]:checked + label::before, .pypal-price li span, .cs-content404 a, .cs-content404 a i, .cs-search-results ul li a {
 color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}
/*!
* Theme Background Color */
.csbg-color,.csbg-hovercolor:hover,.widget-section-title h2:before,.widget_form ul li input[type="submit"],.cs-infolist:hover .info-btn,.booking-btn:hover,.comment-reply-title:before,
#respond form p input[type="submit"],/* widget */.widget_search form label input[type="submit"], .widget-section-title h2:before, .widget_cs-services .cs-services:before, .widget_categories ul li:hover, .widget_cetegorie ul li:hover, .widget_pages ul li:hover, .widget_meta ul li:hover, .widget_nav_menu ul li a:hover, .widget_archive ul li:hover,
.cs-tags ul li:hover a,/* Event */.date-time, .search-heading h2:before, .cs-price-table .sigun_up, .page-no-search .cs-search-area input[type="submit"], .box_spreater .dividerstyle i:hover, .cs-author span, .user-signup form input[type="submit"],
.cs-blog .cs-media .jp-play-bar, .cs-services.classic::before, .info-toggle.active, .flex-direction-nav a:hover, .owl-nav div:hover, .widget_calendar table tr td:hover,
.widget_calendar table tr td:after,/* Blog */ .pagination li.pgprev:hover i, .pagination li.pgnext:hover i, .prev-next-post article.prev:hover .left-arrow, .prev-next-post article.next:hover .right-arrow, .cs-list .cs-linked-list li a:hover i,
.thumblist .comment-reply-link:hover:before,.events-grid .event-icon:hover,.cs-pricing-table table thead tr th,.cs-show-msg > div, .widget .cs-services:before, .widget_newsletter input[type="submit"], .cs-classic-form #respond .form-submit input[type="button"], .edit-btn, .rooms-list .continue-btn,
.pypal-sec li .button_style a, .cs-selection-complete a, .rooms-list li.cs-current-room small, .select-heading .select-number, .cs-reservation-tabs .tabs-nav li.active i, .cs-confirmation figure i, .go-back, .cs-error, .cs-section-title h2::before, .search-heading h2::before,
.cs-services.modren.cs-fancy2:after, .cs-accomodation.modrenview .booking-btn, .date-time time span, .table-condensed tbody tr td.active, .cs-chcked-area .continue-btn, .pypal-sec .continue-btn, .go-back, .cs-blog article figure figcaption a, .cs-show-msg, .navbar-toggle, .header-v3 .navigation>ul>li>a:after{
    background-color:<?php echo cs_allow_special_char($cs_theme_color); ?> !important;
}
/*!
* Theme Border Color */
.csborder-color,.csborder-hovercolor:hover,.accomodation-tabs .nav li.active a:after, .flex-direction-nav a:hover, .owl-nav div:hover,
.pagination li a.active::before, .pagination li a.active::after{
    border-color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}
.events-minimal:hover,.widget.event-calendar .eventsCalendar-list-wrap,.cs-tabs.vertical .nav-tabs .active a:before, .rooms-list li.cs-current-room, .user-signup form .submit-button::before, .widget_newsletter .submit-button::before {
    border-left-color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}
.cs-tabs .nav-tabs > .active > a:before {
    border-top-color:<?php echo cs_allow_special_char($cs_theme_color);
?> !important;
}

<?php
if((is_page() || is_single()) and ($page_header_style == 'breadcrumb_header' and $page_subheader_border_color <> '')){
    ?>
    .breadcrumb-sec {
        border-top: 1px solid <?php echo cs_allow_special_char($page_subheader_border_color); ?>;
        border-bottom: 1px solid <?php echo cs_allow_special_char($page_subheader_border_color); ?>;
    }
    <?php
}
else{
    if($sub_header_border_color <> ''){
    ?>
        .breadcrumb-sec {
            border-top: 1px solid <?php echo cs_allow_special_char($sub_header_border_color); ?>;
            border-bottom: 1px solid <?php echo cs_allow_special_char($sub_header_border_color); ?>;
        }
    <?php
    }
}

if((is_page() || is_single()) and ($page_header_style == 'no-header' and $page_header_border_colr <> '')){
    ?>
    #main-header {
        border-bottom: 1px solid <?php echo cs_allow_special_char($page_header_border_colr); ?>;
    }
    <?php
}
else{
    if(isset($cs_theme_options['cs_default_header']) and $cs_theme_options['cs_default_header'] == 'No sub Header'){
        if($main_header_border_color <> ''){
        ?>
            #main-header {
                border-bottom: 1px solid <?php echo cs_allow_special_char($main_header_border_color); ?>;
            }
        <?php
        }
    }
}
?>

</style>
<?php } 
}


/** 
 * @Set Header color Css
 *
 *
 */
if ( ! function_exists( 'cs_header_color' ) ) {
    function cs_header_color(){
        global $cs_theme_options;
        
        $cs_header_bgcolor    = (isset($cs_theme_options['cs_header_bgcolor']) and $cs_theme_options['cs_header_bgcolor']<>'') ? $cs_theme_options['cs_header_bgcolor']: '';
        
        $cs_nav_bgcolor =  (isset($cs_theme_options['cs_nav_bgcolor']) and $cs_theme_options['cs_nav_bgcolor']<>'') ? $cs_theme_options['cs_nav_bgcolor']: '';
        
        $cs_menu_color = (isset($cs_theme_options['cs_menu_color']) and $cs_theme_options['cs_menu_color']<>'') ? $cs_theme_options['cs_menu_color']:'';
        
        $cs_menu_active_color = (isset($cs_theme_options['cs_menu_active_color']) and $cs_theme_options['cs_menu_active_color']<>'') ? $cs_theme_options['cs_menu_active_color']: '';
        
        $cs_submenu_bgcolor = (isset($cs_theme_options['cs_submenu_bgcolor']) and $cs_theme_options['cs_submenu_bgcolor']<>'' ) ? $cs_theme_options['cs_submenu_bgcolor']: '';
        
        $cs_submenu_color = (isset($cs_theme_options['cs_submenu_color']) and $cs_theme_options['cs_submenu_color']<>'') ? $cs_theme_options['cs_submenu_color']: '';
        
        $cs_submenu_hover_color = (isset($cs_theme_options['cs_submenu_hover_color']) and $cs_theme_options['cs_submenu_hover_color']<>'') ? $cs_theme_options['cs_submenu_hover_color']: '';
        
        $cs_topstrip_bgcolor = (isset($cs_theme_options['cs_topstrip_bgcolor']) and $cs_theme_options['cs_topstrip_bgcolor']<>'') ? $cs_theme_options['cs_topstrip_bgcolor']: '';
        
        $cs_topstrip_text_color = (isset($cs_theme_options['cs_topstrip_text_color']) and $cs_theme_options['cs_topstrip_text_color']<>'') ? $cs_theme_options['cs_topstrip_text_color']: '';
        
        $cs_topstrip_link_color = (isset($cs_theme_options['cs_topstrip_link_color']) and $cs_theme_options['cs_topstrip_link_color']<>'') ? $cs_theme_options['cs_topstrip_link_color']: '';
        
        $cs_menu_activ_bg = (isset($cs_theme_options['cs_theme_color'])) ? $cs_theme_options['cs_theme_color']: '';
        
        /* logo margins*/
        $cs_logo_margint = (isset($cs_theme_options['cs_logo_margint']) and  $cs_theme_options['cs_logo_margint'] <> '') ? $cs_theme_options['cs_logo_margint']: '0';
 $cs_logo_marginb = (isset($cs_theme_options['cs_logo_marginb']) and  $cs_theme_options['cs_logo_marginb'] <> '') ? $cs_theme_options['cs_logo_marginb']: '0';

        $cs_logo_marginr = (isset($cs_theme_options['cs_logo_marginr']) and  $cs_theme_options['cs_logo_marginr'] <> '') ? $cs_theme_options['cs_logo_marginr']: '0';
  $cs_logo_marginl = (isset($cs_theme_options['cs_logo_marginl']) and  $cs_theme_options['cs_logo_marginl'] <> '') ? $cs_theme_options['cs_logo_marginl']: '0';

        /* font family */
        $cs_content_font = (isset($cs_theme_options['cs_content_font'])) ? $cs_theme_options['cs_content_font']: '';
        $cs_content_font_att = (isset($cs_theme_options['cs_content_font_att'])) ? $cs_theme_options['cs_content_font_att']: '';
        
        $cs_mainmenu_font = (isset($cs_theme_options['cs_mainmenu_font'])) ? $cs_theme_options['cs_mainmenu_font']: '';
        $cs_mainmenu_font_att = (isset($cs_theme_options['cs_mainmenu_font_att'])) ? $cs_theme_options['cs_mainmenu_font_att']: '';
        
        $cs_heading_font = (isset($cs_theme_options['cs_heading_font'])) ? $cs_theme_options['cs_heading_font']: '';
        $cs_heading_font_att = (isset($cs_theme_options['cs_heading_font_att'])) ? $cs_theme_options['cs_heading_font_att']: '';
        
        $cs_widget_heading_font = (isset($cs_theme_options['cs_widget_heading_font'])) ? $cs_theme_options['cs_widget_heading_font']: '';
        $cs_widget_heading_font_att = (isset($cs_theme_options['cs_widget_heading_font_att'])) ? $cs_theme_options['cs_widget_heading_font_att']: '';
        
        // setting content fonts
        $cs_content_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_content_font_att);
        
        $cs_content_font_atts = cs_get_font_att_array($cs_content_fonts);
        
        // setting main menu fonts
        $cs_mainmenu_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_mainmenu_font_att);
        
        $cs_mainmenu_font_atts = cs_get_font_att_array($cs_mainmenu_fonts);
        
        // setting heading fonts
        $cs_heading_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_heading_font_att);
        
        $cs_heading_font_atts = cs_get_font_att_array($cs_heading_fonts);
        
        // setting widget heading fonts
        $cs_widget_heading_fonts = preg_split('#(?<=\d)(?=[a-z])#i', $cs_widget_heading_font_att);
        
        $cs_widget_heading_font_atts = cs_get_font_att_array($cs_widget_heading_fonts);
         
        /* font size */
        $cs_content_size = (isset($cs_theme_options['cs_content_size'])) ? $cs_theme_options['cs_content_size']: '';
        $cs_mainmenu_size = (isset($cs_theme_options['cs_mainmenu_size'])) ? $cs_theme_options['cs_mainmenu_size']: '';
        $cs_heading_1_size = (isset($cs_theme_options['cs_heading_1_size'])) ? $cs_theme_options['cs_heading_1_size']: '';
        $cs_heading_2_size = (isset($cs_theme_options['cs_heading_2_size'])) ? $cs_theme_options['cs_heading_2_size']: '';
        $cs_heading_3_size = (isset($cs_theme_options['cs_heading_3_size'])) ? $cs_theme_options['cs_heading_3_size']: '';
        $cs_heading_4_size = (isset($cs_theme_options['cs_heading_4_size'])) ? $cs_theme_options['cs_heading_4_size']: '';
        $cs_heading_5_size = (isset($cs_theme_options['cs_heading_5_size'])) ? $cs_theme_options['cs_heading_5_size']: '';
        $cs_heading_6_size = (isset($cs_theme_options['cs_heading_6_size'])) ? $cs_theme_options['cs_heading_6_size']: '';
        
        /* font Color */
        $cs_heading_h1_color = (isset($cs_theme_options['cs_heading_h1_color']) and $cs_theme_options['cs_heading_h1_color'] <> '') ? $cs_theme_options['cs_heading_h1_color']: '';
        $cs_heading_h2_color = (isset($cs_theme_options['cs_heading_h2_color']) and $cs_theme_options['cs_heading_h2_color'] <> '') ? $cs_theme_options['cs_heading_h2_color']: '';
        $cs_heading_h3_color = (isset($cs_theme_options['cs_heading_h3_color']) and $cs_theme_options['cs_heading_h3_color'] <> '') ? $cs_theme_options['cs_heading_h3_color']: '';
        $cs_heading_h4_color = (isset($cs_theme_options['cs_heading_h4_color']) and $cs_theme_options['cs_heading_h4_color'] <> '') ? $cs_theme_options['cs_heading_h4_color']:'';
        $cs_heading_h5_color = (isset($cs_theme_options['cs_heading_h5_color']) and $cs_theme_options['cs_heading_h5_color'] <> '') ? $cs_theme_options['cs_heading_h5_color']: '';
        $cs_heading_h6_color = (isset($cs_theme_options['cs_heading_h6_color']) and $cs_theme_options['cs_heading_h6_color'] <> '') ? $cs_theme_options['cs_heading_h6_color']: '';
        $cs_text_color = $cs_theme_options['cs_text_color'];         
        
        $cs_widget_heading_size = (isset($cs_theme_options['cs_widget_heading_size'])) ? $cs_theme_options['cs_widget_heading_size']: '';
		$cs_section_heading_size = (isset($cs_theme_options['cs_section_heading_size'])) ? $cs_theme_options['cs_section_heading_size']: '';
        
        if(
            ( isset( $cs_theme_options['cs_custom_font_woff'] ) && $cs_theme_options['cs_custom_font_woff'] <> '' ) &&
            ( isset( $cs_theme_options['cs_custom_font_ttf'] ) && $cs_theme_options['cs_custom_font_ttf'] <> '' ) &&
            ( isset( $cs_theme_options['cs_custom_font_svg'] ) && $cs_theme_options['cs_custom_font_svg'] <> '' ) &&
            ( isset( $cs_theme_options['cs_custom_font_eot'] ) && $cs_theme_options['cs_custom_font_eot'] <> '' )
        ):
        
        $font_face_html = "
        @font-face {
            font-family: 'cs_custom_font';
            src: url('".$cs_theme_options['cs_custom_font_eot']."');
            src:
                url('".$cs_theme_options['cs_custom_font_eot']."?#iefix') format('eot'),
                url('".$cs_theme_options['cs_custom_font_woff']."') format('woff'),
                url('".$cs_theme_options['cs_custom_font_ttf']."') format('truetype'),
                url('".$cs_theme_options['cs_custom_font_svg']."#cs_custom_font') format('svg');
            font-weight: 400;
            font-style: normal;
        }";
        
        $custom_font = true; else: $custom_font = false; endif;
     ?>
        <style type="text/css">
		
            <?php 
                if($custom_font == true){
                    echo cs_allow_special_char($font_face_html);
                }
                else{
                    echo cs_get_font_family($cs_content_font, $cs_content_font_att);
                    echo cs_get_font_family($cs_mainmenu_font, $cs_mainmenu_font_att);
                    echo cs_get_font_family($cs_heading_font, $cs_heading_font_att);
                    echo cs_get_font_family($cs_widget_heading_font, $cs_widget_heading_font_att);
                }
            ?>
    body,.main-section p {
        <?php 
        if($custom_font == true){
            echo 'font-family: cs_custom_font !important;';
            echo 'font-size: '.$cs_content_size.';';
        }
        else{
            echo cs_font_font_print($cs_content_font_atts, $cs_content_size, $cs_content_font);
        }
        ?>
         color:<?php echo cs_allow_special_char($cs_text_color);?>;
    }
    header .logo{
        margin:<?php echo cs_allow_special_char($cs_logo_margint);?>px  <?php echo cs_allow_special_char($cs_logo_marginr);?>px <?php echo cs_allow_special_char($cs_logo_marginb);?>px <?php echo cs_allow_special_char($cs_logo_marginl);?>px !important;
       }
    .nav li a,.navigation ul li{
        <?php 
        if($custom_font == true){
            echo 'font-family: cs_custom_font !important;';
            echo 'font-size: '.$cs_mainmenu_size.';';
        }
        else{
             echo cs_font_font_print($cs_mainmenu_font_atts, $cs_mainmenu_size, $cs_mainmenu_font, true);
        }
        ?>
    }
     h1{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font !important;';
        echo 'font-size: '.$cs_heading_1_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_1_size, $cs_heading_font, true);
    }
     
    ?>}
    h2{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font !important;';
        echo 'font-size: '.$cs_heading_2_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_2_size, $cs_heading_font, true);
    }
    
    ?>}
    h3{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font !important;';
        echo 'font-size: '.$cs_heading_3_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_3_size, $cs_heading_font, true);
    }
    
    ?>}
    h4{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font !important;';
        echo 'font-size: '.$cs_heading_4_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_4_size, $cs_heading_font, true);
    }
    
    ?>}
    h5{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font !important;';
        echo 'font-size: '.$cs_heading_5_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_5_size, $cs_heading_font, true);
    }
    
    ?>}
    h6{
    <?php 
    if($custom_font == true){
        echo 'font-family: cs_custom_font !important;';
        echo 'font-size: '.$cs_heading_6_size.';';
    }
    else{
        echo cs_font_font_print($cs_heading_font_atts, $cs_heading_6_size, $cs_heading_font, true);
    }
    
    ?>}
    
    .main-section h1, .main-section h1 a {color: <?php echo cs_allow_special_char($cs_heading_h1_color);?> !important;}
    .main-section h2, .main-section h2 a{color: <?php echo cs_allow_special_char($cs_heading_h2_color);?> !important;}
    .main-section h3, .main-section h3 a{color: <?php echo cs_allow_special_char($cs_heading_h3_color);?> !important;}
    .main-section h4, .main-section h4 a{color: <?php echo cs_allow_special_char($cs_heading_h4_color);?> !important;}
    .main-section h5, .main-section h5 a{color: <?php echo cs_allow_special_char($cs_heading_h5_color);?> !important;}
    .main-section h6, .main-section h6 a{color: <?php echo cs_allow_special_char($cs_heading_h6_color);?> !important;}
    .widget .widget-section-title h2{
        <?php
        if($custom_font == true){
            echo 'font-family: cs_custom_font !important;';
            echo 'font-size: '.$cs_widget_heading_size.';';
        }
        else{
            echo cs_font_font_print($cs_widget_heading_font_atts, $cs_widget_heading_size, $cs_widget_heading_font, true);
        }
        ?>
    }
	  .cs-section-title h2{
        <?php
             echo 'font-size:'.$cs_section_heading_size.'px !important;';
		  ?>
    }
	.top-bar,#lang_sel ul ul {background-color:<?php echo cs_allow_special_char($cs_topstrip_bgcolor);?> !important;}
	#lang_sel ul ul:before { border-bottom-color: <?php echo cs_allow_special_char($cs_topstrip_bgcolor);?>; }
	.top-bar p{color:<?php echo cs_allow_special_char($cs_topstrip_text_color);?> !important;}
	.top-bar a,.top-bar i{color:<?php echo cs_allow_special_char($cs_topstrip_link_color);?> !important;}
	.logo-section,.main-head{background:<?php echo cs_allow_special_char($cs_header_bgcolor);?> !important;}
	.main-navbar,#main-header .btn-style1,.wrapper:before {background:<?php echo cs_allow_special_char($cs_nav_bgcolor);?> !important;}
	.header-v3 .main-head .cs-nav-block, .navigation {background:<?php echo cs_allow_special_char($cs_nav_bgcolor);?> !important;}
	.navigation ul > li > a {color:<?php echo cs_allow_special_char($cs_menu_color);?> !important;}
	.sub-dropdown { background-color:<?php echo cs_allow_special_char($cs_submenu_bgcolor);?> !important;}
	.navigation > ul ul li > a {color:<?php echo cs_allow_special_char($cs_submenu_color);?> !important;}
	.navigation > ul ul li:hover > a {color:<?php echo cs_allow_special_char($cs_submenu_hover_color);?>;color:<?php echo cs_allow_special_char($cs_submenu_hover_color);?> !important;}
	.navigation > ul > li:hover > a {color:<?php echo cs_allow_special_char($cs_menu_active_color);?> !important;}
	.sub-dropdown:before {border-bottom:8px solid <?php echo cs_allow_special_char($cs_menu_active_color);?> !important;}
	.sub-dropdown{border-top:2px solid <?php echo cs_allow_special_char($cs_menu_active_color);?> !important;}
    .navigation .sub-dropdown > li:hover > a,
	.navigation > ul > li.parentIcon:hover > a:before { background-color:<?php echo cs_allow_special_char($cs_menu_active_color);?> !important; }
	.cs-user,.cs-user-login { border-color:<?php echo cs_allow_special_char($cs_menu_active_color);?> !important; }
    {
        box-shadow: 0 4px 0 <?php echo cs_allow_special_char($cs_topstrip_bgcolor); ?> inset !important;
    }
    .header_2 .nav > li:hover > a,.header_2 .nav > li.current-menu-ancestor > a {
       
    }
    </style>
<?php
    }
}



/** 
 * @Set Footer colors
 *
 *
 */
if ( ! function_exists( 'cs_footer_color' ) ) {
    function cs_footer_color(){
        global $cs_theme_options;
            $cs_footerbg_color = (isset($cs_theme_options['cs_footerbg_color']) and $cs_theme_options['cs_footerbg_color'] <> '') ? $cs_theme_options[            'cs_footerbg_color']: '';
        
            $cs_footerbg_image = (isset($cs_theme_options['cs_footer_background_image']) and $cs_theme_options['cs_footer_background_image'] <> '') ?             $cs_theme_options['cs_footer_background_image']: '';
		    $footer_bg_color = cs_hex2rgb($cs_footerbg_color);
			
		    $cs_bg_footer_color = 'background-color:rgba('.$footer_bg_color[0].', '. $footer_bg_color[1].', '.$footer_bg_color[2].', 0.95) !important;'; 
            $cs_title_color = (isset($cs_theme_options['cs_title_color']) and $cs_theme_options['cs_title_color'] <> '') ? $cs_theme_options[            'cs_title_color']: '';
      
          $cs_footer_text_color = (isset($cs_theme_options['cs_footer_text_color']) and $cs_theme_options['cs_footer_text_color'] <> '') ?           $cs_theme_options['cs_footer_text_color']: '';
           $cs_link_color = (isset($cs_theme_options['cs_link_color']) and $cs_theme_options['cs_link_color'] <> '') 
		   ? $cs_theme_options['cs_link_color']: '';
         $cs_sub_footerbg_color = (isset($cs_theme_options['cs_sub_footerbg_color']) and $cs_theme_options['cs_sub_footerbg_color'] <> '') ?                   $cs_theme_options['cs_sub_footerbg_color']: '';
        
        $cs_copyright_text_color = (isset($cs_theme_options['cs_copyright_text_color']) and $cs_theme_options['cs_copyright_text_color'] <> '') ?                 $cs_theme_options['cs_copyright_text_color']: '';
?>
<style type="text/css">
        footer#footer-sec, footer.group:before {
            background-color:<?php echo cs_allow_special_char($cs_footerbg_color); ?> !important;
        }
		#footer-sec {
            background:url(<?php echo esc_url($cs_footerbg_image); ?>) <?php echo cs_allow_special_char($cs_footerbg_color); ?> repeat scroll 0 0 / cover !important; 
         }
       #footer-sec::before {
         <?php echo cs_allow_special_char($cs_bg_footer_color); ?>;
         }
        .footer-content {
            background-color:<?php echo cs_allow_special_char($cs_footerbg_color); ?> !important;
        }
        footer #copyright p {
            color:<?php echo cs_allow_special_char($cs_copyright_text_color); ?> !important;
        }
        footer a,footer .widget-form ul li input[type='submit'],footer.group .tagcloud a,footer.group .widget ul li a {
            color:<?php echo cs_allow_special_char($cs_link_color); ?> !important;
        }
		#footer-sec .widget{
			 background-color:<?php echo cs_allow_special_char($cs_bg_footer_color); ?> !important;
		}
        footer#footer-sec .widget h2, footer#footer-sec .widget h5,footer.group h2,footer#footer-sec h3,footer#footer-sec h4,footer#footer-sec h5,footer#footer-sec h6 {
            color:<?php echo cs_allow_special_char($cs_title_color); ?> !important;
        }
      #newslatter-sec,#newslatter-sec span,footer#footer-sec .widget ul li,footer#footer-sec .widget p, footer#footer-sec .widget_calendar tr td,footer.group,footer .widget_latest_post .post-options li,footer#footer-sec .widget i,.widget-form ul li i {
            color:<?php echo cs_allow_special_char($cs_footer_text_color); ?> !important;
        }
    </style>
<?php 
}
}