<?php

/**
 * @Flickr widget Class
 *
 *
 */
if ( ! class_exists( 'cs_flickr' ) ) { 
    class cs_flickr extends WP_Widget {    
        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
             
        /**
         * @init Flickr Module
         *
         *
         */
     
		
			public function __construct() {
		
		parent::__construct(
			'cs_flickr', // Base ID
			__( 'CS : Flickr Gallery','luxury-hotel' ), // Name
			array( 'classname' => 'widget_gallery', 'description' =>__('Type a user name to show photos in widge.', 'luxury-hotel'), ) // Args
		);
	}
	         
         /**
         * @Flickr html form
         *
         *
         */
        function form($instance){
            $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
            $title = $instance['title'];
            $username = isset( $instance['username'] ) ? esc_attr( $instance['username'] ) : '';
            $no_of_photos = isset( $instance['no_of_photos'] ) ? esc_attr( $instance['no_of_photos'] ) : '';    
        ?>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"><?php _e('Title','luxury-hotel');?>
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('username')); ?>"><?php _e('Flickr username','luxury-hotel');?> 
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('username')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('username')); ?>" type="text" value="<?php echo esc_attr($username); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo cs_allow_special_char($this->get_field_id('no_of_photos')); ?>"><?php _e('Number of Photos','luxury-hotel');?> 
                <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('no_of_photos')); ?>" size='2' name="<?php echo cs_allow_special_char($this->get_field_name('no_of_photos')); ?>" type="text" value="<?php echo esc_attr($no_of_photos); ?>" />
                </label>
            </p>
        <?php
        }            
        /**
         * @Flickr update form data
         *
         *
         */
        function update($new_instance, $old_instance){
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['username'] = $new_instance['username'];
            $instance['no_of_photos'] = $new_instance['no_of_photos'];            
            return $instance;
        }    
        /**
         * @Display Flickr widget
         *
         *
         */
        function widget($args, $instance){
            global $cs_theme_options;            
            extract($args, EXTR_SKIP);
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
			$title = wp_specialchars_decode(stripslashes($title));
            $username = empty($instance['username']) ? ' ' : apply_filters('widget_title', $instance['username']);            
            $no_of_photos = empty($instance['no_of_photos']) ? ' ' : apply_filters('widget_title', $instance['no_of_photos']);    
            if($instance['no_of_photos'] == ""){$instance['no_of_photos'] = '3';}            
            echo cs_allow_special_char($before_widget);            
            if (!empty($title) && $title <> ' '){
                echo cs_allow_special_char($before_title);
                echo cs_allow_special_char($title);
                echo cs_allow_special_char($after_title);
            }            
            $get_flickr_array = array();                    
            $apiKey = $cs_theme_options['flickr_key'];
            $apiSecret = $cs_theme_options['flickr_secret'];            
            if($apiKey <> ''){            
                // Getting transient
                $cachetime = 86400;
                $transient = 'flickr_gallery_data';
                $check_transient = get_transient($transient);                
                // Get Flickr Gallery saved data
                $saved_data = get_option('flickr_gallery_data');                
                $db_apiKey = '';
                $db_user_name = '';
                $db_total_photos = '';                
                if($saved_data <> ''){
                    $db_apiKey = isset($saved_data['api_key']) ? $saved_data['api_key'] : '';
                    $db_user_name = isset($saved_data['user_name']) ? $saved_data['user_name'] : '';
                    $db_total_photos = isset($saved_data['total_photos']) ? $saved_data['total_photos'] : '';
                }                
                if( $check_transient === false || ($apiKey <> $db_apiKey || $username <> $db_user_name || $no_of_photos <> $db_total_photos) ){ 	$user_id = "https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key=".$apiKey."&username=".$username."&format=json&nojsoncallback=1";
					
					
					$response = wp_remote_get( esc_url_raw( $user_id ),  array( 'decompress' => false ) );
                    $response = $response['body'];
					$user_info = json_decode( wp_remote_retrieve_body( $response ), true );
				 
					 
								
					if ($user_info['stat'] == 'ok') {
						
						$user_get_id = $user_info['user']['id'];
						
						$get_flickr_array['api_key'] = $apiKey;
						$get_flickr_array['user_name'] = $username;
						$get_flickr_array['user_id'] = $user_get_id;
						
						$url = "https://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=".$apiKey."&user_id=".$user_get_id."&per_page=".$no_of_photos."&format=json&nojsoncallback=1";
						
						$response = wp_remote_get( esc_url_raw( $url ) , array( 'decompress' => false ));
                        $response = $response['body'];
					    $content = json_decode( wp_remote_retrieve_body( $response ), true );
					 	if ($content['stat'] == 'ok') {
							$counter = 0;
							echo '<ul class="gallery-list">';			 				
							foreach ((array)$content['photos']['photo'] as $photo) {
								
								$image_file = "https://farm{$photo['farm']}.staticflickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}_s.jpg";
								
								$img_headers = get_headers($image_file);
								if(strpos($img_headers[0], '200') !== false) {
									
									$image_file = $image_file;
								}
								else{
									$image_file = "https://farm{$photo['farm']}.staticflickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}_q.jpg";
									$img_headers = get_headers($image_file);
									if(strpos($img_headers[0], '200') !== false) {
										
										$image_file = $image_file;
									}
									else{
										$image_file = get_template_directory_uri().'/assets/images/no_image_thumb.jpg';
									}
								}
								
								echo '<li>';
								echo "<a target='_blank' title='" . $photo['title'] . "' href='https://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>";
								echo "<img alt='".$photo['title']."' src='".$image_file."'>";
								echo "</a>";
								echo '</li>';
														
								$counter++;
								
								$get_flickr_array['photo_src'][] = $image_file;
								$get_flickr_array['photo_title'][] = $photo['title'];
								$get_flickr_array['photo_owner'][] = $photo['owner'];
								$get_flickr_array['photo_id'][] = $photo['id'];
								
							}
							echo '</ul>';
							
							$get_flickr_array['total_photos'] = $counter;
							
							// Setting Transient
							set_transient( $transient, true, $cachetime );
					 update_option('flickr_gallery_data', $get_flickr_array);
							
							if($counter == 0) _e('No result found.', 'luxury-hotel');
						}
						
						else {
							echo 'Error:' . $content['code'] . ' - ' . $content['message'];
						}
					}
					
					else {
						echo 'Error:' . $user_info['code'] . ' - ' . $user_info['message'];
					}
					} else{
                    if( get_option('flickr_gallery_data') <> '' ){                        
                        $flick_data = get_option('flickr_gallery_data');
                        echo '<ul class="gallery-list">';
                            if(isset($flick_data['photo_src'])):
                                $i = 0;
                                foreach($flick_data['photo_src'] as $ph){
                                    echo '<li>';
                                    echo "<a target='_blank' title='" . $flick_data['photo_title'][$i] . "' href='https://www.flickr.com/photos/" . $flick_data['photo_owner'][$i] . "/" . $flick_data['photo_id'][$i] . "/'>";
                                    echo "<img alt='".$flick_data['photo_title'][$i]."' src='".$flick_data['photo_src'][$i]."'>";
                                    echo "</a>";
                                    echo '</li>';
                                    $i++;
                                }
                            endif;
                        echo '</ul>';
                    }
                    else{
                        _e('No result found.', 'luxury-hotel');
                    }
                }            
            }
            else{
                _e('Please Enter Flickr API key from Theme Options.', 'luxury-hotel');
            }
            echo cs_allow_special_char($after_widget);            
        }
    }
}
if (function_exists('cs_widget_register')) {
    cs_widget_register('cs_flickr');
}
?>