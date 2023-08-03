<?php
/**
 * File Type: Template Functions
 */
/**
 *
 * @Check Booking
 *
 */
if (!function_exists('cs_check_booking')) {

    function cs_check_booking($key = '', $date_from = '', $date_to = '') {
        global $wpdb;

        $check_in_date = strtotime($date_from);
        $check_out_date = strtotime($date_to);
        $room_key = strtolower($key);

        $sql = "SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts 
				INNER JOIN {$wpdb->prefix}postmeta ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id ) 
				INNER JOIN {$wpdb->prefix}postmeta AS mt1 ON ( {$wpdb->prefix}posts.ID = mt1.post_id ) 
				INNER JOIN {$wpdb->prefix}postmeta AS mt2 ON ( {$wpdb->prefix}posts.ID = mt2.post_id )
				INNER JOIN {$wpdb->prefix}postmeta AS mt3 ON ( {$wpdb->prefix}posts.ID = mt3.post_id )
				INNER JOIN {$wpdb->prefix}postmeta AS mt4 ON ( {$wpdb->prefix}posts.ID = mt4.post_id )
				
				WHERE 1=1 
				
				AND (
						(
								( mt1.meta_key = 'cs_check_in_date' AND mt1.meta_value  <= '" . $check_in_date . "' ) 
							AND 
								( mt2.meta_key = 'cs_check_out_date' AND mt2.meta_value  >= '" . $check_in_date . "' )
						)
					
						OR (
									( mt1.meta_key = 'cs_check_in_date' AND mt1.meta_value  <= '" . $check_out_date . "' ) 
								AND 
									( mt2.meta_key = 'cs_check_out_date' AND mt2.meta_value  >= '" . $check_out_date . "' )
						)
						
						OR (
									( mt1.meta_key = 'cs_check_in_date' AND mt1.meta_value  <= '" . $check_in_date . "' ) 
								AND 
									( mt2.meta_key = 'cs_check_out_date' AND mt2.meta_value  >= '" . $check_out_date . "' )
						)
				)
				
				AND ( mt3.meta_key = 'cs_booking_status' AND CAST(mt3.meta_value AS CHAR) = 'confirmed' ) 
				AND ( mt4.meta_key = 'cs_booked_room_data' AND CAST(mt4.meta_value AS CHAR) LIKE '%" . $room_key . "%' )
				
				AND {$wpdb->prefix}posts.post_type = 'booking' 
				AND (({$wpdb->prefix}posts.post_status = 'publish')) 
				GROUP BY {$wpdb->prefix}posts.ID 
				ORDER BY {$wpdb->prefix}posts.post_date DESC";

        $room_query = $wpdb->get_results($sql, OBJECT);
        if (isset($wpdb->num_rows) && $wpdb->num_rows > 0) {
            return '1';
        } else {
            return '0';
        }
    }

}

/**
 *
 * @Set Session
 *
 */
function cs_set_session($params) {
    global $post;
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
    $cs_post_data['member_data'] = isset($member_data['room_data']) ? $member_data['room_data'] : '';

    $_SESSION['cs_reservation'] = $cs_post_data;
}

/**
 *
 * @Search Form
 *
 */
