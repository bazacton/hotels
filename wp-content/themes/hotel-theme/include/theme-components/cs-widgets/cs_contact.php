<?php

/**
 * @Contact Form Widget Class
 *
 *
 */
if ( ! class_exists( 'cs_contact_msg' ) ) { 
	class cs_contact_msg extends WP_Widget {	
	
	/**
	 * @init Contact Module
	 *
	 *
	 */
 
	 
		public function __construct() {
		
		parent::__construct(
			'cs_contact_msg', // Base ID
			__( 'CS : Contact Form', 'luxury-hotel' ), // Name
			array( 'classname' => 'widget_form', 'description' =>__('Select contact form to show in widget', 'luxury-hotel'), ) // Args
		);
	}
	
	 /**
	 * @Contact html form
	 *
	 *
	 */
	 function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '' ));
		$title = $instance['title'];
		$contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
		$contact_succ_msg = isset($instance['contact_succ_msg']) ? esc_attr($instance['contact_succ_msg']) : '';
		?>
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>"> <?php _e('Title:', 'luxury-hotel') ?>
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('title')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
          </label>
        </p>
        
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('contact_email')); ?>"> <?php _e('Contact Email:', 'luxury-hotel') ?>
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('contact_email')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('contact_email')); ?>" type="text" value="<?php echo sanitize_email($contact_email); ?>" />
          </label>
        </p>
        
        <p>
          <label for="<?php echo cs_allow_special_char($this->get_field_id('contact_succ_msg')); ?>"> <?php _e('Success Message:', 'luxury-hotel') ?>
            <input class="upcoming" id="<?php echo cs_allow_special_char($this->get_field_id('contact_succ_msg')); ?>" size="40" name="<?php echo cs_allow_special_char($this->get_field_name('contact_succ_msg')); ?>" type="text" value="<?php echo esc_attr($contact_succ_msg); ?>" />
          </label>
        </p>
        

<?php
 		}
		
		/**
		 * @Contact Update form data
		 *
		 *
		 */
		 function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			$instance['contact_email'] = $new_instance['contact_email'];
			$instance['contact_succ_msg'] = $new_instance['contact_succ_msg'];
			
   			return $instance;
		}
		
		/**
		 * @Display Contact widget
		 *
		 *
		 */
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			global $wpdb, $post;
			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
			$contact_email = isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : '';
			$contact_succ_msg = isset($instance['contact_succ_msg']) ? esc_attr($instance['contact_succ_msg']) : '';
			
			// WIDGET display CODE Start
			echo cs_allow_special_char($before_widget);
			if (strlen($title) <> 1 || strlen($title) <> 0) {
				echo cs_allow_special_char($before_title . $title . $after_title);
			}
			
            $cs_email_counter = rand(1343, 9999); 
			$error =__('An error Occured, please try again later.', 'luxury-hotel');
			?>
			
            <form data-sucmsg="<?php echo esc_html($contact_succ_msg);?>" data-errmsg="<?php echo esc_html($error);?>" data-ajaxurl="<?php echo esc_url(admin_url('admin-ajax.php')) ?>" id="frm<?php echo absint($cs_email_counter);?>" name="frm<?php echo absint($cs_email_counter) ?>" method="post" action="javascript:cs_form_validation(<?php echo absint($cs_email_counter) ?>, 'widget')">
                <ul>
                    <li>
                      <input type="text" placeholder="<?php _e('Name', 'luxury-hotel') ?>" name="contact_name" value="" >
                    </li>
                    <li>
                      <input type="text" placeholder="<?php _e('Email', 'luxury-hotel') ?>" name="contact_email" value="">
                    </li>
                    <li>
                      <input type="text" placeholder="<?php _e('Subject', 'luxury-hotel') ?>" name="subject" value="">
                    </li>
                    <li>
                      <textarea placeholder="<?php _e('Message', 'luxury-hotel') ?>" name="contact_msg"></textarea>
                    </li>
                    <li>
                      <input type="submit" value="<?php _e('Send', 'luxury-hotel') ?>" name="submit" id="submit_btn<?php echo absint($cs_email_counter)?>">
                    </li>
                </ul>
            </form>
            <span id="loading_div<?php echo absint($cs_email_counter)?>"></span>
            <div id="message<?php echo absint($cs_email_counter);?>" style="display:none;"></div>
			<?php
			
			echo cs_allow_special_char($after_widget); // WIDGET display CODE End
		}
	}
}
if (function_exists('cs_widget_register')) {
    cs_widget_register('cs_contact_msg');
}
?>