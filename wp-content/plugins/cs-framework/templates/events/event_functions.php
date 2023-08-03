<?php
/**
 * File Type: Event Shortcode
 */
if ( ! function_exists( 'cs_event_listing' ) ) {

	function cs_event_listing( $atts, $content = "" ) {
		global $post, $wp_query, $wpdb, $cs_node, $cs_theme_option, $events_time, $event_date, $cs_event_meta, $event_excerpt, $category, $cs_notification, $wp_query;
		date_default_timezone_set( 'UTC' );
		$current_time = strtotime( current_time( 'Y/m/d H:i', $gmt = 0 ) );

		$defaults = array( 'column_size' => '1/1', 'section_title' => '', 'view' => '', 'category' => '', 'post_order' => 'DESC', 'event_type' => '', 'orderby' => 'ID', 'event_excerpt' => '255', 'pagination' => '10', 'filterable' => '', 'events_time' => '', 'pagination_switch' => '' );

		extract( shortcode_atts( $defaults, $atts ) );

		$coloumn_class = cs_custom_column_class( $column_size );

		$cs_dataObject = get_post_meta( $post->ID, 'cs_full_data' );

		ob_start();

		$organizer_filter = '';
		$user_meta_key = '';
		$user_meta_value = '';
		$meta_compare = "";
		$meta_value = $current_time;
		$meta_key = 'date_time';
		//cs_dynamic_event_from_date_time
		//==Filters
		$filter_category = '';
		$filter_category_array = array();
		$row_cat = $wpdb->get_row( $wpdb->prepare( "SELECT * from " . $wpdb->prefix . "terms WHERE slug = %s", $category ) );

		if ( isset( $_GET['filter_category'] ) ) {
			$filter_category_array = $_GET['filter_category'];
			$catname = '';
			$contcat = ',';

			foreach ( $filter_category_array as $cat_name ) {
				$catname .= $cat_name . ',';
			}

			$filter_category = rtrim( $catname, ',' );
		} else {
			if ( isset( $row_cat->slug ) ) {
				$filter_category = $row_cat->slug;
			}
		}
		//==Filters End

		if ( $event_type == "upcoming-events" )
			$meta_compare = ">=";
		else if ( $event_type == "past-events" )
			$meta_compare = "<";
		$cs_counter_events = 0;

		if ( isset( $_GET['sort'] ) and $_GET['sort'] == 'asc' ) {
			$order = 'ASC';
		} else {
			$order = 'DESC';
		}

		if ( isset( $_GET['sort'] ) and $_GET['sort'] == 'alphabetical' ) {
			$orderby = 'title';
			$order = 'ASC';
		} else {
			$orderby = 'meta_value';
		}

		$order = isset( $post_order ) ? $post_order : '';
		if ( empty( $_GET['page_id_all'] ) )
			$_GET['page_id_all'] = 1;

		if ( isset( $_GET['organizer'] ) && $_GET['organizer'] <> '' ) {
			$meta_key = 'dynamic_event_members';
			$meta_value = $_GET['organizer'];
			$meta_compare = "LIKE";
			$organizer_filter = $_GET['organizer'];
		}

		if ( $event_type == "all-events" ) {
			$args = array(
				'posts_per_page' => "-1",
				'post_type' => 'events',
				'post_status' => 'publish',
				'orderby' => $orderby,
				'order' => $order,
			);
		} else {
			$args = array(
				'posts_per_page' => "-1",
				'post_type' => 'events',
				'post_status' => 'publish',
				'meta_key' => $meta_key,
				'meta_value' => $meta_value,
				'meta_compare' => $meta_compare,
				'orderby' => $orderby,
				'order' => $order,
			);
		}


		if ( isset( $_GET['filter_category'] ) && $_GET['filter_category'] <> '' && $_GET['filter_category'] <> '0' ) {
			$event_category_array = array( 'event-category' => $filter_category );
			$args = array_merge( $args, $event_category_array );
		} else if ( isset( $category ) && $category <> '' && $category <> '0' ) {
			$event_category_array = array( 'event-category' => "$category" );
			$args = array_merge( $args, $event_category_array );
		}

		//echo "<pre>";
		//  print_r($args);
		//echo "</pre>";

		$custom_query = new WP_Query( $args );
		$count_post = 0;
		$counter = 1;
		//echo "count si -->".
		$count_post = $custom_query->post_count;

		if ( $event_type == "upcoming-events" ) {

			$args = array(
				'posts_per_page' => "$pagination",
				'paged' => $_GET['page_id_all'],
				'post_type' => 'events',
				'post_status' => 'publish',
				'meta_key' => $meta_key,
				'meta_value' => $meta_value,
				'meta_compare' => $meta_compare,
				'orderby' => $orderby,
				'order' => $order,
			);
		} else if ( $event_type == "all-events" ) {

			$args = array(
				'posts_per_page' => "$pagination",
				'paged' => $_GET['page_id_all'],
				'post_type' => 'events',
				//'meta_key'				=> $meta_key,
				//'meta_value'				=> '',
				'post_status' => 'publish',
				'orderby' => $orderby,
				'order' => $order,
			);
		} else {
			$args = array(
				'posts_per_page' => "$pagination",
				'paged' => $_GET['page_id_all'],
				'post_type' => 'events',
				'post_status' => 'publish',
				'meta_key' => $meta_key,
				'meta_value' => $meta_value,
				'meta_compare' => $meta_compare,
				'orderby' => $orderby,
				'order' => $order,
			);
		}


		if ( isset( $_GET['filter_category'] ) && $_GET['filter_category'] <> '' && $_GET['filter_category'] <> '0' ) {
			$event_category_array = array( 'event-category' => "$filter_category" );
			$args = array_merge( $args, $event_category_array );
		} else if ( isset( $category ) && $category <> '' && $category <> '0' ) {
			$event_category_array = array( 'event-category' => "$category" );
			$args = array_merge( $args, $event_category_array );
		}
		// below block will be commnet
		if ( isset( $filter_category ) && $filter_category <> '' && $filter_category <> '0' ) {
			$events_category_array = array( 'event-category' => "$filter_category" );
			$args = array_merge( $args, $events_category_array );
		}

		if ( isset( $_GET['organizer'] ) && $_GET['organizer'] <> '' ) {
			$user_meta_key = '';
			$user_meta_value = '';
		}

		$user_args = array(
			'posts_per_page' => "",
			'paged' => "",
			'meta_key' => $user_meta_key,
			'meta_value' => $user_meta_value,
		);

		$user_args = array_merge( $args, $user_args );

		// below block will be commnet
		if ( isset( $category ) && $category <> '' && $category <> '0' ) {
			$event_category_array = array( 'event-category' => "$category" );
			$args = array_merge( $args, $event_category_array );
		}

		if ( isset( $section_title ) && $section_title <> '' ) {
			echo '<div class="cs-section-title col-md-12">
					<h2>' . esc_attr( $section_title ) . '</h2>
				  </div>';
		}

		if ( isset( $filterable ) && $filterable == 'Yes' ) {
			cs_get_event_filters( $filter_category_array );
		}

		set_query_var( 'args', $args );
		if ( $view == 'events-simple' ) {
			include('event-simple.php');
		} else if ( $view == 'events-fancy' ) {
			include('event-fancy.php');
		} else if ( $view == 'events-featured' ) {
			include('event-featured.php');
		} else {
			include('event-list.php');
		}



		//==Pagination Start
		if ( $count_post > $pagination && $pagination > 0 && $view <> 'events-featured' ) {
			$qrystr = '';
			if ( isset( $_GET['page_id'] ) )
				$qrystr .= "&amp;page_id=" . $_GET['page_id'];
			if ( isset( $_GET['filter_category'] ) )
                $filter_category = $_GET['filter_category'];
			   if(isset($_GET['filter_category']) && is_array($_GET['filter_category']) && !empty($_GET['filter_category'])){
               $filter_category =  implode(',', $_GET['filter_category']);
               }
				$qrystr .= "&amp;filter_category=" .$filter_category;
			if ( $pagination_switch == 'Yes' ) {
				echo cs_pagination( $count_post, $pagination, $qrystr, 'Show Pagination' );
			}
		}
		//==Pagination End  

		$eventpost_data = ob_get_clean();
		return $eventpost_data;
	}

	add_shortcode( 'cs_events', 'cs_event_listing' );
}

