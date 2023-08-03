<?php

/*
 *
 * @Shortcode Name : Facilities
 * @retrun
 *
 */

if (!function_exists('cs_facilities_shortcode')) {

    function cs_facilities_shortcode($atts, $content = "") {
        $defaults = array(
            'column_size' => '1/1',
            'cs_section_title' => '',
        );

        global $facilities_element_size, $item_counter;
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $item_counter = 1;
        $cs_elm_title = '';

        if (isset($cs_section_title) && trim($cs_section_title) <> '') {
            $cs_elm_title = '<div class="cs-section-title"><h2>' . $cs_section_title . '</h2></div>';
        }

        $html = '';

        $randomid = rand(0, 999);
        cs_enqueue_flexslider_script();

        $html .= '<div class="col-md-12">';
        $html .= $cs_elm_title;
        $html .= '<div id="cs-flexslider-' . absint($randomid) . '" class="flexslider2 cs-services-slider prev-next">';
        $html .= '<ul class="slides">';
        $html .= do_shortcode($content);
        $html .= '</ul>';
        $html .= '<script type="text/javascript">
						  jQuery(window).load(function(){
							jQuery("#cs-flexslider-' . $randomid . '").flexslider({
							  animation: "slide",
							  prevText:"<em class=\'icon-arrow-left9\'></em>",
							  nextText:"<em class=\'icon-arrow-right9\'></em>",
							  start: function(slider){
								jQuery(\'body\').removeClass(\'loading\');
							  }
							});
						  });
					</script>';
        $html.= '</div>';
        $html.= '</div>';
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_FACILITIES, 'cs_facilities_shortcode');

    }
}

/*
 *
 * @Multiple Service Item
 * @retrun
 *
 */

if (!function_exists('cs_facilities_item_shortcode')) {

    function cs_facilities_item_shortcode($atts, $content = "") {
        $defaults = array('title' => '', 'title_color' => '', 'text_color' => '', 'image' => '', 'facilities_text' => '');
        global $item_counter;

        extract(shortcode_atts($defaults, $atts));
        $html = '';

        $title_color = $title_color <> '' ? ' style="color:' . $title_color . ' !important;"' : '';
        $text_color = $text_color <> '' ? ' style="color:' . $text_color . ' !important;"' : '';

        $start_list = '';
        $end_list = '';
        if ($item_counter == 1) {
            $html .= '<li>';
        }
        if ($item_counter % 3 == 1) {
            $html .= '</li>';
            $html .= '<li>';
        }

        $html .= '<article class="cs-services classic left">';
        if ($image <> '') {
            $html .= '<figure><img src="' . $image . '" alt="' . $title . '"></figure>';
        }
        $html .= '<div class="text">';

        if ($title <> '') {
            $html .= '<h5><a' . $title_color . '>' . $title . '</a></h5>';
        }

        if ($facilities_text <> '') {
            $html .= '<p ' . $text_color . '>' . do_shortcode($facilities_text) . '</p>';
        }

        $html .= '</div>';
        $html .= '</article>';
        $item_counter++;

        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_FACILITIESITEM, 'cs_facilities_item_shortcode');

    }
}
?>