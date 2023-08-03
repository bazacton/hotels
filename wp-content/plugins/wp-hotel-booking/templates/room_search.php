<?php
/**
 *
 * @Search page Template
 *
 */
global $post, $cs_notification, $cs_plugin_options;
get_header();

$params['return'] = 'false';
if (isset($_GET['invoice']) && $_GET['invoice'] != '') {
    include('confirmation.php');
} else {
    $view_start_time = '';
    $view_end_time = '';
    if (isset($start_time) && $start_time != '') {
        $view_start_time = ' ' . date('h:i A', strtotime($start_time));
    }
    if (isset($end_time) && $end_time != '') {
        $view_end_time = ' ' . date('h:i A', strtotime($end_time));
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="section-fullwidth reservation-editor">
                <form action="#" method="post" id="booking_form">
                    <div class="page-sidebar">
                        <div class="col-md-12">
                            <div class="reservation-search ">
                                <div class="reservation-box">
                                    <div class="search-inner">
                                        <div class="search-summery">
                                            <div class="undo-search">
                                                <span><?php _e('Search Summery', 'booking'); ?></span>
                                                <a href="javascript:history.go(-1)"><i class="icon-refresh3"></i></a>
                                            </div>
                                            <ul>
                                                <li><?php _e('Check-In', 'booking'); ?><span><?php echo esc_attr($date_from) . $view_start_time; ?></span></li>
                                                <li><?php _e('Check-Out', 'booking'); ?><span><?php echo esc_attr($date_to) . $view_end_time; ?></span></li>
                                                <li><?php _e('No. of Rooms', 'booking'); ?><span><?php echo esc_attr(sprintf("%02d", $no_of_rooms)); ?><?php _e(' Rooms', 'booking'); ?></span></li>
                                            </ul>
                                        </div>
                                        <div class="rooms-list">
                                            <ul class="booking-rooms cs-gross-calculation"  data-full_pay="<?php echo esc_attr($full_pay); ?>" data-advance="<?php echo esc_attr($cs_advance_deposit); ?>" data-vat_switch="<?php echo esc_attr($cs_vat_switch); ?>" data-vat="<?php echo esc_attr($cs_payment_vat); ?>" data-currency="<?php echo esc_attr($currency_sign); ?>">
                                                <?php
                                                if ($member_data && !empty($member_data)) {
                                                    $cs_select_counter = 0;
                                                    foreach ($member_data as $key => $data) {
                                                        $cs_select_counter++;
                                                        $selected = $cs_select_counter == 1 ? ' cs-current-room' : '';
                                                        ?>
                                                        <li class="room-<?php echo absint($cs_select_counter); ?><?php echo esc_attr($selected); ?>" data-key="<?php echo absint($key); ?>" data-id="<?php echo absint($cs_select_counter); ?>">
                                                            <div class="text">
                                                                <h6><?php _e('Select Room ', 'booking'); ?><?php echo sprintf("%02d", $cs_select_counter); ?></h6>
                                                                <p><?php _e('Guests: ', 'booking'); ?><?php echo absint($data['adults']); ?><?php _e(' Adult(s), ', 'booking'); ?>  <?php echo absint($data['childs']); ?><?php _e(' Child(s)', 'booking'); ?></p>
                                                            </div>
                                                            <small><?php echo absint($cs_select_counter); ?></small>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                        <div class="extras" id="sidebar-extras-area" style="display:none">
                                            <ul class="booking-extras" style="display:none"></ul>
                                            <div class="total-price">
                                                <ul>
                                                    <li class="total-price-wrap">
                                                        <p><?php _e('Total Amount', 'booking'); ?></p>
                                                        <span></span></li>
                                                    <?php if ($cs_vat_switch == 'on') { ?>
                                                        <li class="vat-wrap">
                                                            <p><?php _e('Vat', 'booking'); ?>(<?php echo esc_attr($cs_payment_vat); ?>%)</p>
                                                            <span></span>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="price-wrapp" id="sidebar-price-area" style="display:none">
                                            <div class="price-area">
                                                <div class="price-box">
                                                    <h6><?php _e('Grand total', 'booking'); ?></h6>
                                                    <h1></h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <section class="page-section">
                            <div class="container">
                                <div class="row">
                                    <div class="element-size-100">
                                        <div class="col-md-12">
                                            <div class="cs-reservation-tabs">
                                                <ul class="tabs-nav" style="display:none">
                                                    <li class="active">
                                                        <i class="icon-plus8"></i>
                                                        <a href="javascript:;" data-id="tab1"><span><?php _e('STEP 1', 'booking'); ?></span><?php _e('Add Essentials', 'booking'); ?></a>
                                                    </li>
                                                    <li>
                                                        <i class="icon-spotify3"></i>
                                                        <a href="javascript:;" data-id="tab2"><span><?php _e('STEP 2', 'booking'); ?></span><?php _e('Reservation and Payments', 'booking'); ?></a>
                                                    </li>
                                                    <li>
                                                        <i class="icon-checkmark6"></i>
                                                        <a href="javascript:;" data-id="tab3"><span><?php _e('STEP 3', 'booking'); ?></span><?php _e('Confirmation', 'booking'); ?></a>
                                                    </li>
                                                </ul>
                                                <div class="heading-wrappe">
                                                    <div class="select-heading">
                                                        <span class="select-number"><?php _e('1', 'booking'); ?></span>
                                                        <h2><?php _e('Select Room ', 'booking'); ?><span class="cs-current-no"><?php _e('1', 'booking'); ?></span></h2>
                                                        <span class="select-info"><?php _e('Guests', 'booking'); ?> <span class="room-adults"><?php echo esc_attr($total_adults); ?></span> <?php _e(' Adult(s)', 'booking'); ?>, <span class="room-childs"><?php echo esc_attr($total_childs); ?></span> <?php _e(' Child(s)', 'booking'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="cs-accomodation plainview fancy-view" data-admin_url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                                                    <script>jQuery(document).ready(function ($) {
                                                            cs_check_room_availabilty()
                                                        });
                                                    </script>
                                                    <?php
                                                    $output = '';
                                                    $cs_rooms = array();
                                                    $cs_room_capacity_data = array();
                                                    $temp_data = array();
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
                                                    $flag = false;
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
                                                            $room_capacity_type = get_post_meta($post->ID, 'cs_room_capacity', true);
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
                                                                                $booking_count = cs_check_booking($cs_room_refernce['id'], $date_from, $date_to);
                                                                                if ($booking_count <= 0) {
                                                                                    //Rooms Available
                                                                                    $cs_availabilty_counter++;
                                                                                }
                                                                            } else {
                                                                                //No Rooms Found
                                                                            }
                                                                        }

                                                                        if (isset($cs_availabilty_counter) && $cs_availabilty_counter > 0) {
                                                                            $flag = true;
                                                                            $cs_room_capacity_data['id'] = $capacity_type;
                                                                            $cs_room_capacity_data['title'] = get_the_title($capacity_type);
                                                                            $temp_data['data'][$key] = $cs_room_capacity_data;
                                                                        }
                                                                    }
                                                                } //

                                                                if (isset($temp_data['data']) && !empty($temp_data['data'])) {

                                                                    $excerpt_data = CS_FUNCTIONS()->cs_get_the_excerpt($excerpt, 'false', 'Read More');
                                                                    $featureList = get_post_meta($cs_post_id, 'cs_room_features', true);
                                                                    $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                                                                    $cs_output = '';
                                                                    $cs_price = get_post_meta($cs_post_id, 'cs_room_starting_price', true);
                                                                    $cs_room_type = get_post_meta($cs_post_id, 'cs_room_type', true);
                                                                    ?>
                                                                    <div class="row">
                                                                        <article class="col-md-12">
                                                                            <div class="plain-holder">
                                                                                <figure><a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumbnail); ?>" alt=""  /></a></figure>
                                                                                <div class="accomodation-info">
                                                                                    <div class="plain-info">
                                                                                        <h5><a href="<?php the_permalink(); ?>"><?php echo cs_get_room_title(get_the_title(), $title_limit); ?></a></h5>  
                                                                                        <?php cs_hotel_rating($cs_post_id, false); ?>
                                                                                        <?php
                                                                                        if (isset($excerpt) && $excerpt > 0) {
                                                                                            echo '<p>' . $excerpt_data . '</p>';
                                                                                        };
                                                                                        ?>
                                                                                        <?php if (isset($cs_price) && $cs_price != '') { ?>
                                                                                            <div class="cs-price"><small><?php _e('Starts from', 'booking'); ?></small> <span><?php echo esc_attr($cs_plugin_options['currency_sign'] . $cs_price); ?></span> </div>
                                                                                        <?php } ?>

                                                                                    </div>
                                                                                    <div class="short-info">
                                                                                        <ul class="cslist-info">
                                                                                            <?php
                                                                                            $featureList = get_post_meta($cs_post_id, 'cs_room_features', true);
                                                                                            $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                                                                                            $cs_output = '';
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
                                                                                                            echo '<li><a href="javascript:;">' . $cs_image . '</a></li>';
                                                                                                        }
                                                                                                    }
                                                                                                    // End if condition...
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                        </ul>
                                                                                        <div class="cs-room-selection">
                                                                                            <select name="cs_room_capacity" class="cs_room_capacity" data-reference="null" data-room="<?php echo absint($cs_post_id); ?>">
                                                                                                <option value=""><?php _e('Select Room', 'booking'); ?></option>
                                                                                                <?php
                                                                                                foreach ($temp_data['data'] as $key => $value) {
                                                                                                    echo '<option value="' . $value['id'] . '">' . $value['title'] . '</option>';
                                                                                                }
                                                                                                ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </article>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }

                                                        endwhile;
                                                        if ($flag == false) {
                                                            $cs_notification->error(__('No Rooms found', 'booking'));
                                                        }
                                                        wp_reset_postdata();
                                                    } else {
                                                        $cs_notification->error(__('No Rooms found', 'booking'));
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </form>
            </div>  
        </div>
    </div>
    <?php
} // Booking
get_footer();