/**
 *
 * @Get Event CAtegories
 * @return
 */
if ( ! function_exists( 'cs_event_categories' ) ) {

	function cs_event_categories( $category ) {

		if ( $category != '' && $category != '0' ) {
			$row_cat = $wpdb->get_row( $wpdb->prepare( "SELECT * from $wpdb->terms WHERE slug = %s", $category ) );
		}

		if ( isset( $category ) && $category != '' && $category != '0' ) {
			echo '<a href="' . site_url() . '?cat=' . $row_cat->term_id . '">' . $row_cat->name . '</a>';
		} else {
			/* Get All Tags */
			$before_cat = '<i class="fa fa-align-left"></i> ';
			$categories_list = get_the_term_list( get_the_id(), 'event-category', $before_cat, ', ', '' );
			if ( $categories_list ) {
				printf( '%1$s', $categories_list );
			}
			// End if Tags 
		}
	}

}

/**
 *
 * @Get Event Filters
 * @return
 */
if ( ! function_exists( 'cs_get_event_filters' ) ) {

	function cs_get_event_filters( array $filter_category ) {
		global $post;
		$categories = get_categories( array( 'taxonomy' => 'event-category', 'hide_empty' => 0 ) );
		?>
		<div class="col-md-12">
			<div class="cs-check-filter">
				<div class="cs-check-tittle">
					<h6><?php _e( 'Sort by', 'Hotel' ); ?></h6>
				</div>
				<div class="inner-sec">
					<form action="#" method="get" name="filterable">
						<ul>
							<?php
							$i = 0;
							foreach ( $categories as $category ) {
								?>
								<li>
									<input type="checkbox" onChange="this.form.submit()" value="<?php echo esc_attr( $category->slug ); ?>" name="filter_category[<?php echo absint( $i ); ?>]" class="cs-check-filter" id="<?php echo esc_attr( $category->slug ); ?>" <?php
										   if ( in_array( $category->slug, $filter_category ) ) {
											   echo 'checked';
										   }
										   ?> />
									<label for="<?php echo esc_attr( $category->slug ); ?>"><?php echo esc_attr( $category->cat_name ); ?></label>
								</li>
								<?php
								$i ++;
							}
							?>
						</ul>
					</form>
				</div>
			</div>	
		</div>
		<?php
	}

}

