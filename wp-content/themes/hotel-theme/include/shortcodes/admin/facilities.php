<?php
/**
 *@File : Facilities
 *@retrun
 *
 */
if ( ! function_exists( 'cs_pb_facilities' ) ) {
    function cs_pb_facilities($die = 0){
        global $cs_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $cs_counter = $_POST['counter'];
        $facilities_num = 0;
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $PREFIX = CS_SC_FACILITIES.'|'.CS_SC_FACILITIESITEM;
            $parseObject     = new ShortcodeParse();
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        $defaults = array('cs_section_title' => '');
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        if(isset($output['0']['content']))
            $atts_content = $output['0']['content'];
        else 
            $atts_content = array();
        if(is_array($atts_content))
                $facilities_num = count($atts_content);
        $facilities_element_size = '25';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
         }

        $name = 'cs_pb_facilities';
        $coloumn_class = 'column_'.$facilities_element_size;
        if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
            $shortcode_element = 'shortcode_element_class';
            $shortcode_view = 'cs-pbwp-shortcode';
            $filter_element = 'ajax-drag';
            $coloumn_class = '';
        }
        $randD_id = rand(34, 453453);
    ?>
<div id="<?php echo cs_allow_special_char($name.$cs_counter);?>_del" class="column  parentdelete <?php echo cs_allow_special_char($coloumn_class);?> <?php echo cs_allow_special_char($shortcode_view);?>" item="blog" data="<?php echo cs_element_size_data_array_index($facilities_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$facilities_element_size,'','weixin');?>
  <div class="cs-wrapp-class-<?php echo cs_allow_special_char($cs_counter)?> <?php echo cs_allow_special_char($shortcode_element);?>" id="<?php echo cs_allow_special_char($name.$cs_counter);?>" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Facilities Options','luxury-hotel');?></h5>
      <a href="javascript:removeoverlay('<?php echo cs_allow_special_char($name.$cs_counter)?>','<?php echo cs_allow_special_char($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a>
	  </div>
    <div class="cs-clone-append cs-pbwp-content" >
      <div class="cs-wrapp-tab-box">
        <div id="shortcode-item-<?php echo esc_attr($cs_counter);?>" data-shortcode-template="{{child_shortcode}} [/<?php echo esc_attr( CS_SC_FACILITIES ) ;?>]" data-shortcode-child-template="[<?php echo esc_attr( CS_SC_FACILITIESITEM ) ;?> {{attributes}}] {{content}} [/<?php echo esc_attr( CS_SC_FACILITIESITEM ) ;?>]">
          <div class="cs-wrapp-clone cs-shortcode-wrapp cs-disable-true" data-template="[<?php echo esc_attr( CS_SC_FACILITIES ) ;?> {{attributes}}]">
            <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
            <ul class="form-elements">
                <li class="to-label"><label><?php _e('Section Title','luxury-hotel');?></label></li>
                <li class="to-field">
                    <input  name="cs_section_title[]" type="text"  value="<?php echo cs_allow_special_char($cs_section_title);?>"   />
                </li>                  
             </ul>
          </div>
          <?php
                  if ( isset($facilities_num) && $facilities_num <> '' && isset($atts_content) && is_array($atts_content)){
                    $itemCounter  = 0 ;        
                    foreach ( $atts_content as $items ){
                        $itemCounter++;
                        $rand_id = rand(34534,54646890);
						$facilities_text = $items['content'];
                        $defaults = array('title'=>'','title_color'=>'','text_color'=>'','image'=>'','facilities_text'=>'');
                        foreach($defaults as $key=>$values){
                            if(isset($items['atts'][$key]))
                                $$key = $items['atts'][$key];
                            else 
                                $$key =$values;
                         }
                ?>
                      <div class='cs-wrapp-clone cs-shortcode-wrapp'  id="cs_infobox_<?php echo cs_allow_special_char($rand_id);?>">
                        <header>
                          <h4><i class='icon-arrows'></i><?php _e('Facilities','luxury-hotel');?></h4>
                          <a href='#' class='deleteit_node'><i class='icon-minus-circle'></i><?php _e('Remove','luxury-hotel');?></a>
                        </header>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php _e('Title','luxury-hotel');?></label>
                          </li>
                          <li class="to-field">
                            <input type="text" id="title" class="" name="title[]" value="<?php echo cs_allow_special_char($title);?>" />
                          </li>
                        </ul>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php _e('Title Color','luxury-hotel');?></label>
                          </li>
                          <li class="to-field">
                            <input type="text" id="title_color" class="bg_color" name="title_color[]" value="<?php echo esc_attr($title_color);?>" />
                          </li>
                        </ul>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php _e('Text Color','luxury-hotel');?></label>
                          </li>
                          <li class="to-field">
                            <input type="text" id="text_color" class="bg_color" name="text_color[]" value="<?php echo esc_attr($text_color);?>" />
                          </li>
                        </ul>
                        <ul class="form-elements">
                          <li class="to-label">
                            <label><?php _e('Image','luxury-hotel');?></label>
                          </li>
                          <li class="to-field">
                            <input id="image<?php echo cs_allow_special_char($rand_id)?>" name="image[]" type="hidden" class="" value="<?php echo cs_allow_special_char($image);?>"/>
                            <input name="image<?php echo cs_allow_special_char($rand_id)?>"  type="button" class="uploadMedia left" value="<?php _e('Browse','luxury-hotel');?>"/>
                          </li>
                        </ul>
                        <div class="page-wrap" style="overflow:hidden; display:<?php echo cs_allow_special_char($image) && trim($image) !='' ? 'inline' : 'none';?>" id="image<?php echo cs_allow_special_char($rand_id)?>_box" >
                          <div class="gal-active">
                            <div class="dragareamain" style="padding-bottom:0px;">
                              <ul id="gal-sortable">
                                <li class="ui-state-default" id="">
                                  <div class="thumb-secs"> <img src="<?php echo cs_allow_special_char($image);?>"  id="image<?php echo cs_allow_special_char($rand_id)?>_img" width="100" height="150"  />
                                    <div class="gal-edit-opts"> <a href="javascript:del_media('logo<?php echo cs_allow_special_char($rand_id)?>')" class="delete"></a> </div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                        <ul class='form-elements'>
                          <li class='to-label'>
                            <label><?php _e('Text:','luxury-hotel');?></label>
                          </li>
                          <li class='to-field'>
                            <div class='input-sec'>
                              <textarea class='txtfield' data-content-text="cs-shortcode-textarea" name='facilities_text[]'><?php echo cs_allow_special_char($facilities_text);?></textarea>
                              <div class='left-info'>
                                <p><?php _e('Enter your content','luxury-hotel');?></p>
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
          <?php }
             }
            ?>
        </div>
        <div class="hidden-object">
          <input type="hidden" name="facilities_num[]" value="<?php echo (int)$facilities_num;?>" class="fieldCounter"  />
        </div>
        <div class="wrapptabbox no-padding-lr">
          <div class="opt-conts">
            <ul class="form-elements noborder">
              <li class="to-field"> <a href="#" class="add_servicesss cs-main-btn" onclick="cs_shortcode_element_ajax_call('facilities', 'shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>', '<?php echo cs_allow_special_char(admin_url('admin-ajax.php'));?>')"><i class="icon-plus-circle"></i><?php _e('Add Facility','luxury-hotel');?></a> </li>
               <div id="loading" class="shortcodeload"></div>
            </ul>
          </div>
          <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
          <ul class="form-elements insert-bg">
            <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo cs_allow_special_char(str_replace('cs_pb_','',$name));?>','shortcode-item-<?php echo cs_allow_special_char($cs_counter);?>','<?php echo cs_allow_special_char($filter_element);?>')" ><?php _e('Insert','luxury-hotel');?></a> </li>
          </ul>
          <div id="results-shortocde"></div>
          <?php } else {?>
          <ul class="form-elements noborder no-padding-lr">
            <li class="to-label"></li>
            <li class="to-field">
              <input type="hidden" name="cs_orderby[]" value="facilities" />
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
    add_action('wp_ajax_cs_pb_facilities', 'cs_pb_facilities');
}
?>
