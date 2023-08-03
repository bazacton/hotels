<?php
/**
 * @Theme option function
 * @return
 *
 */
if (!function_exists('cs_options_page')) {

    function cs_options_page() {
        global $cs_theme_options, $cs_options;
        //$cs_theme_options=get_option('cs_theme_options');
        ?>

        <div class="theme-wrap fullwidth">
            <div class="inner">
                <div class="outerwrapp-layer">
                    <div class="loading_div"> <i class="icon-circle-o-notch icon-spin"></i> <br>
        <?php _e('Saving changes...', "luxury-hotel"); ?>
                    </div>
                    <div class="form-msg"> <i class="icon-check-circle-o"></i>
                        <div class="innermsg"></div>
                    </div>
                </div>
                <div class="row">
                    <form id="frm" method="post">
                        <?php
                        $theme_options_fields = new theme_options_fields();
                        $return = $theme_options_fields->cs_fields($cs_options);
                        ?>
                        <div class="col1">
                            <nav class="admin-navigtion">
                                <div class="logo"> <a href="#" class="logo1"><img src="<?php echo get_template_directory_uri() ?>/include/assets/images/logo-themeoption.png" /></a> <a href="#" class="nav-button"><i class="icon-align-justify"></i></a> </div>
                                <ul>
        <?php echo force_balance_tags(cs_data_validation($return[1], true)); ?>
                                </ul>
                            </nav>
                        </div>
                        <div class="col2">
        <?php echo force_balance_tags(cs_data_validation($return[0]), true); /* Settings */ ?>
                        </div>
                        <div class="clear"></div>
                        <div class="footer">
                            <input type="button" id="submit_btn" name="submit_btn" class="bottom_btn_save" value="<?php _e('Save All Settings', "luxury-hotel"); ?>" onclick="javascript:theme_option_save('<?php echo esc_js(admin_url('admin-ajax.php')) ?>', '<?php echo esc_js(get_template_directory_uri()); ?>');" />
                            <input type="hidden" name="action" value="theme_option_save"  />
                            <input class="bottom_btn_reset" name="reset" type="button" value="<?php _e('Reset All Options', "luxury-hotel"); ?>" onclick="javascript:cs_rest_all_options('<?php echo esc_js(admin_url('admin-ajax.php')) ?>', '<?php echo esc_js(get_template_directory_uri()) ?>');" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <!--wrap--> 
        <script type="text/javascript">
            // Sub Menus Show/hide
            jQuery(document).ready(function ($) {
                jQuery(".sub-menu").parent("li").addClass("parentIcon");
                $("a.nav-button").click(function () {
                    $(".admin-navigtion").toggleClass("navigation-small");
                });

                $("a.nav-button").click(function () {
                    $(".inner").toggleClass("shortnav");
                });

                $(".admin-navigtion > ul > li > a").click(function () {
                    var a = $(this).next('ul')
                    $(".admin-navigtion > ul > li > a").not($(this)).removeClass("changeicon")
                    $(".admin-navigtion > ul > li ul").not(a).slideUp();
                    $(this).next('.sub-menu').slideToggle();
                    $(this).toggleClass('changeicon');
                });
            });

            function show_hide(id) {
                var link = id.replace('#', '');
                jQuery('.horizontal_tab').fadeOut(0);
                jQuery('#' + link).fadeIn(400);
            }

            function toggleDiv(id) {
                jQuery('.col2').children().hide();
                jQuery(id).show();
                location.hash = id + "-show";
                var link = id.replace('#', '');
                jQuery('.categoryitems li').removeClass('active');
                jQuery(".menuheader.expandable").removeClass('openheader');
                jQuery(".categoryitems").hide();
                jQuery("." + link).addClass('active');
                jQuery("." + link).parent("ul").show().prev().addClass("openheader");
            }
            jQuery(document).ready(function () {
                jQuery(".categoryitems").hide();
                jQuery(".categoryitems:first").show();
                jQuery(".menuheader:first").addClass("openheader");
                jQuery(".menuheader").live('click', function (event) {
                    if (jQuery(this).hasClass('openheader')) {
                        jQuery(".menuheader").removeClass("openheader");
                        jQuery(this).next().slideUp(200);
                        return false;
                    }
                    jQuery(".menuheader").removeClass("openheader");
                    jQuery(this).addClass("openheader");
                    jQuery(".categoryitems").slideUp(200);
                    jQuery(this).next().slideDown(200);
                    return false;
                });

                var hash = window.location.hash.substring(1);
                var id = hash.split("-show")[0];
                if (id) {
                    jQuery('.col2').children().hide();
                    jQuery("#" + id).show();
                    jQuery('.categoryitems li').removeClass('active');
                    jQuery(".menuheader.expandable").removeClass('openheader');
                    jQuery(".categoryitems").hide();
                    jQuery("." + id).addClass('active');
                    jQuery("." + id).parent("ul").slideDown(300).prev().addClass("openheader");
                }
            });
            jQuery(function ($) {
                $("#cs_launch_date").datepicker({
                    defaultDate: "+1w",
                    dateFormat: "dd/mm/yy",
                    changeMonth: true,
                    numberOfMonths: 1,
                    onSelect: function (selectedDate) {
                        $("#cs_launch_date").datepicker();
                    }
                });
            });
        </script>
        <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()) ?>/include/assets/css/jquery_ui_datepicker.css">
        <link rel="stylesheet" href="<?php echo esc_url(get_template_directory_uri()) ?>/include/assets/css/jquery_ui_datepicker_theme.css">
        <?php
    }

}

/**
 * @Background Count function
 * @return
 *
 */
if (!function_exists('cs_bgcount')) {

    function cs_bgcount($name, $count) {
        for ($i = 0; $i <= $count; $i++) {
            $pattern['option' . $i] = $name . $i;
        }
        return $pattern;
    }

}

/**
 * @Theme Options Initilize
 * @return
 *
 */
