<?php
/**
 * The template for Event Detail
 */

	global $post, $cs_plugin_options;
	$cs_uniq = rand(11111111, 99999999);
  	get_header();	
	$width = 818;
	$height = 460;
	
	$cs_content_class = 'page-content-fullwidth';
	if ( is_active_sidebar( 'room-detail-sidebar' ) ) {
		$cs_content_class = 'page-content';
	}
	
	wp_hotel_booking::cs_prettyphoto_script();
	?>

    <div class="container">
      <div class="row">
        <div class="section-fullwidth blog-editor">
			<?php 
			if ( is_active_sidebar( 'room-detail-sidebar' ) ) {
				echo '<aside class="page-sidebar">';
					if ( ! function_exists('dynamic_sidebar') || ! dynamic_sidebar('room-detail-sidebar') ) : endif; 
				echo '</aside>';
			}
			?>
            <div class="<?php echo sanitize_html_class( $cs_content_class ) ?>">
            <section class="page-section">
              <div class="container">
                <div class="row">
                  <?php
                    if (have_posts()):
                        while (have_posts()) : the_post();	
                            $postname = 'rooms';
                            $cs_post_id		 = $post->ID;
                            $cs_price 		 = get_post_meta($post->ID, 'cs_room_starting_price', true);
                            $galleryDataThumb    = array();
                            $galleryData         = array();
                            $rooms_image_gallery = get_post_meta( $post->ID, 'cs_room_image_gallery', true );
                            $organizerID    	 = intval( get_the_author_meta('ID') );
                            $attachments 		   = array_filter( explode( ',', $rooms_image_gallery ) );
                            $attachmentsArray      = array();
                            $thumbArray            = array();
                            
                            if ( $attachments ) {
                                foreach ( $attachments as $attachment_id ) {
                                    $class			= '';
                                    $iconZoom		= '';
                                    $width_thumb	= 113;
                                    $height_thumb	= 64;
                                    $width			= 818;
                                    $height			= 460;
                                    $ZoomClass		= '';
                                    $thumb_url		= cs_attachment_image_src( $attachment_id ,$width_thumb,$height_thumb); 
                                    $image_url		= cs_attachment_image_src( $attachment_id ,$width,$height);
                                    
                                    $attachments_data	= get_post( $attachment_id, 'OBJECT', 'raw' );
                                    
                                    if ( isset( $image_url ) && $image_url != '' ){ 
                                        $attachmentsArray[] = $image_url;
                                    }
                                    if (isset( $thumb_url ) && $thumb_url != '' ){ 
                                        $thumbArray[] = $thumb_url;
                                    }
                                }
                            }

                            $attachments      = array_merge( $galleryData , $attachmentsArray );
                            $thumbnail        = array_merge( $galleryDataThumb , $thumbArray );
                            if($attachments){
                             
                             ?>
                              <div class="element-size-100">
                                  <div class="col-md-12">
                                    <div class="cs-gallery cs-loading">
                                       <?php cs_rooms_flex_slider( $attachments , $thumbnail , 'true');?>
                                    </div>
                                  </div>
                              </div>	 
                        <?php }?>
                            <div class="element-size-100">
                                <div class="cs-section-title col-md-12">
                                  <h2><?php _e('Room Overview','booking');?></h2>
                                </div>
                            </div>
                            <div class="element-size-100">
                              <div class="col-md-12">
                                <div class=" widget widget-reviews">
                                  <div class="reviews-inner box-br-style">
                                    <?php cs_hotel_rating_detail($cs_post_id,false); ?>
                                      <?php if ( isset( $cs_price ) && $cs_price !='' ) {?>
                                        <div class="cs-price-sec"><strong><?php _e('Starts from','booking');?></strong> <span  class="price"><?php echo esc_attr($cs_plugin_options['currency_sign'].$cs_price);?></span> </div>
                                      <?php }?>
                                  </div>
                                </div>
                                <div class="rich_editor_text">
                                  <?php 
                                        the_content();
                                        wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'booking' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) );
                                  ?>
                                </div>
                              </div>
                            </div>
                           <?php 
                            $featureList = get_post_meta($cs_post_id, 'cs_room_features', true);
                            $cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
                            $cs_output = '';
                            if( is_array($cs_feature_options) && sizeof($cs_feature_options) > 0 ) {?>
                            <div class="element-size-100">
                                    <div class="col-md-12">
                                      <div class="detail-head"> 
                                        <h6><?php _e('Room Features','booking');?></h6>
                                      </div>
                                      <ul class="cslist-info">
                                        <?php
                                            $counter	= 0;
                                            foreach($cs_feature_options as $feature){
                                                $feature_title 	 = $feature['cs_feats_title'];
                                                $feature_image 	 = $feature['cs_feats_image'];
                                                $feature_slug 	 = isset($feature['feats_id']) ? $feature['feats_id'] : '';
                                                $checked		 = '';
                                                $cs_image		 = '';
                                                if( isset( $feature_image ) && $feature_image !='' ){
                                                    $cs_image	= '<img src="'.esc_url( $feature_image ).'" alt="" />';
                                                }
												else{
													 $cs_image	= '<i>&nbsp;</i>';
												}
                                                
                                                if ( is_array( $featureList ) && in_array( $feature_slug , $featureList )  ) {
                                                    echo '<li><a href="javascript:;">'.$cs_image.esc_attr( $feature_title ).'</a></li>';
                                                }
                                            }
                                        ?>
                                      </ul>
                                    </div>
                                </div>
                            <?php }
								$tabs_sho = get_post_meta($cs_post_id, 'cs_tabs_shortcode', true);
								if( $tabs_sho <> '' ) {
									echo '<div class="element-size-100">
										<div class="cs-section-title col-md-12">
											<h2>'.__('Complete Overview','booking').'</h2>
										</div>
									</div>';
									echo htmlspecialchars_decode(do_shortcode($tabs_sho));
								}
								$share_btn = get_post_meta($cs_post_id, 'cs_share_btn', true);
 								if( $share_btn == 'on' && function_exists('cs_social_share') ) {
									cs_social_share('', '', __('Share', 'booking'));
								}
							?>
                            <div class="col-md-12">
                                <?php
                                    wp_reset_postdata();
                                    $count_args = array(
                                        'posts_per_page'             => "-1",
                                        'post_type'                  => 'cs-reviews',
                                        'post_status'                => 'publish',
                                        'meta_key'                   => 'cs_reviews_room',
                                        'meta_value'                 => $cs_post_id,
                                        'meta_compare'               => "=",
                                        'orderby'                    => 'meta_value',
                                        'order'                      => 'ASC',
                                    );
                                    $count_query = new WP_Query($count_args);
                                    $reviews_count = $count_query->post_count;
                                ?>
                                <div class="cs-section-title"><h2><?php printf(__('%s Reviews by guests','booking'), $reviews_count);?></h2></div>
                                <div class="cs-reviews">
                                  <ul class="row">
                                    <?php
                                    $page_id_all = '';
                                    if(isset($_GET['page_id_all']) && $_GET['page_id_all'] !=''){
                                        $page_id_all    = $_GET['page_id_all'];
                                    }
                                    
                                    $cs_per_page = isset($cs_theme_options['reviews_per_page']) ? $cs_theme_options['reviews_per_page'] : 10;
								    $reviews_args = array(
                                        'posts_per_page'	=> "$cs_per_page",
                                        'paged'				=> $page_id_all,
                                        'post_type'			=> 'cs-reviews',
                                        'post_status'		=> 'publish',
                                        'meta_key'			=> 'cs_reviews_room',
                                        'meta_value'		=> $cs_post_id,
                                        'meta_compare'		=> "=",
                                        'orderby'			=> 'ID',
                                        'order'				=> 'DESC',
                                    );
                                    $reviews_query = new WP_Query($reviews_args);
                              
                                    if ( $reviews_query->have_posts() <> "" ){
                                        while ( $reviews_query->have_posts() ): $reviews_query->the_post();    
                                            $cs_reviews_members 	= get_post_meta($post->ID, "cs_reviews_user", true);
                                            $cs_reviews_booking 	= get_post_meta($post->ID, "cs_reviews_room", true);
                                            $cs_rating_options 		= $cs_plugin_options['cs_dyn_reviews_options'];
                                            
                                            $rating = 0;
                                            $rating_array = array();
                                            
                                            if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
                                                foreach($cs_rating_options as $rating_key	=> $rating){
                                                    if(isset($rating_key) && $rating_key <> ''){
                                                    $counter_rating = $rating_id = $rating['dyn_reviews_id'];
                                                   	$rating_title 	= $rating['cs_dyn_reviews_title'];
                                                    $rating_slug 	= $rating['dyn_reviews_id'];
                                                    $rating_point 	= get_post_meta($post->ID, $rating_slug, true);
                                                    if($rating_point)
                                                        $rating_array[] = $rating_point;
                                                    }
                                                }
                                                $rating = round(array_sum($rating_array)/count($cs_rating_options), 2);
                                            }
											
											$rating_number	= $rating;
                                            if(isset($rating)){$rating = $rating*20;} else {$rating = 0;}
                                            ?>            
                                            <li class="col-md-12">
                                                <?php 
                                                    $cs_display_image = '';
                                                    $cs_display_image = cs_get_user_avatar(1 ,$cs_reviews_members);
                                                    if( $cs_display_image <> ''){?>
                                                        <figure>
                                                        <a><img height="60" width="60" src="<?php echo esc_url( $cs_display_image );?>"  /></a>
                                                        </figure>
                                                        <?php } else { ?>
                                                        <figure>
                                                        <a><?php echo get_avatar(get_the_author_meta('user_email',$cs_reviews_members), apply_filters('PixFill_author_bio_avatar_size', 60));?></a>
                                                        </figure>
                                                <?php } ?>
                                                <div class="review-date">
                                                      <a href="javascript:;"><?php echo get_the_author_meta( 'display_name', $cs_reviews_members ); ?></a>
                                                    <time datetime="<?php echo date_i18n( 'Y-m-d', strtotime(get_the_date()) );?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime(get_the_date()) );?><span><?php echo date_i18n( 'H:i a', strtotime(time()) );?></span></time>
                                                </div>
                                                 <div class="reviews-description">
                                                    <div class="rating-section"><div class="accomodation-rating"><span style="width:<?php echo absint($rating);?>%;"></span></div><span class="total-count"><?php echo esc_attr( $rating_number );?></span><span class="rate-overview">i</span>
                                                    <?php cs_total_score_section($cs_post_id);?>
                                                    </div>
                                                    <p><?php echo get_the_content();?></p>
                                                 </div>
                                                
												
                                            </li>
                                            <?php
												
                                        endwhile;
                                    } else { 
                                    ?>
                                        <div class="rich_editor_text succ_mess">
                                            <p><?php echo esc_html_e('Looking for review? There is no review here!','booking');?></p>
                                        </div>
                                    <?php 
                                    }
                                    ?>
                                  </ul>
                                </div>
                             </div>
                            
                            <!--Add Review -->
                            <?php if( $cs_plugin_options['cs_reviews_front'] && $cs_plugin_options['cs_reviews_front'] == 'on' ) {?> 
                            <div class="col-md-12">
                              <div class="cs-section-title">
                                <h2><?php _e('Leave a Review','booking');?></h2>
                              </div>
                              
                              <?php  
                                    wp_hotel_booking::cs_enqueue_rating_style_script();
/*                                    if ( $organizerID != $current_user->ID ) {?>
                                        <p><?php _e('Oops! You cannot add review to your own Ad.','booking');?></p>
                                    <?php 
                                    } else { */?>
                                    <div class="review-message-type succ_mess" style="display:none"><p></p></div>
                                    <div class="comment-respond cs-reviews-form" id="respond">
                                    <form name="reviews-form" id="cs-reviews-form">
                                        <ul class="rating-list">
                                        <div id="loading"></div>
                                        <?php
											$cs_rating_options = $cs_plugin_options['cs_dyn_reviews_options'];
                                            if(isset($cs_rating_options) && is_array($cs_rating_options) && count($cs_rating_options)>0){
                                                foreach($cs_rating_options as $rating_key=>$rating){
                                                    if(isset($rating_key) && $rating_key <> ''){
                                                        $rating_title 	= $rating['cs_dyn_reviews_title'];
                                                        $rating_slug 	= $rating['dyn_reviews_id'];
                                                        $rating = get_post_meta($post->ID, $rating_slug, true);
                                                        if(is_numeric($rating)){
                                                            if(isset($rating)){$rating = $rating*20;} else {$rating = 0;}
                                                        }else{
                                                            $rating = 0;
                                                        }
                                                        ?>
                                                        <script type="text/javascript">
                                                        jQuery(document).ready(function(){
                                                            jQuery(".<?php echo esc_js($rating_slug);?>").jRating({
                                                                step:true, 
                                                                bigStarsPath : "<?php echo esc_js(wp_hotel_booking::plugin_img_url());?>assets/images/cs-stars.png",
                                                                smallStarsPath : "<?php echo esc_js(wp_hotel_booking::plugin_img_url());?>assets/images/cs-stars.png",
                                                                rateMax : 5,
                                                                length  : 5,
                                                                canRateAgain : true,
                                                                nbRates : 10,
                                                                onClick : function(element,rate) {
                                                                    jQuery('#<?php echo esc_js($rating_slug);?>').val(rate);
                                                                    jQuery('#cs_<?php echo esc_js($rating_slug);?>').html('('+rate+')');
                                                                    jQuery('#cs_<?php echo esc_js($rating_slug);?>').show();
                                                                }
                                                            });
                                                        });
                                                        </script>
                                                        <li> <strong class="rating-title"><?php echo esc_attr($rating_title);?></strong>
                                                          <div class="rating-section">
                                                            <div class=""> <span style="width: 60%;"></span> </div>
                                                            <div class="accomodation-rating small <?php echo sanitize_html_class($rating_slug);?>" data-average="0" data-id="0"></div>
                                                            <input type="hidden" name="<?php echo esc_attr($rating_slug);?>" id="<?php echo esc_attr($rating_slug);?>" value="0" />
                                                            <small style="display:none" id="cs_<?php echo sanitize_html_class($rating_slug);?>"></small></div>
                                                        </li>
                                                        <?php
													
                                                    }
                                                }
                                            }
                                            ?>
                                        </ul>
                                        <div class="cs-classic-form cs_form_styling" id="Form">
                                            <div class="comment-respond" id="respond">
                                        
                                        <p>
                                          <label class="icon-usr">
                                            <input type="text" tabindex="1" value="" class="reviewer_name" name="reviewer_name" id="reviewer_name" placeholder="<?php _e('Enter Name', 'booking');?>">
                                          </label>
                                        </p>
                                        <p>
                                          <label class="icon-usr">
                                            <input type="text" tabindex="1" value="" class="reviewer_email" name="reviewer_email" id="reviewer_email" placeholder="<?php _e('Enter Email', 'booking');?>">
                                          </label>
                                        </p>
                                        <p>
                                          <label class="icon-usr">
                                            <input type="text" tabindex="1" value="" class="reviews_title" name="reviews_title" id="reviews_title" placeholder="<?php _e('Enter Subject', 'booking');?>">
                                          </label>
                                        </p>
                                        <?php if( isset( $cs_plugin_options['cs_review_booking_id'] ) == 'on' ) {?>
                                        <p>
                                          <label class="icon-usr">
                                            <input type="text" tabindex="1" value="" class="booking_id" name="booking_id" id="booking_id" placeholder="<?php _e('Enter Booking Id', 'booking');?>">
                                          </label>
                                        </p>
                                        <?php }?>
                                        
                                        <p class="comment-form-comment">
                                          <label class="icon-qute">
                                            <textarea cols="39" rows="4" class="reviews_description" name="reviews_description" id="reviews_description" placeholder="<?php _e('Message', 'booking');?>"></textarea>
                                          </label>
                                        </p>
                                        <p class="form-submit">
                                          <input type="button" value="Submit" class="form-style" id="cs-bg-color" name="<?php _e('Add Review','booking');?>"  onclick="cs_reviews_submission('<?php echo admin_url('admin-ajax.php')?>');">
                                          <input type="hidden" name="room_id" value="<?php echo absint($cs_post_id);?>" />
                                        </p>
                                        </div>
                                        </div>
                                    </form>
                                    </div>
                                <?php //}?>
                            </div>
                            <?php } ?>

                        <?php
                        endwhile;
                    endif;  
                    ?>
                </div>
              </div>
            </section>
            </div>
            
        </div>
      </div>
    </div>
 
<?php
	get_footer();


