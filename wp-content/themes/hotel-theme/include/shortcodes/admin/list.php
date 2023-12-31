<?php
/*
 *
 *@File : List
 *@retrun
 *
 */	
 if ( ! function_exists( 'cs_pb_list' ) ) {
    function cs_pb_list($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $counter = $_POST['counter'];
        $cs_counter = $_POST['counter'];
        $list_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = CS_SC_LIST.'|'. CS_SC_LISTITEM;
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array(
		'cs_list_section_title'=>'',
		'cs_list_type'=>'',
		'cs_list_icon'=>'',
		'cs_border'=>'',
		'cs_list_item'=>'',
		'cs_list_class'=>''
		);
            if(isset($output['0']['atts']))
                $atts = $output['0']['atts'];
            else 
                $atts = array();
            if(isset($output['0']['content']))
                $atts_content = $output['0']['content'];
            else 
                $atts_content = array();
            if(is_array($atts_content))
                    $list_num = count($atts_content);
            $list_element_size = '25';
            foreach($defaults as $key=>$values){
                if(isset($atts[$key]))
                    $$key = $atts[$key];
                else 
                    $$key =$values;
             }
            $name = 'cs_pb_list';
            $coloumn_class = 'column_'.$list_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="list" data="<?php echo cs_element_size_data_array_index($list_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$list_element_size, '', 'list-ol',$type='');?>
  <div class="cs-wrapp-class-<?php echo intval($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" style="display: none;">
    <div class="cs-heading-area">
		  <h5><?php _e('Edit List Style Options','luxury-hotel');?></h5>
		  <a href="javascript:removeoverlay('<?php echo esc_js($name.$cs_counter)?>','<?php echo esc_js($filter_element);?>')"
		  class="cs-btnclose"><i class="icon-times"></i>
		  </a>
	  </div>
    <div class="cs-wrapp-tab-box">
      <div class="cs-clone-append cs-pbwp-content" >
        <div id="shortcode-item-<?php echo intval($cs_counter);?>" data-shortcode-template="{{child_shortcode}} [/<?php echo esc_attr( CS_SC_LIST );?>]" data-shortcode-child-template="[<?php echo esc_attr( CS_SC_LISTITEM );?> {{attributes}}] {{content}} [/<?php echo esc_attr( CS_SC_LISTITEM );?>]">
          <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true" data-template="[<?php echo esc_attr( CS_SC_LIST );?> {{attributes}}]">
				<ul class="form-elements">
					  <li class="to-label">
						<label><?php _e('Section Title','luxury-hotel');?></label>
					  </li>
					  <li class="to-field">
						<input  name="cs_list_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_list_section_title)?>"   />
						<p><?php _e('This is used for the one page navigation, to identify the section below. Give a title','luxury-hotel');?> </p>
					  </li>
				</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('List Style','luxury-hotel');?></label>
				  </li>
				  <li class="to-field select-style">
					<select class="dropdown" id="cs_list_type_selected" name="cs_list_type[]" onchange="cs_toggle_list(this.value,'cs_slider_height	                      <?php echo esc_attr($name.$cs_counter)?>')">
					  <option value="none" <?php if($cs_list_type =="none")echo "selected";?>><?php _e('None','luxury-hotel');?></option>
					  <option value="icon" <?php if($cs_list_type =="icon")echo "selected";?>><?php _e('Icon','luxury-hotel');?> </option>
					  <option value="built" <?php if($cs_list_type =="built")echo "selected";?>><?php _e('Bullet','luxury-hotel');?></option>
					  <option value="decimal" <?php if($cs_list_type =="decimal") echo "selected";?> ><?php _e('Decimal','luxury-hotel');?></option>
					  <option value="alphabatic" <?php if($cs_list_type =="alphabatic")echo "selected";?>><?php _e('alphabetic','luxury-hotel');?> </option>
					  <option value="numeric-icon" <?php if($cs_list_type =="numeric-icon")echo "selected";?>><?php _e('Numeric and Icon','luxury-hotel');?> </option>
					  <!-- <option value="custom_icon">Custom Icon</option>-->
					</select>
					<p><?php _e('set a list style from the dropdown','luxury-hotel');?></p>
				  </li>
				</ul>
				<ul class="form-elements">
				  <li class="to-label">
					<label><?php _e('Border Bottom','luxury-hotel');?></label>
				  </li>
				  <li class="to-field select-style">
					<select class="dropdown" name="cs_border[]">
					  <option <?php if($cs_border == "yes")echo "selected";?> value="yes"><?php _e('Yes','luxury-hotel');?></option>
					  <option value="no" <?php if($cs_border == "no")echo "selected";?>><?php _e('No','luxury-hotel');?></option>
					</select>
					<p><?php _e('set on/off for the list bottom border','luxury-hotel');?> </p>
				  </li>
				</ul>
           
          </div>
          <?php
               if ( isset($list_num) && $list_num <> '' && isset($atts_content) && is_array($atts_content)){
                foreach ( $atts_content as $list_items ){
                    $rand_id = $cs_counter.''.cs_generate_random_string(3);
                    $cs_list_item = $list_items['content'];
                    $defaults = array('cs_list_icon'=>'','cs_cusotm_class'=>'','cs_custom_animation'=>'');
                    foreach($defaults as $key=>$values){
                        if(isset($list_items['atts'][$key]))
                            $$key = $list_items['atts'][$key];
                        else 
                            $$key =$values;
                    }
                ?>
                <div class='cs-wrapp-clone cs-shortcode-wrapp'  id="cs_infobox_<?php echo esc_attr($rand_id);?>">
                    <header>
                      <h4><i class='icon-arrows'></i><?php _e('List Item','luxury-hotel');?></h4>
                      <a href='#' class='deleteit_node'><i class='icon-minus-circle'></i><?php _e('Remove','luxury-hotel');?></a>
					  </header>
						<ul class='form-elements'>
						  <li class='to-label'>
							<label><?php _e('List Item:','luxury-hotel');?></label>
						  </li>
						  <li class='to-field'>
							<div class='input-sec'>
							  <textarea class='txtfield' type='text' name='cs_list_item[]' data-content-text="cs-shortcode-textarea"><?php echo cs_allow_special_char($cs_list_item) ?></textarea>
							</div>
						  </li>
						</ul>
						<ul class='form-elements' id="cs_infobox_<?php echo esc_attr($name.$cs_counter);?>">
						  <li class='to-label'>
							<label><?php _e('Font awsome Icon:','luxury-hotel');?> </label>
						  </li>
						  <li class="to-field">
						   <?php cs_fontawsome_icons_box($cs_list_icon,$rand_id,'cs_list_icon');?>
						  </li>
						</ul>
                  </div>
                <?php
                        }
                    }
                    ?>
        </div>
        <div class="hidden-object">
          <input type="hidden" name="list_num[]" value="<?php echo intval($list_num);?>" class="fieldCounter"  />
        </div>
        <div class="wrapptabbox no-padding-lr">
          <div class="opt-conts">
				<ul class="form-elements">
				  <li class="to-field"> <a href="#" class="add_servicesss cs-main-btn"
				   onclick="cs_shortcode_element_ajax_call('list', 'shortcode-item-<?php echo esc_js($cs_counter);?>', '<?php echo admin_url( 'admin-ajax.php');?>')">
				  <i class="icon-plus-circle"></i><?php _e('Add List Item','luxury-hotel');?> </a> </li>
				  <div id="loading" class="shortcodeload"></div>
				</ul>
          </div>
          <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
			  <ul class="form-elements insert-bg noborder">
				<li class="to-field"> 
					<a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo str_replace('cs_pb_','',$name);?>                     ','shortcode-item-<?php echo esc_js($cs_counter);?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','luxury-hotel');?></a>
					 </li>
			  </ul>
          <div id="results-shortocde"></div>
          <?php } else {?>
          <ul class="form-elements noborder no-padding-lr">
            <li class="to-label"></li>
            <li class="to-field">
              <input type="hidden" name="cs_orderby[]" value="list" />
              <input type="button" value="<?php _e('Save','luxury-hotel');?>" style="margin-right:10px;" onclick="javascript:_removerlay(jQuery(this))" />
            </li>
          </ul>
          <?php }?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_list', 'cs_pb_list');
}


function cs_get_pagebuilder_element($shortcode_element_id,$POSTID){
        $cs_page_bulider = get_post_meta($POSTID, "cs_page_builder", true);
        if(isset($cs_page_bulider) && $cs_page_bulider<>''){
            $cs_xmlObject = new SimpleXMLElement($cs_page_bulider);
        }
        $shortcode_element_array = explode('_',$shortcode_element_id);
        $section_no = $shortcode_element_array['0'];
        $columnn_no = $shortcode_element_array['1'];
        $section = 0;
        $colummmn = 0;
        foreach ($cs_xmlObject->column_container as $column_container) {
            $section++;
            if($section ==$section_no){
                foreach ($column_container->children() as $column) {
                    foreach ($column->children() as $cs_node) {
                        $colummmn++;
                        if($colummmn ==$columnn_no){
                            break;
                        }
                    }
                }
            }
            break;
        }
        return $cs_node;
}
