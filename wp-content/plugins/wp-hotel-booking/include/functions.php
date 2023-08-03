<?php
/**
 * File Type: Plugin Functions
 */
if (!class_exists('cs_booking_functions')) {

    class cs_booking_functions {

        // The single instance of the class
        protected static $_instance = null;

        public function __construct() {

            add_action('save_post', array($this, 'cs_save_post_option'));
            //echo "here";
            //exit;
        }

        // Main Fuunctions Instance
        public static function instance() {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        // Saving Post Meta
        public function cs_save_post_option($post_id = '') {

            global $post, $cs_plugin_options;

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            $data = array();

            foreach ($_POST as $key => $value) {

                if (strstr($key, 'cs_')) {
                    if ($key == 'cs_check_in_date' || $key == 'cs_check_out_date') {

                        $value = strtotime($value);
                        $_POST[$key] = $value;
                        update_post_meta($post_id, $key, $value); //exit;
                    } else {
                        //print_r($value);
                        $data[$key] = $value;
                        update_post_meta($post_id, $key, $value);
                    }
                }
            }

            update_post_meta($post_id, 'cs_array_data', $data);


            if (isset($_POST['cs_admin_booking']) && $_POST['cs_admin_booking'] == 'new') {
                $reserved_rooms = $_SESSION['admin_reserved_rooms'];
                $cs_booked_rooms = array();
                foreach ($reserved_rooms as $key => $value) {
                    $cs_booked_rooms[] = $key;
                }
                $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
                update_post_meta($post_id, 'cs_invoice', $post_id);
                update_post_meta($post_id, 'cs_bkng_vat_percentage', $cs_payment_vat);
                update_post_meta($post_id, 'cs_booked_room_data', $reserved_rooms);
                update_post_meta($post_id, 'cs_booked_room', $cs_booked_rooms);
            }

            if (isset($_POST['cs_room_meta'])) {

                $rooms_data = array();
                for ($i = 0; $i < $_POST['cs_room_num']; $i++) {
                    if ($_POST['cs_room_meta'][$i] != '') {
                       
					    if( $_POST['cs_room_key'][$i] == '' ) {
							$id = $this->cs_generate_random_string(5);
						} else{
							$id	=  $_POST['cs_room_key'][$i];
						}
						
						$id	= strtolower( $id );
						$rooms_data[$id]['id'] 			 = strtolower($_POST['cs_room_meta'][$i]);
                        $rooms_data[$id]['reference_no'] = $_POST['cs_room_meta'][$i];
                        $rooms_data[$id]['status'] 		 = $_POST['cs_room_status'][$i];
                        $rooms_data[$id]['reason'] 		 = $_POST['cs_room_reason'][$i];
                    }
                }
                update_post_meta($post_id, 'cs_room_meta_data', $rooms_data);
            }
            //echo "hello";exit;
        }

        // Special Characters
        public function cs_special_chars($input = '') {
            $output = $input;
            return $output;
        }

        // Slugify the Text
        public function cs_slugy_text($str) {
            $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
            $clean = strtolower(trim($clean, '_'));
            $clean = preg_replace("/[\/_|+ -]+/", '_', $clean);
            return $clean;
        }

        // Random Id
        public function cs_rand_id() {
            $output = rand(12345678, 98765432);
            return $output;
        }

        // Advance Deposit
        public function cs_percent_return($num) {

            if (is_numeric($num) && $num > 0 && $num <= 100) {
                $num = $num;
            } else if (is_numeric($num) && $num > 0 && $num > 100) {
                $num = 100;
            } else {
                $num = 0;
            }

            return $num;
        }

        // Number Format
        public function cs_num_format($num) {
            $cs_number = number_format((float) $num, 2, '.', '');
            return $cs_number;
        }

        // Calculate Percentage
        public function cs_calc_percentage($number, $perc) {
            $cs_number = 0;
            if (is_numeric($number) && $number > 0 && is_numeric($perc) && $perc > 0) {
                $cs_number = ($number / 100) * $perc;
            }
            return $cs_number;
        }

        // Get Image Src
        public function cs_attach_image_src($attachment_id, $width, $height) {
            $image_url = wp_get_attachment_image_src($attachment_id, array($width, $height), true);
            if ($image_url[1] == $width and $image_url[2] == $height)
                ;
            else
                $image_url = wp_get_attachment_image_src($attachment_id, "full", true);
            $parts = explode('/uploads/', $image_url[0]);
            if (count($parts) > 1)
                return $image_url[0];
        }

        // Get Gallery First Image Src
        public function cs_gallery_image_src($post_id, $width = 150, $height = 150) {

            $image_url = get_post_meta($post_id, '_room_image_gallery', true);
            $image_url = explode(',', $image_url);
            if (is_array($image_url) && sizeof($image_url) > 0) {
                $image_url = isset($image_url[0]) ? $this->cs_attach_image_src((int) $image_url[0], $width, $height) : '';
            } else {
                $image_url = '';
            }
            return $image_url;
        }

        // Get Post id through meta key
        public function cs_get_post_id_by_meta_key($key, $value) {
            global $wpdb;
            $meta = $wpdb->get_results("SELECT * FROM `" . $wpdb->postmeta . "` WHERE meta_key='" . $key . "' AND meta_value='" . $value . "'");

            if (is_array($meta) && !empty($meta) && isset($meta[0])) {
                $meta = $meta[0];
            }
            if (is_object($meta)) {
                return $meta->post_id;
            } else {
                return false;
            }
        }

        public function cs_show_all_cats($parent, $separator, $selected = "", $taxonomy) {
            if ($parent == "") {
                global $wpdb;
                $parent = 0;
            } else
                $separator .= " &ndash; ";
            $args = array(
                'parent' => $parent,
                'hide_empty' => 0,
                'taxonomy' => $taxonomy
            );
            $categories = get_categories($args);
            foreach ($categories as $category) {
                ?>
                <option <?php if ($selected == $category->slug) echo "selected"; ?> value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_attr($separator . $category->cat_name); ?></option>
                <?php
                cs_show_all_cats($category->term_id, $separator, $selected, $taxonomy);
            }
        }

        // Excerpt
        public function cs_get_the_excerpt($charlength = '255', $readmore = 'true', $readmore_text = 'Read More') {
            global $post, $cs_theme_option;

            $excerpt = trim(preg_replace('/<a[^>]*>(.*)<\/a>/iU', '', get_the_excerpt()));

            if (strlen($excerpt) > $charlength) {
                if ($charlength > 0) {
                    $excerpt = substr($excerpt, 0, $charlength);
                } else {
                    $excerpt = $excerpt;
                }
                if ($readmore == 'true') {
                    $more = '<a href="' . esc_url(get_permalink()) . '" class="read-more">::' . esc_attr($readmore_text) . '</a>';
                } else {
                    $more = '...';
                }
                return $excerpt . $more;
            } else {
                return $excerpt;
            }
        }

        /**
         *
         * Get Post Image
         *
         */
        public function cs_get_post_img($post_id, $width, $height) {
            $image_url = wp_get_attachment_image_src($post_id, array($width, $height), true);
            if ($image_url[1] == $width and $image_url[2] == $height) {
                return $image_url[0];
            }
        }

        /**
         *
         * Get Post Image
         *
         */
        public function cs_icomoons($icon_value = '', $id = '', $name = '') {
            ob_start();
            ?>
            <script>
                jQuery(document).ready(function ($) {

                    var e9_element = $('#e9_element_<?php echo cs_allow_special_char($id); ?>').fontIconPicker({
                        theme: 'fip-bootstrap'
                    });
                    // Add the event on the button
                    $('#e9_buttons_<?php echo cs_allow_special_char($id); ?> button').on('click', function (e) {
                        e.preventDefault();
                        // Show processing message
                        $(this).prop('disabled', true).html('<i class="icon-cog demo-animate-spin"></i> Please wait...');
                        $.ajax({
                            url: '<?php echo wp_hotel_booking::plugin_url(); ?>/assets/icomoon/js/selection.json',
                            type: 'GET',
                            dataType: 'json'
                        })
                                .done(function (response) {
                                    // Get the class prefix
                                    var classPrefix = response.preferences.fontPref.prefix,
                                            icomoon_json_icons = [],
                                            icomoon_json_search = [];
                                    $.each(response.icons, function (i, v) {
                                        icomoon_json_icons.push(classPrefix + v.properties.name);
                                        if (v.icon && v.icon.tags && v.icon.tags.length) {
                                            icomoon_json_search.push(v.properties.name + ' ' + v.icon.tags.join(' '));
                                        } else {
                                            icomoon_json_search.push(v.properties.name);
                                        }
                                    });
                                    // Set new fonts on fontIconPicker
                                    e9_element.setIcons(icomoon_json_icons, icomoon_json_search);
                                    // Show success message and disable
                                    $('#e9_buttons_<?php echo cs_allow_special_char($id); ?> button').removeClass('btn-primary').addClass('btn-success').text('Successfully loaded icons').prop('disabled', true);
                                })
                                .fail(function () {
                                    // Show error message and enable
                                    $('#e9_buttons_<?php echo cs_allow_special_char($id); ?> button').removeClass('btn-primary').addClass('btn-danger').text('Error: Try Again?').prop('disabled', false);
                                });
                        e.stopPropagation();
                    });

                    jQuery("#e9_buttons_<?php echo cs_allow_special_char($id); ?> button").click();
                });


            </script>
            <input type="text" id="e9_element_<?php echo cs_allow_special_char($id); ?>" name="<?php echo cs_allow_special_char($name); ?>[]" value="<?php echo cs_allow_special_char($icon_value); ?>"/>
            <span id="e9_buttons_<?php echo cs_allow_special_char($id); ?>" style="display:none">
                <button autocomplete="off" type="button" class="btn btn-primary">Load from IcoMoon selection.json</button>
            </span>
            <?php
            $fontawesome = ob_get_clean();
            return $fontawesome;
        }

        /**
         * @ render Random ID
         *
         *
         */
        public static function cs_generate_random_string($length = 3) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $randomString;
        }

        /**
         * @get user ID
         *
         */
        public function cs_get_user_id() {
            global $current_user;
            return $current_user->ID;
        }

    }

    /**
     *
     * Design Pattern for Object initilization
     *
     */
    function CS_FUNCTIONS() {
        return cs_booking_functions::instance();
    }

    $GLOBALS['cs_booking_functions'] = CS_FUNCTIONS();
}