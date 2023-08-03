<?php

/*
  Plugin Name: WP Hotel Booking
  Plugin URI: http://themeforest.net/user/Chimpstudio/
  Description: Hotel Booking Management
  Version: 1.10.3
  Author: ChimpStudio
  Author URI: http://themeforest.net/user/Chimpstudio/
  License: GPL2
  Copyright 2015  chimpgroup  (email : info@chimpstudio.co.uk)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, United Kingdom
 */

if (!class_exists('wp_hotel_booking')) {

    class wp_hotel_booking {

        public $plugin_url;

        //=====================================================================
        // Construct
        //=====================================================================
        public function __construct() {
            
            add_action('init', array($this, 'load_plugin_textdomain'), 0);
            require_once('settings/cs-global-variables.php');
            global $post, $wp_query, $cs_plugin_options;

            $this->plugin_url = plugin_dir_url(__FILE__);
            $this->plugin_dir = plugin_dir_path(__FILE__);

            require_once('include/wpml_functions.php');
            require_once('templates/class_reservation_templates.php');
            require_once('templates/templates_functions.php');

            require_once('payments/class-payments.php');
            require_once('payments/class-payments.php');
            require_once('payments/config.php');

            require_once('post-types/booking.php');
            require_once('include/booking_meta.php');
            require_once('include/customers.php');

            require_once('include/hotels_meta.php');

            require_once('post-types/reviews.php');
            require_once('include/reviews_meta.php');

            require_once('include/transactions_meta.php');

            require_once('post-types/rooms.php');
            require_once('include/rooms_meta.php');

            require_once('post-types/rooms_capacity.php');
            require_once('include/rooms_capacity_meta.php');

            require_once('rooms/functions.php');
            require_once('rooms/rooms_element.php');
            require_once('rooms/rooms_template.php');

            require_once('widgets/room_search.php');

            require_once('include/functions.php');
            require_once('include/form_fields.php');
            require_once('helpers/notifications/notification-helper.php');
            require_once('helpers/emails/email-helper.php');
            require_once('include/block_meta.php');
            require_once('include/pricing_meta.php');
            require_once('settings/plugin_settings.php');
            require_once('settings/includes/plugin_options.php');
            require_once('settings/includes/plugin_options_fields.php');
            require_once('settings/includes/plugin_options_array.php');
            require_once('settings/includes/plugin_options_functions.php');

            require_once('shortcodes/admin/room_search.php');
            require_once('shortcodes/room_search.php');

            add_filter('template_include', array(&$this, 'cs_single_template'));
            add_action('wp_enqueue_scripts', array(&$this, 'cs_defaultfiles_plugin_enqueue'));
            add_action('admin_enqueue_scripts', array(&$this, 'cs_defaultfiles_plugin_enqueue'));
            add_action('init', array($this, 'cs_add_custom_role'));
            add_action('init', array($this, 'cs_register_sidebar'));
            add_action('admin_menu', array($this, 'edit_admin_menus'));
            add_filter('custom_menu_order', array($this, 'rename_admin_menus'));
        }

        public function cs_register_sidebar() {
            register_sidebar(array(
                'name' => 'Room Detail Sidebar',
                'id' => 'room-detail-sidebar',
                'description' => __('This Widget Show the Content in Room Detail.', 'booking'),
                'before_widget' => '<div class="widget col-md-3 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-section-title"><h2>',
                'after_title' => '</h2></div>'
            ));
        }

        /**
         *
         * @Menu Rename
         */
        public function edit_admin_menus() {
            global $menu, $submenu;

            foreach ($menu as $key => $menu_item) {
                if ($menu_item[2] == 'edit.php?post_type=rooms') {
                    $menu[$key][0] = __('Hotel Booking', 'booking');
                }
            }
        }

        /**
         *
         * @Sub Menu Rename
         */
        public function rename_admin_menus() {
            global $menu, $submenu;
            $arr = array();

            $cs_rooms = array('Rooms', 'edit_posts', 'edit.php?post_type=rooms', 'Rooms');

            $arr[] = isset($submenu['edit.php?post_type=rooms'][15]) ? $submenu['edit.php?post_type=rooms'][15] : '';
            $arr[] = $cs_rooms;
            $arr[] = isset($submenu['edit.php?post_type=rooms'][13]) ? $submenu['edit.php?post_type=rooms'][13] : '';
            $arr[] = isset($submenu['edit.php?post_type=rooms'][17]) ? $submenu['edit.php?post_type=rooms'][17] : '';
            $arr[] = isset($submenu['edit.php?post_type=rooms'][12]) ? $submenu['edit.php?post_type=rooms'][12] : '';
            $arr[] = isset($submenu['edit.php?post_type=rooms'][11]) ? $submenu['edit.php?post_type=rooms'][11] : '';
            $arr[] = isset($submenu['edit.php?post_type=rooms'][14]) ? $submenu['edit.php?post_type=rooms'][14] : '';
            $arr[] = isset($submenu['edit.php?post_type=rooms'][18]) ? $submenu['edit.php?post_type=rooms'][18] : '';
            $arr[] = isset($submenu['edit.php?post_type=rooms'][19]) ? $submenu['edit.php?post_type=rooms'][19] : '';

            $submenu['edit.php?post_type=rooms'] = $arr;

            return $submenu;
        }

        /**
         *
         * @Text Domain
         */
        public function load_plugin_textdomain() {
            global $cs_plugin_options;
            
            if (function_exists('icl_object_id')) {

                global $sitepress, $wp_filesystem;

                require_once ABSPATH . '/wp-admin/includes/file.php';

                $backup_url = '';

                if (false === ($creds = request_filesystem_credentials($backup_url, '', false, false, array()) )) {

                    return true;
                }

                if (!WP_Filesystem($creds)) {
                    request_filesystem_credentials($backup_url, '', true, false, array());
                    return true;
                }

                $cs_languages_dir = wp_hotel_booking::plugin_dir() . '/languages/';

                $cs_all_langs = $wp_filesystem->dirlist($cs_languages_dir);

                $cs_mo_files = array();
                if (is_array($cs_all_langs) && sizeof($cs_all_langs) > 0) {

                    foreach ($cs_all_langs as $file_key => $file_val) {

                        if (isset($file_val['name'])) {

                            $cs_file_name = $file_val['name'];

                            $cs_ext = pathinfo($cs_file_name, PATHINFO_EXTENSION);

                            if ($cs_ext == 'mo') {
                                $cs_mo_files[] = $cs_file_name;
                            }
                        }
                    }
                }

                $cs_active_langs = $sitepress->get_current_language();
                foreach ($cs_mo_files as $mo_file) {
                    if (strpos($mo_file, $cs_active_langs.'.mo') !== false) {
                        $cs_lang_mo_file = $mo_file;
                    }
                }
            }
            
            $languageFile = isset($cs_plugin_options['cs_language_file']) ? $cs_plugin_options['cs_language_file'] : '';
            
            $locale = apply_filters('plugin_locale', get_locale(), 'booking');
            $dir = trailingslashit(WP_LANG_DIR);
            
            if (isset($cs_lang_mo_file) && $cs_lang_mo_file != '') {
                load_textdomain('booking', plugin_dir_path(__FILE__) . "languages/" . $cs_lang_mo_file);
            } else if (isset($languageFile) && $languageFile != '') {
                load_textdomain('booking', plugin_dir_path(__FILE__) . "languages/" . $cs_plugin_options['cs_language_file']);
            }
        }

        /**
         *
         * @Add Custom Roles
         */
        public function cs_add_custom_role() {
            add_role('guest', 'Guest', array(
                'read' => true, // True allows that capability
                'edit_posts' => true,
                'delete_posts' => false, // Use false to explicitly deny
            ));
        }

        /**
         *
         * @PLugin URl
         */
        public static function plugin_url() {
            return plugin_dir_url(__FILE__);
        }

        /**
         *
         * @Plugin Images Path
         */
        public static function plugin_img_url() {
            return plugin_dir_url(__FILE__);
        }

        /**
         *
         * @Plugin URL
         */
        public static function plugin_dir() {
            return plugin_dir_path(__FILE__);
        }

        /**
         *
         * @Activate the plugin
         */
        public static function activate() {
            global $cs_settings_init;
            add_option('cs_booking_plugin_activation', 'installed');
            add_option('cs_booking', '1');
            add_action('init', 'cs_plugin_activation_data');


            //Activation Data
            if (!get_option('cs_plugin_options')) {
                $demo_plugin_data = '';
                $demo_hotels_data = '';
                if (isset($cs_settings_init) && $cs_settings_init <> '') {
                    // Plugin Options
                    $cs_settings = $cs_settings_init['plugin_options'];
                    $plugin_settings = unserialize(base64_decode($cs_settings));
                    $demo_plugin_data = $plugin_settings;

                    //Hotel Options
                    $cs_hotels = $cs_settings_init['hotels'];
                    $plugin_hotels = unserialize(base64_decode($cs_hotels));
                    $demo_hotels_data = $plugin_hotels;
                }

                update_option("cs_plugin_options", $demo_plugin_data);
                update_option("cs_hotel_options", $demo_hotels_data);
            }
        }

        /**
         *
         * @Deactivate the plugin
         */
        static function deactivate() {
            delete_option('cs_plugin_activation_data');
            delete_option('cs_booking', false);
        }

        /**
         *
         * @ Include Template
         */
        public function cs_single_template($single_template) {
            global $post;
            $single_path = dirname(__FILE__);
            if (get_post_type() == 'rooms') {
                if (is_single()) {
                    $single_template = plugin_dir_path(__FILE__) . '/rooms/single-rooms.php';
                }
            }
            return $single_template;
        }

        /**
         *
         * @ Include Default Scripts and styles
         */
        public function cs_defaultfiles_plugin_enqueue() {
            wp_enqueue_media();
            wp_enqueue_script('my-upload', '', array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));
            wp_enqueue_script('booking_functions_js', plugins_url('/assets/scripts/booking_functions.js', __FILE__), '', '', true);
            wp_enqueue_script('booking_exra_js', plugins_url('/assets/scripts/extra_functions.js', __FILE__), '', '', true);
            wp_enqueue_style('cs_fontawesome_styles', plugins_url('/assets/css/font-awesome.min.css', __FILE__));
            //wp_enqueue_script('cs_bootstrap_js', plugins_url( '/assets/scripts/bootstrap.min.js' , __FILE__ ), '', '', true );
            //wp_enqueue_style('iconmoon_css', plugins_url( '/assets/css/icons/css/iconmoon.css' , __FILE__ ));

            if (is_admin()) {
                wp_enqueue_style('booking_admin_styles', plugins_url('/assets/css/admin_style.css', __FILE__));
            }
            wp_enqueue_style('cs_datepicker_css', plugins_url('/assets/css/jquery_datetimepicker.css', __FILE__));
            wp_enqueue_script('cs_datepicker_js', plugins_url('/assets/scripts/jquery_datetimepicker.js', __FILE__), '', '', true);
            
            if (!is_admin()) {
                wp_enqueue_style('bootstrap_css', plugins_url('/assets/css/bootstrap.min.css', __FILE__));
                //wp_enqueue_style('cs_booking_styles', plugins_url( '/assets/css/booking_styles.css' , __FILE__ ));
            }

            if (is_admin()) {
                wp_enqueue_script('fonticonpicker_js', plugins_url('/assets/icomoon/js/jquery.fonticonpicker.min.js', __FILE__), '', '', true);
                wp_enqueue_style('fonticonpicker_css', plugins_url('/assets/icomoon/css/jquery.fonticonpicker.min.css', __FILE__));
                wp_enqueue_style('iconmoon_css', plugins_url('/assets/icomoon/css/iconmoon.css', __FILE__));
                wp_enqueue_style('fonticonpicker_bootstrap_css', plugins_url('/assets/icomoon/theme/bootstrap-theme/jquery.fonticonpicker.bootstrap.css', __FILE__));
            }
        }

        /**
         *
         * @Rating Styles and Scripts
         */
        public static function cs_enqueue_rating_style_script() {
            wp_enqueue_script('jquery.rating_js', plugins_url('/assets/scripts/jRating.jquery.js', __FILE__), '', '', true);
            wp_enqueue_style('jquery.rating_css', plugins_url('/assets/css/jRating.jquery.css', __FILE__));
        }

        /**
         *
         * @Scroll Scripts
         */
        public static function cs_enqueue_scroll_script() {
            wp_enqueue_script('jquery.scroll_js', plugins_url('/assets/scripts/jquery.mCustomScrollbar.concat.min.js', __FILE__), '', '', true);
            wp_enqueue_style('jquery.scroll_css', plugins_url('/assets/css/jRating.jquery.css', __FILE__));
        }

        /**
         *
         * @Rating Styles and Scripts
         */
        public static function cs_enqueue_datepicker_script() {
            wp_enqueue_script('cs_bootstrap_datepicker_js', plugins_url('/assets/scripts/bootstrap-datepicker.js', __FILE__), '', '', true);
        }

        /**
         *
         * @Date Range Style Scripts
         */
        public static function cs_date_range_style_script() {
            wp_enqueue_script('moment.min_js', plugins_url('/assets/scripts/moment.min.js', __FILE__), '', '', true);
            wp_enqueue_script('jquery.daterangepicker', plugins_url('/assets/scripts/jquery.daterangepicker.js', __FILE__), '', '', true);
            wp_enqueue_style('cs_daterangepicker_css', plugins_url('/assets/css/daterangepicker.css', __FILE__));
        }

        /**
         *
         * @Data Table Style Scripts
         */
        public static function cs_data_table_style_script() {
            wp_enqueue_script('jquery.datatable', plugins_url('/assets/scripts/jquery.data_tables.js', __FILE__), '', '', true);
            wp_enqueue_style('datatable_css', plugins_url('/assets/css/jquery.data_tables.css', __FILE__));
        }

        /**
         *
         * @Data Table Style Scripts
         */
        public static function cs_owl_carousel_script() {
            wp_enqueue_script('pg_jquery_carousel', plugins_url('/assets/scripts/owl.carousel.min.js', __FILE__), '', '', true);
            wp_enqueue_style('pg_carousel', plugins_url('/assets/css/owl.carousel.css', __FILE__));
        }

        /**
         *
         * @Pretty Photo
         */
        public static function cs_prettyphoto_script() {
            wp_enqueue_script('pg_jquery_prettyphoto', plugins_url('/assets/scripts/jquery.prettyphoto.js', __FILE__), '', '', true);
            wp_enqueue_style('pg_prettyphoto', plugins_url('/assets/css/prettyphoto.css', __FILE__));
            wp_enqueue_script('lightbox_js', plugins_url('/assets/scripts/lightbox.js', __FILE__), '', '', true);
        }

    }

}

/**
 *
 * @Create Object of class To Activate Plugin
 */
if (class_exists('wp_hotel_booking')) {
    $cs_booking = new wp_hotel_booking();
    register_activation_hook(__FILE__, array('wp_hotel_booking', 'activate'));
    register_deactivation_hook(__FILE__, array('wp_hotel_booking', 'deactivate'));
}