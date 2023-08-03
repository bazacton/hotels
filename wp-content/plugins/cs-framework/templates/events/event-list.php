<?php
/**
 * Event Listing View
 *
 */
	global $cs_theme_options,$events_time,$event_date,$cs_event_meta,$event_excerpt,$category,$cs_notification,$wp_query;
	extract( $wp_query->query_vars );
$google_api =   isset($cs_theme_options['google_api_key']) ? $cs_theme_options['google_api_key'] : '';
	$width 		  = '202';
	$height	      = '146';
	$title_limit  = 46;
	$randomid	  = cs_generate_random_string('10');
	//echo "on listing page";
	
?>

<div class="cs-events events-listing col-md-12">
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=true&key=".$google_api></script>
	<?php 
		$query = new WP_Query( $args );
		//echo "</br>";
		//echo "count on page is".
		$post_count = $query->post_count;
		//echo "</br>";
		if ( $query->have_posts() ) {  
		  $postCounter    = 0;
		  while ( $query->have_posts() )  : $query->the_post();         
		  
		  
		  $thumbnail 	  = cs_get_post_img_src( $post->ID, $width, $height );                
		  $cs_postObject  	= get_post_meta(get_the_id(), "cs_full_data", true);
		  
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
		  $cs_event_status = get_post_meta($post->ID, "cs_event_status", true);	
		  $cs_status_color =  get_post_meta($post->ID, "cs_status_color", true);	
		  if( $thumbnail =='' ){
		  	$thumbnail	= get_template_directory_uri().'/assets/images/no-image.png';
		  } 
		  
		  $cs_event_from_date = get_post_meta(get_the_id(), "cs_event_from_date", true);

		  $cs_right_date = cs_correct_date_form( $cs_event_from_date );
		  
		  $cs_date_time = get_post_meta(get_the_id(), "date_time", true);
                  
		  
      ?>
    	<article class="post-<?php echo esc_attr( $post->ID );?>">
        <div class="date-time">
            <strong><?php echo date_i18n('d',strtotime($cs_right_date));?></strong>
          	<span><?php  echo date_i18n('M',strtotime($cs_right_date));?></span>
        </div>
        <section>
            <figure>
                <a href="<?php esc_url(the_permalink());?>"><img src="<?php echo esc_url($thumbnail);?>" alt=""  /></a>
                <figcaption>
                    <a href="<?php esc_url(the_permalink());?>"><i class="icon-plus-square-o"></i></a>
                </figcaption>
            </figure>
            <div class="text">
                <?php
                  $background = '';
				  if ( isset( $cs_status_color ) &&  $cs_status_color !='' ) {
					$color	= $cs_status_color;
					$background	= ' style="background-color:'.$color.' !important; color:#FFF !important; border-color:'.$color.' !important;"';
				  }
				
				if( isset( $cs_event_status) && $cs_event_status !='' ) {
				?>
                <span class="free" <?php echo cs_allow_special_char($background); ?>>
                 <?php
					if ( isset( $cs_status_color ) && $cs_status_color !=''){
						$event_ticket_color = 'style="color:#FFF"';
					} else {
						$event_ticket_color = '';
					}
				   		$cs_event_status = isset($cs_event_status) ? $cs_event_status : '';
					?>
                    <a class="ev-btn" target="_blank" <?php echo cs_allow_special_char($background);?> href="javascript:;"><?php echo esc_attr($cs_event_status);?></a>
        		</span>
                <?php }?>
                
                <?php if( isset( $events_time ) && $events_time =='Yes' ) {
					 if( isset( $cs_event_all_day ) && $cs_event_all_day =='on' ) {
							echo '<time datetime="'.date_i18n( 'Y-m-d', strtotime($cs_event_start_time)).'">';
							_e('All Day','cs_frame');
							echo '</time>';
					 } else {
						  
						  $cs_event_start_time = isset($cs_event_start_time) ? $cs_event_start_time : '';
						  $cs_event_end_time = isset($cs_event_end_time) ? $cs_event_end_time : '';
						  if( $cs_event_start_time != '' || $cs_event_end_time != '' ) {
							  echo '<time datetime="'.date_i18n( 'Y-m-d', strtotime($cs_event_start_time)).'">';
							  if( isset( $cs_event_start_time ) && $cs_event_start_time <> ''){
								echo date('h:i A',strtotime($cs_event_start_time)).' ';
							  }
							  
							  if(isset($cs_event_end_time) && $cs_event_end_time <> ''){
								_e('-', 'cs_frame');
								echo ' '.date('h:i A',strtotime($cs_event_end_time));
							  }
							  echo '</time>';
						   }
					 }
				 }?> 
				 <?php
				 
				 ?>
                
                <h2><a href="<?php esc_url(the_permalink());?>"><?php echo cs_get_event_title(get_the_title(),$title_limit);?></a></h2>
                <?php if( isset( $event_excerpt ) && $event_excerpt > 0 ) {?> 
                <p><?php echo cs_get_the_excerpt($event_excerpt,'false','Read More');?></p>
                <?php }?>
                <ul class="post-options" id="post-<?php echo esc_attr( $post->ID );?>">
                    <?php if( isset( $cs_location_address ) && $cs_location_address <> '' ){ ?>
                    	<li><i class="icon-map5"></i><p><?php echo cs_get_event_address($cs_location_address,'45');?></p></li>
                    <?php }?>
                    <?php if( isset( $cs_map_switch ) && $cs_map_switch == 'on' ) {?>
                    <li>
                         <a href="javascript:;" onclick="cs_show_map('<?php echo esc_attr( $post->ID ); ?>', '<?php echo esc_attr( $address_map );?>', '<?php echo esc_attr( $event_loc_lat );?>', '<?php echo esc_attr( $event_loc_long );?>', '<?php echo esc_attr( $event_loc_zoom );?>', '<?php echo home_url() ?>','<?php echo cs_framework::plugin_url() ?>')"  class="map-marker toggle" ><i class="icon-target5"></i><?php esc_html_e( 'See map','cs_frame' );?></a>
                    </li>
                    <?php }?>
                    <?php if( isset( $cs_buy_url ) && isset($cs_ticket_title) && $cs_buy_url !='' ) {?>
                    	<li><i class="icon-ticket6"></i><a href="<?php echo esc_url($cs_buy_url);?>" class="event-icon" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="<?php echo esc_attr($cs_ticket_title);?>"><?php echo __('Get Tickets','cs_frame');?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php if( isset( $cs_map_switch ) && $cs_map_switch == 'on' ) {?>
            <div class="event-map csmap post-<?php echo intval($post->ID);?>" id="event-<?php echo intval($post->ID);?>" style="display:none">
                <figure>
                	<div class="mapcode fullwidth mapsection gmapwrapp" id="map_canvas<?php echo intval($post->ID); ?>" style="height:182px; width:100%;"></div>
                </figure>
        	</div>
            <?php
            }
			?>
        </section>
    </article>
      <?php 
		endwhile;
	  } else {
	  	 $cs_notification->error('No Event found.');
	  }
	 ?>
</div>