if (!function_exists('cs_search_form')) {

    function cs_search_form($params) {
        global $post, $cs_notification, $cs_plugin_options;
        extract($params);

        $cs_page_id = isset($cs_plugin_options['cs_reservation']) && $cs_plugin_options['cs_reservation'] != '' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
        $cs_max_childs = isset($cs_plugin_options['cs_childs']) && $cs_plugin_options['cs_childs'] != '' && absint($cs_plugin_options['cs_childs']) ? $cs_plugin_options['cs_childs'] : '10';
        $cs_max_adults = isset($cs_plugin_options['cs_adults']) && $cs_plugin_options['cs_adults'] != '' && absint($cs_plugin_options['cs_adults']) ? $cs_plugin_options['cs_adults'] : '10';
        wp_hotel_booking::cs_enqueue_datepicker_script();

        $search_link = add_query_arg(array('action' => 'booking'), esc_url(get_permalink($cs_page_id)));
        ob_start();
        ?>
        <div class="reservation-form" style="display:none">
            <script>
                jQuery(document).ready(function ($) {
                    cs_widget_datepicker();
                });
            </script>
            <div class="reservation-inner col-md-2 cs-search-room-elm" data-action="<?php echo esc_url($search_link); ?>">
                <div class="form-reviews">
                    <ul class="review-style box-br-style">
                        <li> <span class="select-style-two">
                                <label for=""><?php _e('Check in Date', 'booking') ?></label>
                                <label id="Deadline" class="cs-check-in cs-calendar-combo">
                                    <input type="text" name="date_from" id="check-in-date" value="<?php echo esc_attr($start_date); ?>">
                                </label>
                            </span>
                        </li>
                        <li> <span class="select-style-three">
                                <label for=""><?php _e('Check out Date', 'booking') ?></label>
                                <label id="Deadline" class="cs-check-out cs-calendar-combo">
                                    <input type="text" autocomplete="off" name="date_to" id="check-out-date" value="<?php echo esc_attr($end_date); ?>">
                                </label>
                            </span>
                        </li>
                        <li class="select-area">
                            <label for=""><?php _e('Adults', 'booking') ?> </label>
                            <span class="select-style">
                                <select id="cs_max_adults" name="adults">
                                    <option value=""><?php _e('Select', 'booking') ?></option>
                                    <?php
                                    for ($i = 1; $i <= $cs_max_adults; $i++) {
                                        $selected = '';
                                        if (isset($total_adults) && $total_adults == $i) {
                                            $selected = 'selected="selected"';
                                        }

                                        echo'<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </span> 
                        </li>
                        <li class="select-area">
                            <label for=""><?php _e('Children', 'booking') ?></label>
                            <span class="select-style-foure">
                                <select id="cs_max_childs" name="childs">
                                    <option value=""><?php _e('Select', 'booking') ?></option>
                                    <?php
                                    for ($i = 1; $i <= $cs_max_childs; $i++) {
                                        $selected = '';
                                        if (isset($total_childs) && $total_childs == $i) {
                                            $selected = 'selected="selected"';
                                        }
                                        echo'<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </span> 
                        </li>
                        <li>
                            <input class="search-btn csbg-color" id="seach_room_btn" type="button" value="<?php _e("Search Room", "booking") ?>" name="Search Room">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        $data = ob_get_clean();
        if ($return == 'true') {
            return $data;
        } else {
            echo $data;
        }
    }

}

/**
 *
 * @Get Extras
 *
 */
if (!function_exists('cs_booking_extras')) {

    function cs_booking_extras($params = '') {
        global $post, $cs_plugin_options;
        extract($params);
        $cs_meta_key = 'cs_booking_extras';

        $extrasList = array();
        $cs_currency = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '';
        $cs_extras_options = isset($cs_plugin_options['cs_extra_features_options']) ? $cs_plugin_options['cs_extra_features_options'] : '';

        $cs_output = '';
        if (is_array($cs_extras_options) && sizeof($cs_extras_options) > 0) {
            $cs_output .= '<div class="cs-check-holder">';
            $cs_output .= '<article class="cs-check-list">';
            $cs_output .= '<ul>';
            $cs_extras_counter = 0;
            foreach ($cs_extras_options as $extra_key => $extras) {

                if (isset($extra_key) && $extra_key <> '') {
                    $extras_title = isset($extras['cs_extra_feature_title']) ? $extras['cs_extra_feature_title'] : '';
                    $feature_desc = isset($extras['cs_extra_feature_desc']) ? $extras['cs_extra_feature_desc'] : '';
                    $extras_price = isset($extras['cs_extra_feature_price']) ? $extras['cs_extra_feature_price'] : '';
                    $extras_id = isset($extras['extra_feature_id']) ? $extras['extra_feature_id'] : '';
                    $checked = '';
                    $feature_type = isset($extras['cs_extra_feature_type']) ? $extras['cs_extra_feature_type'] : '';
                    $feature_guests = isset($extras['cs_extra_feature_guests']) ? $extras['cs_extra_feature_guests'] : '';

                    if (is_array($extrasList) && in_array($extras_id, $extrasList)) {
                        $checked = 'checked="checked"';
                    }

                    $cs_output .= '<li class="extras-list" data-price="' . $extras_price . '">';
                    $cs_output .= '<div class="cs-checkbox">';
                    $cs_output .= '<input type="checkbox" class="cs-extras-check" id="extra_' . $extras_id . '" name="cs_' . sanitize_html_class($id) . '[' . $extras_id . '][]" ' . $checked . ' value="' . $extras_id . '" />';
                    $cs_output .= '<label for="extra_' . $extras_id . '"><div class="title">' . esc_attr($extras_title) . '</div>';
                    $cs_output .= '<span>' . esc_attr($feature_desc) . '</span> </label>';
                    $cs_output .= '</div>';
                    $cs_output .= '<div class="extras-detail">';
                    $cs_output .= '<input type="hidden" class="cs_currency_type" id="cs_currency_type" value="' . esc_attr($cs_currency) . '" />';

                    if ($extras_price != '') {
                        $cs_output .= '<div class="check-price"> <span>' . __('Total', 'booking') . '</span> <small>' . esc_attr($cs_currency) . $extras_price . '</small> ';
                        $cs_output .= '<input type="hidden" id="cs_extras_price" class="cs_extras_price" name="cs_extras_price[' . $extras_id . '][]" value="' . $extras_price . '" />';
                        $cs_output .= '</div>';
                    }


                    if ($feature_type == 'daily') {
                        $cs_output .= '<div class="select-area"> <span class="select-label">' . __('Nights', 'booking') . '</span>';
                        $cs_output .= '<div class="select-box">';
                        $cs_output .= '<select name="cs_nights[' . $extras_id . '][]" disabled="disabled" id="cs-total-nights" class="cs-total-nights"  data-extra_id="' . $extras_id . '">';

                        for ($i = 1; $i <= $nights; $i++) {
                            $cs_output .= '<option value="' . $i . '">' . $i . '</option>';
                        }

                        $cs_output .= '</select>';
                        $cs_output .= '</div>';

                        $cs_output .= '</div>';
                    }
                    $cs_output .= '<a href="javascript:;" class="cross-icon"><i class="icon-cross3"></i></a>';
                    if ($feature_guests == 'per-head') {
                        $cs_output .= '<div class="select-area"> <span class="select-label">' . __('Guests', 'booking') . '</span>';
                        $cs_output .= '<div class="select-box">';
                        $cs_output .= '<select name="cs_guests[' . $extras_id . '][]" disabled="disabled" id="cs-total-guests" class="cs-total-guests" data-extra_id="' . $extras_id . '">';

                        for ($i = 1; $i <= $guests; $i++) {
                            $cs_output .= '<option value="' . $i . '">' . $i . '</option>';
                        }

                        $cs_output .= '</select>';
                        $cs_output .= '</div>';
                        $cs_output .= '</div>';
                    }
                    $cs_output .= '</div>';
                    $cs_output .= '</li>';
                    $cs_extras_counter++;
                }
            }
            $cs_output .= '</ul>';
            $cs_output .= '</article>';
            $cs_output .= '</div>';
        }

        return force_balance_tags($cs_output);
    }

}

/**
 *
 * @Check Room Availability
 *
 */
if (!function_exists('cs_check_availabilty')) {

    function cs_check_availabilty() {
        global $post, $cs_plugin_options;

        $cs_charge_base = isset($cs_plugin_options['cs_charge_base']) ? $cs_plugin_options['cs_charge_base'] : '';

        $capacity = $_REQUEST['capacity'];
        $room_type = $_REQUEST['room'];
        $current_room = $_REQUEST['current_room'];

        $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : array();
        $date_from = $session_data['start_date'];
        $date_to = $session_data['end_date'];
        $start_time = isset($session_data['start_time']) ? $session_data['start_time'] : '';
        $end_time = isset($session_data['end_time']) ? $session_data['end_time'] : '';
        $total_adults = $session_data['member_data'][$current_room]['adults'];
        $total_childs = $session_data['member_data'][$current_room]['childs'];
        $no_of_rooms = $session_data['no_of_rooms'];

        $json = array();
        $json['rooms_list'] = '';
        $json['selected_room'] = '';
        $json['selection_done'] = '';
        $price_breakdown = '';
        $total_days = 0;
        $price['total_price'] = 0;
        $price['total_sum'] = 0;
        $offer_discount = 0;
        $total_temp = 0;
        $total_orignal = 0;

        $booking_type = isset($cs_plugin_options['cs_rooms_booking_type']) && $cs_plugin_options['cs_rooms_booking_type'] != '' ? $cs_plugin_options['cs_rooms_booking_type'] : 'by_per_person';

        $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
        $cs_page_id = isset($cs_plugin_options['cs_reservation']) && $cs_plugin_options['cs_reservation'] != '' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
        $search_link = add_query_arg(array('action' => 'booking'), esc_url(get_permalink($cs_page_id)));

        $start_date = strtotime($date_from);
        $end_date = strtotime($date_to);

        // start to end dates with time
        $cs_start_date_time = strtotime($date_from . ' ' . $start_time);
        $cs_end_date_time = strtotime($date_to . ' ' . $end_time);

        $datetime1 = date_create($date_from);
        $datetime2 = date_create($date_to);
        $interval = date_diff($datetime1, $datetime2);
        $cs_booking_days = $interval->days;

        $cs_booking_days = $cs_bking_days = ( ($end_date - $start_date) / (60 * 60 * 24) + 1 );

        if (isset($capacity) && $capacity != '') {

            $cs_room_price = get_post_meta($capacity, 'cs_room_price', true);
            $max_adults = get_post_meta($capacity, 'cs_room_max_adults', true);
            $max_child = get_post_meta($capacity, 'cs_room_max_child', true);
            $cs_rooms = get_post_meta($capacity, 'cs_room_meta_data', true);

            $room_key = '';
            $room_id = '';
            if (isset($cs_rooms) && !empty($cs_rooms)) {
                $cs_availabilty_counter = 0;

                foreach ($cs_rooms as $key => $cs_room_refernce) {
                    if ($cs_room_refernce['status'] == 'active') {
                        $room_id = $cs_room_refernce['id'];
                        $room_key = $key;
                        $booking_count = cs_check_booking($key, $date_from, $date_to);

                        if ($booking_count <= 0) {
                            if (isset($_SESSION['reserved_rooms'][strtolower($room_key)])) {
                                // do nothing
                            } else {
                                break;
                            }
                        }
                    }
                }
            }

            $cs_counter_plus_plus = 86400;
            if ($cs_charge_base == 'hourly') {
                $cs_counter_plus_plus = 3600;
                if ($cs_end_date_time > 3600) {
                    $cs_end_date_time = (int) $cs_end_date_time - 3600;
                }
            }

            //get_num_queries().timer_stop(1);

            $flag = false;

            if ($booking_type == 'by_per_person') {
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
                    $offer_discount = 0;

                    $capacity_key = 0;
                    foreach ($capacities as $key => $value) {
                        if ($value == $capacity) {
                            $capacity_key = $key;
                        }
                    }

                    $start_date = strtotime($date_from);
                    $end_date = strtotime($date_to);

                    // Loop between timestamps, 24 hours at a time
                    $total_price = '';
                    $adult_price = 0;
                    $child_price = 0;
                    $pricing_data = array();
                    $brk_counter = 0;

                    for ($i = $cs_start_date_time; $i <= $cs_end_date_time; $i = $i + $cs_counter_plus_plus) {

                        $total_days++;
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
                                            $flag = true;
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
                                            $flag = true;
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
                        $cs_total = $adult_price_total + $child_price_total;

                        $total_temp = ( $adult_temp_price * $total_adults ) + ( $child_temp_price * $total_childs );
                        $total_orignal += $total_temp;
                        //For Discount

                        $total_price = $price['total_price'] + $cs_total;

                        $price['total_price'] = $total_price;

                        $adult_sum_total = $adult_temp_price * $total_adults;
                        $child_sum_total = $child_temp_price * $total_childs;
                        $total_sum = $adult_sum_total + $child_sum_total;
                        $total_price = $price['total_sum'] + $total_sum;
                        $price['total_sum'] = $total_price;


                        //price Breakdown
                        $price_breakdown .= '<tr>';
                        $price_breakdown .= '<td>' . date_i18n(get_option('date_format'), strtotime($thisDate)) . '</td>';
                        $price_breakdown .= '<td>' . $currency_sign . $adult_temp_price . ' (x' . $total_adults . ')</td>';
                        $price_breakdown .= '<td>' . $currency_sign . $child_temp_price . ' (x' . $total_childs . ')' . '</td>';
                        if ($flag == true) {
                            $price_breakdown .= '<td><span class="discount-total">' . $currency_sign . number_format($total_temp, 2) . ',</span><span class="discount-price"> ' . $currency_sign . number_format($cs_total, 2) . '</span>';
                        } else {
                            $price_breakdown .= '<td>' . $currency_sign . number_format($cs_total, 2);
                        }
                        $price_breakdown .= '<input type="hidden" name="cs_date[' . $brk_counter . '][]" value="' . $thisDate . '" />';
                        $price_breakdown .= '<input type="hidden" name="cs_adult_price[' . $brk_counter . '][]" value="' . $adult_price . '" />';
                        $price_breakdown .= '<input type="hidden" name="cs_child_price[' . $brk_counter . '][]" value="' . $child_price . '" />';
                        $price_breakdown .= '</td>';
                        $price_breakdown .= '</tr>';
                    }
                }

                if (empty($price_breakdown)) {
                    $price_breakdown .= '<tr><td colspan="4">' . __('Price is empty', 'booking') . '</td></tr>';
                }
            } else {
                $price['total_price'] = get_post_meta($capacity, 'cs_room_price', true);
                $start_date = strtotime($date_from);
                $end_date = strtotime($date_to);

                $bking_days = (($end_date - $start_date) / (60 * 60 * 24));

                if ($bking_days > 1) {
                    $price['total_price'] = $price['total_price'] * $bking_days;
                }

                $temp_price = $price['total_price'];
                $cs_offers_options = get_option("cs_offers_options");

                $total_orignal = $temp_price;

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
                                        $flag = true;
                                        $discount_price = ( $temp_price / 100 ) * $offer_discount;
                                        $new_price = $temp_price - $discount_price;
                                        $price['total_price'] = $new_price;
                                    }
                                }
                            }
                        }
                    }
                }

                $price['total_sum'] = $temp_price;
            }



            $json['selected_room'] .= '<div class="room-detail-wrap 55" data-price="' . str_replace(',', '', $price['total_price']) . '">';
            $json['selected_room'] .= '<div class="text">';
            $json['selected_room'] .= '<h6>' . get_the_title($room_type) . ': ' . get_the_title($capacity) . ' #' . $room_id . '</h6>';

            $json['selected_room'] .= '<p>' . __('Guests: ', 'booking') . esc_attr($total_adults) . __(' Adult(s), ', 'booking') . esc_attr($total_childs) . __(' Child(s)', 'booking') . '</p>';
            $json['selected_room'] .= '<div class="removed-data">';
            $json['selected_room'] .= '<strong>';

            if ($flag == true) {
                $json['selected_room'] .= '<span class="price">' . __('Price: ', 'booking') . '</span><span class="sidebar-total">' . esc_attr($currency_sign . number_format($price['total_sum'], 2)) . ',</span><span class="sidebar-discount-total">  ' . esc_attr($currency_sign . number_format($price['total_price'], 2)) . '</span>';
            } else {
                $json['selected_room'] .= '<span class="price">' . __('Price: ', 'booking') . esc_attr($currency_sign . number_format($price['total_price'], 2)) . '</span>';
            }

            $json['selected_room'] .= '</strong>';

            if ($booking_type == 'by_per_person') {
                $json['selected_room'] .= '<span data-toggle="modal" data-target="#myModal-' . $room_key . '">' . __('+ Price Break down', 'booking') . '</span> ';
            }

            $json['selected_room'] .= '<a href="javascript:;" data-adults="' . $total_adults . '" data-childs="' . $total_childs . '" class="change-room" id="change-room" data-change_room="' . $room_key . '" data-room="' . $room_type . '" data-capacity="' . $capacity . '"><i class="icon-compose"></i>' . __('Edit', 'booking') . '</a>';
            $json['selected_room'] .= '</div>';
            $json['selected_room'] .= '</div>';
            $json['selected_room'] .= '<small>' . __($current_room + 1, 'booking') . '</small>';

            if ($booking_type == 'by_per_person') {
                $json['selected_room'] .= '<div class="modal fade" id="myModal-' . $room_key . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none">';
                $json['selected_room'] .= '<div class="modal-dialog">';
                $json['selected_room'] .= '<div class="modal-content">';
                $json['selected_room'] .= '<div class="modal-header">';
                $json['selected_room'] .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" class="icon-cross3"></span></button>';
                $json['selected_room'] .= '<h5 class="modal-title" id="myModalLabel">' . __('Price Breakdwon', 'booking') . '</h5>';
                $json['selected_room'] .= '</div>';
                $json['selected_room'] .= '<div class="modal-body">';
                $json['selected_room'] .= '<div class="breakdown-table">';
                $json['selected_room'] .= '<table>';
                $json['selected_room'] .= '<thead>';
                $json['selected_room'] .= '<tr>';
                $json['selected_room'] .= '<th>' . __('Date', 'booking') . '</th>';
                $json['selected_room'] .= '<th>' . __('Adults:', 'booking') . '</th>';
                $json['selected_room'] .= '<th>' . __('Childrens', 'booking') . '</th>';
                $json['selected_room'] .= '<th>' . __('Total', 'booking') . '</th>';
                $json['selected_room'] .= '</tr>';
                $json['selected_room'] .= '</thead>';
                $json['selected_room'] .= '<tbody>';
                $json['selected_room'] .= force_balance_tags($price_breakdown);
                $json['selected_room'] .= '</tbody>';
                $json['selected_room'] .= '</table>';
                $json['selected_room'] .= '</div>';
                $json['selected_room'] .= '</div>';
                $json['selected_room'] .= '</div>';
                $json['selected_room'] .= '</div>';
                $json['selected_room'] .= '</div>';
                $json['selected_room'] .= '<script>
                                            (function($){
                                                    $(window).load(function(){
                                                            $(".breakdown-table").mCustomScrollbar({
                                                              theme:"dark"
                                                            });
                                                    });
                                            })(jQuery);
                                    </script>';
                $json['selected_room'] .= '</div>';
            }

            $rooms_current_array = array();
            if ($no_of_rooms > 1) {
                if (isset($_SESSION['reserved_rooms'])) {
                    $rooms_current_array = $_SESSION['reserved_rooms'];
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
            $rooms_array[strtolower($room_key)]['orignal_price'] = $total_orignal;
            $rooms_array[strtolower($room_key)]['discount'] = $offer_discount;


            $rooms_new_array = array_merge($rooms_current_array, $rooms_array);

            $_SESSION['reserved_rooms'] = $rooms_new_array;

            if (count($_SESSION['reserved_rooms']) == $no_of_rooms) {
                $json['selection_done'] .= cs_booking_detail();
                $json['status'] = 'completed';
            } else {
                $cs_next = array();
                $cs_next['capacity'] = $_REQUEST['capacity'];
                $cs_next['room_type'] = $_REQUEST['room'];
                $cs_next['current_room'] = count($_SESSION['reserved_rooms']);
                $selection_done = cs_next_room_data($cs_next);

                if (trim($selection_done) == 'false') {
                    $json['selection_done'] .= __('No Room Found', 'booking');
                } else {
                    $json['selection_done'] .= $selection_done;
                }

                $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : array();
                $total_adults = $session_data['member_data'][$cs_next['current_room']]['adults'];
                $total_childs = $session_data['member_data'][$cs_next['current_room']]['childs'];
                $json['total_adults'] = $total_adults;
                $json['total_childs'] = $total_childs;
            }

            echo json_encode($json);
            die;
        }
    }

    add_action('wp_ajax_cs_check_availabilty', 'cs_check_availabilty');
    add_action('wp_ajax_nopriv_cs_check_availabilty', 'cs_check_availabilty');
}

