<?php
/**
 * The template for Settings up Functions
 */
/**
 * @Get logo
 *
 */
global $cs_theme_options;
if (!function_exists('cs_logo')) {

    function cs_logo() {
        global $cs_theme_options;
        $logo = $cs_theme_options['cs_custom_logo'];
        ?>
        <a href="<?php echo esc_url(home_url()); ?>">    
            <img src="<?php echo esc_url($logo); ?>" style="width:<?php echo cs_allow_special_char($cs_theme_options['cs_logo_width']); ?>px; height: <?php echo cs_allow_special_char($cs_theme_options['cs_logo_height']); ?>px;" alt="<?php bloginfo('name'); ?>">
        </a>
        <?php
    }

}



if (!function_exists('cs_sticky_logo')) {

    function cs_sticky_logo() {
        global $cs_theme_options;
        $stickey_logo = isset($cs_theme_options['cs_sticky_logo']) ? $cs_theme_options['cs_sticky_logo'] : '';
        ?>
        <a href="<?php echo esc_url(home_url()); ?>">    
            <img src="<?php echo esc_url($stickey_logo); ?>" alt="<?php bloginfo('name'); ?>">
        </a>
        <?php
    }

}
/**
 * @Set Header Position
 *
 *
 */
if (!function_exists('cs_header_postion_class')) {

    function cs_header_postion_class() {
        global $cs_theme_options;
        return 'header-' . $cs_theme_options['cs_header_position'];
    }

}
/**
 * @Set Header strip
 *
 *
 */
if (!function_exists('cs_header_strip')) {

    function cs_header_strip($container = 'on') {
        global $cs_theme_options;
        $cs_top_menu_switch = isset($cs_theme_options['cs_top_menu_switch']) ? $cs_theme_options['cs_top_menu_switch'] : '';
        $cs_wpml_switch = isset($cs_theme_options['cs_wpml_switch']) ? $cs_theme_options['cs_wpml_switch'] : '';
        $cs_music_text = isset($cs_theme_options['cs_music_text']) && $cs_theme_options['cs_music_text'] != '' ? $cs_theme_options['cs_music_text'] : 'Music on';
        $cs_music_url = isset($cs_theme_options['cs_music_url']) ? $cs_theme_options['cs_music_url'] : '';
        $cs_get_locations = isset($cs_theme_options['cs_get_locations']) && $cs_theme_options['cs_get_locations'] != '' ? $cs_theme_options['cs_get_locations'] : 'Get all Locations';
        $cs_get_locations_url = isset($cs_theme_options['cs_get_locations_url']) ? $cs_theme_options['cs_get_locations_url'] : '';
        $cs_header_strip_tagline_text = isset($cs_theme_options['cs_header_strip_tagline_text']) ? wp_specialchars_decode($cs_theme_options['cs_header_strip_tagline_text']) : '';
        $cs_music_setting_switch = isset($cs_theme_options['cs_music_setting_switch']) ? wp_specialchars_decode($cs_theme_options['cs_music_setting_switch']) : '';
        ?>
        <!-- Top Strip -->
        <?php
        if (isset($cs_theme_options['cs_header_top_strip']) and $cs_theme_options['cs_header_top_strip'] == 'on') {
            $cs_afterlogin_class = '';
            ?>
            <div class="top-bar"> 
                <!-- Container -->
                <?php if ($container == 'on') { ?>
                    <div class="container"> 
                    <?php } ?>
                    <!-- Left Side -->
                    <aside class="left-side">
                        <?php if (isset($cs_header_strip_tagline_text) and $cs_header_strip_tagline_text <> '') { ?>
                            <?php echo do_shortcode($cs_header_strip_tagline_text); ?> 
                            <?php
                        }
                        ?> 
                    </aside>     
                    <aside class="right-side">
                        <ul class="location-list">
                            <?php
                            if (isset($_COOKIE['cs_off'])) {
                                $cs_music_setting_switch = 'off';
                            } else if (isset($_COOKIE['cs_on'])) {
                                $cs_music_setting_switch = 'on';
                            }
                            if (isset($cs_music_url) && $cs_music_url != '' and $cs_music_setting_switch != 'off') {
                                ?>
                                <li class="active">
                                    <i class="icon-volume-up"></i> 
                                    <a class="music" href="javascript:;" onclick="cs_music_toggle('<?php echo admin_url('admin-ajax.php') ?>')">
                                        <audio id="audio" src="<?php echo esc_url($cs_music_url); ?>" autoplay></audio><?php _e('Music', 'luxury-hotel'); ?> <span><?php _e('off', 'luxury-hotel'); ?></span></a>
                                </li>
                            <?php } else { ?>
                                <li>
                                    <i class="icon-volume-off"></i> 
                                    <a class="music" href="javascript:;" onclick="cs_music_toggle('<?php echo admin_url('admin-ajax.php') ?>')">
                                        <audio id="audio" src="<?php echo esc_url($cs_music_url); ?>" ></audio><?php _e('Music', 'luxury-hotel'); ?> <span><?php _e('on', 'luxury-hotel'); ?></span></a> </li>
                            <?php } ?>

                            <?php if (isset($cs_get_locations_url) && $cs_get_locations_url != '') { ?>
                                <li>  <a target="_blank" href="<?php echo esc_url($cs_get_locations_url); ?>">
                                        <?php echo wp_specialchars_decode($cs_get_locations);
                                        ?></a> </li>
                            <?php } ?>
                            <?php if (function_exists('icl_object_id') and $cs_wpml_switch != 'off') { ?>  
                                <li>
                                    <?php echo do_action('icl_language_selector'); ?>         

                                </li>
                            <?php }
                            ?> 
                        </ul>
                    </aside>  
                </div>
            </div>
            <?php
        }
    }

}

