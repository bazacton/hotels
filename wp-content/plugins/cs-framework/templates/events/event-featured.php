<?php
/**
 * Event Listing View
 *
 */
	global $cs_theme_options,$events_time,$event_date,$cs_event_meta,$event_excerpt,$category,$cs_notification,$wp_query;

	$google_api =   isset($cs_theme_options['google_api_key']) ? $cs_theme_options['google_api_key'] : '';

	extract( $wp_query->query_vars );
	$title_limit	= 46;
	$randomid		= cs_generate_random_string('10');
	
?>

    <div class="cs-events events-timeline col-md-12">
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true&key=".$google_api></script>
        <?php 
        $query = new WP_Query( $args );
        $post_count = $query->post_count;
        
        if ( $query->have_posts() ) {  
			$postCounter    = 0;
			while ( $query->have_posts() )  : $query->the_post();             
				$cs_postObject		= get_post_meta(get_the_id(), "cs_full_data", true);
				
				$event_loc_long = get_post_meta($post->ID, "cs_location_longitude", true);
			    $event_loc_zoom = get_post_meta($post->ID, "cs_location_zoom", true);
			    $event_loc_lat  = get_post_meta($post->ID, "cs_location_latitude", true);
			    $cs_map_switch  = get_post_meta($post->ID, "cs_map_switch", true);
				$address_map    = get_post_meta($post->ID, "cs_location_address", true);
				$cs_event_all_day = get_post_meta($post->ID, "cs_event_all_day", true);
			    $cs_event_start_time = get_post_meta($post->ID, "cs_event_start_time", true);
			    $cs_event_end_time = get_post_meta($post->ID, "cs_event_end_time", true);
			    $cs_location_address = get_post_meta($post->ID, "cs_location_address", true);
			    $cs_buy_url = get_post_meta($post->ID, "cs_buy_url", true);
			    $cs_ticket_title = get_post_meta($post->ID, "cs_ticket_title", true);
		  
				$cs_event_from_date = get_post_meta(get_the_id(), "cs_event_from_date", true);
				
				$cs_right_date		= cs_correct_date_form( $cs_event_from_date );
				?>
				<article>
                    <div class="date-time">
                        <strong><?php echo date_i18n('j', strtotime($cs_right_date));?></strong>
                        <span><?php  echo date_i18n('M', strtotime($cs_right_date));?></span>
                    </div>
                    <section>
                        <div class="text">
                            <h6><a href="<?php esc_url(the_permalink()); ?>"><?php echo cs_get_event_title(get_the_title(), $title_limit); ?></a></h6>
                            <p><?php echo cs_get_the_excerpt($event_excerpt, 'false');?></p>
                            <ul class="post-options">
                            	<?php
								$event_organizer = get_post_meta( $post->ID, 'cs_event_organizer', true );
								if( $event_organizer <> '' ) {
									echo '<li><i class="icon-user9"></i>';
									_e('by', 'cs_frame');
									echo '&nbsp;<span>&nbsp;';
									echo esc_attr($event_organizer);
									echo '</span></li>';
								}
								?>
                                <li>
                                	<i class="icon-clock7"></i>
                                	<?php
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
									?>
                                </li>
                            </ul>
                        </div>
                    </section>
                </article>
				<?php 
			endwhile;
		} else {
			$cs_notification->error('No Event found.');
        }
        ?>
    </div>