/**
 *
 * @Get Event Address
 * @return
 */
if ( ! function_exists( 'cs_get_event_address' ) ) {

	function cs_get_event_address( $address = '', $limit = 35 ) {
		return substr( $address, 0, $limit );
		if ( strlen( $address ) > $limit ) {
			echo '...';
		}
	}

}

/**
 *
 * @Get Event Title
 * @return
 */
if ( ! function_exists( 'cs_get_event_title' ) ) {

	function cs_get_event_title( $address = '', $limit = 35 ) {
		return substr( $address, 0, $limit );
		if ( strlen( $address ) > $limit ) {
			echo '...';
		}
	}

}

/**
 *
 * @Get Event Date
 * @return
 */
if ( ! function_exists( 'cs_correct_date_form' ) ) {

	function cs_correct_date_form( $date = '' ) {

		$cs_date_params = explode( '-', $date );
		if ( strpos( $date, '/' ) !== false ) {
			$cs_date_params = explode( '/', $date );
		}

		$cs_right_date = $date;
		if ( is_array( $cs_date_params ) && sizeof( $cs_date_params ) == 3 ) {
			$cs_right_date = $cs_date_params[1] . '-' . $cs_date_params[0] . '-' . $cs_date_params[2];
		}
		return $cs_right_date;
	}

}