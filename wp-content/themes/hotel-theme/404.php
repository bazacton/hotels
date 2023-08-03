<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 */
get_header();

global $cs_theme_options;
$cs_sub_footer_social_icons = isset($cs_theme_options['cs_sub_footer_social_icons'])? $cs_theme_options['cs_sub_footer_social_icons'] : ''; ?>
<div class="wrapper">
	<!--// Header Start //-->
	<header id="main-header" class="header_1"></header>
    <!--// Header Start //-->
    <div class="clear"></div>
	<section class="page-section">
    	<div class="container">
        	<div class="row">
				<div class="page-not-found">
                	<figure><img src="<?php echo get_template_directory_uri() ?>/assets/images/img1.png"></figure>
                    <div class="cs-content404">
					
            			<h2><span class="message-404"><?php _e('404','luxury-hotel');?></span><span style="color:#df2725; font-size:60px;"><?php _e('Error','luxury-hotel');?></span></h2>
            			<p><?php _e("Sorry, you're lost my friend, the page you're looking for does not exist anymore. Take your<br>luck at searching for a new one.","luxury-hotel");?></p>
                        <div class="cs-search-area">
                        	<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
                          		<input id="s" name="s" type="text" required 
                                	onfocus="if(this.value == '<?php _e('Enter any keyword','luxury-hotel');?>') { this.value = ''; }"
                                	onblur="if(this.value == '') { this.value = '<?php _e('Enter any keyword','luxury-hotel');?>'; }"
                               		placeholder="<?php _e('Enter any keyword','luxury-hotel');?>" />
                          		<label>
                           			<input type="submit" class="btn csbg-color" value="">
                           		</label>
                          </form>
                           <?php if( $cs_sub_footer_social_icons == 'on' ){ ?>
                            <div class="social-media">
                            	<ul>
									<?php if ( function_exists( 'cs_social_network' ) ) { cs_social_network(); } ?>
                                </ul>
                            </div>
                          <?php } ?>
                       </div>    
                        <a class="go-home cs-color" href="<?php echo esc_url(site_url()); ?>"><i class="icon-angle-double-left"></i><?php _e('Return to Homepage','luxury-hotel');?> </a>
                    </div>
                </div>
             </div>
        </div>
    </section>
</div>
<?php get_footer();?>