/**
 *
 * @Get Next Room Data
 *
 */
if (!function_exists('cs_next_room_data')) {

    function cs_next_room_data($params = array()) {
        global $post, $cs_plugin_options;
        extract($params);

        $json = array();
        $output = '';
        $cs_rooms = array();
        $cs_room_capacity_data = array();
        $temp_data = array();
        $json['output'] = '';
        $json['sidebar_content'] = '';

        $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : array();

        $date_from = $session_data['start_date'];
        $date_to = $session_data['end_date'];
        $total_adults = $session_data['member_data'][$current_room]['adults'];
        $total_childs = $session_data['member_data'][$current_room]['childs'];
        $no_of_rooms = $session_data['no_of_rooms'];
        $cs_hotel_id = $session_data['cs_hotel_id'];


        $cs_args = array('posts_per_page' => '-1', 'post_type' => 'rooms', 'orderby' => 'ID', 'post_status' => 'publish');

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
        $width = '202';
        $height = '146';
        $title_limit = 46;
        $excerpt = 120;
        $data_set = 'false';
        $json['output'] .= '<script>jQuery(document).ready(function($) { cs_check_room_availabilty() });</script>';

        if ($query->have_posts() <> "") {
            while ($query->have_posts()): $query->the_post();

                $thumbnail = '';
                $cs_post_id = $post->ID;
                $cs_postObject = get_post_meta(get_the_id(), "cs_array_data", true);
                $cs_gallery = get_post_meta($post->ID, "cs_room_image_gallery", true);
                $cs_gallery = explode(',', $cs_gallery);

                if (is_array($cs_gallery) && count($cs_gallery) > 0 && $cs_gallery[0] != '') {
                    $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                }

                if ($thumbnail == '') {
                    $thumbnail = wp_hotel_booking::plugin_url() . '/assets/images/no-image.png';
                }

                $excerpt_data = CS_FUNCTIONS()->cs_get_the_excerpt($excerpt, 'false', 'Read More');

                $room_type = $post->ID;
                $cs_room_title = get_the_title($post->ID);
                $is_single = 'true';
                $temp_data['data'] = array();
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
                            $capacity_range[$key] = $capacity_type->ID;
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

                                    $booking_count = cs_check_booking($key, $date_from, $date_to);
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
                                $temp_data['data'][$key] = $cs_room_capacity_data;
                            }
                        }
                    } //

                    if (isset($temp_data['data']) && !empty($temp_data['data'])) {

                        $data_set = 'true';

                        $cs_room_type = '';
                        $cs_room_type = get_post_meta($cs_post_id, 'cs_room_type', true);
                        $cs_price = get_post_meta($cs_post_id, 'cs_room_starting_price', true);

                        $json['output'] .= '<div class="row">';
                        $json['output'] .= '<article class="col-md-12">';
                        $json['output'] .= '<div class="plain-holder">';
                        $json['output'] .= '<figure><a href="' . get_the_permalink() . '"><img src="' . esc_url($thumbnail) . '" alt=""  /></a></figure>';
                        $json['output'] .= '<div class="accomodation-info">';
                        $json['output'] .= '<div class="plain-info">';
                        $json['output'] .= '<h5><a href="' . get_the_permalink() . '">' . cs_get_room_title(get_the_title(), $title_limit) . '</a></h5>';
                        $json['output'] .= cs_hotel_rating($cs_post_id, true);

                        if (isset($excerpt) && $excerpt > 0) {
                            $json['output'] .= '<p>' . $excerpt_data . '</p>';
                        }

                        if (isset($cs_price) && $cs_price != '') {
                            $json['output'] .= '<div class="cs-price"><small>' . __('Starts from', 'booking') . '</small> <span>' . esc_attr($cs_plugin_options['currency_sign'] . $cs_price) . '</span></div>';
                        }

                        $json['output'] .= '</div>';

                        $json['output'] .= '<div class="short-info">';

                        $featureList = get_post_meta($cs_post_id, 'cs_room_features', true);
                        $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                        $cs_output = '';
                        if (isset($featureList) && !empty($featureList)) {
                            $json['output'] .= '<ul class="cslist-info">';
                            if (is_array($cs_feature_options) && sizeof($cs_feature_options) > 0) {
                                $counter = 0;
                                foreach ($cs_feature_options as $feature) {

                                    $feature_title = $feature['cs_feats_title'];
                                    $feature_image = $feature['cs_feats_image'];
                                    $feature_slug = isset($feature['feats_id']) ? $feature['feats_id'] : '';
                                    $checked = '';
                                    $cs_image = '';

                                    if (isset($feature_image) && $feature_image != '') {
                                        $cs_image = '<img src="' . esc_url($feature_image) . '" alt="" />';
                                    } else {
                                        $cs_image = '<i>&nbsp;</i>';
                                    }

                                    if (is_array($featureList) && in_array($feature_slug, $featureList)) {
                                        $counter++;
                                        if ($counter < 5) {
                                            $json['output'] .= '<li><a href="javascript:;">' . $cs_image . '</a></li>';
                                        }
                                    }
                                }
                            }
                            $json['output'] .= '</ul>';
                        }

                        $json['output'] .= '<div class="cs-room-selection">';
                        $json['output'] .= '<select name="cs_room_capacity" class="cs_room_capacity" data-reference="null" data-room="' . absint($cs_post_id) . '">';
                        $json['output'] .= '<option value="">' . __('Select Room', 'booking') . '</option>';

                        foreach ($temp_data['data'] as $key => $value) {
                            $json['output'] .= '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
                        }

                        $json['output'] .= '</select>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</article>';
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

        return $json['output'];
        die;
    }

}

