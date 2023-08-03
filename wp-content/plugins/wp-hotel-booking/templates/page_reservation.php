<?php
/**
 * @Reservation page Template
 *
 */
	global $post, $cs_plugin_options, $cs_notification;
	
	get_header();
	
	unset($_SESSION['reserved_rooms']);
	$params = array();

if (( isset($_POST['date_from']) && $_POST['date_from'] != '' ) &&
        ( isset($_POST['date_to']) && $_POST['date_to'] != '' ) &&
        ( isset($_POST['cs_hotels']) && $_POST['cs_hotels'] != '' )
) {
    $cs_number_room = isset($_POST['cs-rooms']) ? $_POST['cs-rooms'] : array();
    $date_from = str_replace('/', '-', $_POST['date_from']);
    $date_to = str_replace('/', '-', $_POST['date_to']);
	
	$start_time = date('H:i', strtotime($date_from));
    $end_time = date('H:i', strtotime($date_to));
	
    $date_from = date('Y-m-d', strtotime($date_from));
    $date_to = date('Y-m-d', strtotime($date_to));

    $params['cs_hotel'] 	= $_POST['cs_hotels'];
    $params['start_date'] 	= $date_from;
    $params['end_date'] 	= $date_to;
	$params['start_time'] 	= $start_time;
    $params['end_time'] 	= $end_time;
    $params['total_adults'] = $_POST['adults'];
    $params['total_childs'] = $_POST['childs'];
    $params['booking_id']	= '';
    $params['room_id'] 		= '';
    $params['capacity'] 	= '';
    $params['total_days'] 	= '';
    $params['no_of_rooms']  = isset($_POST['cs-rooms']) ? $_POST['cs-rooms'] : array();


    $member_data = array();
    for ($i = 0; $i < $cs_number_room; $i++) {
        $member_data['room_data'][$i]['adults'] = isset($_POST['adults'][$i]) ? $_POST['adults'][$i] : 0;
        $member_data['room_data'][$i]['childs'] = isset($_POST['childs'][$i]) ? $_POST['childs'][$i] : 0;
    }

    $params['member_data'] = $member_data;

    //Set Session
    cs_set_session($params);
    $params['member_data'] = isset($member_data['room_data']) ? $member_data['room_data'] : array();
} else {

    $session_data 			= isset($_SESSION['cs_reservation']) ? $_SESSION['cs_reservation'] : '';
    $params['start_date']   = isset($session_data['start_date']) && $session_data['start_date'] != '' ? $session_data['start_date'] : date('Y-m-d');
    $params['end_date'] 	= isset($session_data['end_date']) && $session_data['end_date'] != '' ? $session_data['end_date'] : date('Y-m-d');
	$params['start_time'] 	= isset($session_data['start_time']) && $session_data['start_time'] != '' ? $session_data['start_time'] : date('H:i A');
    $params['end_time'] 	= isset($session_data['end_time']) && $session_data['end_time'] != '' ? $session_data['end_time'] : date('H:i A');
    $params['no_of_rooms']  = isset($session_data['no_of_rooms']) && $session_data['no_of_rooms'] != '' ? $session_data['no_of_rooms'] : '2';
    $params['member_data']  = isset($session_data['member_data']) && !empty($session_data['member_data']) ? $session_data['member_data'] : array();
    $params['cs_hotel'] 	= isset($session_data['cs_hotel_id']) && !empty($session_data['cs_hotel_id']) ? $session_data['cs_hotel_id'] : '';
}

	


if (isset($_GET['action']) && $_GET['action'] == 'booking') {
    if ($cs_plugin_options['cs_allow_user_booking'] == 'on') {
        if (isset($_SESSION['cs_reservation']) && !empty($_SESSION['cs_reservation'])) {
			
			$cs_hotel_id = $params['cs_hotel'];
			$date_from 	 = $params['start_date'];
			$date_to	 = $params['end_date'];
			$no_of_rooms = $params['no_of_rooms'];
			$member_data  = $params['member_data'];
			$total_adults = isset($params['member_data'][0]['adults']) ? $params['member_data'][0]['adults'] : '';
			$total_childs = isset($params['member_data'][0]['childs']) ? $params['member_data'][0]['childs'] : '';
		
			$cs_page_id = isset($cs_plugin_options['cs_reservation']) && $cs_plugin_options['cs_reservation'] != '' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
			$search_link = add_query_arg(array('action' => 'booking'), esc_url(get_permalink($cs_page_id)));
			$currency_sign = isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] != '' ? $cs_plugin_options['currency_sign'] : '$';
			$cs_payment_vat = isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] != '' ? $cs_plugin_options['cs_payment_vat'] : '0';
			$cs_vat_switch = isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';
			
			$full_pay = isset($cs_plugin_options['cs_allow_full_pay']) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
			$cs_advance_deposit = isset($cs_plugin_options['cs_advnce_deposit']) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '100';
	
            include('room_search.php');
			
        } else {
            ?>
            <div class="container">
                <div class="row">
                    <div class="element-size-100">
                        <div class="col-md-12">
                            <div class="search-back">
                                <?php
									if (isset($_POST['cs_hotels']) && $_POST['cs_hotels'] == '') {
										$cs_notification->error(__('Oops! please select a hotel first.', 'booking'));
									} else {
										$cs_notification->error(__('Oops! direct access is not allowed.', 'booking'));
									}
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    } else {
        ?>
        <div class="container">
            <div class="row">
                <div class="element-size-100">
                    <div class="col-md-12">
                        <div class="search-back">
        					<?php $cs_notification->warning(__('Booking is disabled.', 'booking')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

get_footer();
