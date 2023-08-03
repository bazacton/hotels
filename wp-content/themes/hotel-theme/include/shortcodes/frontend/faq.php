<?php
/*
 *
 *@Shortcode Name : FAQ
 *@retrun
 *
 */

if (!function_exists('cs_faq_shortcode')) {
    function cs_faq_shortcode($atts, $content = "") {
        global $acc_counter,$cs_faq_view_title,$cs_faq_view;
        $acc_counter = rand(40, 9999999);
        $html    = '';
        $defaults = array(
		'column_size'=>'1/1', 
		'class' => 'cs-faq',
		'faq_class' => '',
		'cs_faq_section_title'=>'',
		'cs_faq_view'=>'simple'
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        $CustomId = '';
        if ( isset( $faq_class ) && $faq_class ) {
            $CustomId    = 'id="'.$faq_class.'"';
        }
        $section_title = '';
        if(isset($cs_faq_section_title) && trim($cs_faq_section_title) <> ''){
            $section_title = '<div class="cs-section-title"><h2>'.$cs_faq_section_title.'</h2></div>';
        }
		if(isset($cs_faq_view) && $cs_faq_view == 'simple'){
            $faq_view = 'simple';
        }else {	
			$faq_view = 'modern';
		}
        $html .= '<div '.$CustomId.' class="'.$column_class.'">';
		$html .= $section_title;
        $html .= '<div class="panel-group cs-default  '.$cs_faq_view.' '.$faq_class.' " id="faq-' . $acc_counter . '">'.do_shortcode($content).'</div>';
        $html .= '</div>';
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_FAQ, 'cs_faq_shortcode');

    }
}
/*
 *
 *@FAQ Item
 *@retrun
 *
 */
if (!function_exists('cs_faq_item_shortcode')) {
    function cs_faq_item_shortcode($atts, $content = "") {
        global $acc_counter,$faq_animation,$cs_faq_view_title,$cs_faq_view;
        $defaults = array( 'faq_title' => 'Title','faq_active' => 'yes','cs_faq_icon' => '', 'cs_faq_view'=>'view-1');
        extract( shortcode_atts( $defaults, $atts ) );
        $faq_count = 0;
        $faq_count = rand(40, 9999999);
        $html = "";
        $active_in = '';
        $active_class = '';
        $styleColapse = '';
        $styleColapse    = 'collapse collapsed';
        if(isset($faq_active) && $faq_active == 'yes'){
            $styleColapse    = '';
            $active_in = 'in';
        } else {
            $active_class = 'collapsed';
        }
        $cs_faq_icon_class = '';
        if(isset($cs_faq_icon)){
            $cs_faq_icon_class = '<i class="'.$cs_faq_icon.'"></i>';
        }		
		if(isset($cs_faq_view) && $cs_faq_view == 'simple'){
            $faq_view = 'simple';
				  
        }else {	
			$faq_view = 'modern';
		}
        $html = '<div class="panel panel-default">
					<div class="panel-heading">					
					<a class="'.$active_class.'" href="#faq-'.$faq_count.'" data-parent="#faq-'.$acc_counter.'"  data-toggle="collapse">' . $cs_faq_icon_class . esc_attr($faq_title) . '</a>				
					</div>
				  <div id="faq-'.$faq_count.'" class="panel-collapse collapse '.$active_in.' ">
					<div class="panel-body">'.do_shortcode( $content ) .'</div>
				</div>
                  </div>';
         return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_FAQITEM, 'cs_faq_item_shortcode');

    }
}
?>