function cs_k_to_f($temp) {
    if (!is_numeric($temp)) {
        return false;
    }
    return round((($temp - 273.15) * 1.8) + 32);
}

function cs_k_to_c($temp) {
    if (!is_numeric($temp)) {
        return false;
    }
    return round(($temp - 273.15));
}

/*
 * Header Weather function
 */

if (!function_exists('cs_weather_functions')) {

    function cs_weather_functions() {
        global $cs_theme_options;

        $cs_weather_header_section = isset($cs_theme_options['cs_weather_header_section']) ? $cs_theme_options['cs_weather_header_section'] : '';
        $cs_weather_text = isset($cs_theme_options['cs_weather_text']) ? $cs_theme_options['cs_weather_text'] : '';
        $cs_weather_country = isset($cs_theme_options['cs_weather_country']) ? $cs_theme_options['cs_weather_country'] : '';
        $cs_weather_city = isset($cs_theme_options['cs_weather_city']) ? $cs_theme_options['cs_weather_city'] : '';
        $cs_weather_tem_setting = isset($cs_theme_options['cs_weather_tem_setting']) ? $cs_theme_options['cs_weather_tem_setting'] : '';

        if ($cs_weather_tem_setting == 'Fahrenheit') {
            $cs_weather_tem_setting = 'f';
        } else {
            $cs_weather_tem_setting = 'c';
        }
        if ($cs_weather_header_section = 'on') {
            ?>
            <li><div id="location-weather"></div> </li>
            <?php hotel_enqueue_weather_widget_script(); ?>
            <script type="text/javascript">
                'use strict';
                function loadWeatherWidget(location, woeid, cf) {
                    var today;
                     var title;
                    jQuery.simpleWeather({
                        location: location,
                        woeid: woeid,
                        unit: cf,
                        success: function (weather) {
                         
                            today = '<small><?php echo esc_attr($cs_weather_text); ?></small><div class=\"weather-detail\">';
                            today += '<span class=\"cs-temperature\" ><img width = \"30px\" src=\"' + weather.image + '\"><em> ' + weather.low +' - '+ weather.high + '&deg;' + weather.units.temp + '</em></span><small>' + location + '</small>';
                           
                            jQuery('#location-weather').html(today);
                        },
                        error: function (error) {
                            jQuery('#location-weathert').html('<p>' + error + '</p>');
                        }
                    });

                }
                jQuery(document).ready(function () {
                    loadWeatherWidget('<?php echo cs_data_validation($cs_weather_city); ?>', '<?php echo cs_data_validation($cs_weather_country); ?>', '<?php echo cs_data_validation($cs_weather_tem_setting); ?>'); //@params location, woeid
                });
            </script>
            <?php
        }
    }

}


/**
 * @Top and Main Navigation
 *
 *
 */
if (!function_exists('cs_navigation')) {

    function cs_navigation($nav = '', $menus = 'menus', $menu_class = '', $depth = '0') {
        global $cs_theme_options;
        if (has_nav_menu($nav)) {
            $defaults = array(
                'theme_location' => "$nav",
                'menu' => '',
                'container' => '',
                'container_class' => '',
                'container_id' => '',
                'menu_class' => "$menu_class",
                'menu_id' => "$menus",
                'echo' => false,
                'fallback_cb' => 'wp_page_menu',
                'before' => '',
                'after' => '',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul class="nav navbar-nav">%3$s</ul>',
                'depth' => "$depth",
                'walker' => '',);
            echo do_shortcode(str_replace('sub-menu', 'sub-dropdown', (wp_nav_menu($defaults))));
        } else {
            $defaults = array(
                'theme_location' => "",
                'menu' => '',
                'container' => '',
                'container_class' => '',
                'container_id' => '',
                'menu_class' => "$menu_class",
                'menu_id' => "$menus",
                'echo' => false,
                'fallback_cb' => 'wp_page_menu',
                'before' => '',
                'after' => '',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul class="nav navbar-nav">%3$s</ul>',
                'depth' => "$depth",
                'walker' => '',);
            echo do_shortcode(str_replace('sub-menu', 'sub-dropdown', (wp_nav_menu($defaults))));
        }
    }

}


