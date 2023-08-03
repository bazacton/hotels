<?php
/**
 * @Reviews Meta Boxes
 * @return html
 *
 */ 
 
if(!class_exists('review_meta')){
	
	class review_meta{
			
			private $cs_plugin_options;
			public function __construct()
			{
				//$cs_plugin_options	= get_option('cs_plugin_option');	
				add_action('admin_init', array(&$this, 'cs_reviews_admin_init'));
				add_action('wp_ajax_cs_add_reviews', array(&$this, 'cs_add_reviews'));
				add_action('wp_ajax_nopriv_cs_add_reviews', array(&$this, 'cs_add_reviews'));
				add_action( 'save_post', array(&$this, 'cs_reviews_save') );
			}
			
			/**
			 * hook into WP's admin_init action hook
			 */
			public function cs_reviews_admin_init()
			{           
				// Add metaboxes
				add_action( 'add_meta_boxes',  array(&$this, 'cs_meta_reviews_add') );
			}
			
			/**
			 * hook into WP's add_meta_boxes action hook
			 */
			public function cs_meta_reviews_add()
			{  
				add_meta_box( 'cs_meta_reviews', 'Reviews Options', array(&$this, 'cs_meta_reviews'), 'cs-reviews', 'normal', 'high' );  
			}

		  /**
			* Delete
			*/
		  public function cs_trash_post($post_id){
			  die();
		  }
			  
			/**
			 *@Add Reviews
			 */
			public function cs_add_reviews(){
				global $post,$cs_plugin_options,$wpdb;
				
				
				$user_id 	= '';
				if( (isset( $cs_plugin_options['cs_review_booking_id'] ) &&  $cs_plugin_options['cs_review_booking_id'] =='on') &&  $_REQUEST['booking_id'] =='' ) {
					$json['type']    = __('error','booking');
					$json['message'] = __('Please enter a Booking Id','booking');
					echo json_encode( $json );
					die;
				}
				
				if( $_REQUEST['booking_id'] !='' &&  $cs_plugin_options['cs_review_booking_id'] =='on' ) {
					$results 	= $wpdb->get_results( "select post_id, meta_key from $wpdb->postmeta where meta_value = '".$_REQUEST['booking_id']."'");
					if( !empty( $results ) ) {
						$post_id	=  $results[0]->post_id;
						$user_id 	= get_post_meta($post_id, 'cs_booking_user', true);
					}else{
						$json['type']    = __('error','booking');
						$json['message'] = __('Please enter valid Booking Id.','booking');
						echo json_encode( $json );
						die();
					}
				}

				
				if ( $_SERVER["REQUEST_METHOD"] == "POST" ){
						
						$reviews_title 			= $_POST['reviews_title'];
						$room_id 				= $_POST['room_id'];
						$reviews_description 	= $_POST['reviews_description'];
						$reviewStatus 			= $cs_plugin_options['cs_review_status'];
						
						$reviews_title 		= $_REQUEST['reviews_title'];
						$reviewer_email 	= $_REQUEST['reviewer_email'];
						$reviewer_name 		= $_REQUEST['reviewer_name'];
						
						if ( $reviews_title == '' || $reviews_description == '' || $reviews_title  == '' || $reviewer_email  == '' || $reviewer_name  == '' ) {
							$json['type']    =__('error','booking');
							$json['message'] = 'All the fields are required.';
							echo json_encode( $json );
							die;
						}
						
						if( isset( $user_id ) && $user_id !='' ) {
							$user_reviews_args = array(
								'posts_per_page'	=> "-1",
								'post_type'			=> 'cs-reviews',
								'post_status'		=> 'any',
								'author' 			=> $user_id,
								'meta_key'			=> 'cs_reviews_room',
								'meta_value'		=> $room_id,
								'meta_compare'		=> "=",
								'orderby'			=> 'meta_value',
								'order'				=> 'ASC',
							);
							
							$user_reviews_query = new WP_Query($user_reviews_args);
							$user_reviews_count = $user_reviews_query->post_count;
							
							if( isset( $user_reviews_count ) && $user_reviews_count > 0 ){
								$json['type']		= __('pending','booking');
								$json['message']	= __('You have Already Submit a Review.', 'booking');
								echo json_encode($json);
								die();
							}
						}
						
						if ( isset ( $reviewStatus ) && $reviewStatus == 'pending' ) {
							$status	= 'pending';
						} else if ( isset ( $reviewStatus ) && $reviewStatus == 'approve' ) {
							$status	= 'publish';
						} else {
							$status	= 'publish';
						}
						
						$reviews_post = array(
							'post_title'	=> $reviews_title ,
							'post_content'	=> $reviews_description,
							'post_status'	=> $status,
							'post_author'	=> $user_id,
							'post_type'		=> 'cs-reviews',
						);
						
						$post_id = wp_insert_post( $reviews_post );
						
						if($post_id){
							$cs_rating_options 		= $cs_plugin_options['cs_dyn_reviews_options'];
							$rating = 0;
							if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
								foreach($cs_rating_options as $rating_key=>$rating){
									if(isset($rating_key) && $rating_key <> ''){
										$rating_title 	= $rating['cs_dyn_reviews_title'];
										$rating_slug 	= $rating['dyn_reviews_id'];
										if(isset($_POST[$rating_slug])){
											$rating_value = $_POST[$rating_slug];
											update_post_meta($post_id, $rating_slug, $rating_value);
										}
									}
								}
							}
							
							update_post_meta($post_id, "cs_reviews_user", $user_id);
							update_post_meta($post_id, "cs_reviews_room", $room_id);
							update_post_meta($post_id, "reviews_title", $reviews_title);
							update_post_meta($post_id, "reviewer_email", $reviewer_email);
							update_post_meta($post_id, "reviewer_name", $reviewer_name);
							
							$this->cs_update_rating($room_id);
							
							$json	= array();
							if ( $reviewStatus == 'pending' ) {
								$json['type']		= __('pending','booking');
								$json['message']	= '<p>'.__('Your Given Review will be Sent to Administrators. Once your Review has been Approved. Review Will be Posted publicly on the web','booking').'</p>';
							} else if ( $reviewStatus == 'publish' ) {
								$json['type']		= 'aproved';							
								$json['message']	=  __("Your Given Review has been Approved and Will be Posted publicly on the web", "booking");
								
							}
						
							echo json_encode($json);
							die();
						}
					} else{
						$json['type']    = __('error','booking');
						$json['message'] = __('Some error occur please try again later.', 'booking');
						echo json_encode( $json );
						die;
					}
				exit;
			}
			
			/**
			 * Reviews Meta Options
			 */
			public function cs_reviews_meta_attributes() {
				global $post,$cs_plugin_options;
				//$cs_plugin_options	= get_option('cs_plugin_options');	
				$reviews_meta_attributes = array(
							'title'=>__('Reviews Options','booking'),
							'description'=>'',
							'meta_attributes' => array(
								'cs_reviews_user' => array(
									'name' => 'cs_reviews_user',
									'type' => 'dropdown_user',
									'id' => 'cs_reviews_user',
									'dropdown_type' => 'single',
									'title' =>__('Select User','booking'),
									'description' =>__('Select The User.','booking'),
									'options' => get_users('orderby=nicename'),
								),
								'cs_reviews_room' => array(
									'name' => 'cs_reviews_room',
									'type' => 'dropdown_query',
									'id' => 'cs_reviews_room',
									'dropdown_type' => 'single',
									'title' =>__('Select Room','booking'),
									'description' =>__('Select Room','booking'),
									'options' => array('showposts' => "-1", 'post_status' => 'publish', 'post_type' => 'rooms'),
								),

								'reviews_form' => array(
									'name' => 'reviews_form',
									'type' => 'hidden',
									'id' => 'reviews_form',
									'title' => '',
									'description' => '',
									'value' => '1',
								),
							),
						);
						
						$rating = 0;
						$cs_reviews = get_post_meta($post->ID, "cs_reviews", true);
						$room_type_select = get_post_meta((int)$cs_reviews, "room_type_select", true);
						
						$cs_rating_options = get_post_meta((int)$post->ID, 'cs_rating_meta', true);
						
						$cs_rating_options = $cs_plugin_options['cs_dyn_reviews_options'];
						$rating = 0;

						if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
							foreach($cs_rating_options as $rating_key => $rating){
								if(isset($rating_key) && $rating_key <> ''){
									$rating_title = $rating['cs_dyn_reviews_title'];
									$rating_slug  = $rating['dyn_reviews_id'];
									if(isset($rating_slug)){
										$reviews_meta_attributes['meta_attributes'][$rating_slug] = array(
																											'name' => $rating_slug,
																											'type' => 'dropdown',
																											'id' => $rating_slug,
																											'dropdown_type' => 'single',
																											'title' => $rating_title,
																											'description' =>__('Select The Rating','booking'),
																											'options' =>  range(0,5),
																										);
									}
								}
							}
						}
						return $reviews_meta_attributes;
			}
			
			/**
			 * Reviews Meta Review
			 */
			public function cs_meta_reviews( $post ) {
				
				global $cs_xmlObject, $post;
				$reviews_attributes = $this->cs_reviews_meta_attributes();
				
				
				$review_id = $post->ID;
				$html = '<div class="page-wrap">
							<div class="option-sec" style="margin-bottom:0;">
								<div class="opt-conts"><div class="cs-review-wrap">';
									foreach($reviews_attributes['meta_attributes'] as $key=>$attribute_values){
										if($attribute_values['type'] == 'hidden'){
											$html .= '<input type="hidden" name="'.$attribute_values['id'].'" value="'.$attribute_values['value'].'" />';
										} else {
											$html .= '<ul class="form-elements">
													  <li class="to-label"><label>'.$attribute_values['title'].'</label></li>
													  <li class="to-field">
														<div class="input-sec">';
															switch( $attribute_values['type'] )
															{
																case 'dropdown' :
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input">' . "\n";
																	foreach( $attribute_values['options'] as $value => $option )
																	{
																		$selected = '';
																		$rating_value = get_post_meta($review_id, (string)$attribute_values['id'], true);
																		if($option == $rating_value){$selected = 'selected = "selected"';}
																		$html .= '<option value="' . $option . '" '.$selected.'>' . $option . '</option>' . "\n";
																	}
																	$html .= '</select>' . "\n";
																	$html .= '<p class="cs-form-desc">' . $attribute_values['description'] . '</p>' . "\n";
																	break;
																case 'dropdown_user' :
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input">' . "\n";
																	foreach( $attribute_values['options'] as  $user )
																	{
																		if($user->ID == get_post_meta($post->ID, $attribute_values['id'], true)){
																			  $selected =' selected="selected"';
																		  }else{ 
																			  $selected = '';
																		  }
																		$html .= '<option value="' . $user->ID . '" '.$selected.'>' .$user->display_name. '</option>' . "\n";
																	}
																	$html .= '</select>' . "\n";
																	$html .= '<p class="cs-form-desc">' . $attribute_values['description'] . '</p>' . "\n";
																	break;
																case 'file' :
																	$html .= '<input id="'. $attribute_values['id'].'" name=" '.$attribute_values['id'].'" value="'.$var_cp_assignment_file.'" type="text" class="small" />
																	<input id="' . $attribute_values['id'] . '" name="'.$attribute_values['id'].'" type="button" class="uploadfile left" value="'.__('Browse','booking').'"/>';
																	break;
																	
																case 'dropdown_query' :
																	$var_cp_course = get_post_meta($post->ID, $attribute_values['id'], true);
																	$html .= '<select name="'.$attribute_values['id'].'" id="' . $attribute_values['id'] . '" class="cs-form-select cs-input">' . "\n";
																	query_posts($attribute_values['options']);
                                        							while (have_posts() ) : the_post();
                                                                          $cs_courses_id = get_the_id();
                                                                  			
                                                                          if($cs_courses_id == $var_cp_course){
                                                                                  $selected =' selected="selected"';
                                                                              }else{ 
                                                                                  $selected = '';
                                                                              }
                                                                         $html.='<option value="'.$cs_courses_id.'" '.$selected.'>'.get_the_title().'</option>';
                                                                          
                                                                	endwhile; 
																	wp_reset_postdata();
																	$html.='</select>';
																	break;
															}
												$html .= '</div>
													 </li>
												  	</ul>';
										}
									}
						$html .= '</div></div>
						</div>
					<div class="clear"></div>
				</div>';
				echo cs_allow_special_char($html);
			}
			
			/**
			 * Save Meta Fields
			 */
			public function cs_reviews_save( $post_id ){ 
				$post = get_post($post_id);
				if ( isset($_POST['reviews_form']) and $_POST['reviews_form'] == 1 ) {
 						$sxe = new SimpleXMLElement("<reviews></reviews>");
						if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
						$reviews_attributes = $this->cs_reviews_meta_attributes();
						foreach($reviews_attributes['meta_attributes'] as $key=>$value)
						{
						  if(isset($key)){
							  $value = (empty($_POST[$key]))? '' : $_POST[$key];
							  update_post_meta($post_id, $key, $value);
							  if($key == 'cs_reviews'){
								  $this->cs_update_rating($value);
							  }
						  }
						 }
						
						$counter = 0;
						update_post_meta( $post_id, 'cs_meta_reviews', $sxe->asXML() );
						
				}elseif($post->post_status == 'trash'){
					$current_post_id = get_post_meta( $post_id, 'cs_reviews', true);
					if(isset($current_post_id) and $current_post_id <> ''){
						// update review on trash post
						//$this->cs_update_rating($current_post_id);
					}
				}
			}
			
			/**
			 * UPdate Reviews
			 */
			public function cs_update_rating( $id = '' ){
			global $post,$wpdb,$cs_plugin_options;
 			$reviews_args = array(
				'posts_per_page'			=> "-1",
				'post_type'					=> 'cs-reviews',
				'post_status'				=> 'publish',
				'meta_key'					=> 'cs_reviews_room',
				'meta_value'				=> $id,
				'meta_compare'				=> "=",
				'orderby'					=> 'meta_value',
				'order'						=> 'ASC',
			);
			$reviews_query = new WP_Query($reviews_args);
			$reviews_count = $reviews_query->post_count;
			$var_cp_rating = 0;
			$post_count = 0;
			if ( $reviews_query->have_posts() <> "" ) {
				$cs_rating_options 		= $cs_plugin_options['cs_dyn_reviews_options'];
				
				$rating 		= 0;
				$dir_rating 	= array();
				$rating_array 	= array();
				
				while ( $reviews_query->have_posts() ): $reviews_query->the_post();	
					$post_count++;
					if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
						foreach($cs_rating_options as $rating_key=>$rating){
							if(isset($rating_key) && $rating_key <> ''){
								$rating_title 	= $rating['cs_dyn_reviews_title'];
								$rating_slug 	= $rating['dyn_reviews_id'];
								if(isset($_POST[$rating_slug])){
									$rating_value = $_POST[$rating_slug];
									if($rating_value){
										$rating_point = get_post_meta($post->ID, $rating_slug, true);
										if($rating_point)
											$rating_array[] = $rating_point;
									}
								}
							}
						}
						
					}
				endwhile;
				if($rating_array && is_array($rating_array) && count($rating_array)>0){
					$dir_rating[] = round(array_sum($rating_array)/count($cs_rating_options), 2);
				}
			}
			if(isset($dir_rating) && is_array($dir_rating) && count($dir_rating)>0){
				$var_cp_rating_sum = array_sum($dir_rating);
				$var_cp_rating = $var_cp_rating_sum/$post_count;
				$var_cp_rating = round($var_cp_rating, 2);
			}
			update_post_meta($id, "cs_rooms_review_rating", $var_cp_rating);
			return $var_cp_rating;
	  }
	}
	new review_meta();
}
?>