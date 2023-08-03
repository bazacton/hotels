<?php
/**
 * @Add Meta Box For Rooms Post
 * @return
 *
 */

add_action( 'add_meta_boxes', 'cs_meta_rooms_add' );
function cs_meta_rooms_add(){  
	add_meta_box( 'cs_meta_rooms', __('Rooms Options', 'booking'), 'cs_meta_rooms', 'rooms', 'normal', 'high' );  
	add_meta_box( 'room-gallery-images', __( 'Room Gallery', 'booking' ), 'cs_rooms_gallery', 'rooms', 'side' );
}

function cs_rooms_gallery( $post ) {
	global $post,$cs_form_fields;
	$cs_plugin_options = get_option( 'cs_plugin_options', true );
	$cs_form_fields->cs_gallery_render(
		array(  'name'	=> __('Add Room Gallery', 'booking'),
				'id'	=> 'room_image_gallery',
				'classes' => '',
				'std'	=> '',
				'description'  => '',
				'hint'  => ''
			)
	);
}

function cs_meta_rooms( $post ) {
	global $post;
	?>
	<div class="page-wrap page-opts left">
		<div class="option-sec" style="margin-bottom:0;">
			<div class="opt-conts">
				<div class="elementhidden">
					<?php 
                    if ( function_exists( 'cs_room_options' ) ) { 
                        cs_room_options();
                    }
                    ?>
				</div>
                <script>
					jQuery(document).ready(function($) {
						cs_check_availabilty();
					});
				</script>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<?php
}

/**
 * @Rooms options
 * @return html
 *
 */ 
if ( ! function_exists( 'cs_room_options' ) ) {
	function cs_room_options() {
		
		global $post, $cs_form_fields,$cs_plugin_options;
		
		$cs_room_types = array();
		$cs_args = array( 'posts_per_page' => '-1', 'post_type' => 'rooms_capacity', 'orderby'=>'ID', 'post_status' => 'publish' );
		$cust_query = get_posts($cs_args);
		$cs_room_capacity = get_post_meta($post->ID, 'cs_room_capacity', true);
		
		$cs_hotel	= get_option('cs_hotel_options');
		
		$cs_hotels['']   = __('Select Hotel','booking');
		
		if( isset( $cs_hotel ) && is_array( $cs_hotel ) && !empty( $cs_hotel ) ) {
			foreach( $cs_hotel as $key => $hotel ) {
				$cs_hotels[$key] = $hotel['cs_hotel_name'];
			}
		}
		
		wp_reset_postdata();
		
		$cs_form_fields->cs_form_select_render(
			array(  'name'	=> __('Select Hotel', 'booking'),
					'id'	=> 'hotel_id',
					'classes' => '',
					'std'	=> '',
					'description'  => '',
					'options'  => $cs_hotels,
					'hint'  => ''
				)
		);
		
		$cs_form_fields->cs_form_text_render(
			array(  'name'	=> __('Starting Price', 'booking'),
					'id'	=> 'room_starting_price',
					'classes' => '',
					'std'	=> '',
					'description'  => '',
					'hint'  => ''
				)
		);
			
		$cs_form_fields->cs_file_attachments(
			array(  'name'	=> __('File Attachments', 'booking'),
					'id'	=> 'room_file_attach',
					'classes' => '',
					'std'	=> '',
					'description'  => '',
					'hint'  => ''
				)
		);
		
		$cs_form_fields->cs_booking_feature_list(
			array(  'name'	=> __('Features List', 'booking'),
					'id'	=> 'room_features',
					'classes' => '',
					'std'	=> '',
					'description'  => '',
					'hint'  => ''
				)
		);
		
		$cs_form_fields->cs_form_textarea_render(
			array(  'name'	=> __('Add Tabs Shortcode','booking'),
					'id'	=> 'tabs_shortcode',
					'classes' => '',
					'std'	=> '',
					'description'  => '',
					'hint'  => ''
				)
		);
		
		if( function_exists('cs_social_share') ) {
			$cs_form_fields->cs_form_checkbox_render(
				array(  'name'	=> __('Share Button','booking'),
						'id'	=> 'share_btn',
						'classes' => '',
						'std'	=> '',
						'description'  => '',
						'hint'  => ''
					)
			);	
		}
		
	}
}
?>