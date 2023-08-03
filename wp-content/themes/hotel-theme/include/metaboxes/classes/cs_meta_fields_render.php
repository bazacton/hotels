<?php
	class cs_meta_fields_render{
	
	public function __construct(){
		add_action('save_post', array($this, 'save_page_option'));
	}
	
	/*----------------------------------------------------------------------
	 * @ Save Meta Box
	 *---------------------------------------------------------------------*/
	public function save_page_option($post_id='') {
		global $post;
		
		if(defined( 'DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		
		$data	= array();
		
		foreach($_POST as $key => $value) {
			if( strstr( $key, 'cs_' ) ) {
				$data[$key]	= $value;
				update_post_meta($post_id, $key, $value);
			}
		}
				
		update_post_meta($post_id, 'cs_full_data', $data);
			
	}
	
	/*----------------------------------------------------------------------
	 * @ render label
	 *---------------------------------------------------------------------*/
	public function cs_form_label( $name = 'Label Not defined' , $description ='') {
		global $post;
		
		$cs_output  = '<li class="to-label">';
			$cs_output .= '<label>'.$name;
			$cs_output .= $this->cs_form_description($description);
			$cs_output .= '</label>';
		$cs_output .= '</li>';
		
		return $cs_output;
	}
	
	/*----------------------------------------------------------------------
	 * @ render description
	 *---------------------------------------------------------------------*/
	public function cs_form_description( $description ='' ) {
		global $post;
		
		if( $description == '' ) {
			return;
		}
		
		$cs_output = '<span>'.$description.'</span>';
		
		return $cs_output;
	}
	
	/*----------------------------------------------------------------------
	 * @ render text field
	 *---------------------------------------------------------------------*/
	public function cs_form_text_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}
		
		$onblur	= '';
		if( $id == 'location_address' || $id == 'loc_city' || $id == 'loc_postcode' || $id == 'loc_region') {
			$onblur	= 'onBlur=gll_search_map()';	
		}
		
		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= '<input type="text" '.$onblur.' class="cs-form-text cs-input '.sanitize_html_class( $classes ).'" name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
				$cs_output .= '</div>';
			
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		echo force_balance_tags ( cs_data_validation($cs_output ));
	}
	
	/*----------------------------------------------------------------------
	 * @ render Radio field
	 *---------------------------------------------------------------------*/
	public function cs_form_radio_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}

		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= '<input type="radio" class="cs-form-text cs-input " name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
				$cs_output .= '</div>';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		echo force_balance_tags (cs_data_validation( $cs_output ));
	}
	
	/*----------------------------------------------------------------------
	 * @ render Radio field
	 *---------------------------------------------------------------------*/
	public function cs_form_layout_render( $params ='' ) {
 		global $post;
		extract($params);
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}
		
		$cs_left = $cs_right = $cs_none = $cs_left_checklist  = $cs_right_checklist  = $cs_none_checklist = '';
		if( isset( $value ) && $value =='left' ) {
			$cs_left = 'checked';
			$cs_left_checklist	= "class=check-list";
		} if( isset( $value ) && $value =='right' ) {
			$cs_right = 'checked';
			$cs_right_checklist	= "class=check-list";
		} else if( isset( $value ) && $value =='none' ) {
			$cs_none = 'checked';
			$cs_none_checklist	= "class=check-list";
		}
		
		$cs_output = '<ul class="form-elements">';
          $cs_output .= $this->cs_form_label($name,$description);
          $cs_output .= '<li class="to-field">';
            $cs_output .= '<div class="input-sec">';
              $cs_output .= '<div class="meta-input pattern">';
                $cs_output .= '<div class=\'radio-image-wrapper\'>';
                  $cs_output .= '<input '.$cs_none.' onclick="show_sidebar_page(\'none\')" type="radio" name="cs_'.sanitize_html_class( $id ).'" class="radio" value="none" id="page_radio_1" />';
                  $cs_output .= '<label for="page_radio_1">';
				 	  $cs_output .= '<span class="ss">';
				 	 	$cs_output .= '<img src="'.get_template_directory_uri().'/include/assets/images/no_sidebar.png"  alt="img" />';
					  $cs_output .= '</span>';
						$cs_output .= '<span '.$cs_none_checklist.' id="check-list"></span>';
					$cs_output .= '</label>';
                  $cs_output .= '<span class="title-theme">'.__("Full Width","luxury-hotel").'</span></div>';
                $cs_output .= '<div class=\'radio-image-wrapper\'>';
                  $cs_output .= '<input '.$cs_right.' onclick="show_sidebar_page(\'right\')" type="radio" name="cs_'.sanitize_html_class( $id ).'" class="radio" value="right" id="page_radio_2"  />';
                  $cs_output .= '<label for="page_radio_2">';
				     $cs_output .= '<span class="ss">';
				 	 	$cs_output .= '<img src="'.get_template_directory_uri().'/include/assets/images/sidebar_right.png" alt="img" />';
				  	 $cs_output .= '</span>';
						$cs_output .= '<span '.$cs_right_checklist.' id="check-list"></span>';
				  $cs_output .= '</label>';
                  $cs_output .= '<span class="title-theme">'.__("Sidebar Right","luxury-hotel").'</span> </div>';
                $cs_output .= '<div class=\'radio-image-wrapper\'>';
                  $cs_output .= '<input  '.$cs_left.' onclick="show_sidebar_page(\'left\')" type="radio" name="cs_'.sanitize_html_class( $id ).'" class="radio" value="left" id="page_radio_3" />';
                  $cs_output .= '<label for="page_radio_3">';
				  	$cs_output .= '<span class="ss">';
				  		$cs_output .= '<img src="'.get_template_directory_uri().'/include/assets/images/sidebar_left.png" alt="img" />';
					$cs_output .= '</span>';
					$cs_output .= '<span '.$cs_left_checklist.' id="check-list"></span>';
					$cs_output .= '</label>';
                  $cs_output .= '<span class="title-theme">'.__("Sidebar Left","luxury-hotel").'</span> </div>';
              $cs_output .= '</div>';
            $cs_output .= '</div>';
          $cs_output .= '</li>';
        $cs_output .= '</ul>';
		
		echo force_balance_tags (cs_data_validation( $cs_output ));
	}
	
	/*----------------------------------------------------------------------
	 * @ render text field
	 *---------------------------------------------------------------------*/
	public function cs_form_hidden_render( $params ='' ) {
 		global $post;
		extract($params);
		
		if( isset($type) && $type != '' ){
			$type	= '[]';
		}
		
		if ( isset($return) && $return == 'echo' ) {
			echo '<input type="hidden" class="cs-form-text cs-input " name="cs_' . sanitize_html_class( $id ).$type. '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($std). '" />';
		} else {
			return '<input type="hidden" class="cs-form-text cs-input " name="cs_' . sanitize_html_class( $id ).$type. '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($std). '" />';
		}
	}
	
	/*----------------------------------------------------------------------
	 * @ render Date field
	 *---------------------------------------------------------------------*/
	public function cs_form_date_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}

		$cs_output = '<ul class="form-elements">';
					$cs_output .= '<script>
									jQuery(function(){
										jQuery("#cs_'.$id.'").datetimepicker({
											format:"m/d/Y",
											timepicker:false
										});
									});
								  </script>';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= '<input type="text" class="cs-form-text cs-input " name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
				$cs_output .= '</div>';
			$cs_output .= '</li>';
		echo force_balance_tags (cs_data_validation( $cs_output ));
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Textarea field
	 *---------------------------------------------------------------------*/
	public function cs_form_textarea_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}

		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= ' <textarea  rows="5" cols="30" name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '">'.sanitize_text_field($value).'</textarea>';
				$cs_output .= '</div>';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		echo force_balance_tags (cs_data_validation( $cs_output ));
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render select field
	 *---------------------------------------------------------------------*/
	public function cs_form_select_render( $params = '' ) {
		global $post;
		extract($params);
		$cs_onchange	= '';
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}
		
		$cs_display = '';
		if( isset( $status ) && $status == 'hide' ) {
			$cs_display	= 'style=display:none';
		}
		
		$onblur	= '';
		if( $id == 'loc_country' ) {
			$onblur	= 'onBlur=gll_search_map()';	
		}
		
		if ( isset( $onclick ) && $onclick !='' ) {
			$cs_onchange	= 'onchange=javascript:'.$onclick.'(this.value)';
		}
		
		$cs_output = '<ul class="form-elements" id="wrapper_' . sanitize_html_class( $id ) . '" '.$cs_display.'>';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= '<div class="select-style">';
						$cs_output .= '<select id="cs_' . sanitize_html_class( $id ) . '" name="cs_' . sanitize_html_class( $id ) . '" '.$cs_onchange.' '.$onblur.'>';
						foreach($options as $key => $option) {
							
							$cs_output .= '<option '.selected($key ,$value,false). 'value="' . $key . '">' . $option . '</option>';
						}
						$cs_output .= '</select>';
					$cs_output .= '</div>';
				$cs_output .= '</div>';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		echo force_balance_tags (cs_data_validation( $cs_output ));
		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Multi Select field
	 *---------------------------------------------------------------------*/
	public function cs_form_multiselect_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}

		$cs_output = '<input type="text" class="cs-form-text cs-input " name="' . sanitize_html_class( $id ) . '" id="' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
		return $cs_output;
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Multi Select field
	 *---------------------------------------------------------------------*/
	public function cs_heading_render( $params ='' ) {
 		global $post;
		extract($params);

		$cs_output = '<div class="theme-help" id="'.sanitize_html_class( $id ).'">
						<h4 style="padding-bottom:0px;">'.esc_attr( $name ).'</h4>
						<div class="clear"></div>
					  </div>';
		echo cs_allow_special_char( $cs_output );
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Checkbox field
	 *---------------------------------------------------------------------*/
	public function cs_form_checkbox_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}
		
		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field has_input">';
				$cs_output .= '<label class="pbwp-checkbox">';
					$cs_output .= $this->cs_form_hidden_render( array( 'id'=> $id , 'std' => '','type'  => '','return'  => 'return' ) );
					$cs_output .= '<input type="checkbox" class="'.$classes.'" name="cs_' .sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ). '" value="'.sanitize_text_field('on'). '" '.checked('on',$value,false).' />';
				$cs_output .= '<span class="pbwp-box"></span>';
				$cs_output .= '</label>';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		
		echo force_balance_tags ( cs_data_validation($cs_output ) );
		
	}
	
	/*----------------------------------------------------------------------
	 * @ render File Upload field
	 *---------------------------------------------------------------------*/
	public function cs_form_fileupload_render( $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}
		
		if ( isset( $value ) && $value !='' ) {
			$display	= 'style=display:block';
		} else {
			$display	= 'style=display:none';
		}
		
		$cs_random_id = $this->cs_generate_random_string('5');
		
		$cs_output  = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				 $cs_output .= '<div class="page-wrap cs-option-image cs-image-uploader" '.$display.' id="cs_'.sanitize_html_class( $id.$cs_random_id ).'_box">';
					$cs_output .= '<div class="gal-active">';
					$cs_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
						$cs_output .= '<ul id="gal-sortable">';
							$cs_output .= '<li class="ui-state-default" id="">';
								$cs_output .= '<div class="thumb-secs"> <img src="'.esc_url($value).'"  id="cs_' .sanitize_html_class( $id.$cs_random_id ). '_img" width="100" height="150" alt="img" />';
								$cs_output .= '<div class="gal-edit-opts"><a   href="javascript:del_media(\'cs_' .sanitize_html_class( $id.$cs_random_id ). '\')" class="delete"></a> </div>';
								$cs_output .= '</div>';
							$cs_output .= '</li>';
						$cs_output .= '</ul>';
					$cs_output .= '</div>';
					$cs_output .= '</div>';
				$cs_output .= '</div>';
				$cs_output .= '<input id="cs_' .sanitize_html_class( $id.$cs_random_id ). '" name="cs_' .sanitize_html_class( $id ). '" type="hidden" class="" value="'.$value.'"/>';
				$cs_output .= '<label class="browse-icon"><input name="cs_' .sanitize_html_class( $id.$cs_random_id ). '"  type="button" class="uploadMedia left" value="'.__('Browse','luxury-hotel').'"/></label>';
			$cs_output .= '</li>';
		$cs_output .= '</ul>';
       
		
		echo force_balance_tags (cs_data_validation( $cs_output ));
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render File Upload field
	 *---------------------------------------------------------------------*/
	public function cs_form_dynamic_render( $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_output = '<ul class="form-elements"><li class="to-label">';
		$cs_output .= cs_label_render();
    	$cs_output .= '</li><li class="to-field has_input"><label class="pbwp-checkbox">';
		$cs_output .= '<input type="checkbox" class="myClass" name="' . $cs_key . '" id="' . $cs_key . '" value="'.sanitize_text_field($cs_value). '" '.checked('on',$cs_value,false).' /><span class="pbwp-box"></span> </label></li></ul>';
		return $cs_output;
 		
	}

	/*----------------------------------------------------------------------
	 * @ render Button field
	 *---------------------------------------------------------------------*/
	public function cs_form_button_render( $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_output = '<input type="radio" class="cs-form-text cs-input " name="' . $cs_key . '" id="' . $cs_key . '" value="'.sanitize_text_field($cs_value). '" />';
		return $cs_output;
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Checkbox With Input Field
	 *---------------------------------------------------------------------*/
	public function cs_form_checkbox_with_field_render(  $params = ''  ) {
 		global $post;
		extract($params);
		extract($field);

		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;     
		} else {
			$value = $std;
		}
		
		$cs_input_value = get_post_meta($post->ID, 'cs_' . $field_id, true);
		if( isset( $cs_input_value ) && $cs_input_value !='' ) {
			$input_value = $cs_input_value;
		} else {
			$input_value = $field_std;
		}
				
		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field has_input">';
				$cs_output .= '<label class="pbwp-checkbox">';
					$cs_output .= $this->cs_form_hidden_render( array( 'id'=> $id , 'std' => '','type'  => '','return'  => 'return' ) );
					$cs_output .= '<input type="checkbox" class="myClass" name="cs_' .sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ). '" value="'.sanitize_text_field('on'). '" '.checked('on',$value,false).' />';
				$cs_output .= '<span class="pbwp-box"></span>';
				$cs_output .= '</label>';
				$cs_output .= '<input type="text" name="cs_' .sanitize_html_class( $field_id ). '" value="'.sanitize_text_field($input_value). '">';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		
		echo force_balance_tags ( cs_data_validation($cs_output ) );
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Color field
	 *---------------------------------------------------------------------*/
	public function cs_form_color_render(  $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}

		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= '<input type="text" class="cs-form-text cs-input bg_color" name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
				$cs_output .= '</div>';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		echo force_balance_tags ( cs_data_validation($cs_output ));
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Color field
	 *---------------------------------------------------------------------*/
	public static function cs_get_id( $params = '' ){
		$id = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$params);
		$id = sanitize_html_class($id);
		return $id;
	}
	
	/*----------------------------------------------------------------------
	 * @ render Range field
	 *---------------------------------------------------------------------*/
	public function cs_form_range_render( $params ='' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}
		
		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="cs-drag-slider" data-slider-min="'.$min.'" data-slider-max="'.$max.'" data-slider-step="'.$step.'" data-slider-value="'.$value.'"></div>';
				$cs_output .= '<input  class="cs-range-input"   name="cs_' . sanitize_html_class( $id ) . '" type="text"  id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
			$cs_output .= '</li>';
		 $cs_output .= '</ul>';
		echo force_balance_tags (cs_data_validation( $cs_output ));
	}
	
	/*----------------------------------------------------------------------
	 * @ render Random ID
	 *---------------------------------------------------------------------*/
	public static function cs_generate_random_string($length = 3) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	/*----------------------------------------------------------------------
	 * @ render Wrapper Start
	 *---------------------------------------------------------------------*/
	public function cs_wrapper_start_render( $params ='' ) {
 		global $post;
		extract($params);
		$cs_display	= '';
		if ( isset( $status ) && $status == 'hide' ) {
			$cs_display	= 'style=display:none';
		}
		
		$cs_output = '<div class="wrapper_'.sanitize_html_class( $id ).'" id="wrapper_'.sanitize_html_class( $id ).'" '.$cs_display.'>';
		echo cs_allow_special_char($cs_output); 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Wrapper Start
	 *---------------------------------------------------------------------*/
	public function cs_wrapper_end_render( $params ='' ) {
 		global $post;
		extract($params);

		$cs_output = '</div>';
		echo cs_allow_special_char($cs_output); 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render File Upload field
	 *---------------------------------------------------------------------*/
	public function cs_information_box( $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_output = '<p>';
		$cs_output .= $description;	
    	$cs_output .= '</p>';
		echo cs_allow_special_char( $cs_output );
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render File Upload field
	 *---------------------------------------------------------------------*/
	public function cs_gallery_render( $params = '' ) {
 		global $post;
		extract($params);
		$cs_random_id = $this->cs_generate_random_string('5');
		?>
        <ul class="form-elements" id="wrapper_thumb_view">
          <li class="to-label">
            <label><?php _e('Add Gallery','luxury-hotel');?></label>
          </li>
          <li class="to-field">
               <div id="gallery_container_<?php echo esc_attr( $cs_random_id );?>">
                <script>
                    jQuery(document).ready(function() {
                        jQuery( "#gallery_sortable_<?php echo esc_attr( $cs_random_id );?>" ).sortable({
                             out: function( event, ui ) {
                                cs_gallery_sorting_list('<?php echo 'cs_'.sanitize_html_class( $id );?>','<?php echo esc_attr( $cs_random_id );?>')
                             }
                        });
                        
                        jQuery('#gallery_container_<?php echo esc_attr( $cs_random_id );?>').on( 'click', 'a.delete', function() {
                            jQuery(this).closest('li.image').remove();	
                            cs_gallery_sorting_list('<?php echo 'cs_'.sanitize_html_class( $id );?>','<?php echo esc_attr( $cs_random_id );?>')
                        });
            
                    });
                </script>
                <ul class="gallery_images" id="gallery_sortable_<?php echo esc_attr( $cs_random_id );?>">
                    <?php
                        if ( metadata_exists( 'post', $post->ID, 'cs_'.$id ) ) {
                            $gallery = get_post_meta( $post->ID, 'cs_'.$id , true );
                        
                        } else {
                            // Backwards compat
                            $attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&&meta_value=0' );
                            $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
                            $gallery = implode( ',', $attachment_ids );
                        }
                        
                        $attachments = array_filter( explode( ',', $gallery ) );
                        if ( $attachments ) {
                            foreach ( $attachments as $attachment_id ) {
                                
								$attachment_data	= $this->cs_get_icon_for_attachment( $attachment_id );
								echo '<li class="image" data-attachment_id="' . esc_attr( $attachment_id ) . '">
                                        ' . $attachment_data . '
                                        <div class="actions">
                                            <span><a href="javascript:;" class="delete tips" data-tip="' . __( 'Delete image', 'luxury-hotel' ) . '">' . __( '<i class="icon-times"></i>', 'luxury-hotel' ) . '</a></span>
                                        </div>
                                    </li>';
                            }
                        }
                    ?>
                </ul>
                <input type="hidden" id="<?php echo 'cs_'.sanitize_html_class( $id );?>" name="<?php echo 'cs_'.sanitize_html_class( $id );?>" value="<?php echo esc_attr( $gallery ); ?>" />
                <input type="hidden" id="cs_theme_url" name="cs_theme_url" value="<?php echo get_template_directory_uri();?>" />
            </div>
            <label class="browse-icon add_gallery hide-if-no-js" data-id="<?php echo 'cs_'.sanitize_html_class( $id );?>" data-rand_id="<?php echo esc_attr( $cs_random_id );?>">
              	<input type="button" class="left" data-choose="<?php echo esc_attr($name); ?>" data-update="<?php echo esc_attr($name); ?>" data-delete="<?php _e( 'Delete' , 'luxury-hotel' ); ?>" data-text="<?php _e( 'Delete', 'luxury-hotel' ); ?>"  value="<?php echo esc_attr($name); ?>">
              </label>
          </li>
        </ul>
      
        <?php
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Attachment Icon
	 *---------------------------------------------------------------------*/
	public function cs_get_icon_for_attachment( $post_id ) {
	  $base = get_template_directory_uri() . "/include/assets/images/";
	  $type = get_post_mime_type( $post_id );
	  
	  switch ( $type ) {
		case 'image/jpeg':
		case 'image/png':
		case 'image/gif':
		  return wp_get_attachment_image( $post_id, 'thumbnail' ); break;
		case 'video/mpeg':
		case 'video/mp4': 
		case 'video/quicktime':
		  return '<img src="'.get_template_directory_uri().'/include/assets/images/video.png'.'" alt="img"/>'; break;
		case 'text/csv':
		case 'text/plain': 
		case 'text/xml':
		 return '<img src="'.get_template_directory_uri().'/include/assets/images/attachment.png'.'" alt="img"/>'; break;
		default:
		 return '<img src="'.get_template_directory_uri().'/include/assets/images/attachment.png'.'" alt="img"/>'; break;
	  }
	}
	
	/*----------------------------------------------------------------------
	 * @ render File Upload field
	 *---------------------------------------------------------------------*/
	public function cs_gallery_render_old( $params = '' ) {
 		global $post;
		extract($params);
		
		ob_start();
			echo cs_post_attachments($std);
		$post_data = ob_get_clean();
        echo force_balance_tags( cs_data_validation($post_data ));
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render Map Html
	 *---------------------------------------------------------------------*/
	public function cs_location_map_render( $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_location_latitude = get_post_meta($post->ID, 'cs_location_latitude', true);
		$cs_location_longitude = get_post_meta($post->ID, 'cs_location_longitude', true);
		$cs_location_zoom = get_post_meta($post->ID, 'cs_location_zoom', true);

		$cs_output = '<div class="gllpLatlonPicker" style="width:100%;">';
		$cs_output .= '<ul class="form-elements">';
          $cs_output .= '<li class="to-label">';
            $cs_output .= '<label></label>';
          $cs_output .= '</li>';
          $cs_output .= '<li class="to-field">';
           $cs_output .= '<input type="button" class="gllpSearchButton" value="'.$name.'" onClick="gll_search_map()">';
          $cs_output .= '</li>';
       $cs_output .= '</ul>';
       $cs_output .= '<ul class="form-elements ">';
         $cs_output .= '<li>';
			$cs_output .= '<div class="gllpMap" id="cs-map-location-id"></div>';
			$cs_output .= '<input type="hidden" name="add_new_loc" class="gllpSearchField" style="margin-bottom:10px;" >';
            $cs_output .= '<input type="hidden" name="cs_location_latitude" value="'.esc_attr( $cs_location_latitude ).'" class="gllpLatitude" />';
            $cs_output .= '<input type="hidden" name="cs_location_longitude" value="'.esc_attr( $cs_location_longitude ).'" class="gllpLongitude" />';
			$cs_output .= '<input type="hidden" name="cs_location_zoom" value="' . esc_attr( $cs_location_zoom ) . '" class="gllpZoom" />';
            $cs_output .= '<input type="button" class="gllpUpdateButton" value="update map" style="display:none">';
         $cs_output .= '</li>';
       $cs_output .= '</ul>';
	   $cs_output .= '</div>';

	   echo cs_allow_special_char( $cs_output ); 	
 		
	}
	
	/*----------------------------------------------------------------------
	 * @ render File Upload field
	 *---------------------------------------------------------------------*/
	public function cs_media_url( $params = '' ) {
 		global $post;
		extract($params);
		
		$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
		if( isset( $cs_value ) && $cs_value !='' ) {
			$value = $cs_value;
		} else {
			$value = $std;
		}

		$cs_output = '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name,$description);
			$cs_output .= '<li class="to-field">';
				$cs_output .= '<div class="input-sec">';
					$cs_output .= '<input type="text" class="cs-form-text cs-input " name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
					$cs_output .= '<label class="browse-icon">';
						$cs_output .= '<input type="button" id="cs_' . sanitize_html_class( $id ) . '_btn" name="cs_' . sanitize_html_class( $id ) . '" class="uploadfileurl left" 
						value="'.__('Browse','luxury-hotel').'"/>';
					$cs_output .= '</label>';
				$cs_output .= '</div>';
			$cs_output .= '</li>';
	    $cs_output .= '</ul>';
		echo force_balance_tags ( cs_data_validation($cs_output ));
 		
	}

}

global $cs_metaboxes;
$cs_metaboxes	= new cs_meta_fields_render();