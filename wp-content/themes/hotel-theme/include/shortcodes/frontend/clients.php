<?php

/*
 *
 * @Shortcode Name : Clients
 * @retrun
 *
 */

if (!function_exists('cs_clients_shortcode')) {

    function cs_clients_shortcode($atts, $content = "") {
        global $cs_clients_view, $cs_client_border, $cs_client_gray;
        $defaults = array(
            'column_size' => '',
            'cs_clients_view' => '',
            'cs_client_gray' => '',
            'cs_client_border' => '',
            'cs_client_head_style' => 'heading-style-1',
            'cs_client_section_title' => '',
            'cs_client_class' => ''
        );
        extract(shortcode_atts($defaults, $atts));

        $CustomId = '';
        if (isset($cs_client_class) && $cs_client_class) {
            $CustomId = 'id="' . $cs_client_class . '"';
        }

        $column_class = cs_custom_column_class($column_size);
        $cs_client_border = $cs_client_border == 'yes' ? 'has_border' : 'no-clients-border';
        $owlcount = rand(40, 9999999);
        $section_title = isset($cs_client_section_title) ? $cs_client_section_title : '';



        $html = '';
        $html .= '<div ' . $CustomId . ' class="' . $column_class . ' ' . $cs_client_class . '">';

        
            ?>

            <?php

            $html .= '<div class="cs-partner ' . $cs_client_border . ' ' . $cs_client_head_style .'">';
            $html .= '<div class="cs-section-title">';
            $html .= '<h2>' . $cs_client_section_title . '</h2>';
            $html .= '</div>';
            $html .= '<ul class="row">';
            $html .= do_shortcode($content);
            $html .= '</ul>';
            $html .= '</div>';
      
        $html .= '</div>';
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_CLIENTS, 'cs_clients_shortcode');
    }
}

/*
 *
 * @Clinets Item
 * @retrun
 *
 */
if (!function_exists('cs_clients_item_shortcode')) {

    function cs_clients_item_shortcode($atts, $content = "") {
        global $cs_clients_view, $cs_client_border, $cs_client_gray;
        $defaults = array('cs_bg_color' => '', 'cs_website_url' => '', 'cs_client_title' => '', 'cs_client_logo' => '');
        extract(shortcode_atts($defaults, $atts));
        $html = '';
        $grayScale = (isset($cs_client_gray) && $cs_client_gray == 'yes') ? 'grayscale' : '';
        $tooltip = '';
        if (isset($cs_client_title) && $cs_client_title != '') {
            $tooltip = 'title="' . $cs_client_title . '"';
        }
        $cs_url = $cs_website_url ? $cs_website_url : 'javascript:;';
        if ($cs_clients_view == 'grid') {
            if (isset($cs_client_logo) && !empty($cs_client_logo)) {

                $html .= '<li class="col-md-2"  style="background-color:' . $cs_bg_color . '"><figure><a ' . $tooltip . ' href="' . esc_url($cs_url) . '">
				<img ' . $tooltip . ' class="' . sanitize_html_class($grayScale) . '" src="' . esc_url($cs_client_logo) . '" alt="img" ></a></figure></li>';
            }
        } else {
            if (isset($cs_client_logo) && !empty($cs_client_logo)) {

                $html .= '<li class="col-md-2" style="background-color:' . $cs_bg_color . '"><figure><a href="' . esc_url($cs_url) . '"><img ' . $tooltip . ' alt="image" src="' . esc_url($cs_client_logo) . '" class="' . sanitize_html_class($grayScale) . '"></a></figure></li>';
            }
        }
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_CLIENTSITEM, 'cs_clients_item_shortcode');
    }
}