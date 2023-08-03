<?php
/**
 * The template for Home page 
 */ 
    get_header();
     global $cs_node,$cs_theme_options,$cs_counter_node;
    if(isset($cs_theme_options['cs_excerpt_length']) && $cs_theme_options['cs_excerpt_length'] <> ''){ 
        $default_excerpt_length = $cs_theme_options['cs_excerpt_length']; }else{ $default_excerpt_length = '255';
    } 
    $cs_layout     = isset($cs_theme_options['cs_default_page_layout']) ? $cs_theme_options['cs_default_page_layout'] : '';
    if ( isset( $cs_layout ) && ($cs_layout == "sidebar_left" || $cs_layout == "sidebar_right")) {
        $cs_page_layout = "page-content";
     } else {
        $cs_page_layout = "page-content-fullwidth";
     }
    $cs_sidebar    =  $cs_theme_options['cs_default_layout_sidebar'];

    $cs_tags_name = 'post_tag';
    $cs_categories_name = 'category';
    ?>   
    
        <section class="page-section" style="padding:0;">
            <!-- Container -->
            <div class="container">
                <!-- Row -->
              <div class="row">     
                <!--Left Sidebar Starts-->
                <?php if ($cs_layout == 'sidebar_left'){ ?>
                    <div class="page-sidebar">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?>
						<?php endif; ?>
                    </div>
                <?php } ?>
                <!--Left Sidebar End-->
                <!-- Page Detail Start -->
                <div class="<?php echo esc_attr($cs_page_layout); ?>">
                 	<div class="cs-blog blog-medium">
                        <div class="col-md-12">
                        <?php 
                            if ( have_posts() ) : 
                                   if (empty($_GET['page_id_all']))
                                     $_GET['page_id_all'] = 1;
                                  if (!isset($_GET["s"])) {
                                     $_GET["s"] = '';
                                  }
                                while ( have_posts() ) : the_post(); 
                                    $width = '370';
                                    $height = '280';
                                    $title_limit = 1000;
                                    $thumbnail = cs_get_post_img_src( $post->ID, $width, $height );
                              ?>                         
                                <article>
                                    <div class="date-time">
                                        <time datetime="<?php echo date_i18n( 'Y-m-d', strtotime(get_the_date()) );?>"><span><strong><?php echo date_i18n( 'd', strtotime(get_the_date()) );?></strong></span><small><?php echo date_i18n( 'M', strtotime(get_the_date()) );?></small></time>
                                    </div>
                                    <?php if ( isset( $thumbnail ) && $thumbnail != '' ) { ?>
                                    <div class="cs-media">
                                        <figure>
                                            <a href="<?php esc_url(the_permalink());?>"><img alt="img" src="<?php echo esc_url( $thumbnail );?>"></a>
                                            <figcaption><a href="<?php esc_url(the_permalink());?>"><img alt="img" src="<?php echo esc_url( get_template_directory_uri() );?>/assets/images/plus-img.png"></a></figcaption>
                                        </figure>
                                    </div>
                                    <?php } ?>
                                    <section>
                                        <div class="blog-text">
                                            <?php
                                            $categories_list = get_the_term_list ( get_the_id(), 'category', '<li class="categories">', ', ', '</li>' );
                                            if ( $categories_list ){
                                            ?>
                                            <ul class="post-options">
                                                <?php printf( '%1$s', $categories_list );?>
                                            </ul>
                                            <?php
                                            }
                                            ?>
                                            <h4>
                                                <a href="<?php esc_url(the_permalink());?>"><?php echo wp_trim_words(get_the_title(get_the_id()), $title_limit, '...'); ?></a>
                                                <?php
                                                if(is_sticky(get_the_ID())){
                                                    echo '<span class="featured-post">'.__('featured', 'luxury-hotel').'</span>';
                                                }
                                                ?>
                                            </h4>
                                            <p><?php echo cs_get_the_excerpt($default_excerpt_length,'ture','');?></p>
                                            <ul class="post-options">
                                                <li><i class="icon-user9"></i> <?php _e('by', 'luxury-hotel') ?><span> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author();?></a> </span></li>
                                                <li><i class="icon-chat6"></i><?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></li>
                                                <li>
                                                    <?php
                                                    $cs_post_like_counter = get_post_meta(get_the_id(), "cs_post_like_counter", true);
                                                    if ( !isset($cs_post_like_counter) or empty($cs_post_like_counter) ) $cs_post_like_counter = 0;
                                                    if ( isset($_COOKIE["cs_post_like_counter".get_the_id()]) ) { 
                                                        echo '<i class="icon-heart12"></i><a><span>' .__('Likes', 'luxury-hotel') . ' (' . $cs_post_like_counter . ')</span></a>';
                                                    } else {
                                                    ?>
                                                        <i class="icon-heart12"></i><a id="post_likes<?php echo get_the_id(); ?>" onclick="javascript:cs_post_likes_count('<?php echo admin_url('admin-ajax.php')?>','<?php echo get_the_id()?>')"><?php _e('Likes', 'luxury-hotel') ?> (<?php echo absint($cs_post_like_counter) ?>)</a>
                                                    <?php
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </section>
                                </article>
                             
							<?php 
                            endwhile; 
                            wp_reset_query();
                        else:
                             if ( function_exists( 'cs_fnc_no_result_found' ) ) { cs_fnc_no_result_found(); }
                        endif; 
                        $qrystr = '';
                            if ( isset($_GET['page_id']) ) $qrystr .= "&page_id=".$_GET['page_id'];
                            if ($wp_query->found_posts > get_option('posts_per_page')) {
                               if ( function_exists( 'cs_pagination' ) ) { echo cs_pagination(wp_count_posts()->publish,get_option('posts_per_page'), $qrystr); } 
                            }
                        ?>
                        </div>
                    </div>
                </div>  
               <?php
               if ( $cs_layout  == 'sidebar_right'){ ?>
                   <div class="page-sidebar"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : ?><?php endif; ?></div>
               <?php } ?>    
            </div>
        </div>
      </section>
    <?php get_footer(); ?>