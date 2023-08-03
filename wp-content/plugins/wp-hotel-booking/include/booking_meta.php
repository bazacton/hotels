<?php
/**
 * @Add Meta Box For Booking Post
 * @return
 *
 */
add_action('add_meta_boxes', 'cs_meta_booking_add');

function cs_meta_booking_add() {
    add_meta_box('cs_meta_booking', __('Booking Options', 'booking'), 'cs_meta_booking', 'booking', 'normal', 'high');
}

function cs_meta_booking($post) {
    global $post;
    ?>
    <div class="page-wrap page-opts left">
        <div class="option-sec" style="margin-bottom:0;">
            <div class="opt-conts">
                <div class="elementhidden">
                    <div class="tab-content">
                        <div id="tab-booking-settings" class="tab-pane fade active in">
                            <?php
                            wp_hotel_booking::cs_date_range_style_script();
                            if (function_exists('cs_booking_options')) {
                                cs_booking_options();
                            }

                            if (isset($_GET['post']) && $_GET['post'] != '') {
                                // Do Nothing
                            } else {
                                ?>
                                <script type="text/javascript">

                                    jQuery(function () {
                                        jQuery("#wrapper_datepicker").dateRangePicker({
                                            separator: " to ",
                                            format: 'YYYY-MM-DD',
                                            getValue: function ()
                                            {
                                                if (jQuery("cs_check_in_date").val() && jQuery("#cs_check_out_date").val())
                                                    return jQuery("#cs_check_in_date").val() + " to " + jQuery("#cs_check_out_date").val();
                                                else
                                                    return "";
                                            },
                                            setValue: function (s, s1, s2)
                                            {

                                                jQuery("#cs_check_in_date").val(s1);
                                                jQuery("#cs_check_out_date").val(s2);

                                                var start = new Date(s1)
                                                var end = new Date(s2)

                                                if (!start || !end)
                                                    return;
                                                var days = (end - start) / 1000 / 60 / 60 / 24;
                                                jQuery("#cs_booking_num_days").val(days);

                                            },
                                        });
                                    });



                                </script>
                                <?php
                            }

                            if (function_exists('cs_booking_payments')) {
                                cs_booking_payments();
                            }

                            if (function_exists('cs_guest_details')) {
                                cs_guest_details();
                            }

                            if (function_exists('cs_general_settings')) {
                                cs_general_settings();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}

/**
 * @Booking options
 * @return html
 *
 */
if (!function_exists('cs_booking_options')) {

    function cs_booking_options() {

        global $post, $cs_form_fields;

        unset($_SESSION['admin_reserved_rooms']);
        unset($_SESSION['admin_reservation']);

        $active = '';
        if (isset($_GET['post']) && $_GET['post'] != '') {
            $active = 'in-active';
        }

        $cs_form_fields->cs_form_edit_id(
                array(
                    'name' => __('Booking Id', 'booking'),
                    'id' => 'booking_id',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_hotels = get_option('cs_hotel_options');


        $hotel_list = array();
        if (isset($cs_hotels) && is_array($cs_hotels)) {
            foreach ($cs_hotels as $key => $hotel) {
                $hotel_list[$key] = $hotel['cs_hotel_name'];
            }
        }

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Select Hotel', 'booking'),
                    'id' => 'hotel_id',
                    'classes' => 'cs_hotel_id',
                    'std' => '',
                    'description' => '',
                    'options' => $hotel_list,
                    'hint' => ''
                )
        );


        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'datepicker',
                    'status' => 'show',
                )
        );
        $cs_form_fields->cs_form_text_render(
                array('name' => __('Check-in Date', 'booking'),
                    'id' => 'check_in_date',
                    'classes' => '',
                    'active' => $active,
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Check-out Date', 'booking'),
                    'id' => 'check_out_date',
                    'classes' => '',
                    'active' => $active,
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'datepicker_wrapper',
                    'status' => '',
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Total Days', 'booking'),
                    'id' => 'booking_num_days',
                    'classes' => '',
                    'active' => $active,
                    'std' => '',
                    'description' => '',
                    'hint' => ''
                )
        );



        // Rooms Data
        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'rooms_list',
                    'status' => 'show',
                )
        );

        if (isset($_GET['post']) && $_GET['post'] != '') {
            // Do Nothing
        } else {
            $cs_form_fields->cs_rooms_render(
                    array('name' => __('Rooms', 'booking'),
                        'id' => 'rooms',
                        'classes' => '',
                        'active' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'rooms_list',
                    'status' => '',
                )
        );


        if (isset($_GET['post']) && $_GET['post'] != '') {

            $cs_form_fields->cs_wrapper_start_render(
                    array('name' => __('Wrapper', 'booking'),
                        'id' => 'room_detail',
                        'status' => 'show',
                    )
            );

            $cs_form_fields->cs_booking_detail_render(
                    array('name' => __('Booking Detail', 'booking'),
                        'id' => 'room_detail',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );

            $cs_form_fields->cs_wrapper_end_render(
                    array('name' => __('Wrapper', 'booking'),
                        'id' => 'booking_detail',
                        'status' => '',
                    )
            );
        } else {
            $cs_form_fields->cs_form_button_render(
                    array('name' => __('Search Room', 'booking'),
                        'id' => 'search_room',
                        'classes' => '',
                        'std' => __('Search Room', 'booking'),
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'room_availability',
                    'status' => 'show',
                )
        );


        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'room_availability',
                    'status' => '',
                )
        );

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'room_detail',
                    'status' => 'show',
                )
        );


        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'room_detail',
                    'status' => '',
                )
        );

        $cs_form_fields->cs_wrapper_start_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'room_extras',
                    'status' => 'show',
                )
        );

        if (isset($_GET['post']) && $_GET['post'] != '') {

            $cs_form_fields->cs_booking_extras_list(
                    array('name' => __('Extras', 'booking'),
                        'id' => 'booking_extras',
                        'classes' => '',
                        'std' => '',
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_wrapper_end_render(
                array('name' => __('Wrapper', 'booking'),
                    'id' => 'room_extras',
                    'status' => '',
                )
        );

        if (isset($_GET['post']) && $_GET['post'] != '') {
            // Do Nothing	
        } else {
            $cs_form_fields->cs_form_hidden_render(array('id' => 'admin_booking', 'std' => 'new', 'type' => '', 'return' => 'echo'));
        }
    }

}

/**
 * @Guest options
 * @return html
 *
 */
if (!function_exists('cs_guest_details')) {

    function cs_guest_details() {
        global $post, $cs_form_fields;
        $cs_form_fields->cs_heading_render(
                array('name' => __('Guest Detail', 'booking'),
                    'id' => 'guest_detail',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                )
        );

        $cs_cstmr_data = get_option("cs_customer_options");
        $cs_cstmr_counter = 0;
        $customers_options[''] = __('Select a customer', 'booking');
        if (is_array($cs_cstmr_data) && sizeof($cs_cstmr_data) > 0) {
            foreach ($cs_cstmr_data as $key => $cstmr) {
                if (isset($cs_cstmr_data[$key])) {
                    $cs_cstmr_fields = $cs_cstmr_data[$key];
                    if (isset($cs_cstmr_fields)) {
                        if ($cs_cstmr_fields['cus_f_name'] || $cs_cstmr_fields['cus_l_name']) {
                            $customers_options[$cs_cstmr_fields['cus_id']] = $cs_cstmr_fields['cus_f_name'] . ' ' . $cs_cstmr_fields['cus_l_name'];
                        }
                    }
                }
            }
        }

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Select Guest', 'booking'),
                    'id' => 'select_guest',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'options' => $customers_options,
                    'hint' => ''
                )
        );
    }

}

