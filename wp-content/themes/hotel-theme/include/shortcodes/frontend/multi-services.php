<?php
/*
 *
 * @Shortcode Name : Multiple Service
 * @retrun
 *
 */

if (!function_exists('cs_multiple_services_shortcode')) {

    function cs_multiple_services_shortcode($atts, $content = "") {
        $defaults = array(
            'column_size' => '1/1',
            'cs_multiple_service_section_title' => '',
            'multiple_services_element_size' => '',
            'cs_multiple_services_view' => ''
        );

        global $cs_multiple_services_view, $multiple_services_element_size, $slider_counter;
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $cs_section_title = '';
        if (isset($cs_multiple_service_section_title) && trim($cs_multiple_service_section_title) <> '' && $cs_multiple_services_view != 'service-default-three') {
            $cs_section_title = '<div class="cs-section-title"><h2 style="margin-bottom:10px;">' . $cs_multiple_service_section_title . '</h2></div>';
        }

        $html = '';
        if ($column_class <> '') {
            // $html.= '<div class="' . $column_class . '">';
        }

        $html.= $cs_section_title;

        // if slider view selected then if triger

        if ($cs_multiple_services_view == 'service-slider') {

            $randomid = rand(0, 999);

            cs_enqueue_flexslider_script();
            ?>
            <script type='text/javascript'>
                jQuery(document).ready(function () {
                    cs_services(<?php echo absint($randomid) ?>);
                });
            </script>
            <?php
            $html.= '<div id="cs-flexslider-' . absint($randomid) . '" class="flexslider2 cs-services-slider prev-next ' . sanitize_html_class($cs_multiple_services_view) . '">';
            $html.= '<ul class="slides">';
            $html.= do_shortcode($content);
            $html.= '</ul>';
            $html.= '</div>';
        } else {
            $html.= '<div class="cs-services ' . sanitize_html_class($cs_multiple_services_view) . '">';
            $html.= do_shortcode($content);
            $html.= '</div>';
        }
        if ($column_class <> '') {
            //$html.= '</div>';
        }
        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_MULTPLESERVICES, 'cs_multiple_services_shortcode');
    }
}

/*
 *
 * @Multiple Service Item
 * @retrun
 *
 */

