<?php
	global $post,$cs_blog_cat,$cs_blog_description,$cs_blog_excerpt,$cs_notification,$wp_query;
	extract( $wp_query->query_vars ); 
  	$width 		  = '290';
  	$height	      = '218';
  	$title_limit  = 1000;      
?>
<div class="cs-blog blog-small">
 <?php 
 	
 	$query = new WP_Query( $args );
	$post_count = $query->post_count;
	if ( $query->have_posts() ) {  
	  $postCounter    = 0;
	  while ( $query->have_posts() )  : $query->the_post();             
	  $thumbnail 	  	= cs_get_post_img_src( $post->ID, $width, $height );                
	  $cs_postObject 	= get_post_meta(get_the_id(), "cs_full_data", true);
	  $cs_gallery	  	= get_post_meta($post->ID,'cs_post_list_gallery', true);
	  $cs_gallery		= explode(',',$cs_gallery);
	  $post_audio		= $cs_thumb_view	  = get_post_meta($post->ID,'cs_post_detail_audio', true);
	  $cs_thumb_view	= get_post_meta($post->ID,'cs_thumb_view', true);
 	  $cs_post_view		= isset($cs_thumb_view) ? $cs_thumb_view : '';


	 ?>
	  <div class="col-md-12">
        <article>
          <div class="date-time">
              <strong><?php echo date_i18n( 'd', strtotime( get_the_date() ) ) ?></strong>
              <span><?php echo date_i18n( 'M', strtotime( get_the_date() ) ) ?></span>
          </div>
          <div class="cs-media">
            <?php if ( $cs_post_view == 'single' ){
			  if ( isset( $thumbnail ) && $thumbnail !='' ) {?>
                <figure> 
                    <a href="<?php esc_url(the_permalink());?>"><img src="<?php echo esc_url( $thumbnail );?>" alt="img"></a>
                    <figcaption><a href="<?php esc_url(the_permalink());?>"><img src="<?php echo get_template_directory_uri();?>/assets/images/plus-img.png" alt="img"></a></figcaption>
                </figure>
			  <?php }
				   } else if ( $cs_post_view == 'slider' && is_array( $cs_gallery ) and count( $cs_gallery ) > 0 ) {
						cs_post_flex_slider($width,$height,get_the_id(),'post-list');
					 
				   }else if ($cs_post_view == "audio"){										
						$viewType = '<i class="icon-microphone"></i>';
						echo '<div class="cs-media"><figure class="detailpost">';
						echo do_shortcode('[audio mp3="'.$post_audio.'"][/audio]');
						echo '</figure></div>';
   					}
				
				?>
          </div>
          <section>
            <div class="blog-text">
              <ul class="post-options">
                <li class="categories"> <?php cs_get_categories( $cs_blog_cat );?></li>
              </ul>
              <h4><a href="<?php esc_url(the_permalink());?>"><?php the_title();?></a></h4>
              <?php if ($cs_blog_description == 'yes') {?><p> <?php echo cs_get_the_excerpt($cs_blog_excerpt,'false','Read More');?></p><?php } ?> 
              <ul class="post-options">
                <li><i class="icon-user9"></i><?php echo __('by ','luxury-hotel');?><span> <?php echo get_the_author();?> </span>
                <li><i class="icon-chat6"></i><a href="<?php comments_link(); ?>"><?php _e('Comments ','luxury-hotel');?>
                    <span> 
                    <?php 
                        $coments	= get_comments_number(__('0', 'luxury-hotel'),__('1', 'luxury-hotel'),__('%', 'luxury-hotel') );
                        printf('(%s)',$coments);?>
                    </span>
                </a></li>
                <li>
                    <?php
					$cs_post_like_counter = get_post_meta(get_the_id(), "cs_post_like_counter", true);
					if ( !isset($cs_post_like_counter) or empty($cs_post_like_counter) ) $cs_post_like_counter = 0;
						if ( isset($_COOKIE["cs_post_like_counter".get_the_id()]) ) { 
							echo '<i class="icon-heart12"></i>' .__('Likes', 'luxury-hotel') . '<span>(' . $cs_post_like_counter . ')</span>';
						} else {
					?>
						<i class="icon-heart12"></i><a href="javascript:;" id="post_likes<?php echo get_the_id(); ?>" onclick="javascript:cs_post_likes_count('<?php echo admin_url('admin-ajax.php')?>','<?php echo get_the_id()?>')"><?php _e('Likes', 'luxury-hotel') ?><span>(<?php echo absint($cs_post_like_counter) ?>)</span></a>
					<?php }?>
                </li>
              </ul>
            </div>
          </section>
        </article>
      </div>					  
	 <?php 
		endwhile;
	  } else {
	  	 _e('No blog post found.', 'luxury-hotel');
	  }
	  ?>
</div>