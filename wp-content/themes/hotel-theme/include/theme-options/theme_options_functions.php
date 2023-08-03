<?php
/**
 * @Save Theme Options
 * @return
 *
 */
if ( ! function_exists( 'theme_option_save' ) ) {
	function theme_option_save() {
		global $reset_date,$cs_options;
		$_POST = cs_stripslashes_htmlspecialchars($_POST);
		
 		if(isset($_POST['cs_import_theme_options']) and $_POST['cs_import_theme_options'] <> ''){
            $cs_import_theme_options_decode = '';
            if(function_exists('base_64_decode')) {
                $cs_import_theme_options_decode = base_64_decode($_POST['cs_import_theme_options']);
            }
			update_option( "cs_theme_options", unserialize($cs_import_theme_options_decode));
		}else{
			update_option( "cs_theme_options",$_POST );
		}
		echo "All Settings Saved";
		
		die();
	}
	add_action('wp_ajax_theme_option_save', 'theme_option_save');
}

/**
 * @saving all the theme options end
 * @return
 *
 */
if ( ! function_exists( 'theme_option_rest_all' ) ) {
	function theme_option_rest_all() {
		delete_option('cs_theme_options');
		update_option( "cs_theme_options", cs_reset_data());
		echo "Reset All Options";
		die();
	 }
	add_action('wp_ajax_theme_option_rest_all', 'theme_option_rest_all');
}

/**
 * @theme activation
 * @return
 *
 */
if ( ! function_exists( 'cs_activation_data' ) ) {
	function cs_activation_data(){
		update_option('cs_theme_options',cs_reset_data());
	}
}

/**
 * @array for reset theme options
 * @return
 *
 */
if ( ! function_exists( 'cs_reset_data' ) ) {
	function cs_reset_data(){
		global $reset_data,$cs_options;
			foreach ($cs_options as $value) {
			if($value['type'] <> 'heading' and $value['type'] <> 'sub-heading' and $value['type']<>'main-heading'){
				if($value['type']=='sidebar' || $value['type']=='networks' || $value['type']=='badges'){
					$reset_data=(array_merge($reset_data,$value['options']));
				}if($value['type']=='packages_data'){
					update_option('cs_packages_options',$value['std']);
				}if($value['type']=='free_package'){
					update_option('cs_free_package_switch',$value['std']);
				}elseif($value['type']=='check_color'){
					$reset_data[$value['id']] = $value['std'];
					$reset_data[$value['id'].'_switch'] = 'off';
				}else{
					$reset_data[$value['id']] = $value['std'];
				}
			}
		}
		return $reset_data;
	}
}

/**
 * @Sub Header Slider
 * @return
 *
 */
if ( ! function_exists( 'cs_headerbg_slider' ) ) {
	function cs_headerbg_slider(){
		if(class_exists('RevSlider') && class_exists('cs_RevSlider')) {
			$slider = new cs_RevSlider();
			$arrSliders = $slider->getAllSliderAliases();
			foreach ( $arrSliders as $key => $entry ) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $key['alias']) { $selected = ' selected="selected"';} 
				 } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $key['alias']) { $selected = ' selected="selected"'; }
				 }
				$output.= '<option '.$selected.' value="'.$key['alias'].'">'.$entry['title'].'</option>';
			}
		}
	}
}
?>