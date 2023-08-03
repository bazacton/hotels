<?php
   /*
    *
    * @Shortcode Name : Testimonial
    * @retrun
    *
    */
   
   if (!function_exists('cs_testimonials_shortcode')) {
   
       function cs_testimonials_shortcode($atts, $content = null) {
           global $testimonial_style, $cs_testimonial_class, $column_class, $testimonial_text_color, $section_title;
           $randomid = rand(0, 999);
           $defaults = array('column_size' =>'1/1','testimonial_style' =>'','testimonial_text_color' =>'','cs_testimonial_text_align' => '','cs_testimonial_section_title' =>'',		    'cs_testimonial_class' =>'');
           extract(shortcode_atts($defaults, $atts));
           $column_class = cs_custom_column_class($column_size);
           $html = '';
           $section_title = '';
          
           if ($cs_testimonial_section_title && trim($cs_testimonial_section_title) != '') {
               $section_title = '<div class="cs-section-title">
   									<h2>' . $cs_testimonial_section_title . '</h2>
   								 </div>';
			}
			if (isset($testimonial_style) && $testimonial_style == 'modren-slider') {
				cs_enqueue_flexslider_script();
				$testim_clas = ' modren-style';
				$html .=  $section_title . '
				<script type="text/javascript">
				   jQuery(document).ready(function () {
					   cs_testimonial_shortcode('.absint($randomid).');
				   }
				   );
				</script>
				<div id="cs-testimonial-' . absint($randomid) . '" class="flexslider2 cs-Testimonials italic testimonial-slider cs-modren prev-next ' . $cs_testimonial_text_align . '">
					<div class="flex-viewport">
						<ul class="slides">' . do_shortcode($content) . '</ul>
					</div>
				</div>';
			} else if (isset($testimonial_style) && $testimonial_style == 'slider') {
               cs_enqueue_flexslider_script();
            ?>
			<script type='text/javascript'>
               jQuery(document).ready(function () {
                   cs_testimonial_shortcode(<?php echo absint($randomid) ?>);
               }
               );
            </script>
		<?php
		$html .= $section_title . '<div id="cs-testimonial-' . absint($randomid) . '" class="flexslider2 cs-Testimonials italic testimonial-slider prev-next ' . $cs_testimonial_text_align . '">
		   <div class="flex-viewport">
			  <ul class="slides">
				 ' . do_shortcode($content) . ' 
			  </ul>
		   </div>
		</div>';
		} else if (isset($testimonial_style) && $testimonial_style == 'simple') {
			$testim_clas = ' cs-Testimonials v2';
			$html .= $section_title . '
			<div id="cs-testimonial-' . absint($randomid) . '" class="cs-Testimonials v2 ' . $cs_testimonial_text_align . $testim_clas . '">
			   ' . do_shortcode($content) . ' 
			</div>
			';
		} else {
			$testim_clas = ' italic-style';
			$html .= $section_title . '
			<div id="cs-testimonial-' . absint($randomid) . '" class="flexslider testimonial ' . $cs_testimonial_text_align . $testim_clas . '">
			   <ul class="slides">
				  ' . do_shortcode($content) . '
			   </ul>
			</div>
			';
		}
	return '
		<div class="' . $column_class . '">
		   ' . $html . '
		</div>
		';
}
       if(function_exists('cs_shortcode_add')){
           cs_shortcode_add(CS_SC_TESTIMONIALS, 'cs_testimonials_shortcode');

       }
}
/*
*
* @Shortcode Name : Testimonial Item
* @retrun
*
*/
if (!function_exists('cs_testimonial_item')) {
	function cs_testimonial_item($atts, $content = null) {
		global $testimonial_style, $cs_testimonial_class, $column_class, $testimonial_text_color;
		$defaults = array('testimonial_author' =>'', 'testimonial_img' =>'', 'cs_testimonial_text_align' =>'', 'testimonial_company' =>'');
		extract(shortcode_atts($defaults, $atts));
		$figure = '';
		$html	= '';
		if (isset($testimonial_img) && $testimonial_img <>'') {
			$testimonial_img_id = cs_get_attachment_id_from_url($testimonial_img);
			$width = 150;
			$height = 150;
			$testimonial_img_url = cs_attachment_image_src($testimonial_img_id, $width, $height);
			$figure = '';
			if ($testimonial_img_url <>'') {
				$figure = '<figure>
							   <img src="' . esc_url($testimonial_img_url) . '" alt="img" />
							</figure>';
				}
		}
		$tc_color = '';
		if (isset($testimonial_text_color) && $testimonial_text_color <>'') {
			$tc_color = 'style=color:' . $testimonial_text_color . '!important';
		}
		if (isset($testimonial_style) && $testimonial_style == 'modren-slider') {
			$html	 .= '<li>';
			   $html	 .= '<div class="question-mark col-md-12">';
				  $html	 .= '<p ' . $tc_color . '>' . do_shortcode($content) . ' </p>';
				  $html	 .= '<div class="cs-author">';
					 if( isset( $figure ) && $figure !='' ) {
						$html	 .= $figure;
					 }
					 
					 if( isset( $testimonial_author ) && $testimonial_author !='' ) {
					 	$html	 .= '<h6>	' . $testimonial_author . '</h6>';
					 }
					 
					 if( isset( $testimonial_company ) && $testimonial_company !='' ) {
						$html .= '<span>' . $testimonial_company . '</span>';
					 }
				  $html	 .= '</div>';
			   $html	 .= '</div>';
			$html	 .=' </li>';
		} else if (isset($testimonial_style) && $testimonial_style == 'slider') {
			$html	 .= '<li>';
			   $html	 .= '<div class="question-mark col-md-12">';
				  $html	 .= '<p ' . $tc_color . '>' . do_shortcode($content) . ' </p>';
				  $html	 .= '<div class="cs-author">';
					 if( isset( $figure ) && $figure !='' ) {
						$html	 .= $figure;
					 }
					 
					 if( isset( $testimonial_author ) && $testimonial_author !='' ) {
					 	$html	 .= '<h6>	' . $testimonial_author . '</h6>';
					 }
					 
					 if( isset( $testimonial_company ) && $testimonial_company !='' ) {
						$html .= '<span>' . $testimonial_company . '</span>';
					 }
				  $html	 .= '</div>';
			   $html	 .= '</div>';
			$html	 .=' </li>';
		} else if (isset($testimonial_style) && $testimonial_style == 'simple') {
			$html	 .= '<div class="question-mark col-md-12">';
			   $html	 .= '<p ' . $tc_color . '> ' . do_shortcode($content) . '</p>';
			   $html	 .= '<div class="cs-author">';
				  if( isset( $figure ) && $figure !='' ) {
						$html	 .= $figure;
				  }
				  
				  if( isset( $testimonial_author ) && $testimonial_author !='' ) {
				  	$html	 .= '<h6>' . $testimonial_author . '</h6>';
				  }
				  
				  if( isset( $testimonial_company ) && $testimonial_company !='' ) {
				  	$html .= '<span>' . $testimonial_company . '</span>';
				  }
			   $html	 .= '</div>';
			$html	 .= '</div>';
		} else {
			$html	 .= '<li>';
			   $html	 .= '<div class="question-mark">';
				  $html	 .= '<p ' . $tc_color . '>' . do_shortcode($content) . '</p>';
				  $html	 .= '<div class="ts-author">';
					if( isset( $figure ) && $figure !='' ) {
						$html	 .= $figure;
					}
					$html	 .= '<h4 class="cs-author">';
						$html	 .= $testimonial_author;
						if( isset( $testimonial_company ) && $testimonial_company !='' ) {
							$html	 .= '<br><span>';
							$html	 .=  $testimonial_company;
							$html	 .= '</span>';
						}
					$html	 .= '</h4>';
				  $html	 .= '</div>';
			  $html	 .= ' </div>';
			$html	 .= '</li>';
		}
		
		return $html;
	}
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_TESTIMONIALSITEM, 'cs_testimonial_item');

    }
}