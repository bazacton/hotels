<?php

/**
 * File Type: Booking Post Type
 */
if (!class_exists('post_type_booking')) {

    class post_type_booking {

        // The Constructor
        public function __construct() {
            global $pagenow;
            add_action('init', array(&$this, 'cs_booking_init'));

            // Adding columns
            add_filter('manage_booking_posts_columns', array(&$this, 'cs_booking_columns_add'));
            add_action('manage_booking_posts_custom_column', array(&$this, 'cs_booking_columns'), 10, 2);

            if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'booking') {
                add_filter('months_dropdown_results', '__return_empty_array');
                add_filter('bulk_actions-edit-booking', '__return_empty_array');
                add_filter('views_edit-booking', '__return_empty_array');
                add_filter('post_row_actions', '__return_empty_array');
                add_action('admin_footer-edit.php', array(&$this, 'cs_admin_remove_js'));
            }
        }

        // Hook into WP's init action hook
        public function cs_booking_init() {
            // Initialize Post Type
            $this->cs_booking_register();
        }

        public function cs_booking_register() {
            $labels = array(
                'name' => __('Bookings', 'booking'),
                'all_items' => __('Bookings', 'booking'),
                'singular_name' => __('Bookings', 'booking'),
                'add_new' => __('Add Booking', 'booking'),
                'add_new_item' => __('Add New Booking', 'booking'),
                'edit' => __('Edit', 'booking'),
                'edit_item' => __('Edit Booking', 'booking'),
                'new_item' => __('New Booking', 'booking'),
                'view' => __('View Booking', 'booking'),
                'view_item' => __('View Booking', 'booking'),
                'search_items' => __('Search Booking', 'booking'),
                'not_found' => __('No Booking found', 'booking'),
                'not_found_in_trash' => __('No Booking found in trash', 'booking'),
                'parent' => __('Parent Booking', 'booking'),
            );
            $args = array(
                'labels' => $labels,
                'description' => __('This is where you can add new booking', 'booking'),
                'public' => false,
                'supports' => array('title'),
                'show_ui' => true,
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'publicly_queryable' => false,
                'exclude_from_search' => false,
                'hierarchical' => false,
                'show_in_menu' => 'edit.php?post_type=rooms',
                'rewrite' => array('slug' => 'booking', 'with_front' => true),
                'query_var' => false,
                'has_archive' => 'false',
            );
            register_post_type('booking', $args);
        }

        // Adding columns Title
        public function cs_booking_columns_add($columns) {
            unset($columns['date']);
            $columns['title'] = 'Booking ID';
            $columns['guest'] = 'Guest';
            $columns['check_in_out'] = 'Check-in /Check-out	';
            $columns['grand_total'] = 'Grand Total';
            $columns['paid'] = 'Paid';
            $columns['remaining'] = 'Remaining';
            $columns['status'] = 'Status';

            return $columns;
        }

        // Adding columns
        public function cs_booking_columns($name) {
            global $post, $gateway;
            $cs_checking = $cs_checkout = $cs_total_adults = $cs_total_childs = '';
            $cs_checking = get_post_meta($post->ID, 'cs_check_in_date', true);
            $cs_checkout = get_post_meta($post->ID, 'cs_check_out_date', true);
            $cs_total_adults = get_post_meta($post->ID, 'cs_total_adults', true);
            $cs_total_childs = get_post_meta($post->ID, 'cs_total_childs', true);
            $cs_select_guest = get_post_meta($post->ID, 'cs_select_guest', true);
            $cs_bkng_grand_total = get_post_meta($post->ID, 'cs_bkng_grand_total', true);
            $cs_bkng_remaining = get_post_meta($post->ID, 'cs_bkng_remaining', true);
            $cs_bkng_advance = get_post_meta($post->ID, 'cs_bkng_advance', true);

            $cs_bkng_grand_total = $cs_bkng_grand_total != '' ? $cs_bkng_grand_total : 0;
            $cs_bkng_remaining = $cs_bkng_remaining != '' ? $cs_bkng_remaining : 0;
            $cs_bkng_advance = $cs_bkng_advance != '' ? $cs_bkng_advance : 0;

            $cs_checking = $cs_checking != '' ? date('Y-m-d h:i A', $cs_checking) : '';
            $cs_checkout = $cs_checkout != '' ? date('Y-m-d h:i A', $cs_checkout) : '';

            $cs_cstmr_data = get_option("cs_customer_options");
            if (isset($cs_cstmr_data[$cs_select_guest])) {
                $customer_data = $cs_cstmr_data[$cs_select_guest];
                $customer_name = $customer_data['cus_f_name'] . ' ' . $customer_data['cus_l_name'];
            } else {
                $customer_name = '';
            }
            // return payment gateway name

            switch ($name) {
                case 'guest':
                    echo esc_attr($customer_name);
                    break;
                case 'check_in_out':
                    if ($cs_checking <> '' || $cs_checkout <> '') {
                        echo $cs_checking . ' / ' . $cs_checkout;
                    }
                    break;
                case 'grand_total':
                    echo number_format($cs_bkng_grand_total, 2);
                    break;
                case 'paid':
                    echo number_format($cs_bkng_remaining, 2);
                    break;
                case 'remaining':
                    echo number_format($cs_bkng_advance, 2);
                    break;
                case 'status':
                    echo get_post_meta($post->ID, 'cs_booking_status', true);
                    break;
            }
        }

        public function cs_admin_remove_js() {
            ?>

            <?php

        }

        // End of class	
    }

    // Initialize Object
    $booking_object = new post_type_booking();
}