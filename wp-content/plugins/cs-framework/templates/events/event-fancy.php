<?php
/**
 * Event Listing View
 *
 */
	global $cs_theme_options,$events_time,$event_date,$cs_event_meta,$event_excerpt,$category,$cs_notification,$wp_query;
	extract( $wp_query->query_vars );
	$width 		  = '290';
	$height	      = '218';
	$title_limit = 45;
	$randomid = cs_generate_random_string('10');
		
?>
<div class="cs-events events-grid">
	<?php 
		$query = new WP_Query( $args );
		$post_count = $query->post_count;
		
		if ( $query->have_posts() ) {  
		  $postCounter    = 0;
		  while ( $query->have_posts() )  : $query->the_post();             
		  $thumbnail 	  = cs_get_post_img_src( $post->ID, $width, $height );                
		  $cs_postObject  = get_post_meta($post->ID, "cs_full_data", true);
		  if( $thumbnail =='' ){
		  	$thumbnail	= get_template_directory_uri().'/assets/images/no-image.png';
		  }
		  
		  $cs_event_from_date = get_post_meta($post->ID, "cs_event_from_date", true);
		  
		  $cs_event_all_day = get_post_meta($post->ID, "cs_event_all_day", true);
		  $cs_event_start_time = get_post_meta($post->ID, "cs_event_start_time", true);
		  $cs_event_end_time = get_post_meta($post->ID, "cs_event_end_time", true);
		  $cs_location_address = get_post_meta($post->ID, "cs_location_address", true);
		  $cs_buy_url = get_post_meta($post->ID, "cs_buy_url", true);
		  $cs_ticket_title = get_post_meta($post->ID, "cs_ticket_title", true);
		   $cs_event_status = get_post_meta($post->ID, "cs_event_status", true);	
		  $cs_status_color =  get_post_meta($post->ID, "cs_status_color", true);

		  $cs_right_date = cs_correct_date_form( $cs_event_from_date ); 
      ?>
      <div class="col-md-4">
          <article class="alighn-center">
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
				 
              <h5><a href="<?php esc_url(the_permalink());?>"><?php echo cs_get_event_title(get_the_title(),$title_limit);?></a></h5>
			   
              <ul class="post-options">
                <li><span><?php echo date_i18n(get_option('date_format'),strtotime($cs_right_date));?></span></li>
              </ul>
              <?php if( ( isset( $cs_location_address ) && $cs_location_address !='' ) ||  ( isset( $cs_buy_url ) && $cs_buy_url !='' ) ) {?>
              <div class="info-box">
                <div class="box-inner">
                   <?php if( isset( $cs_location_address ) && $cs_location_address <> '' ){ ?>
                    	<ul class="post-options">
                        	<li><?php echo cs_get_event_address($cs_location_address,'45');?></li>
                        </ul>
                    <?php }?>
                  <?php if( isset( $cs_buy_url ) && isset($cs_ticket_title) && $cs_buy_url !='' ) {?>
                  <div class="bottom-info-box">
                    <ul>
                      <li><a href="<?php echo esc_url($cs_buy_url);?>" class="event-icon" data-toggle="tooltip" data-placement="top" data-html="true" title="" data-original-title="<?php echo esc_attr($cs_ticket_title);?>"><i class="icon-ticket6"></i></a></li>
                    </ul>
                  </div>
                  <?php }?>
                </div>
              </div>
              <?php }?>
            </div>
          </article>
        </div>
      <?php 
		endwhile;
	  } else {
	  	 $cs_notification->error('No Event found.');
	  }
	 ?>
</div>