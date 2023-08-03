<?php
/*
 *
 *@Shortcode Name : Services
 *@retrun
 *
 */

if (!function_exists('cs_services_shortcode')) {
    function cs_services_shortcode( $atts, $content = null ) {
        global $service_type, $cs_service_content_color;
        
        $defaults = array( 
		'column_size'=>'1/2',
		'cs_service_icon_type' => '',
		'cs_service_border_right' => '',
		'cs_service_icon' => '',
		'cs_service_icon_color' => '',
		'cs_service_bg_image' => '',
		'cs_service_bg_color' => '',
		'service_icon_size' => '',
		'cs_service_postion_modern' => '',
		'cs_service_title'=>'',
		'cs_service_title_color'=>'',
		'cs_service_content_color'=>'',
		'cs_service_btn_text_color'=>'',
		'cs_service_content' => '',
		'cs_service_link_text' => '',
		'cs_service_link_color'=>'',
		'cs_service_url' => '', 
		'cs_service_class'=>''
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        
        $html              = '';
        $bgColor        = '';
        $bgColorClass    = '';
        $align            = '';
        $linkColor         = '';
        $LinkIcon         = '';
        
        $CustomId    = '';
        if ( isset( $cs_service_class ) && $cs_service_class ) {
            $CustomId    = 'id="'.$cs_service_class.'"';
        }
        
        
        if ( isset( $cs_service_link_text ) && $cs_service_link_text !='' ) {
            $more        = $cs_service_link_text;
        } else{
            $more    = 'Read More';
        }
        
        if ( isset( $cs_service_icon_color ) && $cs_service_icon_color !='' ) {
            $iconColor	= 'style="color:'.$cs_service_icon_color.' !important;"';
        } else{
            $iconColor  = '';
        }
        $align		= $cs_service_postion_modern;
        $LinkIcon   = '<i class="icon-angle-right"></i>';
        if ( isset( $cs_service_link_color ) && $cs_service_link_color !='' ) {
        	$linkColor = 'style="color: '.$cs_service_link_color.' !important;"';
        } else{
        	$linkColor = '';
        }
		
		$cs_service_border_class = $cs_service_border_right == 'yes' ? ' no-right-border' : '';
		$cs_service_title_color = $cs_service_title_color <> '' ? ' style="color:'.$cs_service_title_color.' !important;"' : '';
		$cs_service_content_color = $cs_service_content_color <> '' ? ' style="color:'.$cs_service_content_color.' !important;"' : '';
		$cs_service_btn_text_color = $cs_service_btn_text_color <> '' ? ' color:'.$cs_service_btn_text_color.' !important;' : '';
		
        $html    .= '<div class="col-md-12 " '.$CustomId.'>';
		$html    .= '<article class="cs-services '.$align.' '.$bgColorClass.'"  '.$bgColor.'>';
        if ( isset ( $cs_service_icon ) && $cs_service_icon !='' && $cs_service_icon_type == 'icon' ) {
            $html    .= '<figure><i class="'.$cs_service_icon .' '.$service_icon_size.'" '.$iconColor.'></i></figure>';
        }else if ( isset ( $cs_service_bg_image ) && $cs_service_bg_image !='' && $cs_service_icon_type == 'image' ) {
            $html    .= '<figure><img alt="img" src="'.$cs_service_bg_image.'"></figure>';
        }
        $html    .= '<div class="text">';
        
        if ( isset ( $cs_service_title ) && $cs_service_title !='' ) {
            $html    .= '<h4'.$cs_service_title_color.'>'.$cs_service_title.'</h4>';
        }
        if ( isset ( $content ) && $content != '' ) {
          
			$html .= '<div '.$cs_service_content_color.'>'.do_shortcode($content).'</div>';
        }
        
        if ( isset ( $cs_service_url ) && $cs_service_url !='' ) {
                $html.= '<a class="read-more" href="'.esc_url($cs_service_url).'">'.$more.' '.$LinkIcon.'</a>';
        }
        
        $html    .= '</div>';
        $html    .= '</article>';
        $html    .= '</div>';
	
        
        return $html;
        
        
        
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add( CS_SC_SERVICES, 'cs_services_shortcode' );
    }
}

/*
 *
 *@Services Contents
 *@retrun
 *
 */
if (!function_exists('cs_service_content')) {
    function cs_service_contentt( $atts, $content = null ) {
		
		global $cs_service_content_color;
        $defaults = array( 'content' => '' );
        extract( shortcode_atts( $defaults, $atts ) );
        return '<p' . $cs_service_content_color . '>'. $content .'</p>';
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add( 'content', 'cs_service_content' );
    }
}
?>