/**
 *
 * @Empty Data
 *
 */
if (!function_exists('cs_trash_data')) {

    function cs_trash_data() {
        global $post, $cs_plugin_options;

        $json = array();

        $change_room = $_REQUEST['change_room'];
        $trash_key = $_REQUEST['key'];
        $trash_id = $_REQUEST['id'];
        $json['selected_room'] = '';

        $rooms_current_array = $_SESSION['reserved_rooms'];
        unset($_SESSION['reserved_rooms'][strtolower($change_room)]);

        $json['type'] = 'success';
        $json['message'] = __('Removed', 'booking');

        $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : array();

        $date_from = $session_data['start_date'];
        $date_to = $session_data['end_date'];
        $total_adults = $session_data['member_data'][$trash_key]['adults'];
        $total_childs = $session_data['member_data'][$trash_key]['childs'];
        $no_of_rooms = $session_data['no_of_rooms'];
        $cs_hotel_id = $session_data['cs_hotel_id'];

        $output = '';
        $cs_rooms = array();
        $cs_room_capacity_data = array();
        $temp_data = array();
        $json['output'] = '';
        $json['sidebar_content'] = '';
        $cs_args = array('posts_per_page' => '-1', 'post_type' => 'rooms', 'orderby' => 'ID', 'post_status' => 'publish');

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
        $width = '202';
        $height = '146';
        $title_limit = 46;
        $excerpt = 120;

        if ($query->have_posts() <> "") {
            while ($query->have_posts()): $query->the_post();

                $thumbnail = '';
                $cs_post_id = $post->ID;
                $cs_postObject = get_post_meta(get_the_id(), "cs_array_data", true);
                $cs_gallery = get_post_meta($post->ID, "cs_room_image_gallery", true);
                $cs_gallery = explode(',', $cs_gallery);

                if (is_array($cs_gallery) && count($cs_gallery) > 0 && $cs_gallery[0] != '') {
                    $thumbnail = CS_FUNCTIONS()->cs_get_post_img($cs_gallery[0], $width, $height);
                }

                if ($thumbnail == '') {
                    $thumbnail = wp_hotel_booking::plugin_url() . '/assets/images/no-image.png';
                }

                $excerpt_data = CS_FUNCTIONS()->cs_get_the_excerpt($excerpt, 'false', 'Read More');

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

                                    $booking_count = cs_check_booking($key, $date_from, $date_to);
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

                    if (isset($temp_data['data']) && !empty($temp_data['data'])) {

                        $cs_room_type = '';
                        $cs_room_type = get_post_meta($cs_post_id, 'cs_room_type', true);
                        $cs_price = get_post_meta($cs_post_id, 'cs_room_starting_price', true);

                        $featureList = get_post_meta($cs_post_id, 'cs_room_features', true);
                        $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                        $cs_output = '';

                        $data_set = 'true';
                        $json['output'] .= '<div class="row">';
                        $json['output'] .= '<article class="col-md-12">';
                        $json['output'] .= '<div class="plain-holder">';
                        $json['output'] .= '<figure><a href="' . get_the_permalink() . '"><img src="' . esc_url($thumbnail) . '" alt=""  /></a></figure>';
                        $json['output'] .= '<div class="accomodation-info">';
                        $json['output'] .= '<div class="plain-info">';
                        $json['output'] .= '<h5><a href="' . get_the_permalink() . '">' . cs_get_room_title(get_the_title(), $title_limit) . '</a></h5>';
                        $json['output'] .= cs_hotel_rating($cs_post_id, true);

                        if (isset($excerpt) && $excerpt > 0) {
                            $json['output'] .= '<p>' . $excerpt_data . '</p>';
                        }

                        if (isset($cs_price) && $cs_price != '') {
                            $json['output'] .= '<div class="cs-price"><small>' . __('Starts from', 'booking') . '</small> <span>' . esc_attr($cs_plugin_options['currency_sign'] . $cs_price) . '</span></div>';
                        }

                        $json['output'] .= '</div>';
                        $json['output'] .= '<div class="short-info">';

                        if (isset($featureList) && !empty($featureList)) {
                            $json['output'] .= '<ul class="cslist-info">';
                            if (is_array($cs_feature_options) && sizeof($cs_feature_options) > 0) {
                                $counter = 0;
                                foreach ($cs_feature_options as $feature) {

                                    $feature_title = $feature['cs_feats_title'];
                                    $feature_image = $feature['cs_feats_image'];
                                    $feature_slug = isset($feature['feats_id']) ? $feature['feats_id'] : '';
                                    $checked = '';
                                    $cs_image = '';

                                    if (isset($feature_image) && $feature_image != '') {
                                        $cs_image = '<img src="' . esc_url($feature_image) . '" alt="" />';
                                    } else {
                                        $cs_image = '<i>&nbsp;</i>';
                                    }

                                    if (is_array($featureList) && in_array($feature_slug, $featureList)) {
                                        $counter++;
                                        if ($counter < 5) {
                                            $json['output'] .= '<li><a href="javascript:;">' . $cs_image . '</a></li>';
                                        }
                                    }
                                }
                            }
                            $json['output'] .= '</ul>';
                        }

                        $json['output'] .= '<div class="cs-room-selection">';
                        $json['output'] .= '<select name="cs_room_capacity" class="cs_room_capacity" data-reference="null" data-room="' . absint($cs_post_id) . '">';
                        $json['output'] .= '<option value="">' . __('Select Room', 'booking') . '</option>';

                        foreach ($temp_data['data'] as $key => $value) {
                            $json['output'] .= '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
                        }

                        $json['output'] .= '</select>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</div>';
                        $json['output'] .= '</article>';
                        $json['output'] .= '</div>';
                    }
                }

            endwhile;
            wp_reset_postdata();

            $json['total_adults'] = $total_adults;
            $json['total_childs'] = $total_childs;

            $json['selected_room'] .= '<div class="room-detail-wrap">';
            $json['selected_room'] .= '<div class="text">';
            $json['selected_room'] .= '<h6>' . __('Select Room', 'booking') . esc_attr(sprintf("%02d", $trash_id)) . '</h6>';

            $json['selected_room'] .= '<p>' . __('Guests', 'booking') . esc_attr($total_adults) . __(' Adult(s)', 'booking') . esc_attr($total_childs) . __(' Child(s)', 'booking') . '</p>';
            $json['selected_room'] .= '</div>';
            $json['selected_room'] .= '<small>' . absint($trash_id) . '</small>';
        } else {
            $json['type'] = 'success';
            $json['message'] = __('No Rooms found', 'booking');
        }

        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_cs_trash_data', 'cs_trash_data');
    add_action('wp_ajax_nopriv_cs_trash_data', 'cs_trash_data');
}

