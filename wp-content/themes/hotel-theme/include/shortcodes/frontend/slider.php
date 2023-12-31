<?php

//======================================================================
// Adding Offer Slider start
//======================================================================
if (!function_exists('cs_offerslider_shortcode')) {
    function cs_offerslider_shortcode($atts, $content = "") {
        $defaults = array(
		'column_size'=>'1/1',
		'cs_offerslider_section_title' => '',
		'cs_offerslider_class' => '',
		'cs_offerslider_animation' => ''
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $CustomId    = '';
        if ( isset( $cs_offerslider_class ) && $cs_offerslider_class ) {
            $CustomId    = 'id="'.$cs_offerslider_class.'"';
        }
        
        if ( trim($cs_offerslider_animation) !='' ) {
            $cs_offerslider_animation    = 'wow'.' '.$cs_offerslider_animation;
        } else {
            $cs_offerslider_animation    = '';
        }

        $html = '';
        $section_title    = '';
        if ($cs_offerslider_section_title && trim($cs_offerslider_section_title) !='' ) {
            $section_title    = '<div class="cs-section-title"><h2 class="'.$cs_offerslider_animation.'">'.$cs_offerslider_section_title.'</h2></div>';
        }
        $randomid = cs_generate_random_string('10');
        cs_owl_carousel();
        ?>
        <script>
        jQuery(document).ready(function($) {
            jQuery('#postslider<?php echo esc_js($randomid);?>').owlCarousel({
                    loop:true,
                    nav:false,
                    autoplay:true,
                    margin: 15,
                    navText: [
                           "",
                           ""
                          ],
                    responsive:{
                        0:{
                            items:1
                        },
                        600:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
            });
        });
        </script>
        <?php
        $html    .= '<div '.$CustomId.' class="col-md-12">';
        $html    .= $section_title;
        $html    .= '<div class="row">';
        $html    .= '<div id="postslider'.$randomid.'" class="owl-carousel has_bgicon cs-offers-slider">';
        $html    .= do_shortcode( $content );
        $html    .= '</div>';
        $html    .= '</div>';
        $html    .= '</div>';
        
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_OFFERSLIDER, 'cs_offerslider_shortcode');

    }
}

//======================================================================
// Offer Slider item
//======================================================================
if (!function_exists('cs_offerslider_item')) {
    function cs_offerslider_item( $atts, $content = null ) {
        $defaults = array( 'cs_slider_image' => '','cs_slider_title' => '','cs_slider_contents' => '','cs_readmore_link' => '','cs_offerslider_link_title' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $html      = '';

        $html    .='<div class="item">';
        
        if ( $cs_slider_image ) {
            $html    .='<div class="col-md-7">';
            $html    .='<figure>';
            if ( $cs_readmore_link ) {
                $html    .='<a href="'.$cs_readmore_link.'">';
            }
            $html    .='<img  src="'.$cs_slider_image.'" alt="img">';
            if ( $cs_readmore_link ) {
                $html    .='</a>';
            }
            $html    .='</figure>';
            $html    .='</div>';
        }
        
        $html    .='<div class="col-md-5">';
        $html    .='<div class="cs-contact-info no_border">';
        if ( $cs_slider_title ) {
            $html    .='<h1>'.$cs_slider_title.'</h1>';
        }
        if ( $content ) {
            $html    .='<p>'.do_shortcode( $content ).'</p>';
        }
        if ( $cs_readmore_link ) {
            $link_title    = $cs_offerslider_link_title ? $cs_offerslider_link_title : 'Get Directions';
            $html    .='<a href="'.$cs_readmore_link.'"><button class="custom-btn cs-bg-color">'.$link_title.'</button</a>';
        }
        $html    .='</div>';
        $html    .='</div>';
        $html    .='</div>';
        
        return $html;
        
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add( CS_SC_OFFERITEM, 'cs_offerslider_item' );

    }
}