if (!function_exists('cs_multiple_services_item_shortcode')) {

    function cs_multiple_services_item_shortcode($atts, $content = "") {
        $defaults = array(
            'cs_title_color' => '',
            'cs_text_color' => '',
            'cs_bg_color' => '',
            'cs_website_url' => '',
            'cs_multiple_service_title' => '',
            'cs_multiple_service_logo' => '',
            'cs_multiple_service_btn' => '',
            'cs_multiple_service_btn_link' => '',
            'cs_multiple_service_btn_bg_color' => '',
            'cs_multiple_service_btn_txt_color' => ''
        );
        global $cs_multiple_services_view, $multiple_services_element_size, $slider_counter;
        extract(shortcode_atts($defaults, $atts));
        $html = '';
        $cs_title_color = $cs_title_color <> '' ? ' style="color:' . $cs_title_color . ' !important;"' : '';
        $cs_text_color = $cs_text_color <> '' ? ' style="color:' . $cs_text_color . ' !important;"' : '';
        $cs_bg_color = $cs_bg_color <> '' ? ' style="background-color:' . $cs_bg_color . ' !important;"' : '';
        $cs_multiple_service_btn_txt_color = $cs_multiple_service_btn_txt_color <> '' ? ' color:' . $cs_multiple_service_btn_txt_color . ' !important;' : '';
        $cs_multiple_service_btn_bg_color = $cs_multiple_service_btn_bg_color <> '' ? ' background-color:' . $cs_multiple_service_btn_bg_color . ' !important;' : '';
        if ($multiple_services_element_size == '100') {

            $element_size = 25;
            if ($cs_multiple_services_view == 'service-modren') {
                $element_size = 33;
            }
        } else {
            $element_size = 25;
            if ($cs_multiple_services_view == 'service-modren') {
                $element_size = 33;
            }
        }
        if ($cs_multiple_services_view == 'service-square') {
            $html.= '<div class="element-size-' . $element_size . '"><div class="col-md-12"><article class="cs-services modren top-center">';
            if ($cs_multiple_service_logo <> '') {
                $html.= '<figure><a href="' . esc_url($cs_website_url) . '"><img src="' . $cs_multiple_service_logo . '"  alt="image" ></a>';
                $html.= '<figcaption>
                            <span><img src="' . get_template_directory_uri() . '/assets/images/hover-img.png"  alt="image"></span>
                          </figcaption>
						</figure>';
            }
            $html .= '<div class="text">';
            if ($cs_multiple_service_title <> '') {
                $html.= '<h5 style="margin-top:5px"><a' . $cs_title_color . ' href="' . esc_url($cs_website_url) . '">' . $cs_multiple_service_title . '</a></h5>';
            }
            $html.= '<p' . $cs_text_color . '>' . do_shortcode($content) . '</p>
				<div class="box_spreater">
					<div class="fullwidth-sepratore" style="text-align: center;"><div class="dividerstyle"><i class=" icon-arrow-right9"></i></div>
				</div>
			</div>';
            if ($cs_multiple_service_btn <> '') {
                $html.= '<a style="' . $cs_multiple_service_btn_txt_color . $cs_multiple_service_btn_bg_color . '" href="' . esc_url($cs_multiple_service_btn_link) . '" class="service-btn">' . $cs_multiple_service_btn . '</a>';
            }
            $html .= '</div></article></div></div>';
        } else if ($cs_multiple_services_view == 'service-modren') {
            $html.= '<div class="element-size-' . $element_size . '"><div class="col-md-12"><article class="cs-services cs-fancy2 modren top-center">';
            if ($cs_multiple_service_logo <> '') {
                $html.= '<figure><a href="' . esc_url($cs_website_url) . '"><img src="' . $cs_multiple_service_logo . '"  alt="image" ></a>';
                $html.= '<figcaption>
                            <span><a href="' . esc_url($cs_website_url) . '"><img src="' . get_template_directory_uri() . '/assets/images/hover-img.png"  alt="image "></a></span>
                          </figcaption>
						</figure>';
            }
            $html.= '<div class="text">';
            if ($cs_multiple_service_title <> '') {
                $html.= '<h3 style="margin-top:5px"><a' . $cs_title_color . ' href="' . esc_url($cs_website_url) . '">' . $cs_multiple_service_title . '</a></h3>';
            }
            $cs_read_more = '';
            if ($cs_website_url != '') {
                $cs_read_more = '<a href="' . esc_url($cs_website_url) . '"> [' . __('Read more', 'luxury-hotel') . ']</a>';
            }
            $html.= '<p' . $cs_text_color . '>' . do_shortcode($content) . $cs_read_more . '</p>
				<div class="box_spreater">
					<div class="fullwidth-sepratore" style="text-align: center;"><div class="dividerstyle"><i class=" icon-arrow-right9"></i></div>
				</div>
			</div>';
            if ($cs_multiple_service_btn <> '') {
                $html.= '<a style="' . $cs_multiple_service_btn_txt_color . $cs_multiple_service_btn_bg_color . '" href="' . esc_url($cs_multiple_service_btn_link) . '" class="service-btn">' . $cs_multiple_service_btn . '</a>';
            }
            $html .= '</div></article></div></div>';
        } else {
            $html .= '<div class="element-size-' . $element_size . '"><div class="col-md-12"><article class="cs-services top-center">';
            if ($cs_multiple_service_logo <> '') {
                $html.= '<figure><a href="' . esc_url($cs_website_url) . '"><img src="' . $cs_multiple_service_logo . '" alt="' . $cs_multiple_service_title . '"></a>
				<figcaption><span><a href="' . esc_url($cs_website_url) . '"><img alt="img" src="' . get_template_directory_uri() . '/assets/images/hover-img.png"></a></span></figcaption></figure>';
            }
            $html.= '<div class="text">';
            if ($cs_multiple_service_title <> '') {
                $html.= '<h2><a' . $cs_title_color . ' href="' . esc_url($cs_website_url) . '">' . $cs_multiple_service_title . '</a></h2>';
            }
            $html.= '<p' . $cs_text_color . '>' . do_shortcode($content) . '</p>
					';
            if ($cs_multiple_service_btn <> '') {
                $html.= '<a style="' . $cs_multiple_service_btn_txt_color . $cs_multiple_service_btn_bg_color . '" href="' . esc_url($cs_multiple_service_btn_link) . '" class="service-btn">' . $cs_multiple_service_btn . '</a>';
            }

            $html.= '</div></article></div></div>';
        }

        return $html;
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_MULTPLESERVICESITEM, 'cs_multiple_services_item_shortcode');
    }
}
?>