/**
 * @Guest options
 * @return html
 *
 */
if (!function_exists('cs_general_settings')) {

    function cs_general_settings() {
        global $post, $cs_form_fields;
        $cs_form_fields->cs_heading_render(
                array('name' => __('Booking Status', 'booking'),
                    'id' => 'status',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                )
        );

        $cs_form_fields->cs_form_select_render(
                array('name' => __('Select Booking Status', 'booking'),
                    'id' => 'booking_status',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                    'options' => array('pending' => __('Pending', 'booking'), 'confirmed' => __('Confirmed', 'booking')),
                    'hint' => ''
                )
        );
    }

}
/**
 * @Booking options
 * @return html
 *
 */
if (!function_exists('cs_booking_payments')) {

    function cs_booking_payments() {

        global $post, $cs_form_fields, $cs_plugin_options;

        $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';
        $active = 'in-active';
        if (isset($_GET['post']) && $_GET['post'] != '') {
            $active = 'in-active';
        }

        $cs_form_fields->cs_heading_render(
                array('name' => __('Payment Detail', 'booking'),
                    'id' => 'payemnt_detail',
                    'classes' => '',
                    'std' => '',
                    'description' => '',
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Gross Total', 'booking'),
                    'id' => 'bkng_gross_total',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );

        if ($cs_vat_switch == 'on') {

            $cs_form_fields->cs_form_text_render(
                    array('name' => __('VAT', 'booking'),
                        'id' => 'bkng_tax',
                        'classes' => '',
                        'std' => '',
                        'active' => $active,
                        'description' => '',
                        'hint' => ''
                    )
            );
        }

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Grand Total', 'booking'),
                    'id' => 'bkng_grand_total',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Advance Payment', 'booking'),
                    'id' => 'bkng_advance',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );

        $cs_form_fields->cs_form_text_render(
                array('name' => __('Remaining', 'booking'),
                    'id' => 'bkng_remaining',
                    'classes' => '',
                    'std' => '',
                    'active' => $active,
                    'description' => '',
                    'hint' => ''
                )
        );
    }

}