add_action('init', 'cs_theme_option');
if (!function_exists('cs_theme_option')) {

    function cs_theme_option() {
        global $cs_options, $cs_header_colors, $cs_theme_options;
        //$cs_theme_options		= get_option('cs_theme_options');
        $on_off_option = array("show" => "on", "hide" => "off");
        $navigation_style = array("left" => "left", "center" => "center", "right" => "right");
        $google_fonts = array('google_font_family_name' => array('', '', ''), 'google_font_family_url' => array('', '', ''));
        $social_network = array('social_net_icon_path' => array('', '', '', '', ''), 'social_net_awesome' => array('icon-facebook9', 'icon-dribbble7', 'icon-twitter2', 'icon-behance2', 'icon-google-plus'), 'social_net_url' => array('https://www.facebook.com/', 'https://dribbble.com/', 'https://www.twitter.com/', 'https://www.behance.net/', 'https://plus.google.com'), 'social_net_tooltip' => array('Facebook', 'Dribbble', 'Twitter', 'Behance', 'Google Plus'), 'social_font_awesome_color' => array('#cccccc', '#cccccc', '#cccccc', '#cccccc', '#cccccc'));

        $banner_fields = array('banner_field_title' => array('Banner 1'), 'banner_field_style' => array('top_banner'), 'banner_field_type' => array('code'), 'banner_field_image' => array(''), 'banner_field_url' => array('#'), 'banner_field_url_target' => array('_self'), 'banner_adsense_code' => array(''), 'banner_field_code_no' => array('0'));


        $sidebar = array(
            'sidebar' => array(
                'blogs_sidebar' => __('Blogs Sidebar', "luxury-hotel"),
                'contact' => __('Contact', "luxury-hotel"),
                'event_listings' => __('Event Listings', "luxury-hotel"),
                'home_luxuryresort' => __('Home Luxuryresort', "luxury-hotel"),
                'event_detail' => __('Event Detail', "luxury-hotel"),
            )
        );
        //$menus_locations = array_flip(get_nav_menu_locations());
        $breadcrumb_option = array("option1" => "option1", "option2" => "option2", "option3" => "option3");
        $deafult_sub_header = array('breadcrumbs_sub_header' => __('Breadcrumbs Sub Header', "luxury-hotel"), 'slider' => __('Revolution Slider', "luxury-hotel"), 'no_header' => __('No sub Header', "luxury-hotel"));
        $padding_sub_header = array('Default' => 'default', 'Custom' => 'custom');

        #Menus List
        $menu_option = get_registered_nav_menus();
        foreach ($menu_option as $key => $menu) {
            $menu_location = $key;
            $menu_locations = get_nav_menu_locations();
            $menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
            $menu_name[] = (isset($menu_object->name) ? $menu_object->name : '');
        }

        #Mailchimp List
        $mail_chimp_list[] = '';
        if (isset($cs_theme_options['cs_mailchimp_key'])) {
            $mailchimp_option = $cs_theme_options['cs_mailchimp_key'];
            if ($mailchimp_option <> '') {
                $mc_list = cs_mailchimp_list($mailchimp_option);
                if (is_array($mc_list) && isset($mc_list['data'])) {
                    foreach ($mc_list['data'] as $list) {
                        $mail_chimp_list[$list['id']] = $list['name'];
                    }
                }
            }
        }

        #Map Search Pages
        $pages = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-ad-search.php',
            'hierarchical' => 0
        ));

        $map_options = array();
        $map_options[] = 'Default';
        foreach ($pages as $page) {
            $map_options[$page->ID] = $page->post_title;
        }

        #google fonts array
        $g_fonts = cs_googlefont_list();
        $g_fonts_atts = cs_get_google_font_attribute();

        $cs_sidebar = array();
        global $cs_theme_options;
        if (isset($cs_theme_options) and $cs_theme_options <> '') {
            if (isset($cs_theme_options['sidebar']) && $cs_theme_options['sidebar']) {
                $cs_sidebar = array('sidebar' => $cs_theme_options['sidebar']);
            } elseif (!isset($cs_theme_options['sidebar'])) {
                $cs_sidebar = array('sidebar' => array());
            }
        } else {
            $cs_sidebar = $sidebar;
        }

        #Set the Options Array
        $cs_options = array();
        $cs_header_colors = cs_header_setting();

        #general setting options
        $cs_options[] = array(
            "name" => __("General", "luxury-hotel"),
            "fontawesome" => 'icon-cog3',
            "type" => "heading",
            "options" => array(
                'tab-global-setting' => __("global", "luxury-hotel"),
                'tab-header-options' => __("Header", "luxury-hotel"),
                'tab-sub-header-options' => __("Sub Header", "luxury-hotel"),
                'tab-footer-options' => __("Footer", "luxury-hotel"),
                'tab-social-setting' => __("social icons", "luxury-hotel"),
                'tab-social-network' => __("social sharing", "luxury-hotel"),
                'tab-custom-code' => __("custom code", "luxury-hotel"),
            )
        );
        $cs_options[] = array(
            "name" => __("color", "luxury-hotel"),
            "fontawesome" => 'icon-magic',
            "hint_text" => "",
            "type" => "heading",
            "options" => array(
                'tab-general-color' => __("general", "luxury-hotel"),
                'tab-header-color' => __("Header", "luxury-hotel"),
                'tab-footer-color' => __("Footer", "luxury-hotel"),
                'tab-heading-color' => __("headings", "luxury-hotel"),
            )
        );
        $cs_options[] = array(
            "name" => __("typography / fonts", "luxury-hotel"),
            "fontawesome" => 'icon-font',
            "desc" => "",
            "hint_text" => "",
            "type" => "heading",
            "options" => array(
                'tab-custom-font' => __('Custom Font', "luxury-hotel"),
                'tab-font-family' => __('font family', "luxury-hotel"),
                'tab-font-size' => __('Font Size', "luxury-hotel"),
            )
        );
        $cs_options[] = array(
            "name" => __("sidebar", "luxury-hotel"),
            "fontawesome" => 'icon-columns',
            "id" => "tab-sidebar",
            "std" => "",
            "type" => "main-heading",
            "options" => ''
        );
        $cs_options[] = array(
            "name" => __("Seo", "luxury-hotel"),
            "fontawesome" => 'icon-globe6',
            "id" => "tab-seo",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        $cs_options[] = array(
            "name" => __("global", "luxury-hotel"),
            "id" => "tab-global-setting",
            "type" => "sub-heading"
        );
        $cs_options[] = array(
            "name" => __("Layout", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Layout type", "luxury-hotel"),
            "id" => "cs_layout",
            "std" => "full_width",
            "options" => array(
                "boxed" => __("Boxed", "luxury-hotel"),
                "full_width" => __("Full width", "luxury-hotel")
            ),
            "type" => "layout",
        );

        $cs_options[] = array(
            "name" => "",
            "id" => "cs_horizontal_tab",
            "class" => "horizontal_tab",
            "type" => "horizontal_tab",
            "std" => "",
            "options" => array('Background' => 'background_tab', 'Pattern' => 'pattern_tab', 'Custom Image' => 'custom_image_tab')
        );

        $cs_options[] = array(
            "name" => __("Background image", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Choose from Predefined Background images.", "luxury-hotel"),
            "id" => "cs_bg_image",
            "class" => "cs_background_",
            "path" => "background",
            "tab" => "background_tab",
            "std" => "bg1",
            "type" => "layout_body",
            "display" => "block",
            "options" => cs_bgcount('bg', '10')
        );

        $cs_options[] = array("name" => __("Background pattern", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Choose from Predefined Pattern images.", "luxury-hotel"),
            "id" => "cs_bg_image",
            "class" => "cs_background_",
            "path" => "patterns",
            "tab" => "pattern_tab",
            "std" => "bg1",
            "type" => "layout_body",
            "display" => "none",
            "options" => cs_bgcount('pattern', '27')
        );
        $cs_options[] = array(
            "name" => __("Custom image", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("This option can be used only with Boxed Layout.", "luxury-hotel"),
            "id" => "cs_custom_bgimage",
            "std" => "",
            "tab" => "custom_image_tab",
            "display" => "none",
            "type" => "upload logo"
        );
        $cs_options[] = array("name" => __("Background image position", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Choose image position for body background", "luxury-hotel"),
            "id" => "cs_bgimage_position",
            "std" => __("Center Repeat", "luxury-hotel"),
            "type" => "select",
            "options" => array(
                "option1" => "no-repeat center top",
                "option2" => "repeat center top",
                "option3" => "no-repeat center",
                "option4" => "Repeat Center",
                "option5" => "no-repeat left top",
                "option6" => "repeat left top",
                "option7" => "no-repeat fixed center",
                "option8" => "no-repeat fixed center / cover"
            )
        );
        $cs_options[] = array("name" => __("Custom favicon", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Custom favicon for your site", "luxury-hotel"),
            "id" => "cs_custom_favicon",
            "std" => get_template_directory_uri() . "/assets/images/favicon.png",
            "type" => "upload logo"
        );

        $cs_options[] = array("name" => __("Smooth Scroll", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Lightweight Script for Page Scrolling animation", "luxury-hotel"),
            "id" => "cs_smooth_scroll",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $cs_options[] = array("name" => __("Responsive", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set responsive design layout for mobile devices ON/OFF here", "luxury-hotel"),
            "id" => "cs_responsive",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox",
            "options" => $on_off_option
        );

        if (class_exists('cs_framework')) {
            $cs_options[] = array("name" => "Language Settings",
                "id" => "tab-general-options",
                "std" => __("Language Settings", "luxury-hotel"),
                "type" => "section",
                "options" => ""
            );


            $dir = cs_framework::plugin_dir() . '/languages/';
            $cs_plugin_language[''] = __("Select Language File", 'luxury-hotel');
            if (is_dir($dir)) {
                if ($dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                        if ($ext == 'mo') {
                            $cs_plugin_language[$file] = $file;
                        }
                    }
                    closedir($dh);
                }
            }

            $cs_options[] = array("name" => __("Select Language", "luxury-hotel"),
                "desc" => "",
                "hint_text" => "",
                "id" => "cs_language_file",
                "std" => "30",
                "type" => "select",
                "options" => $cs_plugin_language,
            );
        }

        // Header options start
        $cs_options[] = array("name" => __("header", "luxury-hotel"),
            "id" => "tab-header-options",
            "type" => "sub-heading"
        );
        /* $cs_options[] = array( "name" =>__( "Attention for Header Position!", "luxury-hotel" ),
          "id" => "header_postion_attention",
          "std"=>__( "<strong>Relative Position:</strong> The element is positioned relative to its normal position. The header is positioned above the content. <br> <strong>Absolute Position:</strong> The element is positioned relative to its first positioned. The header is positioned on the content.", "luxury-hotel" ),
          "type" => "announcement"
          ); */

        $cs_options[] = array(
            "name" => __("Select Header", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_header_style",
            "std" => "header-1",
            "type" => "select_values",
            "options" => array('header-1' => __("Header 1", "luxury-hotel"), 'header-2' => __("Header 2", "luxury-hotel"), 'header-3' => __("Header 3", "luxury-hotel")),
        );

        $cs_options[] = array("name" => __("Logo", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Upload your custom logo in .png .jpg .gif formats only.", "luxury-hotel"),
            "id" => "cs_custom_logo",
            "std" => get_template_directory_uri() . "/assets/images/logo.png",
            "type" => "upload logo"
        );
        $cs_options[] = array("name" => __("Logo Height", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set exact logo height otherwise logo will not display normally.", "luxury-hotel"),
            "id" => "cs_logo_height",
            "min" => '0',
            "max" => '100',
            "std" => "130",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("logo width", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set exact logo width otherwise logo will not display normally.", "luxury-hotel"),
            "id" => "cs_logo_width",
            "min" => '0',
            "max" => '210',
            "std" => "210",
            "type" => "range"
        );

        $cs_options[] = array("name" => __("Logo margin top", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Logo spacing margin from top", "luxury-hotel"),
            "id" => "cs_logo_margint",
            "min" => '0',
            "max" => '200',
            "std" => "30",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Logo margin bottom", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Logo spacing margin from bottom.", "luxury-hotel"),
            "id" => "cs_logo_marginb",
            "min" => '-60',
            "max" => '200',
            "std" => "-60",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Logo margin right", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Logo spacing margin from right.", "luxury-hotel"),
            "id" => "cs_logo_marginr",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Logo margin left", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Logo spacing margin from left", "luxury-hotel"),
            "id" => "cs_logo_marginl",
            "min" => '-20',
            "max" => '200',
            "std" => "-13",
            "type" => "range"
        );
        /* header element settings */

        $cs_options[] = array("name" => __("Header Elements", "luxury-hotel"),
            "id" => "tab-header-options",
            "std" => __("Header Elements", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );


        if (function_exists('is_woocommerce')) {
            $cs_options[] = array(
                "name" => __("Cart Count", "luxury-hotel"),
                "desc" => "",
                "hint_text" => __("Enable/Disable Woocommerce Cart Count", "luxury-hotel"),
                "id" => "cs_woocommerce_switch",
                "std" => "off",
                "type" => "checkbox",
                "options" => $on_off_option
            );
        }

        $cs_options[] = array("name" => __("WPML", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set WordPress Multi Language switcher ON/OFF in header", "luxury-hotel"),
            "id" => "cs_wpml_switch",
            "std" => "on",
            "type" => "wpml",
            "options" => $on_off_option
        );

        $cs_options[] = array("name" => __("Sticky Header On/Off", "luxury-hotel"),
            "desc" => "",
            "id" => "cs_sitcky_header_switch",
            "hint_text" => __("If you enable this option , header will be fixed on top of your browser window.", "luxury-hotel"),
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option
        );
        $cs_options[] = array("name" => __("Sticky Logo", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set sticky logo Image", "luxury-hotel"),
            "id" => "cs_sticky_logo",
            "std" => get_template_directory_uri() . "/assets/images/sticky-logo.jpg",
            "type" => "upload logo");
        $cs_options[] = array("name" => __("Tagline", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Enable/Disable Tagline", "luxury-hotel"),
            "id" => "cs_tag_line",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option);

        $cs_options[] = array("name" => __("Reservation", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_post_ad_title",
            "std" => __("Reservation", "luxury-hotel"),
            "type" => "text",
        );
        $cs_options[] = array("name" => __("Reservation Button link", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_reservation_button_link",
            "std" => "#",
            "type" => "text",
        );


        $cs_options[] = array("name" => __("Header Top Strip", "luxury-hotel"),
            "id" => "tab-header-options",
            "std" => __("Header Top Strip", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );

        $cs_options[] = array("name" => __("Header Top Strip", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Enable/Disable header top strip", "luxury-hotel"),
            "id" => "cs_header_top_strip",
            "std" => "off",
            "type" => "checkbox",
            "options" => $on_off_option);
        $cs_options[] = array("name" => __("Audio play on/off", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Default Music setting", "luxury-hotel"),
            "id" => "cs_music_setting_switch",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option);

        $cs_options[] = array("name" => __("Music Url", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_music_url",
            "std" => "#",
            "type" => "upload font",
        );

        $cs_options[] = array("name" => __("Link Text", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_get_locations",
            "std" => __("Get all Locations", "luxury-hotel"),
            "type" => "text",
        );
        $cs_options[] = array("name" => __("Link Url", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_get_locations_url",
            "std" => "#",
            "type" => "text",
        );

        $cs_options[] = array("name" => __("Short Text", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Write phone no, email or address for Header top strip", "luxury-hotel"),
            "id" => "cs_header_strip_tagline_text",
            "std" => 'Call Us: 000-111-222-33 | <i class="fa fa-envelope-o"></i><a href="mailto: info@envato.com"> info@envato.com</a>',
            "type" => "textarea");
        /*
         * Weather cs theme option
         */
        $cs_options[] = array("name" => __("Header Weather section", "luxury-hotel"),
            "id" => "tab-header-options",
            "std" => __("Header Weather section", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );

        $cs_options[] = array("name" => __("Weather", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Enable/Disable Weather", "luxury-hotel"),
            "id" => "cs_weather_header_section",
            "std" => "on",
            "type" => "checkbox",
            "options" => $on_off_option);

        $cs_options[] = array("name" => __("Weather Text", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_weather_text",
            "std" => __("Local weather", "luxury-hotel"),
            "type" => "text",
            "options" => "");

        $cs_options[] = array("name" => __("Country", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_weather_country",
            "std" => "",
            "type" => "text",
        );

        $cs_options[] = array("name" => __("City", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_weather_city",
            "std" => "",
            "type" => "text",
        );

        $cs_options[] = array("name" => __("Temperature Setting", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("select the Temperature setting for header section.", "luxury-hotel"),
            "id" => "cs_weather_tem_setting",
            "std" => "c",
            "type" => "select",
            "options" => array("Celsius" => __("Celsius", "luxury-hotel"), "Fahrenheit" => __("Fahrenheit", "luxury-hotel"))
        );


        /* sub header element settings */
        $cs_options[] = array("name" => __("sub header", "luxury-hotel"),
            "id" => "tab-sub-header-options",
            "type" => "sub-heading"
        );
        /* $cs_options[] = array( "name" =>__( "Announcement!", "luxury-hotel" ),
          "id" => "sub_header_announcement",
          "std"=>__( "Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.", "luxury-hotel" ),
          "type" => "announcement"
          ); */

        $cs_options[] = array("name" => __("Default", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Sub Header settings made here will be implemented on all pages.", "luxury-hotel"),
            "id" => "cs_default_header",
            "std" => __("Breadcrumbs Sub Header", "luxury-hotel"),
            "type" => "default header",
            "options" => $deafult_sub_header
        );

        $cs_options[] = array("name" => __("Header Border Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_header_border_color",
            "std" => "",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Revolution Slider", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Please select Revolution Slider if already included in package. Otherwise buy Sliders from Code canyon But its optional", "luxury-hotel"),
            "id" => "cs_custom_slider",
            "std" => "",
            "type" => "slider code",
            "options" => ''
        );
        $cs_options[] = array("name" => __("Padding Top", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set custom padding for sub header content top area.", "luxury-hotel"),
            "id" => "cs_sh_paddingtop",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Padding Bottom", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set custom padding for sub header content bottom area.", "luxury-hotel"),
            "id" => "cs_sh_paddingbottom",
            "min" => '0',
            "max" => '200',
            "std" => "0",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Content Text Align", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("select the text Alignment for sub header content.", "luxury-hotel"),
            "id" => "cs_title_align",
            "std" => "center",
            "type" => "select",
            "options" => $navigation_style
        );
        $cs_options[] = array("name" => __("Page Title", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set page title On/Off in sub header", "luxury-hotel"),
            "id" => "cs_title_switch",
            "std" => "on",
            "type" => "checkbox"
        );

        $cs_options[] = array("name" => __("Title Heading Style", "luxury-hotel"),
            "desc" => "",
            "hint_text" => '',
            "id" => "cs_heading_style",
            "std" => "simple",
            "type" => "select",
            "options" => array('simple' => __('Simple', "luxury-hotel"), 'fancy' => __('Fancy', "luxury-hotel"))
        );

        $cs_options[] = array("name" => __("Breadcrumbs", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_breadcrumbs_switch",
            "std" => "on",
            "type" => "checkbox"
        );

        $cs_options[] = array("name" => __("Background Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_sub_header_bg_color",
            "std" => "#e9e9e9",
            "type" => "color"
        );
        $cs_options[] = array("name" => __("Text Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_sub_header_text_color",
            "std" => "#ffffff",
            "type" => "color"
        );
        $cs_options[] = array("name" => __("Border Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_sub_header_border_color",
            "std" => "",
            "type" => "color"
        );
        $cs_options[] = array("name" => __("Background", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Background Image", "luxury-hotel"),
            "id" => "cs_background_img",
            "std" => get_template_directory_uri() . "/assets/images/breadcrumb-bg.jpg",
            "type" => "upload logo"
        );

        $cs_options[] = array("name" => __("Parallax", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_parallax_bg_switch",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox"
        );

        // start footer options    

        $cs_options[] = array("name" => __("footer options", "luxury-hotel"),
            "id" => "tab-footer-options",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Footer section", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("enable/disable footer area", "luxury-hotel"),
            "id" => "cs_footer_switch",
            "std" => __("on", "luxury-hotel"),
            "type" => "checkbox"
        );
        $cs_options[] = array("name" => __("Footer Widgets", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("enable/disable footer widget area", "luxury-hotel"),
            "id" => "cs_footer_widget",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox"
        );


        $cs_options[] = array("name" => __("Social Icons", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("enable/disable Social Icons", "luxury-hotel"),
            "id" => "cs_sub_footer_social_icons",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox");
        $cs_options[] = array("name" => __("Footer Menu", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("enable/disable Footer Menu", "luxury-hotel"),
            "id" => "cs_sub_footer_menu",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Back to top", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("enable/disable Back to top", "luxury-hotel"),
            "id" => "cs_footer_back_to_top",
            "std" => __("off", "luxury-hotel"),
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Footer Logo", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Like footer logo or Credits Cards Images", "luxury-hotel"),
            "id" => "cs_footer_logo",
            "std" => get_template_directory_uri() . "/assets/images/footer-thumb.png",
            "type" => "upload logo");

        $cs_options[] = array("name" => __("Footer logo Link", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("set custom footer logo link", "luxury-hotel"),
            "id" => "cs_tripadvisor_logo_link",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Footer Background Image", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set custom Footer Background Image", "luxury-hotel"),
            "id" => "cs_footer_background_image",
            "std" => get_template_directory_uri() . "/assets/images/footer-bg.jpg",
            "type" => "upload logo");
        $cs_options[] = array("name" => __("Copyright Text", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("write your own copyright text", "luxury-hotel"),
            "id" => "cs_copy_right",
            "std" => "&copy; 2020 Hotel Name All rights reserved. Design by <a class='cscolor' href='#'>Chimp Studio</a>",
            "type" => "textarea"
        );
        $cs_options[] = array("name" => __("Footer Widgets", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set footer widgets sidebar", "luxury-hotel"),
            "id" => "cs_footer_widget_sidebar",
            "std" => "footer-widget-1",
            "type" => "select_sidebar",
            "options" => $cs_sidebar,
        );
        // End footer tab setting
        /* general colors */
        $cs_options[] = array("name" => __("general colors", "luxury-hotel"),
            "id" => "tab-general-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Theme Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Choose theme skin color", "luxury-hotel"),
            "id" => "cs_theme_color",
            "std" => "#b59759",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Background Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Choose Body Background Color", "luxury-hotel"),
            "id" => "cs_bg_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Body Text Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Choose text color", "luxury-hotel"),
            "id" => "cs_text_color",
            "std" => "#555555",
            "type" => "color"
        );

        // start top strip tab options
        $cs_options[] = array("name" => __("header colors", "luxury-hotel"),
            "id" => "tab-header-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("top strip colors", "luxury-hotel"),
            "id" => "tab-top-strip-color",
            "std" => __("Top Strip", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Background Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Top Strip background color", "luxury-hotel"),
            "id" => "cs_topstrip_bgcolor",
            "std" => "#484848",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Text Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Top Strip text color", "luxury-hotel"),
            "id" => "cs_topstrip_text_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        $cs_options[] = array(
            "name" => __("Link Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Top Strip link color", "luxury-hotel"),
            "id" => "cs_topstrip_link_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        // start header color tab options
        $cs_options[] = array("name" => __("Header Colors", "luxury-hotel"),
            "id" => "tab-header-color",
            "std" => __("Header Colors", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Background Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Header background color", "luxury-hotel"),
            "id" => "cs_header_bgcolor",
            "std" => "",
            "type" => "color"
        );
        $cs_options[] = array(
            "name" => __("Navigation Background Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Header Navigation Background color", "luxury-hotel"),
            "id" => "cs_nav_bgcolor",
            "std" => "#ffffff",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Menu Link color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Header Menu Link color", "luxury-hotel"),
            "id" => "cs_menu_color",
            "std" => "#222222",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Menu Active Link color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Header Menu Active Link color", "luxury-hotel"),
            "id" => "cs_menu_active_color",
            "std" => "#b59759 ",
            "type" => "color"
        );


        $cs_options[] = array("name" => __("Submenu Background", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Submenu Background color", "luxury-hotel"),
            "id" => "cs_submenu_bgcolor",
            "std" => "#fffff",
            "type" => "color",
        );

        $cs_options[] = array("name" => __("Submenu Link Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Submenu Link color", "luxury-hotel"),
            "id" => "cs_submenu_color",
            "std" => "#444444",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Submenu Hover Link Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Change Submenu Hover Link color", "luxury-hotel"),
            "id" => "cs_submenu_hover_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        /* footer colors */
        $cs_options[] = array("name" => __("footer colors", "luxury-hotel"),
            "id" => "tab-footer-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Footer Background Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_footerbg_color",
            "std" => "#000",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Footer Title Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_title_color",
            "std" => "#ffffff",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Footer Text Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_footer_text_color",
            "std" => "#cccccc",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("Footer Link Color", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_link_color",
            "std" => "#cccccc",
            "type" => "color"
        );



        $cs_options[] = array("name" => __("Copyright Text", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_copyright_text_color",
            "std" => "#666666",
            "type" => "color"
        );

        /* heading colors */
        $cs_options[] = array("name" => __("heading colors", "luxury-hotel"),
            "id" => "tab-heading-color",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("heading h1", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h1_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("heading h2", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h2_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("heading h3", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h3_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("heading h4", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h4_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("heading h5", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h5_color",
            "std" => "#333333",
            "type" => "color"
        );

        $cs_options[] = array("name" => __("heading h6", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_h6_color",
            "std" => "#333333",
            "type" => "color"
        );

        /* start custom font family */
        $cs_options[] = array("name" => __("Custom Font", "luxury-hotel"),
            "id" => "tab-custom-font",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => __("Custom Font .woff", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Custom font for your site upload .woff format file.", "luxury-hotel"),
            "id" => "cs_custom_font_woff",
            "std" => "",
            "type" => "upload font"
        );

        $cs_options[] = array("name" => __("Custom Font .ttf", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Custom font for your site upload .ttf format file.", "luxury-hotel"),
            "id" => "cs_custom_font_ttf",
            "std" => "",
            "type" => "upload font"
        );

        $cs_options[] = array("name" => __("Custom Font .svg", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Custom font for your site upload .svg format file.", "luxury-hotel"),
            "id" => "cs_custom_font_svg",
            "std" => "",
            "type" => "upload font"
        );

        $cs_options[] = array("name" => __("Custom Font .eot", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Custom font for your site upload .eot format file.", "luxury-hotel"),
            "id" => "cs_custom_font_eot",
            "std" => "",
            "type" => "upload font"
        );

        /* start font family */
        $cs_options[] = array("name" => __("font family", "luxury-hotel"),
            "id" => "tab-font-family",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Content Font", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set fonts for Body text", "luxury-hotel"),
            "id" => "cs_content_font",
            "std" => "Source Sans Pro",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => __("Content Font Attribute", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set Font Attribute", "luxury-hotel"),
            "id" => "cs_content_font_att",
            "std" => "regular",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        $cs_options[] = array("name" => __("Main Menu Font", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set font for main Menu. It will be applied to sub menu as well", "luxury-hotel"),
            "id" => "cs_mainmenu_font",
            "std" => "Droid Serif,serif",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => __("Main Menu Font Attribute", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set Font Attribute", "luxury-hotel"),
            "id" => "cs_mainmenu_font_att",
            "std" => "regular",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        $cs_options[] = array("name" => __("Headings Font", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Select font for Headings. It will apply on all posts and pages headings", "luxury-hotel"),
            "id" => "cs_heading_font",
            "std" => "Droid Serif",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => __("Headings Font Attribute", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set Font Attribute", "luxury-hotel"),
            "id" => "cs_heading_font_att",
            "std" => "regular",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        $cs_options[] = array("name" => __("Widget Headings Font", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set font for Widget Headings", "luxury-hotel"),
            "id" => "cs_widget_heading_font",
            "std" => "Droid Serif",
            "type" => "gfont_select",
            "options" => $g_fonts
        );
        $cs_options[] = array("name" => __("Widget Headings Font Attribute", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set Font Attribute", "luxury-hotel"),
            "id" => "cs_widget_heading_font_att",
            "std" => "regular",
            "type" => "gfont_att_select",
            "options" => $g_fonts_atts
        );
        /* start font size */
        $cs_options[] = array("name" => __("Font size", "luxury-hotel"),
            "id" => "tab-font-size",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => __("Content", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_content_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Main Menu", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_mainmenu_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Heading 1", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_1_size",
            "min" => '6',
            "max" => '50',
            "std" => "24",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Heading 2", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_2_size",
            "min" => '6',
            "max" => '50',
            "std" => "18",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Heading 3", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_3_size",
            "min" => '6',
            "max" => '50',
            "std" => "16",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Heading 4", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_4_size",
            "min" => '6',
            "max" => '50',
            "std" => "16",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Heading 5", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_5_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Heading 6", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_heading_6_size",
            "min" => '6',
            "max" => '50',
            "std" => "14",
            "type" => "range"
        );

        $cs_options[] = array("name" => __("Widget Heading", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_widget_heading_size",
            "min" => '6',
            "max" => '50',
            "std" => "15",
            "type" => "range"
        );
        $cs_options[] = array("name" => __("Section Heading", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_section_heading_size",
            "min" => '6',
            "max" => '50',
            "std" => "24",
            "type" => "range"
        );
        /* social icons setting */
        $cs_options[] = array("name" => __("social icons", "luxury-hotel"),
            "id" => "tab-social-setting",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Social Network", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_social_network",
            "std" => "",
            "type" => "networks",
            "options" => $social_network
        );

        /* social Network setting */
        $cs_options[] = array("name" => __("social Sharing", "luxury-hotel"),
            "id" => "tab-social-network",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Facebook", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_facebook_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Twitter", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_twitter_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Google Plus", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_google_plus_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Pinterest", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_pintrest_share",
            "std" => "on",
            "type" => "checkbox"
        );

        $cs_options[] = array("name" => __("Tumblr", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_tumblr_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Dribbble", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_dribbble_share",
            "std" => "off",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Instagram", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_instagram_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("StumbleUpon", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_stumbleupon_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("youtube", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_youtube_share",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("share more", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_share_share",
            "std" => "off",
            "type" => "checkbox");

        /* custom code setting */
        $cs_options[] = array("name" => __("custom code", "luxury-hotel"),
            "id" => "tab-custom-code",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Custom Css", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("write you custom css without style tag", "luxury-hotel"),
            "id" => "cs_custom_css",
            "std" => "",
            "type" => "textarea"
        );

        $cs_options[] = array("name" => __("Custom JavaScript", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("write you custom js without script tag", "luxury-hotel"),
            "id" => "cs_custom_js",
            "std" => "",
            "type" => "textarea"
        );


        /* sidebar tab */
        $cs_options[] = array("name" => __("sidebar", "luxury-hotel"),
            "id" => "tab-sidebar",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Sidebar", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Select a sidebar from the list already given. (Nine pre-made sidebars are given)", "luxury-hotel"),
            "id" => "cs_sidebar",
            "std" => $sidebar,
            "type" => "sidebar",
            "options" => $sidebar
        );

        $cs_options[] = array("name" => __("post layout", "luxury-hotel"),
            "id" => "cs_non_metapost_layout",
            "std" => __("single post layout", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Single Post Layout", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Use this option to set default layout. It will be applied to all posts", "luxury-hotel"),
            "id" => "cs_single_post_layout",
            "std" => __("sidebar_right", "luxury-hotel"),
            "type" => "layout",
            "options" => array(
                "no_sidebar" => __("full width", "luxury-hotel"),
                "sidebar_left" => __("sidebar left", "luxury-hotel"),
                "sidebar_right" => __("sidebar right", "luxury-hotel"),
            )
        );
		array_push($cs_sidebar['sidebar'], 'sidebar-1');
        $cs_options[] = array("name" => __("Single Layout Sidebar", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Select Single Post Layout of your choice for sidebar layout. You cannot select it for full width layout", "luxury-hotel"),
            "id" => "cs_single_layout_sidebar",
            "std" => __("Default Pages", "luxury-hotel"),
            "type" => "select_sidebar",
            "options" => $cs_sidebar
        );

        $cs_options[] = array("name" => __("default pages", "luxury-hotel"),
            "id" => "default_pages",
            "std" => __("default pages", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Default Pages Layout", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set Sidebar for all pages like Search, Author Archive, Category Archive etc", "luxury-hotel"),
            "id" => "cs_default_page_layout",
            "std" => __("sidebar_right", "luxury-hotel"),
            "type" => "layout",
            "options" => array(
                "no_sidebar" => __("full width", "luxury-hotel"),
                "sidebar_left" => __("sidebar left", "luxury-hotel"),
                "sidebar_right" => __("sidebar right", "luxury-hotel"),
            )
        );
		
        $cs_options[] = array("name" => __("Sidebar", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Select pre-made sidebars for default pages on sidebar layout. Full width layout cannot have sidebars", "luxury-hotel"),
            "id" => "cs_default_layout_sidebar",
            "std" => "sidebar-1",
            "type" => "select_sidebar",
            "options" => $cs_sidebar
        );
        $cs_options[] = array("name" => __("Excerpt", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Set excerpt length/limit from here. It controls text limit for post's content", "luxury-hotel"),
            "id" => "cs_excerpt_length",
            "std" => "255",
            "type" => "text"
        );

        /* SEO */
        $cs_options[] = array("name" => __("Seo", "luxury-hotel"),
            "id" => "tab-seo",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => '<b>' . __("Attention for External SEO Plugins!", "luxury-hotel") . '</b>',
            "id" => "header_postion_attention",
            "std" => '<strong>' . __("  If you are using any external SEO plugin, Turn OFF these options. ", "luxury-hotel") . '</strong>',
            "type" => "announcement"
        );

        $cs_options[] = array("name" => __("Built-in Seo fields", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Turn Seo options ON/OFF", "luxury-hotel"),
            "id" => "cs_builtin_seo_fields",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Meta Description", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("HTML attributes that explain the contents of web pages commonly used on search engine result pages (SERPs) for pages snippets", "luxury-hotel"),
            "id" => "cs_meta_description",
            "std" => "",
            "type" => "text"
        );

        $cs_options[] = array("name" => __("Meta Keywords", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Attributes of meta tags, a list of comma-separated words included in the HTML of a Web page that describe the topic of that page", "luxury-hotel"),
            "id" => "cs_meta_keywords",
            "std" => "",
            "type" => "text"
        );


        /* maintenance mode */
        $cs_options[] = array("name" => __("Maintenance Mode", "luxury-hotel"),
            "fontawesome" => 'icon-tasks',
            "id" => "tab-maintenace-mode",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Maintenance Mode", "luxury-hotel"),
            "id" => "tab-maintenace-mode",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Maintenace Page", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Users will see Maintenance page & logged in Admin will see normal site.", "luxury-hotel"),
            "id" => "cs_maintenance_page_switch",
            "std" => "off",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Show Logo", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Show/Hide logo on Maintenance. Logo can be uploaded from General > Header in CS Theme options.", "luxury-hotel"),
            "id" => "cs_maintenance_logo_switch",
            "std" => "on",
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Maintenance Page Logo", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Upload your maintenance page logo in .png .jpg .gif formats only.", "luxury-hotel"),
            "id" => "cs_maintenance_custom_logo",
            "std" => get_template_directory_uri() . "/assets/images/img2.png",
            "type" => "upload logo"
        );


        $cs_options[] = array("name" => __("Maintenance Text", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Text for Maintenance page. Insert some basic HTML or use shortcodes here.", "luxury-hotel"),
            "id" => "cs_maintenance_text",
            "std" => "<h1>Sorry, We are down for maintenance </h1><p>We're currently under maintenance, if all goas as planned we'll be back in</p>",
            "type" => "textarea"
        );

        $cs_options[] = array("name" => __("Launch Date", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Estimated date for completion of site on Maintenance page.", "luxury-hotel"),
            "id" => "cs_launch_date",
            "std" => gmdate("dd/mm/yy"),
            "type" => "text"
        );


        /* api options tab */
        $cs_options[] = array("name" => __("Api settings", "luxury-hotel"),
            "fontawesome" => 'icon-chain',
            "id" => "tab-api-options",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        //Start Twitter Api    
        $cs_options[] = array("name" => __("All api settings", "luxury-hotel"),
            "id" => "tab-api-options",
            "type" => "sub-heading"
        );

        $cs_options[] = array("name" => __("Attention for API Settings!", "luxury-hotel"),
            "id" => "header_postion_attention",
            "std" => __("API Settings allows admin of the site to show their activity on site semi-automatically. Set your social account API once, it will be update your social activity automatically on your site.", "luxury-hotel"),
            "type" => "announcement"
        );

        //start mailChimp api
        $cs_options[] = array("name" => __("Mail Chimp", "luxury-hotel"),
            "id" => "mailchimp",
            "std" => __("Mail Chimp", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Mail Chimp Key", "luxury-hotel"),
            "desc" => __("Enter a valid Mail Chimp API key here to get started. Once you've done that, you can use the Mail Chimp Widget from the Widgets menu. You will need to have at least Mail Chimp list set up before the using the widget. You can get your mail chimp activation key", "luxury-hotel"),
            "hint_text" => __("Get your mailchimp key by <a href='https://login.mailchimp.com/' target='_blank'>Clicking Here </a>", "luxury-hotel"),
            "id" => "cs_mailchimp_key",
            "std" => "90f86a57314446ddbe87c57acc930ce8-us2",
            "type" => "text"
        );

        $cs_options[] = array("name" => __("Mail Chimp List", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_mailchimp_list",
            "std" => __("on", "luxury-hotel"),
            "type" => "mailchimp",
            "options" => $mail_chimp_list
        );

        $cs_options[] = array("name" => __("Flickr API Setting", "luxury-hotel"),
            "id" => "flickr_api_setting",
            "std" => __("Flickr API Setting", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Flickr key", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "flickr_key",
            "std" => "",
            "type" => "text");
        $cs_options[] = array("name" => __("Flickr secret", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "flickr_secret",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Google API Setting", "luxury-hotel"),
            "id" => "google_api_setting",
            "std" => __("Google API Setting", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Google Api key", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "google_api_key",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Twitter", "luxury-hotel"),
            "id" => "Twitter",
            "std" => __("Twitter", "luxury-hotel"),
            "type" => "section",
            "options" => ""
        );
        $cs_options[] = array("name" => __("Show Twitter", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Turn Twitter option ON/OFF", "luxury-hotel"),
            "id" => "cs_twitter_api_switch",
            "std" => __("on", "luxury-hotel"),
            "type" => "checkbox");

        $cs_options[] = array("name" => __("Cache Time Limit", 'luxury-hotel'),
            "desc" => "",
            "hint_text" => "Please enter the time limit in minutes for refresh cache",
            "id" => "cs_cache_limit_time",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Number of tweet", 'luxury-hotel'),
            "desc" => "",
            "hint_text" => "Please enter number of tweet that you get from twitter for chache file.",
            "id" => "cs_tweet_num_post",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Date Time Formate", 'luxury-hotel'),
            "desc" => "",
            "hint_text" => __("Select date time formate for every tweet.", 'luxury-hotel'),
            "id" => "cs_twitter_datetime_formate",
            "std" => "",
            "type" => "select_values",
            "options" => array(
                'default' => __('Displays November 06 2012', 'luxury-hotel'),
                'eng_suff' => __('Displays 6th November', 'luxury-hotel'),
                'ddmm' => __('Displays 06 Nov', 'luxury-hotel'),
                'ddmmyy' => __('Displays 06 Nov 2012', 'luxury-hotel'),
                'full_date' => __('Displays Tues 06 Nov 2012', 'luxury-hotel'),
                'time_since' => __('Displays in hours, minutes etc', 'luxury-hotel'),
            )
        );
        $cs_options[] = array("name" => __("Consumer Key", "luxury-hotel"),
            "desc" => "",
            "hint_text" => "",
            "id" => "cs_consumer_key",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Consumer Secret", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Insert consumer key. To get your account key, <a href='https://dev.twitter.com/' target='_blank'>Click Here </a>", "luxury-hotel"),
            "id" => "cs_consumer_secret",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Access Token", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Insert Twitter Access Token for permissions. When you create your Twitter App, you get this Token", "luxury-hotel"),
            "id" => "cs_access_token",
            "std" => "",
            "type" => "text");

        $cs_options[] = array("name" => __("Access Token Secret", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("Insert Twitter Access Token Secret here. When you create your Twitter App, you get this Token", "luxury-hotel"),
            "id" => "cs_access_token_secret",
            "std" => "",
            "type" => "text");
        //end Twitter Api
        #import and export theme options tab
        $cs_options[] = array("name" => __("import & export", "luxury-hotel"),
            "fontawesome" => 'icon-database',
            "id" => "tab-import-export-options",
            "std" => "",
            "type" => "main-heading",
            "options" => ""
        );
        $cs_options[] = array("name" => __("import & export", "luxury-hotel"),
            "id" => "tab-import-export-options",
            "type" => "sub-heading"
        );
        $cs_options[] = array("name" => __("Export", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("If you want to make changes in your site or want to preserve your current settings, Export them code by saving this code with you. You can restore your settings by pasting this code in Import section below", "luxury-hotel"),
            "id" => "cs_export_theme_options",
            "std" => "",
            "type" => "export"
        );

        $cs_options[] = array("name" => __("Import", "luxury-hotel"),
            "desc" => "",
            "hint_text" => __("To Import your settings, paste the code that you got in above area and saved it with you", "luxury-hotel"),
            "id" => "cs_import_theme_options",
            "std" => "",
            "type" => "import"
        );

	
        update_option('cs_theme_data', $cs_options);
    }

}

/**
 *
 *
 * Header Colors Setting
 */
function cs_header_setting() {
    global $cs_header_colors;
    $cs_header_colors = array();
    $cs_header_colors['header_colors'] = array(
        'header_1' => array(
            'color' => array(
                'cs_topstrip_bgcolor' => '#00799F',
                'cs_topstrip_text_color' => '#ffffff',
                'cs_topstrip_link_color' => '#ffffff',
                'cs_header_bgcolor' => '',
                'cs_nav_bgcolor' => '#00799F',
                'cs_menu_color' => '#ffffff',
                'cs_menu_active_color' => '#ffffff',
                'cs_submenu_bgcolor' => '#ffffff',
                'cs_submenu_color' => '#333333',
                'cs_submenu_hover_color' => '#00799F',
            ),
            'logo' => array(
                'cs_logo_with' => '210',
                'cs_logo_height' => '130',
                'cs_logo_margintb' => '0',
                'cs_logo_marginlr' => '0',
            )
        ),
    );
    return $cs_header_colors;
}
