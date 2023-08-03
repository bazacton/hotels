<?php
/*
Plugin Name: CS Framework
Plugin URI: http://themeforest.net/user/Chimpstudio/
Description: Custom Post Types Management
Version: 1.1
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

if (!class_exists('cs_framework')) {
    class cs_framework
    {
        public $plugin_url;

        //=====================================================================
        // Construct
        //=====================================================================
        public function __construct()
        {
            global $post, $wp_query, $cs_frame_options;

            $cs_frame_options = get_option('cs_frame_options');

            add_filter('template_include', array(&$this, 'cs_single_template'));
            add_action('wp_enqueue_scripts', array(&$this, 'cs_plugin_files_enqueue'));
            add_action('admin_enqueue_scripts', array(&$this, 'cs_plugin_files_enqueue'));
            add_action('init', array($this, 'load_plugin_textdomain'));

            require_once('include/meta-boxes/form_meta_fields.php');

            require_once('include/post-types/events.php');
            require_once('include/meta-boxes/event_meta.php');

            // Events
            require_once('templates/events/event_element.php');
            require_once('templates/events/event_functions.php');

        }

        /**
         *
         * @Text Domain
         */
        public function load_plugin_textdomain()
        {

            $cs_theme_options = get_option('cs_theme_options');
            $languageFile = isset($cs_theme_options['cs_language_file']) ? $cs_theme_options['cs_language_file'] : '';

            $locale = apply_filters('plugin_locale', get_locale(), 'cs_frame');
            $dir = trailingslashit(WP_LANG_DIR);

            if (isset($languageFile) && $languageFile != '') {
                load_textdomain('cs_frame', plugin_dir_path(__FILE__) . "languages/" . $cs_theme_options['cs_language_file']);
            } else {
                //load_textdomain( 'cs_frame', $dir . 'cs_frame-en_US.mo' );
            }
            include_once plugin_dir_path(__FILE__).'cs-twitter/display-tweets.php';
        }

        /**
         *
         * @PLugin URl
         */
        public static function plugin_url()
        {
            return plugin_dir_url(__FILE__);
        }

        /**
         *
         * @Plugin Path
         */
        public static function plugin_dir()
        {
            return plugin_dir_path(__FILE__);
        }

        /**
         *
         * @Activate the plugin
         */
        public static function activate()
        {
            add_option('cs_frame_plugin_activation', 'installed');
            add_option('cs_frame', '1');
            add_action('init', 'cs_activation_data');
        }

        /**
         *
         * @Deactivate the plugin
         */
        static function deactivate()
        {
            delete_option('cs_frame_plugin_activation');
            delete_option('cs_frame', false);
        }

        /**
         *
         * @ Include Template
         */
        public function cs_single_template($single_template)
        {
            global $post;
            if (is_single()) {

                if (get_post_type() == 'events') {
                    $single_template = plugin_dir_path(__FILE__) . '/templates/events/single-events.php';
                }
            }
            return $single_template;
        }

        /**
         *
         * @ Include Default Scripts and styles
         */
        public function cs_plugin_files_enqueue()
        {
            wp_enqueue_media();
            wp_enqueue_script('my-upload', '', array('jquery', 'media-upload', 'thickbox', 'jquery-ui-droppable', 'jquery-ui-datepicker', 'jquery-ui-slider', 'wp-color-picker'));

            if (!is_admin()) {
                wp_enqueue_script('cs_frame_functions_js', plugins_url('/assets/scripts/cs_frame_functions.js', __FILE__), '', '', true);
            }
        }

        public static function cs_enqueue_timepicker_script()
        {

            if (is_admin()) {
                wp_enqueue_script('cs_datetimepicker_js', plugins_url('/assets/scripts/jquery_datetimepicker.js', __FILE__), '', '', true);
                wp_enqueue_style('cs_datetimepicker_css', plugins_url('/assets/css/jquery_datetimepicker.css', __FILE__));
            }
        }

    }
}

/**
 *
 * @Create Object of class To Activate Plugin
 */
if (class_exists('cs_framework')) {
    $cs_frame = new cs_framework();
    register_activation_hook(__FILE__, array('cs_framework', 'activate'));
    register_deactivation_hook(__FILE__, array('cs_framework', 'deactivate'));
}


/* add action widget register */
if (!function_exists('cs_widget_register')) {
    function cs_widget_register($name)
    {
        add_action('widgets_init', function () use ($name) {
            return register_widget($name);
        });
    }
}

/* add action widget unregister */
if (!function_exists('cs_widget_unregister')) {
    function cs_widget_unregister($name)
    {
        add_action('widgets_init', function () use ($name) {
            return unregister_widget($name);
        });
    }
}

/* add action for meta boxes */
if (!function_exists('cs_meta_boxes')) {
    function cs_meta_boxes($name)
    {
        add_action('add_meta_boxes', $name);
    }
}

