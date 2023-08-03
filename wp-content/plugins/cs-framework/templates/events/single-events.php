<?php
/**
 * The template for Event Detail
 */

	global $post, $cs_theme_options;
	$cs_uniq = rand(11111111, 99999999);
  	get_header();	
	$cs_postObject = get_post_meta( $post->ID, 'cs_full_data' ,true);
	
 	$cs_layout = '';
	$leftSidebarFlag		= false;
	$rightSidebarFlag		= false;
	
	$cs_layout 				= get_post_meta( $post->ID, 'cs_page_layout' ,true);
	$cs_sidebar_left		= get_post_meta( $post->ID, 'cs_page_sidebar_left' ,true);
	$cs_sidebar_right		= get_post_meta( $post->ID, 'cs_page_sidebar_right' ,true);	
	$post_tags_show			= get_post_meta( $post->ID, 'cs_post_tags_show' ,true);
	$cs_post_social_sharing	= get_post_meta( $post->ID, 'cs_post_social_sharing' ,true);
	$cs_event_from_date		= get_post_meta( $post->ID, 'cs_event_from_date' ,true);
	$cs_event_all_day		= get_post_meta( $post->ID, 'cs_event_all_day' ,true);
	$cs_event_start_time	= get_post_meta( $post->ID, 'cs_event_start_time' ,true);
	$cs_event_end_time		= get_post_meta( $post->ID, 'cs_event_end_time' ,true);
	$cs_location_address	= get_post_meta( $post->ID, 'cs_location_address' ,true);
	$cs_location_latitude	= get_post_meta( $post->ID, 'cs_location_latitude' ,true);
	$cs_location_longitude	= get_post_meta( $post->ID, 'cs_location_longitude' ,true);
	$cs_location_zoom		= get_post_meta( $post->ID, 'cs_location_zoom' ,true);
	$cs_event_members		= get_post_meta( $post->ID, 'cs_event_members' ,true);
	$cs_prices_title		= get_post_meta( $post->ID, 'cs_prices_title' ,true);
	$cs_prices_shortcode	= get_post_meta( $post->ID, 'cs_prices_shortcode' ,true);
	$cs_map_switch			= get_post_meta( $post->ID, 'cs_map_switch' ,true);
	$cs_map_heading			= get_post_meta( $post->ID, 'cs_map_heading' ,true);
	$cs_location_zoom		= isset( $cs_location_zoom ) && $cs_location_zoom !='' ? $cs_location_zoom : '10';
	
	if ( $cs_layout == "left") {
		$cs_layout = "page-content";
		$leftSidebarFlag	= true;
	}
	else if ( $cs_layout == "right" ) {
		$cs_layout = "page-content";
		$rightSidebarFlag	= true;
	}
	else {
		$cs_layout = "page-content-fullwidth";
	}
	
	$width = 818;
	$height = 460;
	?>

    <div class="container"> 
        <div class="row">
			<?php
			if (have_posts()):
				while (have_posts()) : the_post();	
					$cs_tags_name = 'event-tag';
					$cs_categories_name = 'event-category';
					$postname = 'events';						
					
					$image_url = cs_get_post_img_src($post->ID, $width, $height);
			
					if ( $leftSidebarFlag == true ) { 
					?>
					<aside class="page-sidebar">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_left) ) : ?>
						<?php endif; ?>
					</aside>
					<?php } ?>

                    <div class="<?php echo esc_attr($cs_layout); ?>"> 
                    	<div class="event-editor cs-events">
							<?php 
							 
								if( isset( $cs_map_switch ) && $cs_map_switch == 'on' ) {
									echo '<div class="cs-event-map-section">';
                                	if( $cs_location_latitude <> '' && $cs_location_longitude <> '' && $cs_location_address <> '' ) {
                                     	echo do_shortcode('[cs_map column_size="1/1" map_height="250" map_lat="'.$cs_location_latitude.'" map_lon="'.$cs_location_longitude.'" map_zoom="'.$cs_location_zoom.'" map_type="ROADMAP" map_info="'.$cs_location_address.'" map_info_width="250" map_info_height="200" map_marker_icon1xis="Browse" map_show_marker="true" map_controls="false" map_draggable="true" map_scrollwheel="true" map_border="yes" cs_map_style=""]');
                                	}
                                echo '</div>';
								}?>
                            <div class="col-md-12">
                                <article>
                                    <?php if( $cs_event_from_date <> '' ) { ?>
                                    <div class="date-time">
                                        <strong><?php echo date_i18n( 'd', strtotime($cs_event_from_date) ) ?></strong>
                                        <span><?php echo date_i18n( 'M', strtotime($cs_event_from_date) ) ?></span>
                                    </div>
                                    <?php } ?>
                                    <section>
                                        <div class="text">
                                            <?php
                                            $categories_list = get_the_term_list ( get_the_id(), 'event-category', '<li>', ', ', '</li>' );
                                            if ( $categories_list ){?>
                                            <ul class="post-options">
                                                <?php printf( '%1$s', $categories_list );?>
                                            </ul>
                                            <?php }?>
                                            <h2><?php the_title() ?></h2>
                                            <?php 
                                                 if( $cs_event_all_day && $cs_event_all_day =='on' ) {
                                                        echo '<time datetime="'.date_i18n( 'Y-m-d', strtotime($cs_event_start_time)).'">';
                                                        _e('All Day','cs_frame');
                                                        echo '</time>';
                                                 } else {
                                                      
                                                      if( $cs_event_start_time !='' || $cs_event_end_time !='' ) {
                                                          echo '<time datetime="'.date_i18n( 'Y-m-d', strtotime($cs_event_start_time)).'">';
                                                          if( isset( $cs_event_start_time) && $cs_event_start_time <> ''){
                                                            echo date('h:i A',strtotime($cs_event_start_time)).' ';
                                                          }
                                                          
                                                          if(isset($cs_event_end_time) && $cs_event_end_time <> ''){
                                                            _e('-', 'cs_frame');
                                                            echo ' '.date('h:i A',strtotime($cs_event_end_time));
                                                          }
                                                          echo '</time>';
                                                       }
                                                 }
                                            ?>
                                            <ul class="post-options">
                                            
                                                <?php
                                                $event_organizer = get_post_meta( $post->ID, 'cs_event_organizer', true );
                                                if( $event_organizer <> '' ) {
                                                    echo '<li><span>';
                                                    _e('by', 'cs_frame');
                                                    echo '&nbsp;'.esc_attr($event_organizer);
                                                    echo '</span></li>';
                                                }
                                                ?>
                                            
                                                <?php if( $cs_location_address <> '' ) { ?>
                                                <li><i class="icon-map5"></i><?php echo esc_attr($cs_location_address) ?></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </section>
                                </article>
                                <div class="rich_editor_text">
                                    <?php if( $image_url <> '' ){ ?>
                                        <figure class="detailpost">
                                            <img src="<?php echo esc_url($image_url) ?>" alt="<?php the_title() ?>">
                                        </figure>
                                    <?php } 
                                    the_content();
                                    if ( $post_tags_show ){
                                        $post_tags_show_text =__('Tags', 'cs_frame');
                                        $cs_tags_list = get_the_term_list ( get_the_id(), 'event-tag', '<li>', '</li><li>', '</li>' );
                                        if ( $cs_tags_list ){
                                        ?>
                                            <div class="cs-tags"> 
                                                <i class="icon-pricetags"></i>
                                                <div class="tags-inn">
                                                    <h6><?php echo esc_html($post_tags_show_text) ?></h6>
                                                    <ul>
                                                        <?php printf( '%1$s',$cs_tags_list ); ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php										
                                        }
                                    }
                                    
                                    if ($cs_post_social_sharing == "on"){
                                        $post_social_sharing_text =__('Share', 'cs_frame');
                                        echo '<div class="detail-post">
                                                <div class="socialmedia">';
                                                cs_social_share_blog(false,true,$post_social_sharing_text);
                                        echo '</div></div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php if( isset( $cs_prices_shortcode ) && $cs_prices_shortcode !='' ) {?>
                            <div class="cs-section-title col-md-12">
                                <h2><?php echo esc_attr($cs_prices_title);?></h2>
                            </div>
                            <?php echo do_shortcode($cs_prices_shortcode);?>
                            <?php }?>	
                    	</div>
                    </div>
	  
				<?php
				endwhile;
			endif;  
			
			if( $rightSidebarFlag == true ) { ?>
      		<aside class="page-sidebar">
       			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar_right) ) : endif; ?>
      		</aside>
			<?php } ?>
    
        </div>
    </div>

	<?php
	get_footer();