<?php
/**
 * File Type: Form Fields
 */

if( ! class_exists('cs_form_fields') ) {
	
	class cs_form_fields {
				
		
		private $counter = 0;
		
		public function __construct() {
						
			// Do something...
		}
		
		/*----------------------------------------------------------------------
		 * @ render label
		 *---------------------------------------------------------------------*/
		public function cs_form_label( $name ='Label Not defined' ) {
			global $post, $pagenow;
			
			$cs_output  = '<li class="to-label">';
				$cs_output .= '<label>'.$name.'</label>';
			$cs_output .= '</li>';
			
			return $cs_output;
		}
		
		/*----------------------------------------------------------------------
		 * @ render description
		 *---------------------------------------------------------------------*/
		public function cs_form_description( $description ='' ) {
			global $post, $pagenow;
			
			if( $description == '' ) {
				return;
			}
			
			$cs_output  = '<div class="left-info">';
				$cs_output .= '<p>'.$description.'</p>';
			$cs_output .= '</div>';
			
			return $cs_output;
		}
		
		/*----------------------------------------------------------------------
		 * @ render Headings
		 *---------------------------------------------------------------------*/
		public function cs_heading_render( $params ='' ) {
			global $post;
			extract($params);
	
			$cs_output = '<div class="theme-help" id="'.sanitize_html_class( $id ).'">
							<h4 style="padding-bottom:0px;">'.esc_attr( $name ).'</h4>
							<div class="clear"></div>
						  </div>';
			echo force_balance_tags ( $cs_output );
			
		}
		/*----------------------------------------------------------------------
		 * @ render text field
		 *---------------------------------------------------------------------*/
		public function cs_form_text_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			if( $pagenow == 'post.php' ) {
				$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			} else{
				$cs_value = $std;
			}
			
			if( isset( $cs_value ) && $cs_value != '' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
			
			if( isset( $no_border ) && $no_border == true ) {
				$no_border = ' noborder';
			} else {
				$no_border = '';
			}
			
			$cs_rand_id = time();
			
			$html_id 	= ' id="cs_' . sanitize_html_class( $id ) . '"';
			$html_name  = ' name="cs_' . sanitize_html_class( $id ) .'"';
			
			if( isset($array) && $array == true ) {
				$html_id = ' id="cs_' . sanitize_html_class( $id ) . $cs_rand_id . '"';
				$html_name = ' name="cs_' . sanitize_html_class( $id ) . '_array[]"';
			}
			
			// Date Formate
			if( isset( $id ) && $id == 'check_in_date' && $value !='' ){
				$value	= date( 'Y-m-d', $value );
			} else if( isset( $id ) && $id == 'check_out_date'  && $value !='' ){
				$value	= date( 'Y-m-d', $value );
			} 
			
			// Disbaled Field
			$cs_visibilty	= '';
			if( isset( $active ) && $active == 'in-active' ){
				$cs_visibilty	= 'readonly="readonly"';
			}
			
			//Calculate Remainings
			if( $id == 'bkng_advance'  ){
				if( $pagenow == 'post.php' ) {
					$cs_booking = get_post_meta($post->ID, 'cs_booking_id', true);
					if( isset( $cs_booking ) &&   $cs_booking != '' ){
						$cs_transactions	= get_option('cs_transactions');
						if( is_array($cs_transactions) && sizeof($cs_transactions) > 0 ) {
							$value	= '';
							foreach( $cs_transactions as $key => $trans ) {
								if( $trans['cs_booking_id'] == $cs_booking ){
									 if( $trans['cs_trans_status'] == 'approved' ) {
										 $value	+= $trans['cs_trans_amount'];
										 update_post_meta($post->ID, 'cs_bkng_advance', $value);
									 } 
									 
								}
							}
						}
					}
				}
			}
			
			if( $id == 'bkng_remaining' ){
				if( $pagenow == 'post.php' ) {
					$cs_booking = get_post_meta($post->ID, 'cs_booking_id', true);
					if( isset( $cs_booking ) &&   $cs_booking != '' ){
						$cs_transactions	= get_option('cs_transactions');
						if( is_array($cs_transactions) && sizeof($cs_transactions) > 0 ) {
							$value_amount	= '';
							foreach( $cs_transactions as $key => $trans ) {
								if( $trans['cs_booking_id'] == $cs_booking ){
									 if( $trans['cs_trans_status'] == 'approved' ) {
									 	$value_amount	+= $trans['cs_trans_amount'];
										$grand_total = get_post_meta($post->ID, 'cs_bkng_grand_total', true);
									 	$value	=  $grand_total - $value_amount;
										update_post_meta($post->ID, 'cs_bkng_remaining', $value);
									 }
								}
							}
						}
					}
				}
			}
			
			$cs_output = '<ul class="form-elements'.$no_border.'">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= '<input type="text" '.$cs_visibilty.' class="cs-form-text cs-input" ' . $html_id . $html_name . ' value="'.sanitize_text_field($value). '" />';
					$cs_output .= '</div>';
				$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			if( isset($return) && $return == true ) {
				return force_balance_tags ( $cs_output ); 
			}else{
				echo force_balance_tags ( $cs_output ); 
			}
		}
		
		/*----------------------------------------------------------------------
		 * @ render text field
		 *---------------------------------------------------------------------*/
		public function cs_form_icon_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			$this->counter++;
			$cs_output = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= CS_FUNCTIONS()->cs_icomoons($std,"plugin_icon_".$this->counter,$name);
					$cs_output .= '</div>';
				$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			echo force_balance_tags ( $cs_output ); 		
		}
		
		/*----------------------------------------------------------------------
		 * @ render Radio field
		 *---------------------------------------------------------------------*/
		public function cs_form_radio_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			if( isset( $cs_value ) && $cs_value !='' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
	
			$cs_output = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= '<input type="radio" class="cs-form-text cs-input " name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
					$cs_output .= '</div>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			echo force_balance_tags ( $cs_output ); 		
		}
		
		/*----------------------------------------------------------------------
		 * @ render text field
		 *---------------------------------------------------------------------*/
		public function cs_form_hidden_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_rand_id = time();
			
			$html_name = ' name="cs_' . sanitize_html_class( $id ) .'"';
			
			if( isset($array) && $array == true ) {
				$html_name = ' name="cs_' . sanitize_html_class( $id ) . '_array[]"';
			}
			
			$cs_output = '<input type="hidden" id="cs_'.sanitize_text_field($id). '" class="cs-form-text cs-input"' . $html_name . 'value="'.sanitize_text_field($std). '" />';
			
			if ( isset($return) && $return == 'echo' ) {
				echo force_balance_tags ( $cs_output );
			} else {
				return force_balance_tags ( $cs_output );
			}
		}
		
		/*----------------------------------------------------------------------
		 * @ render Edit id
		 *---------------------------------------------------------------------*/
		public function cs_form_edit_id( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			
			$cs_output = '';
						
			$cs_output .= '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name);	
			if( $cs_value <> '' ) {
				$cs_output .= '<li class="to-field">#'.$cs_value.'<input type="hidden" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($cs_value). '" /></li>';
			}
			else{
				$cs_output .= '<li class="to-field"><input type="text" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($std). '" /></li>';
			}
			$cs_output .= '</ul>';
			
			if ( isset($return) && $return == 'return' ) {
				return force_balance_tags ( $cs_output );
			} else {
				echo force_balance_tags ( $cs_output );
			}
		}
		
		/*----------------------------------------------------------------------
		 * @ render Edit id
		 *---------------------------------------------------------------------*/
		public function cs_form_edit_title( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_value = '';
			
			$cs_output = '';
						
			$cs_output .= '<ul class="form-elements">';
			$cs_output .= $this->cs_form_label($name);	
			if( $cs_value <> '' ) {
				$get_post_id = CS_FUNCTIONS()->cs_get_post_id_by_meta_key('cs_'.$id, $cs_value);
				if( $get_post_id <> '' ) {
					$cs_output .= '<li class="to-field"><a target="_blank" href="'.get_edit_post_link($get_post_id).'">#'.get_the_title($get_post_id).'</a><input type="hidden" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($cs_value). '" /></li>';
				}
				else{
					$cs_output .= '<li class="to-field"><input type="text" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($std). '" /></li>';
				}
			}
			else{
				$cs_output .= '<li class="to-field"><input type="text" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($std). '" /></li>';
			}
			$cs_output .= '</ul>';
			
			if ( isset($return) && $return == 'return' ) {
				return force_balance_tags ( $cs_output );
			} else {
				echo force_balance_tags ( $cs_output );
			}
		}
		
		/*----------------------------------------------------------------------
		 * @ render Date field
		 *---------------------------------------------------------------------*/
		public function cs_form_date_render( $params ='' ) {
			global $post, $pagenow;
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
												format:"d-m-Y",
												timepicker:false
											});
										});
									  </script>';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= '<input type="text" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
					$cs_output .= '</div>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			echo force_balance_tags ( $cs_output );
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render Date field
		 *---------------------------------------------------------------------*/
		public function cs_form_button_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_output = '<ul class="form-elements">';
				//$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= '<input type="button" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($std). '" />';
					$cs_output .= '</div>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			echo force_balance_tags ( $cs_output );
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render Textarea field
		 *---------------------------------------------------------------------*/
		public function cs_form_textarea_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			if( $pagenow == 'post.php' ) {
				$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			}
			else{
				$cs_value = $std;
			}
			
			if( isset( $cs_value ) && $cs_value !='' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
			
			$cs_rand_id = time();
			
			$html_id = ' id="cs_' . sanitize_html_class( $id ) . '"';
			$html_name = ' name="cs_' . sanitize_html_class( $id ) .'"';
			
			if( isset($array) && $array == true ) {
				$html_id = ' id="cs_' . sanitize_html_class( $id ) . $cs_rand_id . '"';
				$html_name = ' name="cs_' . sanitize_html_class( $id ) . '_array[]"';
			}
			
			$cs_output = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= ' <textarea  rows="5" cols="30"'.$html_id.$html_name.'>'.sanitize_text_field($value).'</textarea>';
					$cs_output .= '</div>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			if( isset($return) && $return == true ) {
				return force_balance_tags ( $cs_output ); 
			}
			else{
				echo force_balance_tags ( $cs_output ); 
			}	
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render select field
		 *---------------------------------------------------------------------*/
		public function cs_form_select_render( $params = '' ) {
			global $post, $pagenow;
			extract($params);
			$cs_onchange	= '';

			if( $pagenow == 'post.php' ) {
				$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			}else{
				$cs_value = $std;
			}
			
			if( isset( $cs_value ) && $cs_value !='' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
			
			$cs_rand_id = time();
			
			$html_wraper = ' id="wrapper_' . sanitize_html_class( $id ) . '"';
			$html_id = ' id="cs_' . sanitize_html_class( $id ) . '"';
			$html_name = ' name="cs_' . sanitize_html_class( $id ) .'"';
			
			if( isset($array) && $array == true ) {
				$html_id = ' id="cs_' . sanitize_html_class( $id ) . $cs_rand_id . '"';
				$html_name = ' name="cs_' . sanitize_html_class( $id ) . '_array[]"';
				$html_wraper = ' id="wrapper_' . sanitize_html_class( $id ) . $cs_rand_id . '"';
			}
			
			$cs_display = '';
			if( isset( $status ) && $status == 'hide' ) {
				$cs_display	= 'style=display:none';
			}
			
			if ( isset( $onclick ) && $onclick !='' ) {
				$cs_onchange	= 'onchange="javascript:'.$onclick.'(this.value, \'' . esc_js(admin_url('admin-ajax.php')) . '\')"';
			}
			
			//Dynamic Rooms Handling
			$holder	= '';
                        $cs_room_data = isset($cs_room_data) ? $cs_room_data : array();
                        $cs_status_data = isset($cs_status_data) ? $cs_status_data : array();
                        $cs_keys_data = isset($cs_keys_data) ? $cs_keys_data : array();
                        $cs_reason_data = isset($cs_reason_data) ? $cs_reason_data : array();
			if( $id == 'room_num' ){
				$list_item	= '';
				$cs_room_data	= array();
				$data_counter	= 0;
				if( isset( $cs_value ) && $cs_value > 0 ) {
					$data_attr	= get_post_meta($post->ID, 'cs_room_meta_data', true);
					if( isset( $data_attr ) && $data_attr !='' ) {
						$cs_room_data = array();
						$cs_room_meta = get_post_meta($post->ID, 'cs_room_meta_data', false);
						$k = 0;
						foreach( $cs_room_meta[0] as $key => $room_reference  ){
							$data_counter++;
							$cs_room_data[$k]		= $room_reference['reference_no'];
							$cs_status_data[$k]	= $room_reference['status'];
							$cs_reason_data[$k]	= $room_reference['reason'];
							$cs_keys_data[$k]		= $key;
							
							$list_item	.= '<li><input readonly="readonly" class="rooms_meta" type="text" value="'.$room_reference['reference_no'].'" name="cs_room_meta[]" id="room_meta"/><input type="hidden" value="'.$key.'" name="cs_room_key[]" id="room_meta"/><input type="hidden" value="'.$room_reference['status'].'" name="cs_room_status[]" /><input type="hidden" value="'.$room_reference['reason'].'" name="cs_room_reason[]" /><i class="icon-arrows-alt"></i><a href="javascript:;" class="delete-capcaity-room"><i class="icon-trash4"></i></a></li>';
						$k++;
						}

						$cs_room_data	= implode(',',$cs_room_data);
						$cs_status_data	= implode(',',$cs_status_data);
						$cs_keys_data	= implode(',',$cs_keys_data);
                        $cs_reason_data	= implode('|||',$cs_reason_data);
					}
				}
				
				$holder	.= '<div class="input-holder" data-reason="'.$cs_reason_data.'" data-rooms="'.$cs_room_data.'"  data-status="'.$cs_status_data.'" data-keys="'.$cs_keys_data.'" data-total="'.$data_counter.'" id="rooms-holder"><ul id="cs_rooms_data">'.$list_item.'</ul></div>';
			}
			
			$cs_output = '<ul class="form-elements"' . $html_wraper . ' '.$cs_display.'>';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= '<div class="select-style">';
							$cs_output .= '<select' . $html_id . $html_name . ' '.$cs_onchange.'>';
							foreach($options as $key => $option) {			
								$cs_output .= '<option '.selected($key ,$value,false). 'value="' . $key . '">' . $option . '</option>';
							}
							$cs_output .= '</select>';
							$cs_output .= $holder;
						$cs_output .= '</div>';
					$cs_output .= '</div>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			if( isset($return) && $return == true ) {
				return force_balance_tags ( $cs_output ); 
			}
			else{
				echo force_balance_tags ( $cs_output ); 
			}	
			
		}
		
		
		/*----------------------------------------------------------------------
		 * @Announcement
		 *---------------------------------------------------------------------*/
		 public function cs_anouncement_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_visible	= 'style=display:none';
			if( isset( $visibilty ) && $visibilty == 'show' ) {
				$cs_visible	= 'style=display:block';
			}
			
			$cs_output	 = '';
			$cs_output	.='<div id="'.sanitize_html_class( $id ).'" '.$cs_visible.' class="alert alert-info fade in nomargin theme_box">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&#215;</button>
							<h4>'.sanitize_text_field($name).'</h4>
							<p>'. sanitize_text_field($std).'</p>
					      </div>';
			echo force_balance_tags ( $cs_output ); 
		 }
		 
		/*----------------------------------------------------------------------
		 * @ render Multi Select field
		 *---------------------------------------------------------------------*/
		public function cs_form_multiselect_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			$cs_onchange	= '';
			
			if( $pagenow == 'post.php' ) {
				$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			}else{
				$cs_value = $std;
			}
			
			if( isset( $cs_value ) && $cs_value !='' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
			
			
			$cs_rand_id = time();
			$html_wraper = ' id="wrapper_' . sanitize_html_class( $id ) . '"';
			$html_id 	 = ' id="cs_' . sanitize_html_class( $id ) . '"';
			$html_name   = ' name="cs_' . sanitize_html_class( $id ) .'[]"';
			
			
			$cs_display = '';
			if( isset( $status ) && $status == 'hide' ) {
				$cs_display	= 'style=display:none';
			}
			
			if ( isset( $onclick ) && $onclick !='' ) {
				$cs_onchange	= 'onchange="javascript:'.$onclick.'(this.value, \'' . esc_js(admin_url('admin-ajax.php')) . '\')"';
			}
			
			if( ! is_array($cs_value)) {
				$cs_value	= array();
			}
			//Dynamic Rooms Handling
			
			$cs_output = '<ul class="form-elements"' . $html_wraper . ' '.$cs_display.'>';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field multiple">';
					$cs_output .= '<select class="multiple" multiple="multiple" ' . $html_id . $html_name . ' '.$cs_onchange.' style="height:110px !important;">';
					foreach($options as $key => $option) {
						$selected	= '';
						if( in_array( $key , $value ) ) {
							$selected	= 'selected="selected"';
						}
							
						$cs_output .= '<option '.$selected. 'value="' . $key . '">' . $option . '</option>';
					}
					$cs_output .= '</select>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			if( isset($return) && $return == true ) {
				return force_balance_tags ( $cs_output ); 
			}else{
				echo force_balance_tags ( $cs_output ); 
			}	
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render Checkbox field
		 *---------------------------------------------------------------------*/
		public function cs_form_checkbox_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			
			if( $pagenow == 'post.php' ) {
				$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			} else {
				$cs_value = $std;
			}
			
			if( isset( $cs_value ) && $cs_value != '' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
			
			$cs_rand_id = time();
			
			$html_id = ' id="cs_' . sanitize_html_class( $id ) .'"';
			$btn_name = ' name="cs_' . sanitize_html_class( $id ) . '"';
			$html_name = ' name="cs_' . sanitize_html_class( $id ) .'"';
			
			if( isset($array) && $array == true ) {
				$html_id = ' id="cs_' . sanitize_html_class( $id ) . $cs_rand_id . '"';
				$btn_name = ' name="cs_' . sanitize_html_class( $id ) . $cs_rand_id . '"';
				$html_name = ' name="cs_' . sanitize_html_class( $id ) . '_array[]"';
			}
			
			$checked = isset( $value ) && $value == 'on' ? ' checked="checked"' : '';
			
			$cs_output = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field has_input">';
					$cs_output .= '<label class="pbwp-checkbox cs-chekbox">';
						$cs_output .= '<input type="hidden"' . $html_id . $html_name . 'value="'.sanitize_text_field($std). '" />';
						$cs_output .= '<input type="checkbox" class="myClass"' . $btn_name . $checked . ' />';
					$cs_output .= '<span class="pbwp-box"></span>';
					$cs_output .= '</label>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			if( isset($return) && $return == true ) {
				return force_balance_tags ( $cs_output ); 
			}
			else{
				echo force_balance_tags ( $cs_output ); 
			}
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render Checkbox With Input Field
		 *---------------------------------------------------------------------*/
		public function cs_form_checkbox_with_field_render(  $params = ''  ) {
			global $post, $pagenow;
			extract($params);
			extract($field);
	
			$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			if( isset( $cs_value ) && $cs_value !='' ) {
				$value = $cs_value;     
			} else {
				$value = $std;
			}
			
			$cs_input_value = get_post_meta($post->ID, 'cs_' . $field_id, true);
			if( isset( $cs_input_value ) && $cs_input_value != '' ) {
				$input_value = $cs_input_value;
			} else {
				$input_value = $field_std;
			}
					
			$cs_output = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field has_input">';
					$cs_output .= '<label class="pbwp-checkbox">';
						$cs_output .= $this->cs_form_hidden_render( array( 'id'=> $id , 'std' => '','type'  => '','return'  => 'return' ) );
						$cs_output .= '<input type="checkbox" class="myClass" name="cs_' .sanitize_html_class( $id ). '" id="cs_' . sanitize_html_class( $id ). '" value="'.sanitize_text_field('on'). '" '.checked('on',$value,false).' />';
					$cs_output .= '<span class="pbwp-box"></span>';
					$cs_output .= '</label>';
					$cs_output .= '<input type="text" name="cs_' .sanitize_html_class( $field_id ). '" value="'.sanitize_text_field($input_value). '">';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			echo force_balance_tags ( $cs_output ); 	
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render File Upload field
		 *---------------------------------------------------------------------*/
		public function cs_media_url( $params = '' ) {
			global $post, $pagenow;
			extract($params);
			
			$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			if( isset( $cs_value ) && $cs_value !='' ) {
				$value = $cs_value;
			} else {
				$value = $std;
			}
	
			$cs_output = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="input-sec">';
						$cs_output .= '<input type="text" class="cs-form-text cs-input" name="cs_' . sanitize_html_class( $id ) . '" id="cs_' . sanitize_html_class( $id ) . '" value="'.sanitize_text_field($value). '" />';
						$cs_output .= '<label class="cs-browse">';
							$cs_output .= '<input type="button" id="cs_' . sanitize_html_class( $id ) . '_btn" name="cs_' . sanitize_html_class( $id ) . '" class="uploadfile left" value="'.__('Browse', 'booking').'"/>';
						$cs_output .= '</label>';
					$cs_output .= '</div>';
					$cs_output .= $this->cs_form_description($description);
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			echo force_balance_tags ( $cs_output ); 
		}
		
		/*----------------------------------------------------------------------
		 * @ render File Upload field
		 *---------------------------------------------------------------------*/
		public function cs_form_fileupload_render( $params = '' ) {
			global $post, $pagenow;
			extract($params);
			
			if( $pagenow == 'post.php' ) {
				$cs_value = get_post_meta($post->ID, 'cs_' . $id, true);
			}
			else{
				$cs_value = $std;
			}
			
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
			
			$cs_random_id = CS_FUNCTIONS()->cs_rand_id();
			
			$btn_name = ' name="cs_' . sanitize_html_class( $id.$cs_random_id ) . '"';
			$html_id = ' id="cs_' . sanitize_html_class( $id ) . '"';
			$html_name = ' name="cs_' . sanitize_html_class( $id.$cs_random_id ) .'"';
			
			if( isset($array) && $array == true ) {
				$html_id = ' id="cs_' . sanitize_html_class( $id ) . $cs_random_id . '"';
				$html_name = ' name="cs_' . sanitize_html_class( $id ) . '_array[]"';
				$btn_name = ' name="cs_' . sanitize_html_class( $id ) . $cs_random_id . '"';
			}
			
			$cs_output  = '<ul class="form-elements">';
				$cs_output .= $this->cs_form_label($name);
				$cs_output .= '<li class="to-field">';
					$cs_output .= '<div class="page-wrap cs-option-image" '.$display.' id="cs_'.sanitize_html_class( $id.$cs_random_id  ).'_box">';
						$cs_output .= '<div class="gal-active">';
						$cs_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
							$cs_output .= '<ul id="gal-sortable">';
								$cs_output .= '<li class="ui-state-default" id="">';
									$cs_output .= '<div class="thumb-secs"> <img src="'.esc_url($value).'"  id="cs_' .sanitize_html_class( $id.$cs_random_id ). '_img" width="100" alt="" />';
									$cs_output .= '<div class="gal-edit-opts"><a   href="javascript:del_media(\'cs_' .sanitize_html_class( $id.$cs_random_id ). '\')" class="delete"></a> </div>';
									$cs_output .= '</div>';
								$cs_output .= '</li>';
							$cs_output .= '</ul>';
						$cs_output .= '</div>';
						$cs_output .= '</div>';
					$cs_output .= '</div>';
					$cs_output .= '<input' . $html_id . $html_name . 'type="hidden" class="" value="'.$value.'"/>';
					$cs_output .= '<label class="browse-icon"><input' . $btn_name . 'type="button" class="uploadMedia left" value='.__('Browse','booking').'/></label>';
				$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			if( isset($return) && $return == true ) {
				return force_balance_tags ( $cs_output ); 
			}
			else{
				echo force_balance_tags ( $cs_output ); 
			}
			
		}
		
		/*----------------------------------------------------------------------
		 * @ render Room Features
		 *---------------------------------------------------------------------*/
		public function cs_booking_feature_list( $params = '' ) {
			global $post, $cs_plugin_options;
			extract($params);
			
			$featureList = get_post_meta($post->ID, 'cs_room_features', true);
			$cs_feature_options = isset($cs_plugin_options['cs_feats_options']) ? $cs_plugin_options['cs_feats_options'] : '';
			
			$cs_output = '';
			if( is_array($cs_feature_options) && sizeof($cs_feature_options) > 0 ) {
				$cs_output .= '
				<ul class="form-elements">
					<li class="to-label"><label>' . esc_attr( $name ) . '</label></li>
					<li class="to-field">';
						$cs_feature_counter = 0;
						foreach($cs_feature_options as $feature){
							$feature_title 	 = $feature['cs_feats_title'];
							$feature_slug 	 = isset($feature['feats_id']) ? $feature['feats_id'] : '';
							$checked		 = '';
							
							if ( is_array( $featureList ) && in_array( $feature_slug , $featureList )  ) {
								$checked	 = 'checked="checked"';
							}
							
							$cs_output .= '<div class="cs-feature-list cs-checkbox checkbox-inline">
											<input type="checkbox" name="cs_' . sanitize_html_class( $id ) . '[]" '.$checked.' value="'.$feature_slug.'" />
											<label>'. esc_attr( $feature_title ).'</label>
										   </div>';

							$cs_feature_counter++;
						}
					$cs_output .= '
					</li>
				</ul>';
			}
			echo force_balance_tags ( $cs_output ); 
		}
		
		/*----------------------------------------------------------------------
		 * @ render Room Extras ajax
		 *---------------------------------------------------------------------*/
		public function cs_booking_extras_list_ajax( $params = '' ) {
			global $post, $cs_plugin_options;
			extract($params);
			$cs_meta_key = 'cs_booking_extras';
			
			$extrasList	= array();
			$cs_currency = isset($cs_plugin_options['currency_sign']) ? $cs_plugin_options['currency_sign'] : '';
			$cs_extras_options = isset($cs_plugin_options['cs_extra_features_options']) ? $cs_plugin_options['cs_extra_features_options'] : '';
			
			$cs_output = '';
			if( is_array($cs_extras_options) && sizeof($cs_extras_options) > 0 ) {
				$cs_output .= '<ul class="list final_price" data-gross="'.$extra.'">';
						$cs_extras_counter = 0;
						foreach($cs_extras_options as $extra_key=>$extras){
							
							if(isset($extra_key) && $extra_key <> ''){
								$extras_title	= isset($extras['cs_extra_feature_title']) ? $extras['cs_extra_feature_title'] : '';
								$feature_desc	= isset($extras['cs_extra_feature_desc']) ? $extras['cs_extra_feature_desc'] : '';
								$extras_price	= isset($extras['cs_extra_feature_price']) ? $extras['cs_extra_feature_price'] : '';
								$extras_id		= isset($extras['extra_feature_id']) ? $extras['extra_feature_id'] : '';
								$checked		= '';
								$feature_type	= isset($extras['cs_extra_feature_type']) ? $extras['cs_extra_feature_type'] : '';
								$feature_guests	= isset($extras['cs_extra_feature_guests']) ? $extras['cs_extra_feature_guests'] : '';
								
								if ( is_array( $extrasList ) && in_array( $extras_id , $extrasList )  ) {
									$checked	 = 'checked="checked"';
								}
								$cs_output .= '<li class="extras-list" data-price="'.$extras_price.'">';
								$cs_output .= '<div class="title-area">';
								$cs_output .= '<div class="check-box-holder">';
								$cs_output .= '<input type="checkbox" class="cs-extras-check" name="cs_' . sanitize_html_class( $id ) . '['.$extras_id.'][]" '.$checked.' value="'.$extras_id.'" />';
								$cs_output .= '</div>';
								$cs_output .= '<div class="title">';
								$cs_output .= '<h4>'.esc_attr( $extras_title ).'</h4>';
								$cs_output .= '<p>'.esc_attr( $feature_desc ).'</p>';
								$cs_output .= '<input type="hidden" class="cs_currency_type" id="cs_currency_type" value="'.esc_attr( $cs_currency ).'" />';
								$cs_output .= '</div>';
								$cs_output .= '</div>';
								$cs_output .= '<div class="total-area">';
								
								if( $feature_guests == 'per-head' ) {
									$cs_output .= '<div class="guest-select">';
									$cs_output .= '<label for="">Guests</label>';
									$cs_output .= '<div class="select-area">';
									$cs_output .= '<select name="cs_guests['.$extras_id.'][]" disabled="disabled" id="cs-total-guests" class="cs-total-guests" data-extra_id="'.$extras_id.'">';
									
									for( $i = 1; $i <= $guests; $i++ ) {
										$cs_output .= '<option value="'.$i.'">'.$i.'</option>';
									}
									
									$cs_output .= '</select>';
									$cs_output .= '</div>';
									$cs_output .= '</div>';
									$cs_output .= '<span class="mul">x</span>';
								}
								
								if( $feature_type =='daily' ) {
									$cs_output .= '<div class="night-select">';
									$cs_output .= '<label for="">Nights</label>';
									$cs_output .= '<div class="select-area">';
									$cs_output .= '<select name="cs_nights['.$extras_id.'][]" disabled="disabled" id="cs-total-nights" class="cs-total-nights"  data-extra_id="'.$extras_id.'">';
									
									for( $i = 1; $i <= $nights; $i++ ) {
										$cs_output .= '<option value="'.$i.'">'.$i.'</option>';
									}
									
									$cs_output .= '</select>';
									$cs_output .= '</div>';
									$cs_output .= '</div>';
									$cs_output .= '<span class="equal">=</span>';
								}
								
								if( $extras_price !='' ) {
									
									$cs_output .= '<div class="total">';
									$cs_output .= '<label for="">Total</label>';
									$cs_output .= '<span class="price">'. esc_attr( $cs_currency ) . $extras_price . '</span>';
									$cs_output .= '<input type="hidden" id="cs_extras_price" class="cs_extras_price" name="cs_extras_price['.$extras_id.'][]" value="'.$extras_price.'" />';
									$cs_output .= '</div>';
								}
								
								$cs_output .= '</div>';
								$cs_output .= '</li>';
	
								$cs_extras_counter++;
							}
						}
					$cs_output .= '
					</li>
				</ul>';
			}
			
			return force_balance_tags ( $cs_output ); 
		}
		
		/*----------------------------------------------------------------------
		 * @ render Room Extras
		 *---------------------------------------------------------------------*/
		public function cs_booking_extras_list( $params = '' ) {
			global $post, $cs_plugin_options;
			extract($params);
			
			$post_type = $post->post_type;
			$cs_meta_key = 'cs_booking_extras';
			$cs_extras_options = isset($cs_plugin_options['cs_extra_features_options']) ? $cs_plugin_options['cs_extra_features_options'] : '';
			
			if( $post_type == 'room_types' ) $cs_meta_key = 'cs_room_type_extras';
			
			$extrasList		= array();
			$extrasList 	= get_post_meta($post->ID, "cs_booking_extras", false);
			$total_adults 	= get_post_meta($post->ID, "cs_total_adults", true);
			$total_childs 	= get_post_meta($post->ID, "cs_total_childs", true);
			$total_nights 	= get_post_meta($post->ID, "cs_booking_num_days", true);
			$cs_extras_price 	= get_post_meta($post->ID, "cs_extras_price", false);
			$cs_guests 			= get_post_meta($post->ID, "cs_guests", false);
			$cs_nights 			= get_post_meta($post->ID, "cs_nights", false);
			$cs_bkng_gross_total 			= get_post_meta($post->ID, "cs_bkng_gross_total", true);
			
			$member_data	=  get_post_meta($post->ID, "cs_booked_room_data", false);
			$total_members	= 0;
			$gross_price	= 0.00;
			foreach( $member_data[0] as $key => $data ){
				$adults	= $data['adults'];
				$childs	= $data['childs'];
				$total_members	+= $adults + $childs;
				$cs_price_val	= $data['price'];
				$gross_price	+= $cs_price_val;
			}
					
			$cs_max_childs	= isset( $cs_plugin_options['cs_childs'] ) && $cs_plugin_options['cs_childs'] !='' && absint($cs_plugin_options['cs_childs']) ? $cs_plugin_options['cs_childs'] : '10';
			$cs_max_adults	= isset( $cs_plugin_options['cs_adults'] ) && $cs_plugin_options['cs_adults'] !='' && absint($cs_plugin_options['cs_adults']) ? $cs_plugin_options['cs_adults'] : '10';
			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			$cs_payment_vat	= isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] !='' ? $cs_plugin_options['cs_payment_vat'] : '0';
			$cs_vat_switch	= isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';
			
			$full_pay			= isset($cs_plugin_options['cs_allow_full_pay']) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
			$cs_advance_deposit	= isset($cs_plugin_options['cs_advnce_deposit']) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '100';
	
			$cs_output = '';
			
			if( isset( $extrasList ) && !empty( $extrasList ) ) {
				$extrasList	= array_keys($extrasList[0]);
			} else{
				$extrasList	= array();
			}
							
			if( is_array($cs_extras_options) && sizeof($cs_extras_options) > 0 ) {
				$cs_output .= '<ul class="list final_price reservation-inner" data-gross="'.$gross_price.'" data-full_pay="'.esc_attr( $full_pay ).'" data-advance="'.esc_attr( $cs_advance_deposit ).'" data-vat_switch="'. esc_attr( $cs_vat_switch ).'" data-vat="'. esc_attr( $cs_payment_vat ).'" data-currency="'.esc_attr( $currency_sign ).'" data-adults="'.esc_attr( $cs_max_adults ).'" data-childs="'.esc_attr( $cs_max_childs ).'">';
						
						$cs_extras_counter = 0;
						
						foreach($cs_extras_options as $extra_key => $extras){
							
							if(isset($extra_key) && $extra_key <> ''){
								
								$extras_title	= isset($extras['cs_extra_feature_title']) ? $extras['cs_extra_feature_title'] : '';
								$feature_desc	= isset($extras['cs_extra_feature_desc']) ? $extras['cs_extra_feature_desc'] : '';
								$extras_price	= isset($extras['cs_extra_feature_price']) ? $extras['cs_extra_feature_price'] : '';
								$extras_id		= isset($extras['extra_feature_id']) ? $extras['extra_feature_id'] : '';
								$checked		= '';
								$feature_type	= isset($extras['cs_extra_feature_type']) ? $extras['cs_extra_feature_type'] : '';
								$feature_guests	= isset($extras['cs_extra_feature_guests']) ? $extras['cs_extra_feature_guests'] : '';
								
								if( isset( $cs_extras_price[0][$extras_id][0] ) ) {
									$extras_price	= $cs_extras_price[0][$extras_id][0];
								}
								
								if ( is_array( $extrasList ) && in_array( $extras_id , $extrasList )  ) {
									$checked	 = 'checked="checked"';
								}
								
								$cs_output .= '<li class="extras-list" data-price="'.$extras_price.'">';
								$cs_output .= '<div class="title-area">';
								$cs_output .= '<div class="check-box-holder">';
								$cs_output .= '<input type="checkbox" class="cs-extras-check" name="cs_' . sanitize_html_class( $id ) . '['.$extras_id.'][]" '.$checked.' value="'.$extras_id.'" />';
								$cs_output .= '</div>';
								$cs_output .= '<div class="title">';
								$cs_output .= '<h4>'.esc_attr( $extras_title ).'</h4>';
								$cs_output .= '<p>'.esc_attr( $feature_desc ).'</p>';
								$cs_output .= '<input type="hidden" class="cs_currency_type" id="cs_currency_type" value="'.esc_attr( $currency_sign ).'" />';
								$cs_output .= '</div>';
								$cs_output .= '</div>';
								$cs_output .= '<div class="total-area">';
								
								if( $feature_guests == 'per-head' ) {
									$cs_output .= '<div class="guest-select">';
									$cs_output .= '<label for="">Guests</label>';
									$cs_output .= '<div class="select-area">';
									$cs_output .= '<select name="cs_guests['.$extras_id.'][]" id="cs-total-guests" class="cs-total-guests" data-extra_id="'.$extras_id.'">';
									
									for( $i = 1; $i <= $total_members; $i++ ) {
										$selected	= '';
										if( isset( $cs_guests[0][$extras_id][0] ) && $cs_guests[0][$extras_id][0] == $i ) {
											$selected	= 'selected="selected"';
										}
										$cs_output .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
									}
									
									$cs_output .= '</select>';
									$cs_output .= '</div>';
									$cs_output .= '</div>';
									$cs_output .= '<span class="mul">x</span>';
								}
								
								if( $feature_type =='daily' ) {
									$cs_output .= '<div class="night-select">';
									$cs_output .= '<label for="">Nights</label>';
									$cs_output .= '<div class="select-area">';
									$cs_output .= '<select name="cs_nights['.$extras_id.'][]" id="cs-total-nights" class="cs-total-nights"  data-extra_id="'.$extras_id.'">>';
									
									for( $i = 1; $i <= $total_nights; $i++ ) {
										$selected	= '';
										if( isset( $cs_nights[0][$extras_id][0] ) && $cs_nights[0][$extras_id][0] == $i ) {
											$selected	= 'selected="selected"';
										}
										$cs_output .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
									}
									
									$cs_output .= '</select>';
									$cs_output .= '</div>';
									$cs_output .= '</div>';
									$cs_output .= '<span class="equal">=</span>';
								}
								
								if( $extras_price !='' ) {
									
									$cs_output .= '<div class="total">';
									$cs_output .= '<label for="">Total</label>';
									$cs_output .= '<span class="price">'. esc_attr( $currency_sign ) . $extras_price . '</span>';
									$cs_output .= '<input type="hidden" id="cs_extras_price" class="cs_extras_price" name="cs_extras_price['.$extras_id.'][]" value="'.$extras_price.'" />';
									$cs_output .= '</div>';
								}
								
								$cs_output .= '</div>';
								$cs_output .= '</li>';
	
								$cs_extras_counter++;
							}
						}
					$cs_output .= '
					</li>
				</ul>';
			}
			
			echo force_balance_tags ( $cs_output ); 
		}
		
		/*----------------------------------------------------------------------
		 * @ render Random String
		 *---------------------------------------------------------------------*/
		public function cs_generate_random_string($length = 3) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
		
		/*----------------------------------------------------------------------
		 * @ render Booking Detail
		 *---------------------------------------------------------------------*/
		public function cs_booking_detail_render( $params ='' ) {
			global $post, $pagenow,$cs_plugin_options;
			extract($params);
			
			$cs_charge_base = isset($cs_plugin_options['cs_charge_base']) ? $cs_plugin_options['cs_charge_base'] : '';
			
			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			$cs_payment_vat	= isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] !='' ? $cs_plugin_options['cs_payment_vat'] : '0';
			
			$room_type 		= get_post_meta($post->ID, 'cs_booked_room_type', true);
			$room_capacity 	= get_post_meta($post->ID, 'cs_booked_room_capacity', true);
			$room_id 		= get_post_meta($post->ID, 'cs_booked_room_id', true);
			$booked_room 	= get_post_meta($post->ID, 'cs_booked_room', true);
			$grand_total 	= get_post_meta($post->ID, 'cs_bkng_grand_total', true);
			$cs_room_price 	= get_post_meta($post->ID, 'cs_room_price', true);
			$date_from  	= get_post_meta($post->ID, 'cs_check_in_date', true);
			$date_to  		= get_post_meta($post->ID, 'cs_check_out_date', true);
			
			$total_adults  	= get_post_meta($post->ID, 'cs_total_adults', true);
			$total_childs  	= get_post_meta($post->ID, 'cs_total_childs', true);
			
			$max_adults  	= get_post_meta($room_capacity, 'cs_room_max_adults', true);
			$max_child  	= get_post_meta($room_capacity, 'cs_room_max_child', true);
			
			$cs_adult_price  	= get_post_meta($post->ID, 'cs_adult_price', false);
			$cs_child_price  	= get_post_meta($post->ID, 'cs_child_price', false);
			$cs_date  			= get_post_meta($post->ID, 'cs_date', false);
			
			$cs_booking  	= get_post_meta($post->ID, 'cs_booked_room_data', false);

			$cs_booking				= isset($cs_booking[0]) ? $cs_booking[0] : '';
			$cs_booking_data		=  $cs_booking;
			$cs_adult_price	= isset($cs_adult_price[0]) ? $cs_adult_price[0] : '';
			$cs_child_price	= isset($cs_child_price[0]) ? $cs_child_price[0] : '';
			
			$start_date = strtotime( $date_from );
			$end_date   = strtotime( $date_to );
			
			$cs_start_date_time = strtotime( $date_from );
			$cs_end_date_time   = strtotime( $date_to );
			
			$start_time  	= get_post_meta($post->ID, 'cs_check_in_date', true);
			$end_time  		= get_post_meta($post->ID, 'cs_check_out_date', true);
			
			if($start_time != ''){
				$start_time = date('H:i', strtotime( $start_time ));
				$cs_start_date_time = strtotime($date_from . ' ' . $start_time);
			}
			if($end_time != ''){
				$end_time = date('H:i', strtotime( $end_time ));
				$cs_end_date_time = strtotime($date_to . ' ' . $end_time);
			}
			
			$cs_output		 = '';

			if( isset( $cs_booking ) && is_array( $cs_booking ) && !empty( $cs_booking ) ) {
			
			$booking_counter	= 0;
			foreach( $cs_booking as $key => $booked_room ) {
				$price_breakdown	= '';
				
			if( $date_from !='' &&  $date_to !='' ) {
				// Loop between timestamps, 24 hours at a time
				$price['total_price']	= 0;
				$total_price	= '';
				$adult_price	= 0;
				$child_price	= 0;
				$pricing_data		= array();
				$pricing_offer_data	= array();
				
				
				$brk_counter	= 0;
				
				$cs_counter_plus_plus = 86400;
				if ($cs_charge_base == 'hourly') {
					$cs_counter_plus_plus = 3600;
					if ($cs_end_date_time > 3600) {
						$cs_end_date_time = (int) $cs_end_date_time - 3600;
					}
				}

				for ($i = $cs_start_date_time; $i <= $cs_end_date_time; $i = $i + $cs_counter_plus_plus) {
					  $brk_counter++;
					  $thisDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
					 
					  
					  if( isset( $cs_adult_price[$brk_counter][$booking_counter] ) ) {
					  	$adult_price	= $cs_adult_price[$brk_counter][$booking_counter];
					  }
					  
					  if( isset( $cs_child_price[$brk_counter][$booking_counter] ) ) {
					  	$child_price	= $cs_child_price[$brk_counter][$booking_counter];
					  }
					  
					  
					  //Total By Person
					  $adult_price_total	= $adult_price * $booked_room['adults'];
					  $child_price_total	= $child_price * $booked_room['childs'];
					  $cs_total				= $adult_price_total + $child_price_total;

					  //price Breakdown
					  $price_breakdown		.= '<div class="bk-pricing-breakdown">';
					  $price_breakdown		.= '<div class="bk-room-date">';
					  $price_breakdown		.= date_i18n( get_option( 'date_format' ), strtotime( $thisDate ) );
					  $price_breakdown		.= '</div">';
					  $price_breakdown		.= '<div class="bk-room-adults">';
					  $price_breakdown		.= $currency_sign.$adult_price.' (x'.$booked_room['adults'].')';
					  $price_breakdown		.= '</div">';
					  $price_breakdown		.= '<div class="bk-room-children">';
					  $price_breakdown		.= $currency_sign.$child_price.' (x'.$booked_room['childs'].')';
					  $price_breakdown		.= '</div">';
					  $price_breakdown		.= '<div class="bk-room-total">';
					  $price_breakdown		.= $currency_sign.number_format($cs_total,2);
					  $price_breakdown		.= '</div">';
					  $price_breakdown		.= '</div">';
				}
			}
			$cs_output		.= '<div class="bk-room-deail cs-gross-calculation" data-vat="'.$cs_payment_vat.'" data-price="'.$cs_room_price.'">';
			$cs_output		.= '<div class="bk-room-name">';
			$cs_output		.= get_the_title( $booked_room['room_type'] ).':'.get_the_title( $booked_room['capacity'] ).' #'.$booked_room['room_id'];
			$cs_output		.= '</div>';				
			$cs_output		.= '<div class="bk-room-capacity">';
			$cs_output		.= '<span> <b>'.__('Guests','booking').'</b>: Max. '. $booked_room['adults'] .' Adult(s), Max. '. $booked_room['childs'].' Child(s)</span>';
			$cs_output		.= '<span> <b>'.__('Price','booking').'</b>: '.$currency_sign. $booked_room['price'].'</span>';
			
			$cs_output		.= '<div id="cs-price-breakdown-'.$booking_counter.'" class="cs-price-breakdown"  style="display:none">';
			$cs_output		.= '<div class="cs-heading-area">';
			$cs_output		.= '<h5><i class="icon-plus-circle"></i> ' .__('Price Breakdown','booking') . '</h5>';
			$cs_output		.= '<span class="cs-btnclose" onClick="javascript:cs_remove_overlay(\'cs-price-breakdown-'.$booking_counter.'\',\'append\')"> <i class="icon-times"></i></span> 	';
			$cs_output		.= '</div>';
			$cs_output		.= '<div class="bk-pricing-breakdown price-header"><div class="bk-room-date">Date</div><div class="bk-room-adults">Adults</div><div class="bk-room-children">Children</div><div class="bk-room-total">Total</div></div>';
			$cs_output		.= $price_breakdown;
			$cs_output		.= '</div>';
			$cs_output		.= '<a href="javascript:cs_createpop(\'cs-price-breakdown-'.$booking_counter.'\',\'filter\')" class="button">' . __("+ Price Break down","booking") . '</a>';
						
			$cs_output		.= '</div>';
			$cs_output		.= '<script>jQuery(document).ready(function() { cs_room_extras(); });</script>';
			$cs_output		.= '</div>';
			
				$booking_counter++;
				
				}
			}
			
			
			
			
			echo force_balance_tags ( $cs_output ); 
		}
		
		/*----------------------------------------------------------------------
		 * @ render Attachmnets
		 *---------------------------------------------------------------------*/
		public function cs_file_attachments( $params = '' ) {
			global $post, $cs_plugin_options;
			extract($params);
			?>

            <ul class="form-elements">
              <li class="to-label">
                <label><?php _e('File Attachments','booking');?></label>
              </li>
              <li class="to-field">
                <div class="input-sec">
                  <div id="file_attachment_container"> 
				  <script>
                      jQuery(function() {
                        jQuery( "#sortable_attachments" ).sortable();
                      });
                  </script>
			  	  <ul class="cs_attachments_list" id="sortable_attachments">
					<?php
                        if ( metadata_exists( 'post', $post->ID, 'cs_room_file_attach' ) ) {
                            $file_attachments = get_post_meta( $post->ID, 'cs_room_file_attach', true );
                        
                        } else {
                            // Backwards compat
                            $attachment_ids = get_posts( 'post_parent=' . $post->ID . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&&meta_value=0' );
                            $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
                            $file_attachments = implode( ',', $attachment_ids );
                        }
                        $attachments = array_filter( explode( ',', $file_attachments ) );
                        if ( $attachments )
                            foreach ( $attachments as $attachment_id ) {
                                $attachment_data	= $this->cs_get_icon_for_attachment( $attachment_id );
								
								echo '<li class="cs-file-list" data-attachment_id="' . esc_attr( $attachment_id ) . '">
                                         '.$attachment_data.'
                                         <div class="actions">
                                            <span><a href="#" class="delete tips" data-tip="' . __( 'Delete Attachment', 'booking' ) . '">' . __( '<i class="icon-times"></i>', 'booking' ) . '</a></span>
                                         </div>
                                      </li>';
                            }
                    ?>
                  </ul>
			  	  <input type="hidden" id="cs_room_file_attach" name="cs_room_file_attach" value="<?php echo esc_attr( $file_attachments ); ?>" />
				 </div>
                  <input type="hidden" id="file_icon_url" name="file_icon_url" value="<?php echo wp_hotel_booking::plugin_url().'/assets/images/attachment.png';?>" />
                  
                  <label class="browse-icon add_file_attachmnets hide-if-no-js">
                    <input type="button" class="left" data-choose="<?php _e( $name, 'booking' ); ?>" data-update="<?php _e( $name, 'booking' ); ?>" data-delete="<?php _e( 'Delete' , 'booking' ); ?>" data-text="<?php _e( 'Delete', 'booking' ); ?>"  value="<?php _e( $name, 'booking' ); ?>">
                  </label>
              
                </div>
              </li>
            </ul>
           
			<?php
		}
		
		/*----------------------------------------------------------------------
		 * @ render Attachment Icon
		 *---------------------------------------------------------------------*/
		public function cs_get_icon_for_attachment( $post_id ,$size ='' ) {
			$base = wp_hotel_booking::plugin_url() . "/assets/images/";
			$type = get_post_mime_type( $post_id );
			
			if( $size='custom' ) {
				$size	= array( 50,50 );		
			} else{
				$size	= 'thumbnail';	
			}
			 
			switch ( $type ) {
			case 'image/jpeg':
			case 'image/png':
			case 'image/gif':
				 return wp_get_attachment_image( $post_id, $size ); break;
			case 'video/mpeg':
			case 'video/mp4': 
			case 'video/quicktime':
				 return '<i class="icon-video-camera"></i>'; break;
			case 'text/csv':
			case 'text/plain': 
			case 'text/xml':
				 return '<i class="icon-documents"></i>'; break;
			case 'audio/mpeg':
				 return '<i class="icon-music6"></i>'; break;
			default:
				return '<i class="icon-documents"></i>'; break;
			}
		}
		/*----------------------------------------------------------------------
		 * @ render Gallery
		 *---------------------------------------------------------------------*/
		public function cs_gallery_render( $params = '' ) {
			global $post, $cs_plugin_options;
			extract($params);
			$cs_random_id = $this->cs_generate_random_string('5');
			?>

           <div id="gallery_container">
            <script>
				jQuery(document).ready(function() {
					jQuery( "#gallery_sortable_<?php echo esc_js($cs_random_id);?>" ).sortable({
						 out: function( event, ui ) {
						 	cs_gallery_sorting('<?php echo 'cs_'.sanitize_html_class( $id );?>','<?php echo esc_js($cs_random_id);?>')
						 }
					});
					
					jQuery('#gallery_container').on( 'click', 'a.delete', function() {
						jQuery(this).closest('li.image').remove();	
						cs_gallery_sorting('<?php echo 'cs_'.sanitize_html_class( $id );?>','<?php echo esc_js($cs_random_id);?>')
					});
		
				});
			</script>
            <ul class="gallery_images" id="gallery_sortable_<?php echo absint($cs_random_id);?>">
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
                                        <span><a href="javascript:;" class="delete tips" data-tip="' . __( 'Delete image', 'booking' ) . '">' . __( '<i class="icon-times"></i>', 'booking' ) . '</a></span>
                                    </div>
                                </li>';
                        }
                    }
                ?>
            </ul>
            <input type="hidden" id="<?php echo 'cs_'.sanitize_html_class( $id );?>" name="<?php echo 'cs_'.sanitize_html_class( $id );?>" value="<?php echo esc_attr( $gallery ); ?>" />
            <input type="hidden" id="cs_plugin_url" name="cs_plugin_url" value="<?php echo wp_hotel_booking::plugin_url();?>" />
        </div>
        <p class="add_gallery_data hide-if-no-js" data-id="<?php echo 'cs_'.sanitize_html_class( $id );?>" data-rand_id="<?php echo absint($cs_random_id);?>">
            <a href="javascript:;" class="button-secondary" data-choose="<?php _e( $name, 'booking' ); ?>" data-update="<?php _e( $name, 'booking' ); ?>" data-delete="<?php _e( 'Delete' , 'booking' ); ?>" data-text="<?php _e( 'Delete', 'booking' ); ?>"><?php _e( $name, 'booking' ); ?></a>
        </p>
       
        <?php
		}
		
		/*----------------------------------------------------------------------
		 * @ render transaction summary
		 *---------------------------------------------------------------------*/
		public function cs_transaction_summary( $params = '' ){
			
			global $post, $gateways , $cs_plugin_options;
			//$cs_plugin_options = get_option('cs_plugin_options');			
			$summary_status = get_post_meta($post->ID, "cs_trans_status", true);
			$trans_pay_method = get_post_meta($post->ID, "cs_trans_gateway", true);
			$gateway_type	= 'NILL';
			$gateway_logo	= '';
			if( isset( $trans_pay_method ) && $trans_pay_method !='' ) {
				$gateway_type	= $gateways[strtoupper($trans_pay_method)];
				$logo			= $cs_plugin_options[strtolower($trans_pay_method).'_logo'];
				if( isset( $logo ) && $logo != '' ) {
					$gateway_logo	=  '<img src="'.$logo.'" alt="" />';
				}
			}
			
			$summary_status = $summary_status ? ucfirst($summary_status) : __('Pending', 'booking');
			
			$cs_output = '<ul class="form-elements">';
			$cs_output .= '<li class="to-label"><label>' . $params['name'] . '</label></li>';
			$cs_output .= '<li class="to-field payment-summary">';
				
				$cs_output .= '<ul class="form-elements">';
					$cs_output .= '<li class="to-label">'.__('Payment Method', 'booking').'</li>';
					$cs_output .= '<li class="to-field">'.$gateway_logo.' '.$gateway_type.'</li>';
				$cs_output .= '</ul>';
				$cs_output .= '<ul class="form-elements">';
					$cs_output .= '<li class="to-label">'.__('Status', 'booking').' </li>';
					$cs_output .= '<li class="to-field">'.$summary_status.'</li>';
				$cs_output .= '</ul>';
				$cs_output .= $this->cs_form_text_render(
								  array(  'name'	=> __('Email', 'booking'),
										  'id'	=> 'trans_email',
										  'classes' => '',
										  'std'	=> '',
										  'description'  => '',
										  'hint'  => '',
										  'return'  => true
									  )
							  );
				$cs_output .= $this->cs_form_text_render(
								  array(  'name'	=> __('First Name', 'booking'),
										  'id'	=> 'trans_first_name',
										  'classes' => '',
										  'std'	=> '',
										  'description'  => '',
										  'hint'  => '',
										  'return'  => true
									  )
							  );
				$cs_output .= $this->cs_form_text_render(
								  array(  'name'	=> __('Last Name', 'booking'),
										  'id'	=> 'trans_last_name',
										  'classes' => '',
										  'std'	=> '',
										  'description'  => '',
										  'hint'  => '',
										  'return'  => true
									  )
							  );
				$cs_output .= $this->cs_form_text_render(
								  array(  'name'	=> __('Full Name', 'booking'),
										  'id'	=> 'trans_full_name',
										  'classes' => '',
										  'std'	=> '',
										  'description'  => '',
										  'hint'  => '',
										  'return'  => true
									  )
							  );
				$cs_output .= $this->cs_form_textarea_render(
								  array(  'name'	=> __('Address', 'booking'),
										  'id'	=> 'trans_address',
										  'classes' => '',
										  'std'	=> '',
										  'description'  => '',
										  'hint'  => '',
										  'return'  => true
									  )
							  );
							  
			$cs_output .= '</li>';
			$cs_output .= '</ul>';
			
			echo force_balance_tags ( $cs_output ); 
		}
		
		/*----------------------------------------------------------------------
		 * @ Rooms Render
		 *---------------------------------------------------------------------*/
		public static function cs_rooms_render( $params = '' ){
			ob_start();
			global $cs_plugin_options;
			$cs_max_childs	= isset( $cs_plugin_options['cs_childs'] ) && $cs_plugin_options['cs_childs'] !='' && absint($cs_plugin_options['cs_childs']) ? $cs_plugin_options['cs_childs'] : '10';
			$cs_max_adults	= isset( $cs_plugin_options['cs_adults'] ) && $cs_plugin_options['cs_adults'] !='' && absint($cs_plugin_options['cs_adults']) ? $cs_plugin_options['cs_adults'] : '10';
			$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
			$cs_payment_vat	= isset($cs_plugin_options['cs_payment_vat']) && $cs_plugin_options['cs_payment_vat'] !='' ? $cs_plugin_options['cs_payment_vat'] : '0';
			$cs_vat_switch	= isset($cs_plugin_options['cs_vat_switch']) && $cs_plugin_options['cs_vat_switch'] == 'on' ? $cs_plugin_options['cs_vat_switch'] : 'off';
			
			$full_pay			= isset($cs_plugin_options['cs_allow_full_pay']) && $cs_plugin_options['cs_allow_full_pay'] == 'on' ? $cs_plugin_options['cs_allow_full_pay'] : 'off';
			$cs_advance_deposit	= isset($cs_plugin_options['cs_advnce_deposit']) && $cs_plugin_options['cs_advnce_deposit'] != '' ? $cs_plugin_options['cs_advnce_deposit'] : '100';
			
			?>
			<div class="reservation-form">
            <ul id="search-wraper" class="reservation-inner" data-full_pay="<?php echo esc_attr( $full_pay );?>" data-advance="<?php echo esc_attr( $cs_advance_deposit );?>" data-vat_switch="<?php echo esc_attr( $cs_vat_switch );?>" data-vat="<?php echo esc_attr( $cs_payment_vat );?>" data-currency="<?php echo esc_attr( $currency_sign );?>" data-adults="<?php echo esc_attr( $cs_max_adults );?>" data-childs="<?php echo esc_attr( $cs_max_childs );?>">
            <li class="rooms">
              <label for=""><?php _e('No. of Rooms','booking')?></label>
              <span class="select-style">
                   <select name="cs-rooms" id="cs-booking-rooms">
                    <?php 
                        for( $i = 1; $i <= 10; $i++ ) {
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                    ?>
                   </select>
               </span>
            </li>
            <li class="select-options">
               <h5><?php _e('Room 01','booking');?></h5>
               <div class="cs-capacity-wrap">
               	<div class="select-area">
                      <span class="select-style">
                      <select id="cs_max_adults" name="adults[]" class="booking-members">
                         <?php 
                            for( $i = 1; $i <= $cs_max_adults; $i++ ) {
                                echo '<option value="'.$i.'">'.$i.__(' Adults','booking').'</option>';
                            }
                         ?>
                      </select>
                     </span> 
                </div>
                <div class="select-area">
                      <span class="select-style">
                      <select id="cs_max_childs" name="childs[]" class="booking-members">
                         <?php 
                            for( $i = 0; $i <= $cs_max_childs; $i++ ) {
                                echo '<option value="'.$i.'">'.$i.__(' Children','booking').'</option>';
                            }
                         ?>
                      </select>
                     </span> 
                </div>
               </div>
              </li>
            </ul>
            </div>
		   <?php 
           echo ob_get_clean();
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
		 * @ render Wrapper Start
		 *---------------------------------------------------------------------*/
		public function cs_wrapper_start_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
			$cs_display	= '';
			if ( isset( $status ) && $status == 'hide' ) {
				$cs_display	= 'style=display:none';
			}
			
			$cs_output = '<div class="wrapper_'.sanitize_html_class( $id ).'" id="wrapper_'.sanitize_html_class( $id ).'" '.$cs_display.'>';
			echo CS_FUNCTIONS()->cs_special_chars($cs_output); 		
		}
		
		/*----------------------------------------------------------------------
		 * @ render Wrapper Start
		 *---------------------------------------------------------------------*/
		public function cs_wrapper_end_render( $params ='' ) {
			global $post, $pagenow;
			extract($params);
	
			$cs_output = '</div>';
			echo CS_FUNCTIONS()->cs_special_chars($cs_output); 		
		}
		
	}
	
	global $cs_form_fields;
	$cs_form_fields	= new cs_form_fields(); 
}