//===============
//@ Header 
//===============
if (!function_exists('cs_get_headers')) {

    function cs_get_headers() {
        global $cs_theme_options;

        $cs_header_style = isset($cs_theme_options['cs_header_style']) ? $cs_theme_options['cs_header_style'] : '';

        $header_top_menu = isset($cs_theme_options['cs_top_menu_switch']) ? $cs_theme_options['cs_top_menu_switch'] : '';
        $cs_wpml_switch = isset($cs_theme_options['cs_wpml_switch']) ? $cs_theme_options['cs_wpml_switch'] : '';
        $cs_header_top_strip = isset($cs_theme_options['cs_header_top_strip']) ? $cs_theme_options['cs_header_top_strip'] : '';
        // $cs_post_ad_title = isset($cs_theme_options['cs_post_ad_title']) ? $cs_theme_options['cs_post_ad_title'] : __('Reservation', 'luxury-hotel');

        $cs_post_ad_title = isset($cs_theme_options['cs_post_ad_title']) && $cs_theme_options['cs_post_ad_title'] != '' ? $cs_theme_options['cs_post_ad_title'] : __('Reservation', 'luxury-hotel');


        $cs_reservation_button_link = isset($cs_theme_options['cs_reservation_button_link']) ? $cs_theme_options['cs_reservation_button_link'] : __('Reservation', 'luxury-hotel');
        $cs_blog_title = get_bloginfo('description');

        $cs_headr_class = '';
        $cs_info_class = 'headerinfo';
        if ($cs_header_style == 'header-2') {
            $cs_headr_class = 'header-v2';
            $cs_info_class = 'cs_nav_bar';
        } else if ($cs_header_style == 'header-3') {
            $cs_headr_class = 'header-v3';
            $cs_info_class = 'cs_nav_bar';
        }
        ?>
        <!-- Header 1 Start -->
        <header id="main-header" class="<?php echo sanitize_html_class($cs_headr_class) ?>">
            <?php cs_header_strip() ?>
            <!-- Main Header -->
            <div class="main-head">                      
                <div class="container">
                    <div class="logo">
                        <?php cs_logo(); ?>
                    </div>

                    <div class="logo sticky">
                        <?php cs_sticky_logo(); ?>
                    </div>
                    <div class="res-btn">
                        <?php echo cs_resbtn_res(); ?>
                    </div>
                    <?php
                    if ($cs_header_style == 'header-3') {
                        echo '<div class="cs-nav-block">';
                    }
                    ?>
                    <aside class="right-side">
                        <div class="<?php echo sanitize_html_class($cs_info_class) ?>">
                            <?php if (isset($cs_theme_options['cs_tag_line']) && $cs_theme_options['cs_tag_line'] == 'on' && $cs_header_style == 'header-1') { ?>
                                <span class="head-title">
                                    <?php echo esc_attr($cs_blog_title); ?>
                                </span> 
                                <?php
                            }

                            if ($cs_header_style == 'header-1') {
                                ?>
                                <ul class="cs-reservation">
                                    <?php
                                    $cs_weather_header_section = isset($cs_theme_options['cs_weather_header_section']) ? $cs_theme_options['cs_weather_header_section'] : '';
                                    if ($cs_weather_header_section <> 'off') {
                                        echo cs_weather_functions();
                                    }
                                    ?>
                                    <!--Reservation pending section-->
                                    <?php echo cs_get_started(); ?>
                                </ul>
                            </div>
                        <?php } ?>
                        <nav class="navbar navbar-default" role="navigation">
                            <div class="navbar-header">
                                <?php echo cs_resbtn_res(); ?>
                                <button type="button" class="navbar-toggle toggle-menu menu-right push-body" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                    <span class="sr-only"><?php _e('Toggle navigation', 'luxury-hotel'); ?></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right navigation" id="bs-example-navbar-collapse-1">
                                <?php cs_header_main_navigation(); ?>
                            </div>
                        </nav>

                        <?php if ($cs_header_style == 'header-2' || $cs_header_style == 'header-3') { ?>
                            <ul class="cs-reservation">
                                <?php echo cs_get_started($cs_header_style); ?>                                     
                            </ul>
                    </div>
                <?php } ?>
                </aside>
                <?php
                if ($cs_header_style == 'header-3') {
                    echo '</div>';
                }
                ?>
            </div>
        </div>      
        </header>
        <?php
    }

}

/**
 *
 * @ Reservation Button
 *
 */
if (!function_exists('cs_get_started')) {

    function cs_get_started($cs_header_style = '') {
        global $cs_theme_options, $current_user;
        // $cs_post_ad_title = isset($cs_theme_options['cs_post_ad_title']) ? $cs_theme_options['cs_post_ad_title'] : __('Reservation', 'luxury-hotel');

        $cs_post_ad_title = isset($cs_theme_options['cs_post_ad_title']) && $cs_theme_options['cs_post_ad_title'] != '' ? $cs_theme_options['cs_post_ad_title'] : __('Reservation', 'luxury-hotel');

        $cs_reservation_button_link = isset($cs_theme_options['cs_reservation_button_link']) ? $cs_theme_options['cs_reservation_button_link'] : '';
        if (isset($cs_post_ad_title) and $cs_reservation_button_link) {

            $cs_icn = $cs_header_style == 'header-2' ? '<i class="icon-pencil2"></i>' : $cs_post_ad_title;
            echo '<li><a href="' . esc_url($cs_reservation_button_link) . '" class="reserve-btn csbg-color" target="_blank">' . $cs_icn . '</a></li>';
        }
    }

}
/**
 *
 * @ Reservation Button For Responsive
 *
 */
if (!function_exists('cs_resbtn_res')) {

    function cs_resbtn_res() {
        global $cs_theme_options, $current_user;
        // $cs_post_ad_title = isset($cs_theme_options['cs_post_ad_title']) ? $cs_theme_options['cs_post_ad_title'] : __('Reservation', 'luxury-hotel');

        $cs_post_ad_title = isset($cs_theme_options['cs_post_ad_title']) && $cs_theme_options['cs_post_ad_title'] != '' ? $cs_theme_options['cs_post_ad_title'] : __('Reservation', 'luxury-hotel');




        $cs_reservation_button_link = isset($cs_theme_options['cs_reservation_button_link']) ? $cs_theme_options['cs_reservation_button_link'] : '';
        if (isset($cs_post_ad_title) and $cs_reservation_button_link) {

            echo '<a href="' . esc_url($cs_reservation_button_link) . '" class="reserve-btn csbg-color" target="_blank">' . $cs_post_ad_title . '</a>';
        }
    }

}

//=================
// @Main navigation
//=================
if (!function_exists('cs_header_main_navigation')) {

    function cs_header_main_navigation() {
        global $post, $post_meta;
        $post_type = get_post_type(get_the_ID());
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_meta = get_post_meta($post_ID, "$meta_element", true);

        if (function_exists("is_shop") and ! is_shop()) {
            if (is_author() || is_search() || is_archive() || is_category() || is_404()) {

                $cs_header_banner_style = '';
            }
        } else if (!function_exists("is_shop")) {
            if (is_author() || is_search() || is_archive() || is_category() || is_404()) {

                $cs_header_banner_style = '';
            }
        }
        cs_navigation('main-menu', 'navbar-nav');
    }

}