/**
 *
 * @Add Booking
 *
 */
if (!function_exists('cs_add_booking')) {

    function cs_add_booking() {
        global $post, $cs_plugin_options, $gateways;

        $json = array();
        $f_name = $_REQUEST['cs_f_name'];
        $l_name = $_REQUEST['cs_l_name'];
        $phone_no = $_REQUEST['cs_phone_no'];
        $email = $_REQUEST['cs_email'];
        $country = $_REQUEST['cs_country'];
        $address = $_REQUEST['cs_address'];
        $city = $_REQUEST['cs_city'];

        $cs_date = isset($_REQUEST['cs_date']) ? $_REQUEST['cs_date'] : '';
        $cs_adult_price = isset($_REQUEST['cs_adult_price']) ? $_REQUEST['cs_adult_price'] : '';
        $cs_child_price = isset($_REQUEST['cs_child_price']) ? $_REQUEST['cs_child_price'] : '';

        if (isset($_REQUEST['cs_extras_price'])) {
            $cs_extras_price = $_REQUEST['cs_extras_price'];
        } else {
            $cs_extras_price = array();
        }

        $cs_payment_gateway = $_REQUEST['cs_payment_gateway'];
        $gross_price = $_REQUEST['gross_price'];
        $vat_price = $_REQUEST['vat_price'];
        $grand_total = $_REQUEST['grand_total'];
        $gateway = $_REQUEST['gateway'];
        $payment_type = $_REQUEST['payment_type'];

        $gateway_name = '';
        if (isset($_REQUEST['cs_payment_gateway'])) {
            $gateway_name = $gateways[strtoupper($_REQUEST['cs_payment_gateway'])];
        }

        $vat_price = number_format($vat_price, 2);

        $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';

        if (isset($_REQUEST['cs_booking_extras'])) {
            $cs_booking_extras = $_REQUEST['cs_booking_extras'];
        } else {
            $cs_booking_extras = array();
        }

        if (isset($_REQUEST['cs_nights'])) {
            $cs_nights = $_REQUEST['cs_nights'];
        } else {
            $cs_nights = array();
        }

        if (isset($_REQUEST['cs_guests'])) {
            $cs_guests = $_REQUEST['cs_guests'];
        } else {
            $cs_guests = array();
        }


        if ($f_name == '' || $f_name == '' || $email == '') {
            $json['type'] = 'error';
            $json['message'] = __('Please fill required fileds', 'booking');
        } else {
            $json['gateway'] = 'pay';
            $json['type'] = 'success';
            $json['message'] = __('No Rooms found', 'booking');
            $booking_id = 'HT-' . CS_FUNCTIONS()->cs_generate_random_string(5);

            $reserved_rooms = $_SESSION['reserved_rooms'];

            $cs_booked_rooms = array();
            foreach ($reserved_rooms as $key => $value) {
                $cs_booked_rooms[] = $key;
            }

            $post_title = $booking_id;

            $booking_post = array(
                'post_title' => $post_title,
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => '',
                'post_type' => 'booking',
                'post_date' => current_time('Y-m-d h:i:s')
            );

            $post_id = wp_insert_post($booking_post);

            if (isset($post_id)) {

                // Add Customer
                $cs_cstmr_data = get_option("cs_customer_options");
                $customer_id = CS_FUNCTIONS()->cs_generate_random_string(10);
                $cs_new_customer = array();

                $cs_customer = get_option('cs_customer_options');

                if (isset($cs_customer) && !is_array($cs_customer) && empty($cs_customer)) {
                    $cs_customer = array();
                }

                $cs_new_customer[$customer_id]['cus_id'] = $customer_id;
                $cs_new_customer[$customer_id]['cus_name'] = '';
                $cs_new_customer[$customer_id]['cus_f_name'] = $f_name;
                $cs_new_customer[$customer_id]['cus_l_name'] = $l_name;
                $cs_new_customer[$customer_id]['cus_email'] = $email;
                $cs_new_customer[$customer_id]['cus_phone_no'] = $phone_no;
                $cs_new_customer[$customer_id]['cus_address'] = $address;
                $cs_new_customer[$customer_id]['cus_city'] = $city;
                $cs_new_customer[$customer_id]['cus_country'] = $country;

                $cs_all_customer = array_merge($cs_customer, $cs_new_customer);
                update_option('cs_customer_options', $cs_all_customer);

                //Calculate Prices
                $pricings = get_option('cs_price_options');
                $price['total_price'] = $gross_price;
                $total_days = 0;

                $check_in_date = $_SESSION['cs_reservation']['start_date'];
                $check_out_date = $_SESSION['cs_reservation']['end_date'];

                $start_time = isset($_SESSION['cs_reservation']['start_time']) ? $_SESSION['cs_reservation']['start_time'] : '';
                $end_time = isset($_SESSION['cs_reservation']['end_time']) ? $_SESSION['cs_reservation']['end_time'] : '';

                $start_date_time = $check_in_date . ' ' . $start_time;
                $end_date_time = $check_out_date . ' ' . $end_time;

                $datetime1 = date_create($check_in_date);
                $datetime2 = date_create($check_out_date);
                $interval = date_diff($datetime1, $datetime2);
                $total_days = $interval->days;

                $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
                $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
                $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

                $full_pay = isset($cs_plugin_options['cs_allow_full_pay']) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
                $cs_advance_deposit = isset($cs_plugin_options['cs_advnce_deposit']) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '100';

                if ($cs_vat_switch == 'on') {
                    $vat = number_format(( $price['total_price'] / 100 ) * $cs_payment_vat, 2);
                } else {
                    $vat = 0;
                }

                $gross_total = $grand_total;
                $grand_total = $grand_total;

                // Advance and Remainings if Transactions exist
                $transaction_amount = 0;

                $advance = number_format($transaction_amount, 2);
                $remaining = number_format($grand_total - $transaction_amount, 2);

                $advance_total = $grand_total;
                $due_total = $grand_total;

                if ($payment_type == 'deposit' && $cs_advance_deposit != '') {
                    $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
                    $due_total = $grand_total - $advance_total;
                }

                $advance_total = $grand_total;
                $due_total = $grand_total;

                if ($payment_type == 'deposit' && $cs_advance_deposit != '') {
                    $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
                    $due_total = $grand_total - $advance_total;
                }

                $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : array();
                $cs_hotel_id = $session_data['cs_hotel_id'];

                //$check_in_date_new	= date('Y-m-d H:i:s', strtotime( $check_in_date ));
                //$check_out_date_new	= date('Y-m-d H:i:s', strtotime( $check_out_date ));
                //$cs_check_in_date	= strtotime( $check_in_date_new );
                //$cs_check_out_date	= strtotime( $check_out_date_new );
                //Update Booking
                update_post_meta($post_id, 'cs_hotel_id', $cs_hotel_id);
                update_post_meta($post_id, 'cs_booking_id', $booking_id);
                update_post_meta($post_id, 'cs_booking_num_days', $total_days);
                update_post_meta($post_id, 'cs_bkng_grand_total', $grand_total);
                update_post_meta($post_id, 'cs_bkng_advance', $advance_total);
                update_post_meta($post_id, 'cs_bkng_remaining', $due_total);
                update_post_meta($post_id, 'cs_bkng_gross_total', $price['total_price']);
                update_post_meta($post_id, 'cs_check_in_date', strtotime($start_date_time));
                update_post_meta($post_id, 'cs_check_out_date', strtotime($end_date_time));
                update_post_meta($post_id, 'cs_booking_status', 'pending');
                update_post_meta($post_id, 'cs_select_guest', $customer_id);
                update_post_meta($post_id, 'cs_invoice', $post_id);
                update_post_meta($post_id, 'cs_bkng_tax', $vat_price);
                update_post_meta($post_id, 'cs_bkng_vat_percentage', $cs_payment_vat);
                update_post_meta($post_id, 'cs_payment_type', $payment_type);

                update_post_meta($post_id, 'cs_booked_room_data', $reserved_rooms);
                update_post_meta($post_id, 'cs_booked_room', $cs_booked_rooms);
                update_post_meta($post_id, 'cs_gateway', $gateway_name);


                foreach ($_POST as $key => $value) {
                    if (strstr($key, 'cs_')) {
                        update_post_meta($post_id, $key, $value);
                    }
                }

                //Gateway
                $gateway = isset($_REQUEST['cs_payment_gateway']) ? $_REQUEST['cs_payment_gateway'] : '';

                $cs_transactions_data = array();
                $cs_transactions_data['order_id'] = $post_id;
                $cs_transactions_data['price'] = $advance_total;
                $cs_transactions_data['item_name'] = $post_title;
                $cs_transactions_data['cs_invoice'] = $post_id;
                $cs_transactions_data['cs_booking_id'] = $booking_id;
                $cs_transactions_data['cs_grand_total'] = $grand_total;
                $cs_transactions_data['remaining'] = $due_total;

                $_SESSION['cs_session_booked_id'] = $post_id;

                if (class_exists('CS_PAYMENTS')) {
                    if (isset($gateway) && $gateway == 'cs_paypal_gateway') {
                        $paypal_gateway = new CS_PAYPAL_GATEWAY();
                        $json['form'] = $paypal_gateway->cs_proress_request($cs_transactions_data);
                    } else if (isset($gateway) && $gateway == 'cs_authorizedotnet_gateway') {
                        $authorizedotnet = new CS_AUTHORIZEDOTNET_GATEWAY();
                        $json['form'] = $authorizedotnet->cs_proress_request($cs_transactions_data);
                    } else if (isset($gateway) && $gateway == 'cs_skrill_gateway') {
                        $skrill = new CS_SKRILL_GATEWAY();
                        $json['form'] = $skrill->cs_proress_request($cs_transactions_data);
                    } else if (isset($gateway) && $gateway == 'cs_pre_bank_transfer') {
                        $banktransfer = new CS_PRE_BANK_TRANSFER();
                        $json['gateway'] = 'transfer';
                        $json['form'] = $banktransfer->cs_proress_request($cs_transactions_data);
                    }
                }
            }
        }

        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_cs_add_booking', 'cs_add_booking');
    add_action('wp_ajax_nopriv_cs_add_booking', 'cs_add_booking');
}

/**
 *
 * @Get Booking HTML
 *
 */
if (!function_exists('cs_booking_detail')) {

    function cs_booking_detail() {
        global $post, $cs_notification, $cs_plugin_options;

        $json = array();
        $json['reservation_detail'] = '';

        $session_data = isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : array();
        $date_from = $session_data['start_date'];
        $date_to = $session_data['end_date'];
        $member_data = $session_data['member_data'];
        $no_of_rooms = $session_data['no_of_rooms'];
        $reserved_rooms = $_SESSION['reserved_rooms'];

        $price['total_price'] = 0.00;
        foreach ($reserved_rooms as $key => $value) {
            $ses_capacity = $value['capacity'];
            $ses_key = $value['key'];
            $ses_room_type = $value['room_type'];
            $ses_adults = $value['adults'];
            $ses_childs = $value['childs'];
            $ses_room_id = $value['room_id'];
            $price['total_price'] = number_format($value['price'], 2);
        }

        $cs_page_id = isset($cs_plugin_options['cs_reservation']) && $cs_plugin_options['cs_reservation'] != '' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';

        $search_link = add_query_arg(array('action' => 'booking'), esc_url(get_permalink($cs_page_id)));

        $currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
        $cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
        $cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';

        $full_pay = isset($cs_plugin_options['cs_allow_full_pay']) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
        $extras_switch = isset($cs_plugin_options['cs_extras_switch']) && $cs_plugin_options['cs_extras_switch'] == 'on' ? $cs_plugin_options['cs_extras_switch'] : 'off';

        $cs_advance_deposit = isset($cs_plugin_options['cs_advnce_deposit']) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '100';

        $total_members = 0;
        foreach ($member_data as $key => $member) {
            $adults = $member['adults'];
            $childs = $member['childs'];
            $total_members += $adults + $childs;
        }


        $datetime1 = date_create($date_from);
        $datetime2 = date_create($date_to);

        $interval = date_diff($datetime1, $datetime2);
        $total_days = $interval->days;

        ob_start();
        //cs_extras_switch
        $json['reservation_detail'] .= '<div class="tabs-content">';
        if ($extras_switch == 'on') {
            $json['reservation_detail'] .= '<div class="tabs" id="tab1" style="display: block;">';
        } else {
            $json['reservation_detail'] .= '<div class="tabs" id="tab1" style="display: none;">'
                    . '<script type="javascript/text">'
                    . '$("#tab1").removeClass("active");'
                    . '$("#tab2").addClass("active");'
                    . '</script>';
        }
        $json['reservation_detail'] .= '<div class="cs-section-title">';
        $json['reservation_detail'] .= '<h2>' . __('Reservation Detail', 'booking') . '</h2>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="cs-chcked-area">';
        $json['reservation_detail'] .= '<script>jQuery(document).ready(function() { cs_gross_calculation(); });</script>';
        if ($extras_switch == 'on') {
            $json['reservation_detail'] .= cs_booking_extras(
                    array('name' => __('Extras', 'booking'),
                        'id' => 'booking_extras',
                        'classes' => '',
                        'guests' => $total_members,
                        'nights' => $total_days,
                        'post_id' => '',
                    )
            );
        }
        $json['reservation_detail'] .= '<div class="button_style cs-process-wrap"> <a href="javascript:;" class="continue-btn btnNext">' . __('Continue', 'booking') . '</a> </div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        if ($cs_vat_switch == 'on') {
            $vat = number_format(( $price['total_price'] / 100 ) * $cs_payment_vat, 2);
        } else {
            $vat = 0;
        }

        $gross_total = $price['total_price'];
        $grand_total = number_format($price['total_price'] + $vat, 2);

        // Advance and Remainings if Transactions exist
        $transaction_amount = 0;

        $advance = number_format($transaction_amount, 2);
        $remaining = number_format($grand_total - $transaction_amount, 2);

        $advance_total = $grand_total;
        $due_total = 0;

        if ($full_pay == 'off' && $cs_advance_deposit != '') {
            $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
            $due_total = $grand_total - $advance_total;
        }


        // User Information

        if ($extras_switch == 'on') {
            $json['reservation_detail'] .= '<div class="tabs" id="tab2" style="display: none;">';
        } else {
            $json['reservation_detail'] .= '<div class="tabs" id="tab2" style="display: block;">';
        }


        $json['reservation_detail'] .= '<div class="cs-section-title">';
        $json['reservation_detail'] .= '<h2>';
        $json['reservation_detail'] .= __('Your Detail', 'booking');
        $json['reservation_detail'] .= '</h2>';
        $json['reservation_detail'] .= ' </div>';
        $json['reservation_detail'] .= '<div class="element-size-100">';
        $json['reservation_detail'] .= '<div class="row">';
        $json['reservation_detail'] .= '<div class="fields-area">';
        $json['reservation_detail'] .= '<div class="field-col col-md-6">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('First Name *', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<input type="text" id="cs_f_name" name="cs_f_name" value="" placeholder="' . __('First Name', 'booking') . '">';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="field-col col-md-6">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('Last Name *', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<input type="text" id="cs_l_name" name="cs_l_name" value="" placeholder="' . __('Last Name', 'booking') . '">';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="fields-area">';
        $json['reservation_detail'] .= '<div class="field-col col-md-6">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('Email *', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_email" name="cs_email" placeholder="' . __('Email', 'booking') . '" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="field-col col-md-6">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('Telephone Number', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_phone_no" name="cs_phone_no" value="" placeholder="' . __('Telephone Number', 'booking') . '" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="fields-area">';
        $json['reservation_detail'] .= '<div class="field-col col-md-6">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('Country', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<div class="select-holder">';
        $json['reservation_detail'] .= '<select name="cs_country" id="cs_country">';
        $countries = cs_get_countries();
        foreach ($countries as $value) {
            $json['reservation_detail'] .= '<option value="' . $value . '">' . $value . '</option>';
        }
        $json['reservation_detail'] .= ' </select>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="field-col col-md-6">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('City', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_city" name="cs_city" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="fields-area">';
        $json['reservation_detail'] .= '<div class="field-col col-md-12">';
        $json['reservation_detail'] .= '<label>';
        $json['reservation_detail'] .= __('Address', 'booking');
        $json['reservation_detail'] .= '</label>';
        $json['reservation_detail'] .= '<input type="text"  id="cs_address" name="cs_address" />';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        //Payments

        if ($full_pay == 'off' && $cs_advance_deposit != '') {
            $advance_total = ( $grand_total / 100 ) * $cs_advance_deposit;
        }

        $json['reservation_detail'] .= '<div class="cs-section-title">';
        $json['reservation_detail'] .= '<h2>' . __('Make Payment', 'booking') . '</h2>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="pypal-sec">';
        $json['reservation_detail'] .= '<div class="radio-box">';

        if (isset($full_pay) && $full_pay == 'on') {
            $selected_depost = '';
            $display_deposit = 'style="display:none"';
            $json['reservation_detail'] .= '<input checked="checked" class="cs-set-pay-type" type="radio" name="payment_type" id="payment_type_full" value="full">';
            $json['reservation_detail'] .= '<label for="payment_type_full">' . __('Full Payment', 'booking') . '</label>';
        } else {
            $selected_depost = 'checked="checked"';
            $display_deposit = '';
        }

        if (isset($cs_advance_deposit) && $cs_advance_deposit != '') {
            $json['reservation_detail'] .= '<input type="radio" ' . $selected_depost . ' class="cs-set-pay-type" name="payment_type" id="payment_type_deposit" value="deposit">';
            $json['reservation_detail'] .= '<label for="payment_type_deposit">' . __('Deposit Amount', 'booking') . '</label>';
        }

        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="pypal-price">';
        $json['reservation_detail'] .= '<ul>';
        $json['reservation_detail'] .= '<li class="cs-total-amount">' . __('Total Amount', 'booking') . '<span></span></li>';
        if (isset($cs_advance_deposit) && $cs_advance_deposit != '') {
            $json['reservation_detail'] .= '<li ' . $display_deposit . ' class="cs-deposit-amount">' . __('Doposit Amount', 'booking') . '<span>' . $currency_sign . $advance_total . '</span></li>';
        }
        $json['reservation_detail'] .= '</ul>';

        if (isset($cs_advance_deposit) && $cs_advance_deposit != '') {
            $json['reservation_detail'] .= '<p class="cs-arrival-data" ' . $display_deposit . '>(' . $cs_advance_deposit . '%)<span> ' . __('*Pay the rest on arrival', 'booking') . '</span></p>';
        }

        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="select-payments">';
        $json['reservation_detail'] .= '<h6>' . __('Select Payment Method', 'booking') . '</h6>';
        $json['reservation_detail'] .= '<ul id="cs-gateway-wrap">';
        $json['reservation_detail'] .= '<li>';

        global $gateways;
        $object = new CS_PAYMENTS();
        $cs_gw_counter = 1;
        foreach ($gateways as $key => $value) {

            $selected = '';
            if ($key == 'CS_PAYPAL_GATEWAY') {
                $selected = 'checked="checked"';
            }

            $status = $cs_plugin_options[strtolower($key) . '_status'];

            if (isset($status) && $status == 'on') {
                $logo = '';

                if (isset($cs_plugin_options[strtolower($key) . '_logo'])) {
                    $logo = $cs_plugin_options[strtolower($key) . '_logo'];
                }



                $json['reservation_detail'] .= '<div class="radio-image-wrapper">';
                $json['reservation_detail'] .= '<input class="cs-gateway-calculation" type="radio" ' . $selected . '  name="cs_payment_gateway" value="' . strtolower($key) . '" id="' . strtolower($key) . '" />';

                if (isset($logo) && $logo != '') {
                    $json['reservation_detail'] .= '<label for="' . strtolower($key) . '"><span><img src="' . $logo . '" alt="" /></span> </label>';
                }
                $json['reservation_detail'] .= '</div>';
            }
        }

        $json['reservation_detail'] .= '</li>';
        $json['reservation_detail'] .= '</ul>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '<div class="button_style cs-process-wrap"> <a href="javascript:;" class="continue-btn btnNext">' . __('Pay Now', 'booking') . '</a> </div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';


        //Confirmation

        $json['reservation_detail'] .= '<div class="tabs" id="tab3" style="display: none;">';
        $json['reservation_detail'] .= '<div class="cs-confirmation">';
        $json['reservation_detail'] .= '<figure>';
        $json['reservation_detail'] .= '<i class="icon-check"></i>';
        $json['reservation_detail'] .= '</figure>';
        $json['reservation_detail'] .= '<div class="text">';
        $json['reservation_detail'] .= '<h5>' . __('Thank you!!!', 'booking') . '</h5>';

        if ($cs_plugin_options['cs_thank_msg'] && $cs_plugin_options['cs_thank_msg'] != '') {
            $json['reservation_detail'] .= '<p>' . __($cs_plugin_options['cs_thank_msg'], 'booking') . '</p>';
        }
        if ($cs_plugin_options['cs_thank_title'] && $cs_plugin_options['cs_thank_title'] != '') {
            $json['reservation_detail'] .= '<p>' . __($cs_plugin_options['cs_thank_title'], 'booking') . '</p>';
        }

        $json['reservation_detail'] .= '<ul>';
        if ($cs_plugin_options['cs_confir_phone'] && $cs_plugin_options['cs_confir_phone'] != '') {
            $json['reservation_detail'] .= '<li><span>' . __('Phone:', 'booking') . '</span>' . __($cs_plugin_options['cs_confir_phone'], 'booking') . '</li>';
        }

        if ($cs_plugin_options['cs_confir_fax'] && $cs_plugin_options['cs_confir_fax'] != '') {
            $json['reservation_detail'] .= '<li><span>' . __('Fax:', 'booking') . '</span>' . __($cs_plugin_options['cs_confir_fax'], 'booking') . '</li>';
        }

        if ($cs_plugin_options['cs_confir_email'] && $cs_plugin_options['cs_confir_email'] != '') {
            $json['reservation_detail'] .= '<li><span>' . __('Email:', 'booking') . '</span><a href="mailto:' . $cs_plugin_options['cs_confir_email'] . '&subject=hello">' . __($cs_plugin_options['cs_confir_email'], 'booking') . '</a></li>';
        }

        $json['reservation_detail'] .= '</ul>';
        $json['reservation_detail'] .= '<a class="go-back" href="' . esc_url(home_url()) . '" class="pyment-btn csbg-color">' . __('BACK TO HOME PAGE', 'booking') . '</a> </div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';
        $json['reservation_detail'] .= '</div>';

        return $json['reservation_detail'];
        die;
    }

    add_action('wp_ajax_cs_booking_detail', 'cs_booking_detail');
    add_action('wp_ajax_nopriv_cs_booking_detail', 'cs_booking_detail');
}

/**
 *
 * @Countries Array
 *
 */
if (!function_exists('cs_get_countries')) {

    function cs_get_countries() {
        $get_countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan",
            "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "British Virgin Islands",
            "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China",
            "Colombia", "Comoros", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Democratic People's Republic of Korea", "Democratic Republic of the Congo", "Denmark", "Djibouti",
            "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "England", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "French Polynesia",
            "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong",
            "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan",
            "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia",
            "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique",
            "Myanmar(Burma)", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Northern Ireland",
            "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico",
            "Qatar", "Republic of the Congo", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa",
            "San Marino", "Saudi Arabia", "Scotland", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa",
            "South Korea", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
            "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "US Virgin Islands", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
            "Uzbekistan", "Vanuatu", "Vatican", "Venezuela", "Vietnam", "Wales", "Yemen", "Zambia", "Zimbabwe");
        return $get_countries;
    }

}

/**
 *
 * @Email Subject Wordpress To Custom
 *
 */
add_filter('wp_mail_from_name', 'cs_wp_mail_from_name');

function cs_wp_mail_from_name($original_email_from) {
    return get_bloginfo('name') . __('-Booking Order', 'boookig');
}
?>