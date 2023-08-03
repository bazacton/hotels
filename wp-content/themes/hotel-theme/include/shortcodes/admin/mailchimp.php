<?php
/*
 *
 *@Shortcode Name : Mail Chimp
 *@retrun
 *
 */

if ( ! function_exists( 'cs_pb_mailchimp' ) ) {
    function cs_pb_mailchimp($die = 0){
        global $cs_node, $count_node, $post;
        $shortcode_element = '';
        $filter_element = 'filterdrag';
        $shortcode_view = '';
        $output = array();
        $PREFIX = CS_SC_MAILCHIMP;
        $cs_counter = $_POST['counter'];
        $parseObject     = new ShortcodeParse();
        if ( isset($_POST['action']) && !isset($_POST['shortcode_element_id']) ) {
            $POSTID = '';
            $shortcode_element_id = '';
        } else {
            $POSTID = $_POST['POSTID'];
            $shortcode_element_id = $_POST['shortcode_element_id'];
            $shortcode_str = stripslashes ($shortcode_element_id);
            $output = $parseObject->cs_shortcodes( $output, $shortcode_str , true , $PREFIX );
        }
        
        $defaults = array( 
		'column_size'=>'1/1',
		'cs_mailchimp_sec_title'=> '',
		'cs_mailchimp_title'=> '',
		'cs_mailchimp_subtitle' => '',
		'cs_mailchimp_bg_color' => '',
		'cs_mailchimp_txt_color' => '',
		'cs_mailchimp_text'=> '' 
		);
            
        if(isset($output['0']['atts']))
            $atts = $output['0']['atts'];
        else 
            $atts = array();
        
        if(isset($output['0']['content']))
            $atts_content = $output['0']['content'];
        else 
            $atts_content = "";
            
        $mailchimp_element_size = '33';
        foreach($defaults as $key=>$values){
            if(isset($atts[$key]))
                $$key = $atts[$key];
            else 
                $$key =$values;
        }
        
		$name = 'cs_pb_mailchimp';
        $coloumn_class = 'column_'.$mailchimp_element_size;
    
    if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){
        $shortcode_element = 'shortcode_element_class';
        $shortcode_view = 'cs-pbwp-shortcode';
        $filter_element = 'ajax-drag';
        $coloumn_class = '';
    }    
    ?>
<div id="<?php echo esc_attr($name.$cs_counter)?>_del" class="column  parentdelete <?php echo esc_attr($coloumn_class);?> <?php echo esc_attr($shortcode_view);?>" item="mailchimp" data="<?php echo cs_element_size_data_array_index($mailchimp_element_size)?>" >
  <?php cs_element_setting($name,$cs_counter,$mailchimp_element_size,'','life-ring');?>
  <div class="cs-wrapp-class-<?php echo esc_attr($cs_counter)?> <?php echo esc_attr($shortcode_element);?>" id="<?php echo esc_attr($name.$cs_counter)?>" data-shortcode-template="[<?php echo esc_attr( CS_SC_MAILCHIMP );?> {{attributes}}]{{content}}[/<?php echo esc_attr( CS_SC_MAILCHIMP );?>]" style="display: none;">
    <div class="cs-heading-area">
      <h5><?php _e('Edit Mail Chimp Options','luxury-hotel');?></h5>
      <a href="javascript:removeoverlay('<?php echo esc_attr($name.$cs_counter)?>','<?php echo esc_attr($filter_element);?>')" class="cs-btnclose"><i class="icon-times"></i></a> </div>
    <div class="cs-pbwp-content">
      <div class="cs-wrapp-clone cs-shortcode-wrapp cs-pbwp-content">
        <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){cs_shortcode_element_size();}?>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Section Title','luxury-hotel');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_mailchimp_sec_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_mailchimp_sec_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Mail chimp Title','luxury-hotel');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_mailchimp_title[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_mailchimp_title)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Mailchimp Sub Title','luxury-hotel');?></label>
          </li>
          <li class="to-field">
            <input type="text" name="cs_mailchimp_subtitle[]" class="txtfield" value="<?php echo cs_allow_special_char($cs_mailchimp_subtitle)?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Background Color','luxury-hotel');?></label>
          </li>
          <li class="to-field">
            <input type="text" class="bg_color" name="cs_mailchimp_bg_color[]" value="<?php echo cs_allow_special_char($cs_mailchimp_bg_color);?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Text Color','luxury-hotel');?></label>
          </li>
          <li class="to-field">
            <input type="text" class="bg_color" name="cs_mailchimp_txt_color[]" value="<?php echo cs_allow_special_char($cs_mailchimp_txt_color);?>" />
          </li>
        </ul>
        <ul class="form-elements">
          <li class="to-label">
            <label><?php _e('Mailchimp Text','luxury-hotel');?></label>
          </li>
          <li class="to-field">
            <textarea rows="20" cols="40" name="cs_mailchimp_text[]" data-content-text="cs-shortcode-textarea"><?php echo esc_textarea($atts_content)?></textarea>
            <div class='left-info'><p><?php _e('Enter content here','luxury-hotel');?></p></div>
          </li>
        </ul>
        
      </div>
      <?php if(isset($_POST['shortcode_element']) && $_POST['shortcode_element'] == 'shortcode'){?>
      <ul class="form-elements insert-bg">
        <li class="to-field"> <a class="insert-btn cs-main-btn" onclick="javascript:Shortcode_tab_insert_editor('<?php echo esc_js(str_replace('cs_pb_','',$name));?>','<?php echo esc_js($name.$cs_counter);?>','<?php echo esc_js($filter_element);?>')" ><?php _e('Insert','luxury-hotel');?></a> </li>
      </ul>
      <div id="results-shortocde"></div>
      <?php } else {?>
      <ul class="form-elements noborder">
        <li class="to-label"></li>
        <li class="to-field">
          <input type="hidden" name="cs_orderby[]" value="mailchimp" />
          <input type="button" value="<?php _e('Save','luxury-hotel');?>" style="margin-right:10px;"  onclick="javascript:_removerlay(jQuery(this))" />
        </li>
      </ul>
      <?php }?>
    </div>
  </div>
</div>
<?php
        if ( $die <> 1 ) die();
    }
    add_action('wp_ajax_cs_pb_mailchimp', 'cs_pb_mailchimp');
}
?>