/* add meta boxe */
if (!function_exists('cs_meta_box')) {
    function cs_meta_box($id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null)
    {
        add_meta_box($id, __($title, 'framework'), $callback, $screen, $context, $priority, $callback_args);
    }
}

/* send mail */
if (!function_exists('cs_mail')) {
    function cs_mail($to, $subject, $message, $headers = '', $attachments = array())
    {
        return wp_mail($to, $subject, $message, $headers, $attachments);
    }
}

/* base 64 encode */
if (!function_exists('base_64_encode')) {
    function base_64_encode($string = '')
    {
        return base64_encode($string);
    }
}

/* base 64 decode */
if (!function_exists('base_64_decode')) {
    function base_64_decode($string = '')
    {
        return base64_decode($string);
    }
}

/* register taxonomy */
if (!function_exists('cs_register_taxonomy')) {
    function cs_register_taxonomy($taxonomy, $object_type, $args)
    {
        register_taxonomy($taxonomy, $object_type, $args);
    }
}

/* register post type */
if (!function_exists('cs_register_post_type')) {
    function cs_register_post_type($post_type, $args = array())
    {
        register_post_type($post_type, $args);
    }
}

/* initialize CURL issue resolve */
if (!function_exists('cs_curl')) {
    function cs_curl()
    {
        $ci = curl_init();
        return $ci;
    }
}

/* Execute CURL issue resolve */
if (!function_exists('cs_curl_exec')) {
    function cs_curl_exec($ci)
    {
        $response = curl_exec($ci);
        return $response;
    }
}

/* Execute CURL CLOSE issue resolve */
if (!function_exists('cs_curl_close')) {
    function cs_curl_close($ci)
    {
        curl_close($ci);
    }
}

/* add short code */
if (!function_exists('cs_shortcode_add')) {
    function cs_shortcode_add($tag, $callback)
    {
        add_shortcode($tag, $callback);
    }
}

/* deregister script */
if (!function_exists('cs_wp_der_script')) {
    function cs_wp_der_script($var)
    {
        wp_deregister_script($var);
    }
}

/* return $_SERVER  */
if (!function_exists('cs_glob_server')) {
    function cs_glob_server($var)
    {
        if ($var != '') {
            return $_SERVER[$var];
        } else {
            return $_SERVER;
        }

    }
}

/* Remove filter */
if (!function_exists('cs_remove_filters')) {
    function cs_remove_filters($tag, $function_to_remove, $priority = 10, $accepted_args = 1)
    {
        remove_filter($tag, $function_to_remove, $priority = 10, $accepted_args = 1);
    }
}

/* force balance tags */
if (!function_exists('complete_tag_forcely')) {
    function complete_tag_forcely($text)
    {
        return force_balance_tags($text);
    }
}

/* force balance tags */
if (!function_exists('tag_complete')) {
    function tag_complete($text, $force = false)
    {
        return balanceTags($text, $force = false);
    }
}

/* force balance tags */
if (!function_exists('is_active_plugin')) {
    function is_active_plugin($plugin)
    {
        return is_plugin_active($plugin);
    }
}

/* file open */
if (!function_exists('framework_fileopen')) {
    function framework_fileopen($file, $mode)
    {
        $file_path = explode('/', $file);
        $get_file_name = end($file_path);
        $get_extention = explode('.', $get_file_name);
        $extention_of_file = end($get_extention);
        $ext_files = array('jpg', 'jpeg', 'png', 'svg');
        if (!in_array($extention_of_file, $ext_files)) {
            return fopen($file, $mode);
        }
        return;
    }
}

/* file end of */
if (!function_exists('framework_file_endof')) {
    function framework_file_endof($file)
    {
        return feof($file);
    }
}

/* file gets */
if (!function_exists('framework_file_gets')) {
    function framework_file_gets($file, $len)
    {
        return fgets($file, $len);
    }
}

/* file close */
if (!function_exists('framework_file_close')) {
    function framework_file_close($file)
    {
        return fclose($file);
    }
}

/* file close */
if (!function_exists('global_server')) {
    function global_server($value = '')
    {
        if ($value != '') {
            return $_SERVER[$value];
        } else {
            return $_SERVER;
        }
    }
}

/* iframe  */
if (!function_exists('html_i_frame')) {
    function html_i_frame($align = '', $frameborder = '', $height = '', $longdesc = '', $marginheight = '', $marginwidth = '', $name = '', $sandbox = '', $scrolling = '', $src = '', $srcdoc = '', $width = '', $extra_attr = '')
    {
        return '<iframe 
        align="' . $align . '" 
        frameborder="' . $frameborder . '" 
        height="' . $height . '" 
        longdesc="' . $longdesc . '" 
        marginheight="' . $marginheight . '" 
        marginwidth="' . $marginwidth . '" 
        name="' . $name . '" 
        sandbox="' . $sandbox . '" 
        scrolling="' . $scrolling . '" 
        src="' . $src . '" 
        srcdoc="' . $srcdoc . '" 
        width="' . $width . '" 
        ' . $extra_attr . ' 
        </iframe>';
    }
}