//====================
// @Subheader Style
//====================
if (!function_exists('cs_subheader_style')) {

    function cs_subheader_style($post_ID = '') {
        global $post, $wp_query, $cs_theme_options, $post_meta;
        $post_type = get_post_type(get_the_ID());
        $post_ID = get_the_ID();
        $meta_element = 'cs_full_data';

        $post_meta = get_post_meta((int) $post_ID, "$meta_element", true);
        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $post_meta = get_post_meta((int) $post_ID, "$meta_element", true);

        if (function_exists("is_shop") and ! is_shop()) {
            if (is_author() || is_search() || is_archive() || is_category()) {
                $cs_header_banner_style = '';
            }
        } else if (!function_exists("is_shop")) {
            if (is_author() || is_search() || is_archive() || is_category()) {

                $cs_header_banner_style = '';
            }
        }
        if (isset($cs_header_banner_style) && $cs_header_banner_style == 'no-header') {
            // Do Nothing
        } else if (isset($cs_header_banner_style) && $cs_header_banner_style == 'breadcrumb_header') {
            cs_breadcrumb_header($post_ID);
        } else if (isset($cs_header_banner_style) && $cs_header_banner_style == 'custom_slider') {
            cs_shortcode_slider('pages', $post_ID);
        } else if (isset($cs_header_banner_style) && $cs_header_banner_style == 'map') {
            cs_shortcode_map($post_ID);
        } else if ($cs_theme_options['cs_default_header']) {
            if ($cs_theme_options['cs_default_header'] == 'No sub Header') {
                // Do Noting          				
            } else if ($cs_theme_options['cs_default_header'] == 'Breadcrumbs Sub Header') {
                cs_breadcrumb_header($post_ID);
                //cs_breadcrumbs(); 
            } else if ($cs_theme_options['cs_default_header'] == 'Revolution Slider') {

                cs_shortcode_slider('default-pages', $post_ID);
            }
        }
    }

}
//====================
// @Below Header Style 
//====================
if (!function_exists('cs_below_header_style')) {

    function cs_below_header_style() {
        global $cs_theme_options;
        $cs_header_position = isset($cs_theme_options['cs_header_position']) ? $cs_theme_options['cs_header_position'] : '';
        $cs_absolute_view = isset($cs_theme_options['cs_headerbg_options']) ? $cs_theme_options['cs_headerbg_options'] : '';
        $cs_absolute_slider = isset($cs_theme_options['cs_headerbg_slider']) ? $cs_theme_options['cs_headerbg_slider'] : '';
        $cs_absolute_image = isset($cs_theme_options['cs_headerbg_image']) ? $cs_theme_options['cs_headerbg_image'] : '';
        $cs_absolute_color = isset($cs_theme_options['cs_headerbg_color']) ? $cs_theme_options['cs_headerbg_color'] : '';
        if ($cs_header_position == 'absolute') {
            if (is_author() || is_search() || is_archive() || is_category() || is_home() || is_404()) {
                if ($cs_absolute_view == 'cs_rev_slider') {
                    ?>
                    <div class="cs-banner"> <?php echo do_shortcode('[rev_slider ' . $cs_absolute_slider . ']'); ?> </div>
                    <?php
                } else if ($cs_absolute_view == 'cs_bg_image_color') {
                    $cs_style_elements = 'style="background:url(' . $cs_absolute_image . ') center top ' . $cs_absolute_color . ';"';
                    ?>
                    <div class="breadcrumb-sec" <?php echo cs_allow_special_char($cs_style_elements); ?>>&nbsp;</div>
                    <?php
                }
            }
        }
    }

}
/**
 * @Custom Slider by using shortcode
 *
 *
 */
if (!function_exists('cs_shortcode_slider')) {

    function cs_shortcode_slider($type = '', $post_ID = '') {
        global $post, $post_meta, $cs_theme_options;
        $cs_custom_slider_id = get_post_meta((int) $post_ID, "cs_custom_slider_id", true);

        if ($cs_custom_slider_id == '') {
            if (isset($cs_theme_options['cs_custom_slider']) && $cs_theme_options['cs_custom_slider'] != '') {
                $cs_custom_slider_id = $cs_theme_options['cs_custom_slider'];
            }
        }

        if ($type == 'pages') {
            if (empty($cs_custom_slider_id))
                $custom_slider_id = "";
            else
                $custom_slider_id = htmlspecialchars(
                        $cs_custom_slider_id);
        } else {
            if (empty($cs_custom_slider_id))
                $custom_slider_id = "";
            else
                $custom_slider_id = htmlspecialchars(
                        $cs_custom_slider_id);
        }
        if (isset($custom_slider_id) && $custom_slider_id != '') {
            ?>
            <div class="cs-banner"> <?php echo do_shortcode('[rev_slider ' . $custom_slider_id . ']'); ?> </div>
            <?php
        }
    }

}
/**
 * @Custom Map by using shortcode
 *
 *
 */
if (!function_exists('cs_shortcode_map')) {

    function cs_shortcode_map($post_ID = '') {
        global $post, $post_meta, $header_map;
        $cs_custom_map = get_post_meta((int) $post_ID, "cs_custom_map", true);
        if (empty($cs_custom_map))
            $custom_map = "";
        else
            $custom_map = html_entity_decode($cs_custom_map);
        if (isset($custom_map) && $custom_map != '') {
            $header_map = true;
            ?>
            <div class="cs-map"> <?php echo do_shortcode($custom_map); ?> </div>
            <?php
        }
    }

}
/**
 * @Breadcrumb Header
 *
 * 
 */