if (!class_exists('booking_meta')) {

    class booking_meta {

        private $cs_plugin_options;

        public function __construct() {
            global $cs_plugin_options;
            //$cs_plugin_options = get_option('cs_plugin_option');
            
            add_action('wp_ajax_cs_get_available_rooms', array(&$this, 'cs_get_available_rooms'));
            add_action('wp_ajax_nopriv_cs_get_available_rooms', array(&$this, 'cs_get_available_rooms'));
            add_action('wp_ajax_cs_get_room_detail', array(&$this, 'cs_get_room_detail'));
            add_action('wp_ajax_cs_get_room_extras_detail', array(&$this, 'cs_get_room_extras_detail'));
        }

        /**
         *
         * @Set Session
         *
         */
        public function cs_set_session($params = array()) {

            extract($params);

            $cs_post_data = array();

            $cs_post_data['cs_hotel_id'] = $cs_hotel;
            $cs_post_data['start_date'] = $start_date;
            $cs_post_data['end_date'] = $end_date;
			$cs_post_data['start_time'] = $start_time;
            $cs_post_data['end_time'] = $end_time;
            $cs_post_data['total_adults'] = $total_adults;
            $cs_post_data['total_childs'] = $total_childs;
            $cs_post_data['booking_id'] = $booking_id;
            $cs_post_data['room_id'] = $room_id;
            $cs_post_data['capacity'] = $capacity;
            $cs_post_data['total_days'] = $total_days;
            $cs_post_data['no_of_rooms'] = $no_of_rooms;
            $cs_post_data['member_data'] = $member_data['room_data'];

            $_SESSION['admin_reservation'] = $cs_post_data;
        }

        /**
         *
         * @Get Available Rooms
         *
         */
        public function cs_get_available_rooms() {
            global $post;

            unset($_SESSION['admin_reserved_rooms']);
            unset($_SESSION['admin_reservation']);

            $json = array();
            $params = array();

            $json['output'] = '';
            $date_from = $_REQUEST['date_from'];
            $date_to = $_REQUEST['date_to'];
            $total_days = $_REQUEST['total_days'];
            $total_adults = $_REQUEST['total_adults'];
            $total_childs = $_REQUEST['total_childs'];
            $cs_hotel_id = $_REQUEST['cs_hotel_id'];
            $no_of_rooms = $_REQUEST['no_of_rooms'];

            $total_adults = explode(',', $total_adults);
            $total_childs = explode(',', $total_childs);
			
			$start_time = date('H:i', strtotime($date_from));
    		$end_time = date('H:i', strtotime($date_to));

            $params['cs_hotel'] = $cs_hotel_id;
            $params['start_date'] = $date_from;
            $params['end_date'] = $date_to;
			$params['start_time'] = $start_time;
            $params['end_time'] = $end_time;
            $params['total_adults'] = $total_adults;
            $params['total_childs'] = $total_childs;
            $params['booking_id'] = '';
            $params['room_id'] = '';
            $params['capacity'] = '';
            $params['total_days'] = '';
            $params['no_of_rooms'] = $no_of_rooms;


            $member_data = array();
            for ($i = 0; $i < $no_of_rooms; $i++) {
                $member_data['room_data'][$i]['adults'] = $total_adults[$i];
                $member_data['room_data'][$i]['childs'] = $total_childs[$i];
            }

            $params['member_data'] = $member_data;

            $this->cs_set_session($params);

            $total_adults = $member_data['room_data'][0]['adults'];
            $total_childs = $member_data['room_data'][0]['childs'];

            if ($date_from == '' || $date_to == '' || $total_days == '') {
                $json['type'] = 'error';
                $json['message'] = __('Some error occur, pleae try again later.', 'booking');
            } else {
                //Check Bookings
                $output = '';

                $cs_rooms = array();
                $cs_room_capacity_data = array();
                $temp_data = array();
                $cs_args = array('posts_per_page' => '-1', 'post_type' => 'rooms', 'orderby' => 'Id', 'post_status' => 'publish');
                if (isset($cs_hotel_id) && $cs_hotel_id != '') {
                    $cs_args['meta_query'] = array('relation' => 'AND',
                        array(
                            'key' => 'cs_hotel_id',
                            'value' => $cs_hotel_id,
                            'compare' => '=',
                        )
                    );
                }
				
                $query = new WP_Query($cs_args);
                $post_count = $query->post_count;
                if ($query->have_posts() <> "") {
                    while ($query->have_posts()): $query->the_post();

                        $room_type = $post->ID;
                        $cs_room_title = get_the_title($post->ID);
                        $is_single = 'true';
                        $temp_data['data'] = '';

                        $capacity_range = array();

                        //Get Ranges
                        $cap_args = array('posts_per_page' => '-1', 'post_type' => 'rooms_capacity', 'orderby' => 'ID', 'post_status' => 'publish');
                        $cap_args['meta_query'] = array('relation' => 'AND',
                            array(
                                'key' => 'cs_room_id',
                                'value' => $post->ID,
                                'compare' => '=',
                            )
                        );

                        $cust_query = get_posts($cap_args);

                        if (isset($cust_query) && !empty($cust_query)) {
                            foreach ($cust_query as $key => $capacity_type) {
                                $cs_room_max_adults = get_post_meta($capacity_type->ID, 'cs_room_max_adults', true);
                                $cs_room_max_child = get_post_meta($capacity_type->ID, 'cs_room_max_child', true);

                                if ($cs_room_max_adults >= $total_adults && $cs_room_max_child >= $total_childs) {
                                    $capacity_range[] = $capacity_type->ID;
                                } else {
                                    //Range Unavailable
                                }
                            }
                        }


                        if (isset($capacity_range) && !empty($capacity_range)) {
                            $capacity_counter = 0;
                            foreach ($capacity_range as $key => $capacity_type) {
                                $capacity_counter++;
                                $cs_rooms = get_post_meta($capacity_type, 'cs_room_meta_data', true);
                                if (isset($cs_rooms) && !empty($cs_rooms)) {
                                    $cs_availabilty_counter = 0;
                                    foreach ($cs_rooms as $key => $cs_room_refernce) {

                                        if ($cs_room_refernce['status'] == 'active') {

                                            $booking_count = $this->cs_check_booking($key, $date_from, $date_to);
                                            if ($booking_count <= 0) {
                                                //Rooms Available
                                                $cs_availabilty_counter++;
                                            }
                                        } else {
                                            //No Rooms Found
                                        }
                                    }


                                    if (isset($cs_availabilty_counter) && $cs_availabilty_counter > 0) {
                                        $cs_room_capacity_data['id'] = $capacity_type;
                                        $cs_room_capacity_data['title'] = get_the_title($capacity_type);
                                        $temp_data['data'][] = $cs_room_capacity_data;
                                    }
                                }
                            } //

                            if (isset($temp_data) && !empty($temp_data)) {

                                $width = '202';
                                $height = '146';

                                $cs_gallery = get_post_meta($room_type, "cs_room_image_gallery", true);
                                $cs_gallery = explode(',', $cs_gallery);

                                if (is_array($cs_gallery) && count($cs_gallery) > 0 && $cs_gallery[0] != '') {
                                    $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                                }

                                if ($thumbnail == '') {
                                    $thumbnail = wp_hotel_booking::plugin_url() . '/assets/images/no-image.png';
                                }

                                $json['output'] .= '<div class="bk-room-availabilty">';
                                $json['output'] .= '<figure><a href="javascript:;"><img src="' . esc_url($thumbnail) . '" alt=""  /></a></figure>';
                                $json['output'] .= '<div class="bk-room-detail">';
                                $json['output'] .= '<div class="bk-room-name">';
                                $json['output'] .= $cs_room_title;
                                $json['output'] .= '</div>';
                                $json['output'] .= '<div class="bk-room-capacity">';
                                $cs_options = '<option value="">Room Capacity</option>';
                                if (isset($temp_data['data']) and $temp_data['data'] <> '') {
                                    foreach ($temp_data['data'] as $key => $value) {
                                        $cs_options .= '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
                                    }
                                }

                                $json['output'] .= '<select name="cs_room_capacity[' . $room_type . '][]" data-reference="null" data-room_type="' . $room_type . '" id="cs-select-room">' . $cs_options . '</select>';
                                $json['output'] .= '</div>';
                                $json['output'] .= '</div>';
                                $json['output'] .= '</div>';
                            }
                        }

                    endwhile;
                    wp_reset_postdata();
                    $member_data = $member_data;
                    $json['output_rooms'] = '';

                    if ($member_data['room_data'] && !empty($member_data)) {
                        $cs_select_counter = 0;
                        foreach ($member_data['room_data'] as $key => $data) {
                            $cs_select_counter++;
                            $selected = $cs_select_counter == 1 ? ' cs-current-room' : '';
                            $json['output_rooms'] .= '<div class="bk-room-deail cs-gross-calculation ' . $selected . '" data-key="' . $key . '" data-id="' . $cs_select_counter . '">';
                            $json['output_rooms'] .= '<div class="bk-room-name">';
                            $json['output_rooms'] .= __('Select Room', 'booking') . ' ' . sprintf("%02d", $cs_select_counter);
                            $json['output_rooms'] .= '<span> <b>Guests</b>: ' . absint($data['adults']) . 'Adult(s)' . absint($data['childs']) . ' Child(s)</span>';
                            $json['output_rooms'] .= '</div>';
                            $json['output_rooms'] .= '</div>';
                        }
                    }

                    $json['type'] = __('success', 'booking');
                    $json['message'] = __('Room Found', 'booking');
                } else {

                    $json['type'] = __('error', 'booking');
                    $json['message'] = __('No Rooms Found', 'booking');
                }
            }

            $json['output'] .= '<script>jQuery(document).ready(function() { cs_select_room(); });</script>';
            echo json_encode($json);
            die();
        }

        /**
         *
         * @Get Room Detail
         *
         */
        public function cs_get_room_detail() {
            global $cs_plugin_options, $cs_form_fields;
			
			$cs_charge_base = isset($cs_plugin_options['cs_charge_base']) ? $cs_plugin_options['cs_charge_base'] : '';
			
            $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
            $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
            $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';
            $booking_type = isset($cs_plugin_options['cs_rooms_booking_type']) && $cs_plugin_options['cs_rooms_booking_type'] != '' ? $cs_plugin_options['cs_rooms_booking_type'] : 'by_per_person';

            $current_room = $_REQUEST['current_room'];
            $capacity = $_REQUEST['capacity'];
            $room_type = $_REQUEST['room_type'];
            $total_days = $_REQUEST['total_days'];
            $cs_booking_id = $_REQUEST['cs_booking_id'];

            $session_data = isset($_SESSION['admin_reservation']) ? $_SESSION['admin_reservation'] : array();
            $date_from = $session_data['start_date'];
            $date_to = $session_data['end_date'];
			$start_time = $session_data['start_time'];
        	$end_time = $session_data['end_time'];
            $total_adults = $session_data['member_data'][$current_room]['adults'];
            $total_childs = $session_data['member_data'][$current_room]['childs'];
            $no_of_rooms = $session_data['no_of_rooms'];

            $start_date = strtotime($date_from);
            $end_date = strtotime($date_to);
            $datetime1 = date_create($date_from);
            $datetime2 = date_create($date_to);
            $interval = date_diff($datetime1, $datetime2);
            $total_days = $interval->days;

            $json = array();
            $json['output'] = '';
            $json['selection_done'] = '';

            if (isset($capacity) && $capacity != '') {
                $cs_room_price = get_post_meta($capacity, 'cs_room_price', true);
                $max_adults = get_post_meta($capacity, 'cs_room_max_adults', true);
                $max_child = get_post_meta($capacity, 'cs_room_max_child', true);
                $cs_rooms = get_post_meta($capacity, 'cs_room_meta_data', true);

                $room_key = '';
                $room_id = '';
                if (isset($cs_rooms) && !empty($cs_rooms)) {
                    foreach ($cs_rooms as $key => $cs_room_refernce) {
                        if ($cs_room_refernce['status'] == 'active') {
                            $room_id = $cs_room_refernce['id'];
                            $room_key = $key;
                            $booking_count = $this->cs_check_booking($key, $date_from, $date_to);
                            if ($booking_count <= 0) {
                                if (isset($_SESSION['admin_reserved_rooms'][strtolower($room_key)])) {
                                    // do nothing
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }

                $start_date = strtotime($date_from);
                $end_date = strtotime($date_to);
				
				$cs_start_date_time = strtotime($date_from . ' ' . $start_time);
        		$cs_end_date_time = strtotime($date_to . ' ' . $end_time);

                $datetime1 = date_create($date_from);
                $datetime2 = date_create($date_to);
                $interval = date_diff($datetime1, $datetime2);
                $cs_booking_days = $interval->days;

                $pricings = get_option('cs_price_options');
                if (isset($pricings[$room_type])) {
                    $pricings_array = $pricings[$room_type];
                    $capacities = $pricings[$room_type]['cs_pricing_branches']['room_capacity'];
                    $cs_offers_options = get_option("cs_offers_options");
                    if (isset($pricings[$room_type]['cs_plan_days'])) {
                        $cs_sp_days = $pricings[$room_type]['cs_plan_days'];
                    }

                    $sp_start_date = '';
                    $sp_end_date = '';
                    $offer_discount = 0.00;

                    $capacity_key = 0;
                    foreach ($capacities as $key => $value) {
                        if ($value == $capacity) {
                            $capacity_key = $key;
                        }
                    }


                    $start_date = strtotime($date_from);
                    $end_date = strtotime($date_to);

                    if ($booking_type == 'by_per_person') {
                        // Loop between timestamps, 24 hours at a time
                        $price['total_price'] = 0;
                        $total_price = '';
                        $adult_price = 0;
                        $child_price = 0;
                        $pricing_data = array();
                        $pricing_offer_data = array();
                        $price_breakdown = '';
                        $brk_counter = 0;
						
						$cs_counter_plus_plus = 86400;
						if ($cs_charge_base == 'hourly') {
							$cs_counter_plus_plus = 3600;
							if ($cs_end_date_time > 3600) {
								$cs_end_date_time = (int) $cs_end_date_time - 3600;
							}
						}

                        for ($i = $cs_start_date_time; $i <= $cs_end_date_time; $i = $i + $cs_counter_plus_plus) {
                            $brk_counter++;
                            $thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
                            $day = strtolower(date('D', strtotime($thisDate)));
                            $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][$capacity_key];
                            $child_price = $pricings_array['cs_pricing_branches']['child_' . $day . '_price'][$capacity_key];

                            $adult_temp_price = $adult_price != '' ? $adult_price : 0;
                            $child_temp_price = $child_price != '' ? $child_price : 0;

                            $adult_price = $adult_temp_price;
                            $child_price = $child_temp_price;

                            $to_check_date = strtotime(date('Y-m-d', $i));

                            // Special Prices Calculations
                            if (isset($cs_sp_days['start_date'][0]) && $cs_sp_days['start_date'][0] != '') {
                                foreach ($cs_sp_days['start_date'] as $key => $sp_price_date) {
                                    $sp_start_date = $cs_sp_days['start_date'][$key];
                                    $sp_end_date = $cs_sp_days['end_date'][$key];

                                    $sp_start_date = date('Y-m-d', strtotime($sp_start_date));
                                    $sp_end_date = date('Y-m-d', strtotime($sp_end_date));

                                    if (isset($sp_start_date) && isset($sp_end_date)) {
                                        $sp_start_date = strtotime($sp_start_date);
                                        $sp_end_date = strtotime($sp_end_date);

                                        if ($to_check_date >= $sp_start_date && $to_check_date <= $sp_end_date) {
                                            if (isset($pricings[$room_type]['cs_plan_prices'])) {
                                                $cs_plan_prices = $pricings[$room_type]['cs_plan_prices'][$key];
                                                $cs_plan_prices['adult_' . $day . '_price'][$capacity_key];
                                                $adult_price = $cs_plan_prices['adult_' . $day . '_price'][$capacity_key];
                                                $child_price = $cs_plan_prices['child_' . $day . '_price'][$capacity_key];
                                            }
                                        }
                                    }
                                }
                            }

                            //Offers Calculations, Note: It will override special Prices if date exist
                            if (isset($cs_offers_options) && !empty($cs_offers_options)) {
                                foreach ($cs_offers_options as $key => $offer_data) {

                                    $offer_start_date = date('Y-m-d', strtotime($offer_data['start_date']));
                                    $offer_end_date = date('Y-m-d', strtotime($offer_data['end_date']));

                                    if (isset($offer_start_date) && isset($offer_end_date)) {
                                        $offer_start_date = strtotime($offer_start_date);
                                        $offer_end_date = strtotime($offer_end_date);

                                        if ($to_check_date >= $offer_start_date && $to_check_date <= $offer_end_date) {
                                            $offer_discount = $offer_data['discount'];
                                            if ($cs_booking_days <= $offer_data['min_days']) {
                                                $adult_price = $pricings_array['cs_pricing_branches']['adult_' . $day . '_price'][$capacity_key];
                                                $child_price = $pricings_array['cs_pricing_branches']['child_' . $day . '_price'][$capacity_key];

                                                $discount_adult_price = ( $adult_temp_price / 100 ) * $offer_discount;
                                                $discount_child_price = ( $child_temp_price / 100 ) * $offer_discount;
                                                $adult_price = $adult_temp_price - $discount_adult_price;
                                                $child_price = $child_temp_price - $discount_child_price;
                                            }
                                        }
                                    }
                                }
                            }

                            //Total By Person
                            $adult_price_total = $adult_price * $total_adults;
                            $child_price_total = $child_price * $total_childs;

                            //discount Calculate
                            $cs_total = $adult_price_total + $child_price_total;
                            $total_price = $price['total_price'] + $cs_total;
                            $price['total_price'] = $total_price;

                            //Array Data
                            $pricing_data['data'][$day]['adult'] = $adult_price;
                            $pricing_data['data'][$day]['child'] = $child_price;

                            //price Breakdown
                            $price_breakdown .= '<div class="bk-pricing-breakdown">';
                            $price_breakdown .= '<div class="bk-room-date">';
                            $price_breakdown .= date_i18n(get_option('date_format'), strtotime($thisDate));
                            ;
                            $price_breakdown .= '</div>';
                            $price_breakdown .= '<div class="bk-room-adults">';
                            $price_breakdown .= $currency_sign . $adult_price . ' (x' . $total_adults . ')';
                            $price_breakdown .= '</div>';
                            $price_breakdown .= '<div class="bk-room-children">';
                            $price_breakdown .= $currency_sign . $child_price . ' (x' . $total_childs . ')';
                            $price_breakdown .= '</div>';
                            $price_breakdown .= '<div class="bk-room-total">';
                            $price_breakdown .= $currency_sign . number_format($cs_total, 2);
                            $price_breakdown .= '<input type="hidden" name="cs_date[' . $brk_counter . '][]" value="' . $thisDate . '" />';
                            $price_breakdown .= '<input type="hidden" name="cs_adult_price[' . $brk_counter . '][]" value="' . $adult_price . '" />';
                            $price_breakdown .= '<input type="hidden" name="cs_child_price[' . $brk_counter . '][]" value="' . $child_price . '" />';
                            $price_breakdown .= '</div>';
                            $price_breakdown .= '</div>';
                        }
                    } else {
                        $price['total_price'] = get_post_meta($capacity, 'cs_room_price', true);
                        $price['total_price'] = get_post_meta($capacity, 'cs_room_price', true);
                        $temp_price = $price['total_price'];
                        $start_date = strtotime($date_from);
                        $end_date = strtotime($date_to);
                        $cs_offers_options = get_option("cs_offers_options");
                        
						$cs_counter_plus_plus = 86400;
						if ($cs_charge_base == 'hourly') {
							$cs_counter_plus_plus = 3600;
							if ($cs_end_date_time > 3600) {
								$cs_end_date_time = (int) $cs_end_date_time - 3600;
							}
						}

                        for ($i = $cs_start_date_time; $i <= $cs_end_date_time; $i = $i + $cs_counter_plus_plus) {
                            $to_check_date = strtotime(date('Y-m-d', $i));
                            if (isset($cs_offers_options) && !empty($cs_offers_options)) {
                                foreach ($cs_offers_options as $key => $offer_data) {

                                    $offer_start_date = date('Y-m-d', strtotime($offer_data['start_date']));
                                    $offer_end_date = date('Y-m-d', strtotime($offer_data['end_date']));

                                    if (isset($offer_start_date) && isset($offer_end_date)) {
                                        $offer_start_date = strtotime($offer_start_date);
                                        $offer_end_date = strtotime($offer_end_date);

                                        if ($to_check_date >= $offer_start_date && $to_check_date <= $offer_end_date) {
                                            $offer_discount = $offer_data['discount'];
                                            if ($cs_booking_days <= $offer_data['min_days']) {
                                                $discount_price = ( $temp_price / 100 ) * $offer_discount;
                                                $new_price = $temp_price - $discount_price;
                                                $price['total_price'] = $new_price;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $width = '202';
                    $height = '146';

                    $cs_gallery = get_post_meta($room_type, "cs_room_image_gallery", true);
                    $cs_gallery = explode(',', $cs_gallery);

                    if (is_array($cs_gallery) && count($cs_gallery) > 0 && $cs_gallery[0] != '') {
                        $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                    }

                    if ($thumbnail == '') {
                        $thumbnail = wp_hotel_booking::plugin_url() . '/assets/images/no-image.png';
                    }


                    $json['output'] .= '<div class="bk-room-wrap" data-price="' . $price['total_price'] . '">';
                    $json['output'] .= '<div class="bk-room-name">';
                    $json['output'] .= get_the_title($capacity) . ' #' . $room_id;
                    $json['output'] .= '</div>';
                    $json['output'] .= '<div class="bk-room-capacity">';
                    $json['output'] .= '<span> <b>' . __('Guests', 'booking') . '</b>: Max. ' . $total_adults . ' Adult(s) Max. ' . $total_childs . ' Child(s)</span>';
                    $json['output'] .= '<span> <b>' . __('Price', 'booking') . '</b>: ' . $currency_sign . number_format($price['total_price'], 2) . '</span>';
                    $json['output'] .= '</div>';
                    $json['output'] .= '<script>jQuery(document).ready(function() { cs_room_extras(); });</script>';

                    if ($booking_type == 'by_per_person') {
                        $json['output'] .= '<div id="cs-price-breakdown-' . $room_key . '" class="cs-price-breakdown"  style="display:none">';
                        $json['output'] .= '<div class="cs-heading-area">';
                        $json['output'] .= '<h5><i class="icon-plus-circle"></i> ' . __('Price Breakdown', 'booking') . '</h5>';
                        $json['output'] .= '<span class="cs-btnclose" onClick="javascript:cs_remove_overlay(\'cs-price-breakdown-' . $room_key . '\',\'append\')"> <i class="icon-times"></i></span> 	';
                        $json['output'] .= '</div>';
                        $json['output'] .= '<div class="bk-pricing-breakdown price-header"><div class="bk-room-date">Date</div><div class="bk-room-adults">Adults</div><div class="bk-room-children">Children</div><div class="bk-room-total">Total</div></div>';
                        $json['output'] .= $price_breakdown;
                        $json['output'] .= '</div>';
                        $json['output'] .= '<a href="javascript:cs_createpop(\'cs-price-breakdown-' . $room_key . '\',\'filter\')" class="button">' . __("+ Price Break dwon", "booking") . '</a>';
                    }

                    $json['output'] .= '</div>';


                    $rooms_current_array = array();
                    if ($no_of_rooms > 1) {
                        if (isset($_SESSION['admin_reserved_rooms'])) {
                            $rooms_current_array = $_SESSION['admin_reserved_rooms'];
                        }
                    }

                    $rooms_array = array();
                    $rooms_array[strtolower($room_key)]['key'] = $room_key;
                    $rooms_array[strtolower($room_key)]['capacity'] = $capacity;
                    $rooms_array[strtolower($room_key)]['room_id'] = $room_id;
                    $rooms_array[strtolower($room_key)]['room_type'] = $room_type;
                    $rooms_array[strtolower($room_key)]['adults'] = $total_adults;
                    $rooms_array[strtolower($room_key)]['childs'] = $total_childs;
                    $rooms_array[strtolower($room_key)]['price'] = $price['total_price'];

                    $rooms_new_array = array_merge($rooms_current_array, $rooms_array);

                    $_SESSION['admin_reserved_rooms'] = $rooms_new_array;

                    // Calculate Price
                    $session_data = $_SESSION['admin_reserved_rooms'];

                    $price_sum = 0.00;
                    $total_members = 0;
                    foreach ($session_data as $key => $data) {
                        $price = $data['price'];
                        $price_sum = $price + $price_sum;
                        $adults = $data['adults'];
                        $childs = $data['childs'];
                        $total_members += $adults + $childs;
                    }

                    $json['price'] = number_format($price_sum, 2);

                    if ($cs_vat_switch == 'on') {
                        $json['vat'] = number_format(( $price_sum / 100 ) * $cs_payment_vat, 2);
                    } else {
                        $json['vat'] = 0;
                    }

                    $json['grand_total'] = number_format($price_sum + $json['vat'], 2);

                    // Advance and Remainings if Transactions exist
                    $transaction_amount = 0;
                    if (isset($cs_booking_id) && $cs_booking_id != '') {
                        $cs_transactions = get_option('cs_transactions');
                        if (is_array($cs_transactions) && sizeof($cs_transactions) > 0) {

                            foreach ($cs_transactions as $key => $trans) {
                                if ($trans['cs_booking_id'] == $cs_booking_id) {
                                    if ($trans['cs_trans_status'] == 'approved') {
                                        $transaction_amount += $trans['cs_trans_amount'];
                                    }
                                }
                            }
                        }
                    }

                    $json['advance'] = number_format($transaction_amount, 2);
                    $json['remaining'] = number_format($json['grand_total'] - $transaction_amount, 2);


                    if (count($_SESSION['admin_reserved_rooms']) == $no_of_rooms) {
                        $json['status'] = 'completed';
                        $cs_form_fields = new cs_form_fields();
                        $json['selection_done'] .= $cs_form_fields->cs_booking_extras_list_ajax(
                                array('name' => __('Extras', 'booking'),
                                    'id' => 'booking_extras',
                                    'classes' => '',
                                    'std' => '',
                                    'description' => '',
                                    'guests' => $total_members,
                                    'nights' => $total_days,
                                    'post_id' => '',
                                    'hint' => '',
                                    'extra' => $json['price'],
                                )
                        );
                    } else {
                        $cs_next = array();
                        $cs_next['capacity'] = $_REQUEST['capacity'];
                        $cs_next['room_type'] = $_REQUEST['room_type'];
                        $cs_next['current_room'] = count($_SESSION['admin_reserved_rooms']);
                        $selection_done = $this->cs_next_room_data($cs_next);

                        if (trim($selection_done) == 'false') {
                            $json['selection_done'] .= __('No Room Found', 'booking');
                        } else {
                            $json['selection_done'] .= $selection_done;
                        }

                        $session_data = isset($_SESSION['admin_reservation']) ? $_SESSION['admin_reservation'] : array();
                        $total_adults = $session_data['member_data'][$cs_next['current_room']]['adults'];
                        $total_childs = $session_data['member_data'][$cs_next['current_room']]['childs'];
                        $json['total_adults'] = $total_adults;
                        $json['total_childs'] = $total_childs;
                    }

                    $json['type'] = 'success';
                    $json['message'] = __('Room Found', 'booking');
                } else {
                    $json['type'] = 'error';
                    $json['message'] = __('Please Set Pricing First.', 'booking');
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = __('No Room Found.', 'booking');
            }

            echo json_encode($json);
            die();
        }

        /**
         *
         * @Bext Room Data
         *
         */
        public function cs_next_room_data($params = array()) {
            global $post;
            extract($params);

            $session_data = isset($_SESSION['admin_reservation']) ? $_SESSION['admin_reservation'] : array();

            $date_from = $session_data['start_date'];
            $date_to = $session_data['end_date'];
            $total_adults = $session_data['member_data'][$current_room]['adults'];
            $total_childs = $session_data['member_data'][$current_room]['childs'];
            $no_of_rooms = $session_data['no_of_rooms'];
            $cs_hotel_id = $session_data['cs_hotel_id'];

            $json = array();
            $json['output'] = '';
            $data_set = 'false';
            $json['output'] .= '<script>jQuery(document).ready(function() { cs_select_room(); });</script>';

            if ($date_from == '' || $date_to == '') {
                $json['type'] = 'error';
                $json['message'] = __('Some error occur, pleae try again later.', 'booking');
            } else {
                //Check Bookings
                $output = '';

                $cs_rooms = array();
                $cs_room_capacity_data = array();
                $temp_data = array();
                $cs_args = array('posts_per_page' => '-1', 'post_type' => 'rooms', 'orderby' => 'Id', 'post_status' => 'publish');
                if (isset($cs_hotel_id) && $cs_hotel_id != '') {
                    $cs_args['meta_query'] = array('relation' => 'AND',
                        array(
                            'key' => 'cs_hotel_id',
                            'value' => $cs_hotel_id,
                            'compare' => '=',
                        )
                    );
                }

                $query = new WP_Query($cs_args);
                $post_count = $query->post_count;
                if ($query->have_posts() <> "") {
                    while ($query->have_posts()): $query->the_post();

                        $room_type = $post->ID;
                        $cs_room_title = get_the_title($post->ID);
                        $is_single = 'true';
                        $temp_data['data'] = '';

                        $capacity_range = array();

                        //Get Ranges
                        $cap_args = array('posts_per_page' => '-1', 'post_type' => 'rooms_capacity', 'orderby' => 'ID', 'post_status' => 'publish');
                        $cap_args['meta_query'] = array('relation' => 'AND',
                            array(
                                'key' => 'cs_room_id',
                                'value' => $post->ID,
                                'compare' => '=',
                            )
                        );

                        $cust_query = get_posts($cap_args);

                        if (isset($cust_query) && !empty($cust_query)) {
                            foreach ($cust_query as $key => $capacity_type) {
                                $cs_room_max_adults = get_post_meta($capacity_type->ID, 'cs_room_max_adults', true);
                                $cs_room_max_child = get_post_meta($capacity_type->ID, 'cs_room_max_child', true);

                                if ($cs_room_max_adults >= $total_adults && $cs_room_max_child >= $total_childs) {
                                    $capacity_range[] = $capacity_type->ID;
                                } else {
                                    //Range Unavailable
                                }
                            }
                        }


                        if (isset($capacity_range) && !empty($capacity_range)) {
                            $capacity_counter = 0;
                            foreach ($capacity_range as $key => $capacity_type) {
                                $capacity_counter++;
                                $cs_rooms = get_post_meta($capacity_type, 'cs_room_meta_data', true);
                                if (isset($cs_rooms) && !empty($cs_rooms)) {
                                    $cs_availabilty_counter = 0;
                                    foreach ($cs_rooms as $key => $cs_room_refernce) {

                                        if ($cs_room_refernce['status'] == 'active') {

                                            $booking_count = $this->cs_check_booking($key, $date_from, $date_to);
                                            if ($booking_count <= 0) {
                                                //Rooms Available
                                                $cs_availabilty_counter++;
                                            }
                                        } else {
                                            //No Rooms Found
                                        }
                                    }


                                    if (isset($cs_availabilty_counter) && $cs_availabilty_counter > 0) {
                                        $cs_room_capacity_data['id'] = $capacity_type;
                                        $cs_room_capacity_data['title'] = get_the_title($capacity_type);
                                        $temp_data['data'][] = $cs_room_capacity_data;
                                    }
                                }
                            } //

                            if (isset($temp_data) && !empty($temp_data)) {

                                $width = '202';
                                $height = '146';
                                $data_set = 'true';
                                $cs_gallery = get_post_meta($room_type, "cs_room_image_gallery", true);
                                $cs_gallery = explode(',', $cs_gallery);

                                if (is_array($cs_gallery) && count($cs_gallery) > 0 && $cs_gallery[0] != '') {
                                    $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                                }

                                if ($thumbnail == '') {
                                    $thumbnail = wp_hotel_booking::plugin_url() . '/assets/images/no-image.png';
                                }

                                $json['output'] .= '<div class="bk-room-availabilty">';
                                $json['output'] .= '<figure><a href="javascript:;"><img src="' . esc_url($thumbnail) . '" alt=""  /></a></figure>';
                                $json['output'] .= '<div class="bk-room-detail">';
                                $json['output'] .= '<div class="bk-room-name">';
                                $json['output'] .= $cs_room_title;
                                $json['output'] .= '</div>';
                                $json['output'] .= '<div class="bk-room-capacity">';
                                $cs_options = '<option value="">Room Capacity</option>';
                                if (isset($temp_data['data']) and $temp_data['data'] <> '') {
                                    foreach ($temp_data['data'] as $key => $value) {
                                        $cs_options .= '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
                                    }
                                }

                                $json['output'] .= '<select name="cs_room_capacity[' . $room_type . '][]" data-reference="null" data-room_type="' . $room_type . '" id="cs-select-room">' . $cs_options . '</select>';
                                $json['output'] .= '</div>';
                                $json['output'] .= '</div>';
                                $json['output'] .= '</div>';
                            }
                        }

                    endwhile;
                    wp_reset_postdata();

                    if ($data_set == 'false') {
                        $json['output'] = 'false';
                    }
                } else {
                    $json['data_set'] = 'false';
                }
            }

            return $json['output'];
            die;
        }

        /**
         *
         * @Check Booking
         *
         */
        public function cs_check_booking($key = '', $date_from = '', $date_to = '') {
			

            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'booking',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'cs_booked_room',
                        'value' => $key,
                        'compare' => 'LIKE',
                    ),
                    array(
                        'key' => 'cs_check_in_date',
                        'value' => $date_from,
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    ),
                    array(
                        'key' => 'cs_check_out_date',
                        'value' => $date_to,
                        'compare' => '<=',
                        'type' => 'NUMERIC'
                    ),
                    array(
                        'key' => 'cs_booking_status',
                        'value' => 'confirmed',
                        'compare' => '=',
                    )
                ),
            );


            $booking_query = new WP_Query($args);
            return $booking_query->post_count;
        }

        /**
         *
         * @Check Booking
         *
         */
        public function cs_get_room_extras_detail() {
            global $post, $cs_plugin_options;

            $extra_id = $_REQUEST['extra_id'];
            $guests = $_REQUEST['guests'];
            $nights = $_REQUEST['nights'];

            $cs_currency = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '';
            $cs_extras_options = isset($cs_plugin_options['cs_extra_features_options']) ? $cs_plugin_options['cs_extra_features_options'] : '';
            $extras = $cs_extras_options[$extra_id];
            if (is_array($extras) && !empty($extras)) {
                $price = $extras['cs_extra_feature_price'];
                if ($price != '') {
                    if ($extras['cs_extra_feature_guests'] == 'per-head') {
                        $json['price'] = ($guests * $price);
                    } else {
                        $json['price'] = $guests;
                    }

                    if (isset($nights) && !empty($nights)) {
                        $json['price'] = $json['price'] * $nights;
                    }

                    $json['total_price'] = $json['price'];
                    $json['price'] = $cs_currency . $json['price'];
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = __('Some error occur,please try again later.', 'booking');
            }

            echo json_encode($json);
            die();
        }

    }

    new booking_meta();
}