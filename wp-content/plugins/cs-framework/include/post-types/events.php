<?php
/**
 * @Manage Columns
 * @return
 *
 */
 
if (!class_exists('post_type_event')) {

    class post_type_event {

        // The Constructor
        public function __construct() {
            // Adding columns
            add_filter('manage_events_posts_columns', array(&$this, 'cs_events_columns_add'));
            add_action('manage_events_posts_custom_column', array(&$this, 'cs_events_columns'), 10, 2);
			add_action('init', array(&$this, 'cs_events_register'));
			add_action('init', array(&$this, 'cs_events_categories'));
			add_action('init', array(&$this, 'cs_events_tags'));
        }

		function cs_events_columns_add($columns) {
			$columns['category'] =__('Categories','cs_frame');
			$columns['organizer'] =__('Organizer','cs_frame');
			$columns['start_date'] =__('Start Date','cs_frame');
			$columns['end_date'] =__('End Date','cs_frame');
			$columns['timing'] =__('Timing','cs_frame');
			return $columns;
		}

		function cs_events_columns($name) {
			global $post;
			switch ($name) {
				case 'category':
					$categories = get_the_terms( $post->ID, 'event-category' );
						if($categories <> ""){
							$couter_comma = 0;
							foreach ( $categories as $category ) {
								echo esc_attr($category->name);
								$couter_comma++;
								if ( $couter_comma < count($categories) ) {
									echo ", ";
								}
							}
						}
				break;
				case 'organizer':
					$event_organizer = get_post_meta( $post->ID, 'cs_event_organizer', true );
					if( $event_organizer <> '' ) {
						echo esc_attr($event_organizer);
					} else {
						echo '-';
					}
				break;
				case 'start_date':
					$from_date = get_post_meta( $post->ID, 'cs_event_from_date', true );
					$from_date = cs_correct_date_form( $from_date );
					$from_date = date_i18n(get_option('date_format'), strtotime($from_date));
					echo esc_attr($from_date);
				break;
				case 'end_date':
					$end_date = get_post_meta( $post->ID, 'cs_event_to_date', true );
					$end_date = cs_correct_date_form( $end_date );
					$end_date = date_i18n(get_option('date_format'), strtotime($end_date));
					echo esc_attr($end_date);
				break;
				case 'timing':
					$start_time = get_post_meta( $post->ID, 'cs_event_start_time', true );
					$end_time = get_post_meta( $post->ID, 'cs_event_end_time', true );
					$all_day = get_post_meta( $post->ID, 'cs_event_all_day', true );
					
					$start_time = date_i18n(get_option('time_format'), strtotime($start_time));
					$end_time = date_i18n(get_option('time_format'), strtotime($end_time));
					
					if( $all_day == 'on' ) {
						_e('All day', 'cs_frame');
					} else if( $start_time <> '' && $end_time <> '' ) {
						echo esc_attr($start_time . ' - ' . $end_time);
					} else if( $start_time <> '' && $end_time == '' ) {
						echo esc_attr($start_time);
					} else {
						echo '-';
					}
				break;
			}
		}

		/**
		 * @Register Post Type
		 * @return
		 *
		 */
		function cs_events_register() {
			$labels = array(
				'name' =>__('Events','cs_frame'),
				'all_items' =>__('Events','cs_frame'),
				'add_new_item' =>__('Add New Event','cs_frame'), 
				'edit_item' =>__('Edit Event','cs_frame'), 
				'new_item' =>__('New Event Item','cs_frame'),
				'add_new' =>__('Add New Event','cs_frame'),
				'view_item' =>__('View Event Item','cs_frame'), 
				'search_items' =>__('Search Event','cs_frame'), 
				'not_found' =>__('Nothing found','cs_frame'),  
				'not_found_in_trash' =>__('Nothing found in Trash','cs_frame'),
				'parent_item_colon' => ''
			);
			
			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'query_var' => true,
				'menu_icon' => 'dashicons-book',
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'can_export' => true,
				'supports' => array('title','editor','thumbnail')
			); 
			register_post_type( 'events' , $args );

		}
		
		/**
		 * @Register Categories
		 * @return
		 *
		 */	
		
		function cs_events_categories() { 
			$labels = array(
				'name' =>__('Event Categories','cs_frame'), 
				'search_items' =>__('Search Event Categories','cs_frame'), 
				'edit_item' =>__('Edit Event Category','cs_frame'), 
				'update_item' =>__('Update Event Category','cs_frame'), 
				'add_new_item' =>__('Add New Category','cs_frame'), 
				'menu_name' =>__('Categories','cs_frame'), 
			); 	
			register_taxonomy('event-category',array('events'), array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'event-category' ),
			));
		}
		
		/**
		 * @Register Tags
		 * @return
		 *
		 */	
		
		function cs_events_tags() {
			$labels = array(
				'name' =>__('Event Tags','cs_frame'), 
				'singular_name' =>__('event-tag','cs_frame'), 
				'search_items' =>__('Search Tags','cs_frame'), 
				'popular_items' =>__('Popular Tags','cs_frame'), 
				'all_items' =>__('All Tags','cs_frame'), 
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' =>__('Edit Tag','cs_frame'),  
				'update_item' =>__('Update Tag','cs_frame'), 
				'add_new_item' =>__('Add New Tag','cs_frame'), 
				'new_item_name' =>__('New Tag Name','cs_frame'), 
				'separate_items_with_commas' =>__('Separate tags with commas','cs_frame'), 
				'add_or_remove_items' =>__('Add or remove tags','cs_frame'), 
				'choose_from_most_used' =>__('Choose from the most used tags','cs_frame'), 
				'menu_name' =>__('Tags','cs_frame'), 
			); 
			register_taxonomy('event-tag','events',array(
			
				'hierarchical' => false,
				'labels' => $labels,
				'show_ui' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array( 'slug' => 'event-tag' ),
			));
		}
	}
	
	return new post_type_event();
}