if (!function_exists('cs_breadcrumb_header')) {

    function cs_breadcrumb_header($post_ID = '') {

        global $post, $wp_query, $cs_theme_options, $post_meta;
        $breadcrumSectionStart = '';
        $breadcrumSectionEnd = '';
        $post_type = '';
        if (is_page() || is_single()) {
            if (isset($post) && $post <> '') {
                $post_ID = $post->ID;
            } else {
                $post_ID = '';
            }
            $post_type = get_post_type($post_ID);
        }

        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $cs_page_subheader_color = get_post_meta((int) $post_ID, "cs_page_subheader_color", true);
        $cs_page_subheader_text_color = get_post_meta((int) $post_ID, "cs_page_subheader_text_color", true);
        $cs_page_subheader_no_image = get_post_meta((int) $post_ID, "cs_page_subheader_no_image", true);
        $cs_header_banner_image = get_post_meta((int) $post_ID, "cs_header_banner_image", true);
        $cs_page_subheader_parallax = get_post_meta((int) $post_ID, "cs_page_subheader_parallax", true);
        $cs_subheader_padding_top = get_post_meta((int) $post_ID, "cs_subheader_padding_top", true);
        $cs_subheader_padding_bottom = get_post_meta((int) $post_ID, "cs_subheader_padding_bottom", true);


        $staticContainerStart = '';
        $staticContainerEnd = '';
        $banner_image_height = '170px';
        $cs_sh_paddingtop = '';
        $cs_sh_paddingbottom = '';
        $isDeafultSubHeader = 'false';

        if (is_author() || is_search() || is_archive() || is_category() || is_home() || is_404() || $post_type == 'rooms') {
            $isDeafultSubHeader = 'true';
        }
        if (isset($cs_header_banner_style) && ( $cs_header_banner_style == 'default_header' || $cs_header_banner_style == '' )) {
            //Padding Top & Bottom 
            $cs_sh_paddingtop = ( isset($cs_theme_options['cs_sh_paddingtop']) ) ? 'padding-top:' . $cs_theme_options['cs_sh_paddingtop'] . 'px;' : '';
            $cs_sh_paddingbottom = ( isset($cs_theme_options['cs_sh_paddingbottom']) ) ? 'padding-bottom:' . $cs_theme_options['cs_sh_paddingbottom'] . 'px;' : '';
            $page_subheader_color = ( isset($cs_theme_options['cs_sub_header_bg_color'])) ? $cs_theme_options['cs_sub_header_bg_color'] : '';
            $page_subheader_text_color = ( isset($cs_theme_options['cs_sub_header_text_color']) ) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            $header_banner_image = ( isset($cs_theme_options['cs_background_img']) ) ? $cs_theme_options['cs_background_img'] : '';
            $page_subheader_parallax = ( isset($cs_theme_options['cs_parallax_bg_switch']) ) ? $cs_theme_options['cs_parallax_bg_switch'] : '';
        } else {
            if ($isDeafultSubHeader == 'true') {

                $cs_sh_paddingtop = ( isset($cs_theme_options['cs_sh_paddingtop']) ) ? 'padding-top:' . $cs_theme_options['cs_sh_paddingtop'] . 'px;' : '';
                $cs_sh_paddingbottom = ( isset($cs_theme_options['cs_sh_paddingbottom']) ) ? 'padding-bottom:' . $cs_theme_options['cs_sh_paddingbottom'] . 'px;' : '';
                $header_banner_image = (isset($cs_theme_options['cs_background_img']) && $cs_theme_options['cs_background_img'] ) ? $cs_theme_options['cs_background_img'] : '';
                $page_subheader_parallax = ( isset($cs_theme_options['cs_parallax_bg_switch']) && $cs_theme_options['cs_parallax_bg_switch'] != '' ) ? $cs_theme_options['cs_parallax_bg_switch'] : '';
                $page_subheader_color = (isset($cs_theme_options['cs_sub_header_bg_color']) and $cs_theme_options['cs_sub_header_bg_color'] <> '' ) ? $cs_theme_options['cs_sub_header_bg_color'] : '';
                $page_subheader_text_color = (isset($cs_theme_options['cs_sub_header_text_color']) and $cs_theme_options['cs_sub_header_text_color'] <> '' ) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            } else {
                if (empty($cs_page_subheader_color))
                    $page_subheader_color = "";
                else
                    $page_subheader_color = $cs_page_subheader_color;
                if (empty($cs_page_subheader_text_color))
                    $page_subheader_text_color = "";
                else
                    $page_subheader_text_color = $cs_page_subheader_text_color;
                if (isset($cs_page_subheader_no_image) && $cs_page_subheader_no_image != '') {
                    if (empty($cs_header_banner_image))
                        $header_banner_image = "";
                    else
                        $header_banner_image = $cs_header_banner_image;
                    if (empty($cs_page_subheader_parallax))
                        $page_subheader_parallax = "";
                    else
                        $page_subheader_parallax = $cs_page_subheader_parallax;
                } else {
                    $page_subheader_parallax = "";
                    $header_banner_image = "";
                }
                //Padding Top & Bottom
                if (empty($cs_subheader_padding_top)) {
                    $cs_sh_paddingtop = "";
                } else {
                    $cs_sh_paddingtop = 'padding-top:' . $cs_subheader_padding_top . 'px;';
                }
                if (empty($cs_subheader_padding_bottom)) {
                    $cs_sh_paddingbottom = "";
                } else {
                    $cs_sh_paddingbottom = 'padding-bottom:' . $cs_subheader_padding_bottom . 'px';
                }
            }
        }

        if ($page_subheader_color) {
            $subheader_style_elements = 'background: ' . $page_subheader_color . ';';
        } else {
            $subheader_style_elements = '';
        }

        if (isset($header_banner_image) && $header_banner_image != '') {
            $image_exsist = '';
            if(function_exists('framework_fileopen')){
                $image_exsist = framework_fileopen( $header_banner_image, 'r');
            }

           // $image_exsist = @fopen($header_banner_image, 'r');
            if ($image_exsist <> '') {
                $banner_image_height = getimagesize($header_banner_image);
            } else {
                $banner_image_height = '';
            }
            if ($banner_image_height <> '') {
                $banner_image_height = $banner_image_height[1] . 'px';
            }
            if ($page_subheader_parallax == 'on') {
                $parallaxStatus = 'no-repeat fixed';
            } else {
                $parallaxStatus = '';
            }
            if ($page_subheader_parallax == 'on') {
                $header_banner_image = 'url(' . $header_banner_image . ') center top ' . $parallaxStatus . '';
                $subheader_style_elements = 'background: ' . $header_banner_image . ' ' . $page_subheader_color . ';' . ' background-size:cover;';
            } else {
                $subheader_style_elements = '';
                $header_banner_image = 'url(' . $header_banner_image . ') center top ' . $parallaxStatus . '';
                $subheader_style_elements = 'background: ' . $header_banner_image . ' ' . $page_subheader_color . ';';
            }
            $breadcrumSectionStart = '<div class="absolute-sec">';
            $breadcrumSectionEnd = '</div>';
        }

        $parallax_class = '';
        $parallax_data_type = '';

        if (isset($page_subheader_parallax) && (string) $page_subheader_parallax == 'on') {
            echo '<script>jQuery(document).ready(function($){cs_parallax_func()});</script>';
            $parallax_class = 'parallex-bg';
            $parallax_data_type = ' data-type="background"';
        }

        if ($subheader_style_elements) {
            $subheader_style_elements = 'style="' . $subheader_style_elements . ' min-height:' . $banner_image_height . '!important; ' . $cs_sh_paddingtop . ' ' . $cs_sh_paddingbottom . '  "';
        } else {
            $subheader_style_elements = 'style="min-height:' . $banner_image_height . '; ' . $cs_sh_paddingtop . ' ' . $cs_sh_paddingbottom . ' "';
        }
        $page_tile_align = get_subheader_text_align();
        ?>
        <div class="breadcrumb-sec <?php echo cs_allow_special_char($page_tile_align) . ' ' . cs_allow_special_char($parallax_class); ?>" <?php echo cs_allow_special_char($subheader_style_elements); ?> 
             <?php echo cs_allow_special_char($parallax_data_type); ?>> 
            <!-- Container --> 
            <?php echo balanceTags(cs_data_validation($breadcrumSectionStart), false); ?>
            <div class="container" style="height:<?php echo esc_attr($banner_image_height); ?>">
                <div class="cs-table">
                    <div class="cs-tablerow">
                        <!-- PageInfo -->
                        <?php
                        if (is_page()) {
                            get_subheader_title();
                        } else if (is_single() && $post_type == 'rooms') {
                            get_default_post_title();
                        } else if (is_single() && $post_type != 'post' && $post_type != 'rooms') {
                            get_subheader_title();
                        } else if (is_single() && $post_type == 'post') {
                            get_subheader_title();
                        } else {
                            get_default_post_title();
                        }
                        get_subheader_breadcrumb();
                        ?>
                    </div>
                </div>
            </div>
            <?php echo balanceTags(cs_data_validation($breadcrumSectionEnd), false); ?>
        </div>
        <div class="clear"></div>
        <?php
    }

}
/**
 * @Page Sub header title and subtitle 
 *
 *
 */
