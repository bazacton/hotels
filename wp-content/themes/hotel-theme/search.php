<?php
/**
 * The template for displaying Search Result
 */
	get_header();
	global  $cs_theme_option, $wp_query; 
 	$default_excerpt_length = isset($cs_theme_options['cs_excerpt_length']) ? $cs_theme_options['cs_excerpt_length'] : '255';
	
	$cs_layout = isset($cs_theme_options['cs_default_page_layout']) ? $cs_theme_options['cs_default_page_layout']:'';
 	if ( isset( $cs_layout ) && ($cs_layout == "sidebar_left" || $cs_layout == "sidebar_right")) {
		$cs_page_layout = "page-content";
 	} else {
		$cs_page_layout = "page-content-fullwidth";
 	}
	$cs_sidebar	= isset($cs_theme_options['cs_default_layout_sidebar']) ? $cs_theme_options['cs_default_layout_sidebar']:'sidebar-1';
	$cs_tags_name = 'post_tag';
	$cs_categories_name = 'category';
	if(!isset($GET['page_id'])) $GET['page_id_all']=1;
	?>
    <section class="page-section" style=" padding:0;">
        <!-- Container -->
        <div class="container">
            <!-- Row -->
            <div class="row">
 				<?php if ($cs_layout == 'sidebar_left'){ ?>
                     <div class="page-sidebar">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) :  endif; ?>
                     </div>
                <?php } ?>
				<div class="<?php echo esc_attr($cs_page_layout); ?>">
	           		<div class="page-no-search">
						<?php
							if ( have_posts() ) :
                                $aa = 'Showing result for '.get_search_query();
							?>
                            <div class="search-heading"> <h2><?php printf(_e('%s','luxury-hotel'), $aa); ?></h2></div>
							<div class="cs-search-results">
                                <ul>
                                    <?php
                                        while ( have_posts() ) : the_post();
                                            if ( is_sticky() ){  echo '<span>'.__('Featured:', 'luxury-hotel').'</span>';} ?>
                                            <li>
                                                <strong><?php echo  date_i18n(get_option( 'date_format' ),strtotime(get_the_date())); ?>
                                                    <?php  	echo cs_get_the_excerpt('50',false);?>
                                                </strong>
                                                <a href="<?php esc_url(the_permalink()); ?>"><?php esc_url(the_permalink()); ?></a>
                                            </li>
                                    <?php endwhile;	?>
                                </ul>
                            </div>
                            <?php
								else:
									cs_fnc_no_result_found(); 
								endif;				
                				$qrystr = '';
								if ($wp_query->found_posts > get_option('posts_per_page')) {    
									if ( isset($_GET['s']) ) $qrystr = "&amp;s=".$_GET['s'];
									if ( isset($_GET['page_id']) ) $qrystr .= "&amp;page_id=".$_GET['page_id'];
									echo cs_pagination($wp_query->found_posts,get_option('posts_per_page'), $qrystr);
								}
							?>
					</div>	
           		</div>
				<?php if ( $cs_layout  == 'sidebar_right'){ ?>
                   <div class="page-sidebar">
                       <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar($cs_sidebar) ) : endif; ?>
                   </div>
                <?php } ?> 
       </div>
      </div>
   </section>
<?php 
get_footer();
?>
<!-- Columns End -->