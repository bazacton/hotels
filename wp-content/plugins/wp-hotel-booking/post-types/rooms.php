<?php

/**
 * File Type: Room Post Type
 */
if ( ! class_exists('post_type_room') ) {
	
    class post_type_room {

        // The Constructor
        public function __construct() {
            
			add_action('init', array($this, 'cs_room_init'));

            // Adding columns
            add_filter('manage_rooms_posts_columns', array(&$this, 'cs_room_columns_add'));
            add_action('manage_rooms_posts_custom_column', array(&$this, 'cs_room_columns'), 10, 2);

            // Removing add new Room menu
            add_action('admin_menu', array(&$this, 'add_new_room_menu'));
        }

        // Hook into WP's init action hook
        public function cs_room_init() {
            // Initialize Post Type
            $this->cs_room_register();
        }

        public function cs_room_register() {
            $labels = array(
                'name' =>__('Rooms','booking'),
                'menu_name' =>__('Rooms', 'booking'),
                'add_new_item' =>__('Add New Room','booking'),
                'edit_item' =>__('Edit Room', 'booking'),
                'new_item' =>__('New Room Item','booking'),
                'add_new' =>__('Add New Room','booking'),
                'view_item' => __('View Room Item','booking'),
                'search_items' =>__('Search','booking'),
                'not_found' =>__('Nothing found','booking'),
                'not_found_in_trash' =>__('Nothing found in Trash','booking'),
                'parent_item_colon' => ''
            );
            $args = array(
                'labels' => $labels,
                'public' => false,
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => false,
                'menu_icon' => 'dashicons-admin-post',
                'rewrite' => array( 'slug' => 'rooms' ),
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title', 'editor')
            );
            register_post_type('rooms', $args);
        }

        // Adding columns Title
        public function cs_room_columns_add($columns) {
            unset($columns['date']);
			$columns['hotel'] 		= 'Hotel';
			$columns['price']		= 'Starting Price';
			$columns['gallery']		= 'Gallery';
  	
			return $columns;
        }

        // Adding columns
        public function cs_room_columns($name) {
            global $post,$cs_form_fields;
			$cs_hotel_data  = get_option( "cs_hotel_options" );
			$cs_hotel_id 	=  get_post_meta($post->ID,'cs_hotel_id',true); 
			$price 	 		=  get_post_meta($post->ID,'cs_room_starting_price',true); 
			if(  isset( $cs_hotel_data[$cs_hotel_id] ) ) {
				$hotel_name	= $cs_hotel_data[$cs_hotel_id]['cs_hotel_name'];
			} else{
				$hotel_name	= '';
			}
			
			if( $price == '' ){
				$price	= 0;
			}
			
			if ( metadata_exists( 'post', $post->ID, 'cs_room_image_gallery' ) ) {
				$gallery = get_post_meta( $post->ID, 'cs_room_image_gallery' , true );
			
			} else {
				// Backwards compat
				$attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&&meta_value=0' );
				$attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
				$gallery = implode( ',', $attachment_ids );
			}
			
			$attachments = array_filter( explode( ',', $gallery ) );
            
			// return payment gateway name
            switch ($name) {
				case 'hotel':
					echo esc_attr( $hotel_name ) ;
				break;				
				case 'price':
					echo number_format( $price ,2 );
 				break;
				case 'gallery':
				if ( $attachments ) {
					$counter	= 0;
					foreach ( $attachments as $attachment_id ) {
						$counter++;
						if( $counter < 6 ) {
							$attachment_data	= $cs_form_fields->cs_get_icon_for_attachment( $attachment_id,'custom' );
							echo '<span class="list-thumb">' . $attachment_data . '</span>';
						}
					}
				}
				break;
				
			}
        }

        public function add_new_room_menu() {
            global $submenu;
            unset($submenu['edit.php?post_type=rooms'][10]);
        }

        // End of class	
    }

    // Initialize Object
    $room_object = new post_type_room();
}