if (!function_exists('get_subheader_breadcrumb')) {

    function get_subheader_breadcrumb() {
        global $post, $wp_query, $cs_theme_options, $post_meta;
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_type = get_post_type(get_the_ID());
        $post_meta = get_post_meta((int) $post_ID, "$meta_element", true);
        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $cs_page_breadcrumbs = get_post_meta((int) $post_ID, "cs_page_breadcrumbs", true);
        $cs_page_subheader_text_color = get_post_meta((int) $post_ID, "cs_page_subheader_text_color", true);

        $cs_brec_chk = false;
        $cs_header_banner_style = isset($cs_header_banner_style) ? $cs_header_banner_style : '';

        if (isset($post_meta) and $post_meta <> '') {

            if (isset($cs_header_banner_style) && $cs_header_banner_style == 'breadcrumb_header' && $cs_page_breadcrumbs == 'on') {
                $cs_brec_chk = true;
            } else if (isset($cs_theme_options['cs_default_header']) && $cs_header_banner_style != 'breadcrumb_header' && (isset($cs_theme_options['cs_breadcrumbs_switch']) and $cs_theme_options['cs_breadcrumbs_switch'] == 'on')) {
                $cs_brec_chk = true;
            } else if (isset($cs_theme_options['cs_default_header']) && $post_type == 'rooms' && (isset($cs_theme_options['cs_breadcrumbs_switch']) && $cs_theme_options['cs_breadcrumbs_switch'] == 'on')) {
                $cs_brec_chk = true;
            }
        } else {
            $cs_brec_chk = true;
        }

        if ($cs_brec_chk == true) {
            ?>
            <!-- BreadCrumb -->
            <div class="subheader-style">
                <?php
                if (is_author() || is_search() || is_archive() || is_category() || is_home() || $post_type == 'rooms' || $post_meta == '') {
                    if (isset($cs_theme_options['cs_sub_header_text_color']) && $cs_theme_options['cs_sub_header_text_color'] <> '') {
                        ?>
                        <style>
                            .breadcrumb ul li a,.breadcrumb ul li.active,.breadcrumb ul li:first-child:after {
                                color : <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                            }   
                            .breadcrumb-sec .pageinfo h1::before, .breadcrumb-sec .pageinfo h1::after, .breadcrumb-sec .pageinfo h1, .breadcrumb-sec .pageinfo h1 span::before, .breadcrumb-sec .pageinfo h1 span::after {
                                border-color : <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                            }
                            .header-sp .devider1 {
                                border-bottom-color: <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                            }
                            .breadcrumb-sec .heading-box h1{ border-left-color:<?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important; border-right-color:<?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;}
                            .breadcrumb-sec .heading-box .img-box::before, .breadcrumb-sec .heading-box .img-box::after{
                                border-bottom-color: <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                            }
                        </style>
                        <?php
                    }
                } else {

                    if (isset($cs_header_banner_style) and $cs_header_banner_style == 'default_header') {

                        if (isset($cs_theme_options['cs_sub_header_text_color']) && $cs_theme_options['cs_sub_header_text_color'] <> '') {
                            ?>
                            <style>
                                .breadcrumb ul li a,.breadcrumb ul li.active,.breadcrumb ul li:first-child:after {
                                    color : <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                                }   
                                .breadcrumb-sec .pageinfo h1::before, .breadcrumb-sec .pageinfo h1::after, .breadcrumb-sec .pageinfo h1, .breadcrumb-sec .pageinfo h1 span::before, .breadcrumb-sec .pageinfo h1 span::after {
                                    border-color : <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                                }
                                .header-sp .devider1 {
                                    border-bottom-color: <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                                }
                                .breadcrumb-sec .heading-box h1{ border-left-color:<?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important; border-right-color:<?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;}
                                .breadcrumb-sec .heading-box .img-box::before, .breadcrumb-sec .heading-box .img-box::after{
                                    border-bottom-color: <?php echo esc_attr($cs_theme_options['cs_sub_header_text_color']); ?> !important;
                                }
                            </style>
                            <?php
                        }
                    } else if (isset($cs_page_subheader_text_color) && $cs_page_subheader_text_color != '') {
                        ?>
                        <style>
                            .breadcrumb ul li a,.breadcrumb ul li.active,.breadcrumb ul li:first-child:after {
                                color : <?php echo esc_attr($cs_page_subheader_text_color); ?> !important;
                            }  
                            .breadcrumb-sec .pageinfo h1::before, .breadcrumb-sec .pageinfo h1::after, .breadcrumb-sec .pageinfo h1, .breadcrumb-sec .pageinfo h1 span::before, .breadcrumb-sec .pageinfo h1 span::after {
                                border-color : <?php echo esc_attr($cs_page_subheader_text_color); ?> !important;
                            }
                            .header-sp .devider1 {
                                border-bottom-color: <?php echo esc_attr($cs_page_subheader_text_color); ?> !important;
                            }
                            .breadcrumb-sec .heading-box h1{ border-left-color:<?php echo esc_attr($cs_page_subheader_text_color); ?> !important; border-right-color:<?php echo esc_attr($cs_page_subheader_text_color); ?> !important;}
                            .breadcrumb-sec .heading-box .img-box::before, .breadcrumb-sec .heading-box .img-box::after{
                                border-bottom-color: <?php echo esc_attr($cs_page_subheader_text_color); ?> !important;
                            }  
                        </style>
                        <?php
                    }
                }

                cs_breadcrumbs();
                ?>
            </div>
            <?php
        }
    }

}
/**
 * @Page Sub header title and subtitle 
 *
 *
 */
