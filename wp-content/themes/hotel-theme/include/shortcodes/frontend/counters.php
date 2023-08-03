<?php
/*
 *
 *@Shortcode Name : Counters
 *@retrun
 *
 */

if (!function_exists('cs_counter_item_shortcode')) {
    function cs_counter_item_shortcode($atts, $content = null) {
        global $counter_style;
        extract(shortcode_atts(array(  
            'column_size' => '1/1',
            'counter_style' => '',
            'counter_icon_type' => '',
            'cs_counter_logo' => '',
            'counter_icon'=>'',
            'counter_icon_align'=>'',
            'counter_icon_size'=>'',
            'counter_icon_color' => '#21cdec',
            'counter_numbers' => '',
            'counter_number_color' => '#333333',
            'counter_title' => '',
            'counter_text_color' => '#818181',
			'counter_border_color' => '#ffffff',
            'counter_border' => '',
            'counter_class' => '',
           
         ), $atts));
         
         $column_class  = cs_custom_column_class($column_size);
         
         $CustomId    = '';
         if ( isset( $counter_class ) && $counter_class ) {
            $CustomId    = 'id="'.$counter_class.'"';
         }
         
         
            $rand_id = rand(98,56666);
            $output = '';
            $counter_style_class = '';
            $pattren_bg          = '';
            $has_border     = '';
            $output = '';
            $border_class =  '';
            
            cs_count_numbers_script();
            
            $output .= '
                <script>
                    jQuery(document).ready(function($){
                        jQuery(".custom-counter-'.esc_js($rand_id).'").counterUp({
                            delay: 10,
                            time: 1000
                        });
                    });    
                </script>';
            
            $combine_counter_icon = '';    
            
            $counter_numbers = is_numeric($counter_numbers) ? number_format($counter_numbers) : $counter_numbers;
            
                if($counter_icon_type == 'icon' && $counter_icon <> ''){
                    $combine_counter_icon = '<i class="'.$counter_icon.' '.$counter_icon_size.'" style=" color: '.$counter_icon_color.'; "></i>';
                }
                else if($counter_icon_type == 'image' && $cs_counter_logo <> ''){
                    $combine_counter_icon = '<img src="'.$cs_counter_logo.'" alt="img">';
                }
              	$counter_style_class = 'cs-counter count-boxy '.$counter_icon_align;

				$output .= '<figure>';
				$output .= $combine_counter_icon;
 				$output .= '</figure>';
				$output .='<div class="cs-text">';
				if($counter_numbers <> ''){
					$output .= '<a class="cs-numcount custom-counter-'.$rand_id.'" style=" color: '.$counter_number_color.' !important;">'.$counter_numbers.'</a>';
				}
				if($counter_title <> ''){
					$output .= '<h4 style="color:'.$counter_text_color.' !important;">'.$counter_title.'</H4>';
				}					
				$output .='</div>'; 
	            $html = '<div '.$CustomId.' class="'.$column_class.' ">
				<article style="border:7px solid '.$counter_border_color.' " class="'.$counter_style_class.' '.$counter_class.''.$border_class.'">'.$output.'</article>
			</div>';
        	return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add( CS_SC_COUNTERS, 'cs_counter_item_shortcode' );
    }
}