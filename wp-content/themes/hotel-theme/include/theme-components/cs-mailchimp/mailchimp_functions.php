<?php


/** 
 * @Mailchimp List
 */
if ( ! function_exists( 'cs_mailchimp_list' ) ) {
	function cs_mailchimp_list($apikey){
		global $cs_theme_options;
		$MailChimp = new MailChimp($apikey);
		$mailchimp_list = $MailChimp->call('lists/list');
		return $mailchimp_list;
	}
}

/** 
 * @custom mail chimp form
 */
/*if ( ! function_exists( 'cs_custom_mailchimp' ) ) {
	function cs_custom_mailchimp($description = ''){
		global $cs_theme_options;
		$counter = rand(423,23343490);
		
		if(isset($description) and $description <> ''){
			echo '<p>'.$description.'</p>';
        }*/
		
		if ( ! function_exists( 'cs_custom_mailchimp' ) ) {
	function cs_custom_mailchimp(){
		global $cs_theme_options,$counter;
				$counter++;

		?>
        <form action="javascript:cs_mailchimp_submit('<?php echo get_template_directory_uri()?>','<?php echo esc_js($counter); ?>','<?php echo admin_url('admin-ajax.php'); ?>')" id="mcform_<?php echo intval($counter);?>" class="cs-mailchimp" method="post">
        <div class="clear"></div>
          <div id="newsletter_mess_<?php echo intval($counter);?>" style="display:none" class="cs-error-msg"></div>
          <fieldset>
              <i class="icon-envelope4"></i>
            <input id="cs_list_id" type="hidden" name="cs_list_id" value="<?php if(isset($cs_theme_options['cs_mailchimp_list'])){ echo esc_attr($cs_theme_options['cs_mailchimp_list']); }?>" />
            <label>
              <input id="mc_email" type="text" name="mc_email" value="<?php _e('Your Email','luxury-hotel'); ?>" onblur="if(this.value == '') { this.value ='<?php _e('Your Email','luxury-hotel'); ?>'; }" onfocus="if(this.value =='<?php _e('Your Email','luxury-hotel'); ?>') { this.value = ''; }"  />
            </label>
             <label class="submit-button"> 
               <input type="submit" id="btn_newsletter_<?php echo intval($counter);?>" class="cs-submit" name="submit" value="<?php _e('Join','luxury-hotel'); ?>"  />
             </label>
            <div id="process_<?php echo intval($counter);?>" class="cs-show-msg"></div>           
          </fieldset>          
        </form>
        <?php        
	}
}