if (!function_exists('get_subheader_text_align')) {

    function get_subheader_text_align() {
        global $post, $post_meta, $cs_theme_options;
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_meta = get_post_meta($post_ID, "$meta_element", true);
        //   $cs_page_title_align = get_post_meta($post_ID, 'cs_page_title_align', true);
        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $page_tile_align = '';
        if (isset($cs_header_banner_style) && $cs_header_banner_style == 'default_header') {
            if (isset($cs_theme_options['cs_page_title_align']) && $cs_theme_options['cs_page_title_align'] == 'right') {
                $page_tile_align = 'page-title-align-right';
            } else if (isset($cs_theme_options['cs_title_align']) && $cs_theme_options['cs_title_align'] == 'center') {
                $page_tile_align = 'page-title-align-center';
            } else if (isset($cs_theme_options['cs_title_align']) && $cs_theme_options['cs_title_align'] == 'left') {
                $page_tile_align = 'page-title-align-left';
            } else {
                $page_tile_align = 'page-title-align-center';
            }
        } else {
            if (isset($cs_page_title_align) && $cs_page_title_align == 'right') {
                $page_tile_align = 'page-title-align-right';
            } else if (isset($cs_page_title_align) && $cs_page_title_align == 'center') {
                $page_tile_align = 'page-title-align-center';
            } else if (isset($cs_page_title_align) && $cs_page_title_align == 'left') {
                $page_tile_align = 'page-title-align-left';
            } else {
                $page_tile_align = 'page-title-align-center';
            }
        }
        return $page_tile_align;
    }

}
/**
 * @Page Sub header title and subtitle 
 *
 *
 */
