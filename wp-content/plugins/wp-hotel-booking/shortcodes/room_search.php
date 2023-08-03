<?php
/*
 *
 * @Shortcode Name : Search Room
 * @retrun
 *
 */
if (!function_exists('cs_room_search_shortcode')) {
    global $cs_plugin_options;

    function cs_room_search_shortcode($atts) {
        $defaults = array(
            'search_title' => '',
            'view' => 'default',
        );

        extract(shortcode_atts($defaults, $atts));
        ob_start();
        $cs_plugin_options = get_option('cs_plugin_options');
        $cs_page_id = isset($cs_plugin_options['cs_reservation']) && $cs_plugin_options['cs_reservation'] != '' && absint($cs_plugin_options['cs_reservation']) ? $cs_plugin_options['cs_reservation'] : '';
        $cs_max_childs = isset($cs_plugin_options['cs_childs']) && $cs_plugin_options['cs_childs'] != '' && absint($cs_plugin_options['cs_childs']) ? $cs_plugin_options['cs_childs'] : '10';
        $cs_max_adults = isset($cs_plugin_options['cs_adults']) && $cs_plugin_options['cs_adults'] != '' && absint($cs_plugin_options['cs_adults']) ? $cs_plugin_options['cs_adults'] : '10';
        wp_hotel_booking::cs_enqueue_datepicker_script();

        $search_link = add_query_arg(array('action' => 'booking'), esc_url(get_permalink($cs_page_id)));
        $cs_hotels = get_option('cs_hotel_options');

        if ($view == 'default') {
            $viewClass = 'cs-search-room-elm';
            $wrapper_start = '';
            $wrapper_end = '';
        } else if ($view == 'fancy') {
            $viewClass = 'cs-search-room-elm-full fancy-input';
            $wrapper_start = '<li class="sub-rooms"><ul>';
            $wrapper_end = '</ul></li>';
        } else {
            $viewClass = 'cs-search-room-elm-full';
            $wrapper_start = '<li class="sub-rooms"><ul>';
            $wrapper_end = '</ul></li>';
        }
        ?>
        <div class="reservation-form col-md-12 banner-search <?php echo esc_attr($viewClass); ?>">
            <?php if (isset($search_title) && !empty($search_title)) { ?>
                <div class="cs-section-title" style="margin:32px 0 25px;">
                    <h2><?php echo esc_attr($search_title); ?></h2>
                </div>
            <?php } ?>
            <script>
                jQuery(document).ready(function ($) {
                    cs_widget_datepicker('<?php echo date('d/m/Y'); ?>');
                });
            </script>
            <div class="reservation-inner rooms-options-data">
                <form class="form-reviews" method="post" action="<?php echo esc_url($search_link); ?>" id="room-seach">
                    <ul class="review-style box-br-style" id="search-wraper" data-adults="<?php echo esc_attr($cs_max_adults); ?>" data-childs="<?php echo esc_attr($cs_max_childs); ?>">
                        <li> <span class="select-style-two">
                                <label for=""><?php _e('Select Hotels', 'booking') ?></label>
                                <label id="Deadline" class="cs-check-in cs-calendar-combo">
                                    <select name="cs_hotels" id="cs-hotels" onchange="cs_get_rooms('<?php echo admin_url('admin-ajax.php'); ?>', this.value);">
                                        <?php
                                        if (isset($cs_hotels) && is_array($cs_hotels)) {
                                            $cs_hotel_count = 0;
                                            foreach ($cs_hotels as $key => $hotel) {
                                                if ($cs_hotel_count == 0) {
                                                    $cs_selected_hotel = $key;
                                                }
                                                echo '<option value="' . $key . '">' . $hotel['cs_hotel_name'] . '</option>';
                                                $cs_hotel_count++;
                                            }
                                        } else {
                                            echo '<option value="">' . _e('Select Hotel', 'booking') . '</option>';
                                        }
                                        ?>
                                    </select>
                                </label>
                            </span>
                        </li>
                        <li class="date"> <span class="select-style-two">
                                <label for=""><?php _e('Check in Date', 'booking') ?></label>
                                <label id="Deadline" class="cs-check-in cs-calendar-combo">
                                    <input type="text" name="date_from" id="check-in-date" value="<?php echo date('d/m/Y H:i'); ?>"  placeholder="DD/MM/YYYY HH:ii">
                                </label>
                            </span>
                        </li>

                        <li class="date"> <span class="select-style-three">
                                <label for=""><?php _e('Check out Date', 'booking') ?></label>
                                <label id="Deadline" class="cs-check-out cs-calendar-combo">
                                    <input type="text" autocomplete="off" name="date_to" id="check-out-date" value="" placeholder="DD/MM/YYYY HH:ii">
                                </label>
                            </span>
                        </li>
                        <li class="rooms">
                            <label for=""><?php _e('No. of Rooms', 'booking') ?></label>
                            <span class="select-style">
                                <select name="cs-rooms" id="cs-booking-rooms">
                                    <?php
                                    if (isset($cs_selected_hotel) && $cs_selected_hotel != '') {
                                        $qry_args = array('posts_per_page' => '-1', 'post_type' => 'rooms', 'post_status' => 'publish');
                                        $qry_args['meta_query'] = array('relation' => 'AND',
                                            array(
                                                'key' => 'cs_hotel_id',
                                                'value' => $cs_selected_hotel,
                                                'compare' => '=',
                                            )
                                        );

                                        $query = new WP_Query($qry_args);
                                        $post_count = $query->post_count;
                                        $i = 1;
                                        if (isset($post_count) && $post_count > 0) {
                                            while ($i <= $post_count) {
                                                echo '<option value="' . $i . '">' . $i . '</option>';
                                                $i++;
                                            }
                                        }
                                    }
                                    /* for ($i = 1; $i <= 2; $i++) {
                                      echo '<option value="' . $i . '">' . $i . '</option>';
                                      } */
                                    ?>
                                </select>
                            </span>
                        </li>
                        <?php echo $wrapper_start; ?>
                        <li class="select-options">
                            <h5><?php _e('Room 1', 'booking') ?></h5>
                            <div class="cs-capacity-wrap">   
                                <div class="select-area">
                                    <span class="select-style">
                                        <select id="cs_max_adults" class="booking-members" name="adults[]">
                                            <?php
                                            for ($i = 1; $i <= $cs_max_adults; $i++) {
                                                echo '<option value="' . $i . '">' . $i . ' ' . __('Adult(s)', 'booking') . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </span> 
                                </div>
                                <div class="select-area">
                                    <span class="select-style">
                                        <select id="cs_max_childs" class="booking-members" name="childs[]">
                                            <?php
                                            for ($i = 0; $i <= $cs_max_childs; $i++) {
                                                echo '<option value="' . $i . '">' . $i . ' ' . __('Child(s)', 'booking') . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </span> 
                                </div>
                            </div>
                        </li>
                        <?php echo $wrapper_end; ?>
                        <li>
                            <input class="search-btn csbg-color" id="seach_room_btn" type="button" value="<?php _e("Search Room", "booking") ?>" name="Search Room">
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <?php
        $html = ob_get_clean();

        return do_shortcode($html);
    }

    add_shortcode('cs_room_search', 'cs_room_search_shortcode');
}

/* ------------------------------------------------------------
 * Get Hotel Rooms
 * ----------------------------------------------------------- */

function cs_get_hotel_rooms() {
    $qry_args = array('posts_per_page' => '-1', 'post_type' => 'rooms', 'post_status' => 'publish');
    $qry_args['meta_query'] = array('relation' => 'AND',
        array(
            'key' => 'cs_hotel_id',
            'value' => $_POST['hotel_name'],
            'compare' => '=',
        )
    );

    $query = new WP_Query($qry_args);
    $post_count = $query->post_count;
    $i = 1;
    if (isset($post_count) && $post_count > 0) {
        while ($i <= $post_count) {
            echo '<option value="' . $i . '">' . $i . '</option>';
            $i++;
        }
    }
    die;
}

add_action('wp_ajax_cs_get_hotel_rooms', 'cs_get_hotel_rooms');
add_action('wp_ajax_nopriv_cs_get_hotel_rooms', 'cs_get_hotel_rooms');
