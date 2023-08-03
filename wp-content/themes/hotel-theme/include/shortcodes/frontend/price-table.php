<?php
/*
 *
 *@Shortcode Name : Price Table
 *@retrun
 *
 */

if (!function_exists('cs_pricetable_shortcode')) {
    function cs_pricetable_shortcode($atts, $content = "") {
        global $pricetable_style;
		
        $defaults = array(
		'column_size'=>'1/1',
		'pricetable_style'=>'',
		'pricetable_title'=>'',
		'pricetable_title_bgcolor'=>'',
		'pricetable_price'=>'',
		'currency_symbols'=>'$',
		'pricetable_period'=>'',
		'pricetable_bgcolor'=>'',
		'btn_text'=>'',
		'btn_link'=>'',
		'btn_bg_color'=>'',
		'pricetable_featured'=>'',
		'pricetable_class'=>''
		
		);
        extract( shortcode_atts( $defaults, $atts ) );
        $column_class  = cs_custom_column_class($column_size);
        $CustomId    = '';
        if ( isset( $pricetable_class ) && $pricetable_class ) {
            $CustomId  = 'id="'.$pricetable_class.'"';
        }
       
        $pricetableViewClass = '';
         if(isset($pricetable_style) && $pricetable_style == 'classic'){
            $pricetableViewClass = 'pr-classic';
            $title_color = '#efaa15';
        } else if(isset($pricetable_style) && $pricetable_style == 'simple'){
            $pricetableViewClass = 'pr-simple';
            $title_color = '#efaa15';
        } else if(isset($pricetable_style) && $pricetable_style == 'modren'){
            $pricetableViewClass = 'pr-modren';
            $title_color = '#ffffff';
        } else {
            
        }
        $html = '';
        $bgcolor_style = '';
        if(isset($btn_bg_color) && trim($btn_bg_color) <> ''){
            $btn_bg_color = ' style="background-color:'.$btn_bg_color.' !important;" ';
        }
        if(isset($pricetable_bgcolor) && trim($pricetable_bgcolor) <> ''){
            $bgcolor_style = ' style="background-color:'.$pricetable_bgcolor.' !important"';
        }
        if(isset($pricetable_featured) && $pricetable_featured == 'Yes'){
            $featured = 'featured';
        } else {
            $featured = '';
        }
        $html .= '<article class="cs-price-table '.$pricetableViewClass.' '.$pricetable_class.' '.$featured.'">';
        if(isset($pricetable_title) && $pricetable_title !=''){
            $html .= '<h3 style="color:'.$title_color.' !important; background-color:'.$pricetable_title_bgcolor.' !important;">'.$pricetable_title .'</h3>';
        }
        $btn_text = $btn_text ? $btn_text : 'Buy Now';
        $html .= '<div class="cs-price " '.$bgcolor_style.'><div class="inner-sec">';
         
		 if(isset($currency_symbols) && $currency_symbols !=''){
            $html .= '<span>'.$currency_symbols.'</span>';
         }
		
        if(isset($pricetable_price) && $pricetable_price !=''){
            $html .= $pricetable_price;
        }
        if(isset($pricetable_period) && $pricetable_period !=''){
            $html .= '<p>'.$pricetable_period.'</p>';
        }
        $html .= '</div></div>';
        $html .= '<div class="features"><ul>';
        $html .= do_shortcode($content);
        $html .= '</ul></div>';
        $html .= ' <a class="sigun_up" href="'.esc_url($btn_link).'" '.$btn_bg_color.'>'.$btn_text.'</a>';
        $html .= '</article>';
        return '<div '.$CustomId.' class="'.$column_class.'">'.$html.'</div>';
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_PRICETABLE, 'cs_pricetable_shortcode');
    }
}

/*
 *
 *@Price Table Item
 *@retrun
 *
 */
if (!function_exists('cs_pricing_item')) {
    function cs_pricing_item($atts, $content = "") {
        global $pricetable_style;
        $defaults = array('pricing_feature' => '');
        extract( shortcode_atts( $defaults, $atts ) );
        $html = '';
        $priceCheck = '';
        if ( $pricetable_style =='classic' || $pricetable_style =='clean' ) {
            $priceCheck    = '';
        }
        
        if ( isset( $pricing_feature ) && $pricing_feature !='' ){
            $html .= '<li>'.$pricing_feature.'</li>';
        }
        
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_PRICETABLEITEM, 'cs_pricing_item');
    }
}