if (!function_exists('get_subheader_title')) {

    function get_subheader_title($shop_id = '') {
        global $post, $cs_theme_options;
        $meta_element = 'cs_full_data';
        $post_ID = get_the_ID();
        $post_meta = get_post_meta($post_ID, "$meta_element", true);
        $post_ID = $post->ID;
        $text_color = '';

        $cs_header_banner_style = get_post_meta((int) $post_ID, "cs_header_banner_style", true);
        $cs_sub_header_text_color = get_post_meta((int) $post_ID, "cs_page_subheader_text_color", true);
        $cs_page_title = get_post_meta((int) $post_ID, "cs_page_title", true);
        $cs_page_subheading_title = get_post_meta((int) $post_ID, "cs_page_subheading_title", true);
        $cs_page_title_style = get_post_meta((int) $post_ID, "cs_page_title_style", true);

        $color = '';
        $text_color = '';

        $cs_page_title_style = isset($cs_page_title_style) ? $cs_page_title_style : '';
        $cs_page_title = (isset($cs_page_title) and $cs_page_title <> '') ? $cs_page_title : '';

        if (isset($cs_header_banner_style) and $cs_header_banner_style == 'breadcrumb_header') {
            $text_color = $cs_sub_header_text_color;
        } else {
            if (isset($cs_page_subheader_text_color) and $cs_page_subheader_text_color <> '') {
                $text_color = isset($cs_theme_options['cs_sub_header_text_color']) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            } else {
                $text_color = isset($cs_theme_options['cs_sub_header_text_color']) ? $cs_theme_options['cs_sub_header_text_color'] : '';
            }
        }

        $color = 'style="color:' . $text_color . ' !important"';

        if (isset($cs_header_banner_style) && $cs_header_banner_style != 'default_header' && $cs_header_banner_style != '') {
            if (isset($cs_page_title) && $cs_page_title == 'on') {

                if ($cs_page_title_style == 'fancy') {
                    echo '<div class="heading-box">';
                    echo '<div class="img-box"><img src="' . get_template_directory_uri() . '/assets/images/hover-img.png" alt="img"></div>';
                } else {
                    echo '<div class="pageinfo">';
                }
                echo '<h1 ' . $color . '>';
                if ($cs_page_title_style == 'fancy') {
                    echo get_the_title($post_ID);
                } else {
                    echo '<span>' . get_the_title($post_ID) . '</span>';
                }
                echo '</h1>';
                echo '</div>';
            }

            if (isset($cs_page_subheading_title) && $cs_page_subheading_title != '') {
                echo '<p ' . $color . '>';
                echo do_shortcode($cs_page_subheading_title);
                echo '</p>';
            }
            if ($cs_page_title_style == 'fancy') {
                echo '<div class="header-sp"><div class="devider1"></div></div>';
            }
        } else {
            global $cs_theme_options;
            $cs_page_title_style = isset($cs_theme_options['cs_heading_style']) ? $cs_theme_options['cs_heading_style'] : '';
            $cs_title_switch = $cs_theme_options['cs_title_switch'];


            if (isset($cs_title_switch) && $cs_title_switch == 'on') {
                if ($cs_page_title_style == 'Fancy') {
                    echo '<div class="heading-box">';
                    echo '<div class="img-box"><img src="' . get_template_directory_uri() . '/assets/images/hover-img.png" alt="img"></div>';
                } else {
                    echo '<div class="pageinfo">';
                }

                echo '<h1 ' . $color . '>';

                if ($cs_page_title_style == 'Fancy') {
                    echo get_the_title($post_ID);
                } else {
                    echo '<span>' . get_the_title($post_ID) . '</span>';
                }

                echo '</h1>';
                echo '</div>';

                if ($cs_page_title_style == 'Fancy') {
                    echo '<div class="header-sp"><div class="devider1"></div></div>';
                }
            }
        }
    }

}

/**
 * @ Default page title function
 *
 *
 */
if (!function_exists('get_default_post_title')) {

    function get_default_post_title() {
        global $post, $cs_theme_options;
        $post_type = '';
        if (is_single()) {
            $post_type = get_post_type(get_the_ID());
        }
        $textAlign = isset($cs_theme_options['cs_title_align']) ? $cs_theme_options['cs_title_align'] : 'center';
        $cs_heading_style = isset($cs_theme_options['cs_heading_style']) ? $cs_theme_options['cs_heading_style'] : '';
        if (empty($cs_theme_options['cs_sub_header_text_color']))
            $text_color = "";
        else
            $text_color = 'style="color:' . $cs_theme_options['cs_sub_header_text_color'] . '"';
        ?>
        <div class="pageinfo <?php echo 'page-title-align-' . $textAlign; ?>">
            <?php
            if ($cs_heading_style == 'Fancy') {
                echo '<div class="heading-box">';
                echo '<div class="img-box"><img src="' . get_template_directory_uri() . '/assets/images/hover-img.png" alt="img"></div>';
            }
            echo '<h1 ' . balanceTags(cs_data_validation($text_color), false) . '>';

            if ($cs_heading_style == 'Fancy') {
                if ($post_type == 'rooms') {
                    the_title();
                } else {
                    cs_post_page_title();
                }
            } else {
                ?>
                <span>
                    <?php
                    if ($post_type == 'rooms') {
                        the_title();
                    } else {
                        cs_post_page_title();
                    }
                    ?>
                </span>
                <?php
            }
            echo '</h1>';
            if ($cs_heading_style == 'Fancy') {
                echo '</div>';
                echo '<div class="header-sp"><div class="devider1"></div></div>';
            }
            ?>
        </div>
        <?php
    }

}

// Setting Header top strip Music
if (!function_exists('cs_music_toggle')) {

    function cs_music_toggle() {

        $cs_on = 'cs_on';
        $cs_off = 'cs_off';
        if (isset($_COOKIE[$cs_off])) {
            unset($_COOKIE[$cs_off]);
            setcookie($cs_off, null, -1, '/');
            setcookie($cs_on, 'true', time() + 86400, '/');
        } else if (isset($_COOKIE[$cs_on])) {
            unset($_COOKIE[$cs_on]);
            setcookie($cs_on, null, -1, '/');
            setcookie($cs_off, 'true', time() + 86400, '/');
        } else {
            setcookie($cs_off, 'true', time() + 86400, '/');
        }
        die(0);
    }

    add_action('wp_ajax_cs_music_toggle', 'cs_music_toggle');
    add_action('wp_ajax_nopriv_cs_music_toggle', 'cs_music_toggle');
}