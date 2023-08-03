<?php
/*
 *
 * @Shortcode Name : Contact us
 * @retrun
 *
 */
if (!function_exists('cs_contactus_shortcode')) {

    function cs_contactus_shortcode($atts, $content = "") {
        $defaults = array(
            'column_size' => '1/1',
            'cs_contactus_section_title' => '',
            'cs_contactus_label' => '',
            'cs_contactus_view' => '',
            'cs_contactus_send' => '',
            'cs_success' => '',
            'cs_error' => '',
            'cs_contact_class' => ''
        );
        extract(shortcode_atts($defaults, $atts));
        $column_class = cs_custom_column_class($column_size);
        $cs_email_counter = rand(13242343, 324324990);
        $html = '';
        $class = '';
        $section_title = '';
        if ($cs_contactus_section_title && trim($cs_contactus_section_title) != '') {
            $section_title = '<div class="cs-section-title">
							   <h2>' . $cs_contactus_section_title . '</h2>
							  </div>';
        }

        if (trim($cs_success) && trim($cs_success) != '') {
            $success = $cs_success;
        } else {
            $success = 'Email has been sent Successfully.';
        }

        if (trim($cs_error) && trim($cs_error) != '') {
            $error = $cs_error;
        } else {
            $error = 'An error Occured, please try again later.';
        }

        if (trim($cs_contactus_view) == 'plain') {
            $view_class = 'cs-plan';
        } else {
            $view_class = '';
        }
        ?>
        <script type="text/javascript">
            function cs_contact_frm_submit(form_id) {

                var cs_mail_id = '<?php echo esc_js($cs_email_counter); ?>';
                if (form_id == cs_mail_id) {
                    var $ = jQuery;
                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('<img src="<?php echo esc_js(esc_url(get_template_directory_uri())); ?>/assets/images/ajax-loader.gif" alt="img" />');
                    $("#loading_div<?php echo esc_js($cs_email_counter); ?>").show();
                    $("#message<?php echo esc_js($cs_email_counter); ?>").html('');
                    var datastring = $('#frm<?php echo esc_js($cs_email_counter); ?>').serialize() + "&cs_contact_email=<?php echo esc_js($cs_contactus_send); ?>&cs_contact_succ_msg=<?php echo esc_js($success); ?>&cs_contact_error_msg=<?php echo esc_js($error); ?>&action=cs_contact_form_submit";
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo esc_js(esc_url(admin_url('admin-ajax.php'))); ?>',
                        data: datastring,
                        dataType: "json",
                        success: function (response) {

                            if (response.type == 'error') {
                                $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('');
                                $("#loading_div<?php echo esc_js($cs_email_counter); ?>").hide();
                                $("#message<?php echo esc_js($cs_email_counter); ?>").addClass('error_mess');
                                $("#message<?php echo esc_js($cs_email_counter); ?>").show();
                                $("#message<?php echo esc_js($cs_email_counter) ?>").html(response.message);
                            }
                            else if (response.type == 'success') {
                                $("#frm<?php echo esc_js($cs_email_counter); ?>").slideUp();
                                $("#loading_div<?php echo esc_js($cs_email_counter); ?>").html('');
                                $("#loading_div<?php echo esc_js($cs_email_counter); ?>").hide();
                                $("#message<?php echo esc_js($cs_email_counter); ?>").addClass('succ_mess');
                                $("#message<?php echo esc_js($cs_email_counter) ?>").show();
                                $("#message<?php echo esc_js($cs_email_counter); ?>").html(response.message);
                            }

                        }
                    }
                    );
                }
            }
        </script>
        <?php
        $html .= '<div class="cs-contact-form">';
        $html .= '<div id="respond" class="comment-respond">';
        $html .= '<form  name="frm' . absint($cs_email_counter) . '" id="frm' . absint($cs_email_counter) . '" action="javascript:cs_form_validation(' . absint($cs_email_counter) . ', \'\')"  class="comment-form">';
        $html .= '<p>';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label class="icon-usr">' . __('Enter Your Name', 'luxury-hotel') . '</label>';
        }
        $html .= '<input type="text" name="contact_name" placeholder="' . __('Enter Name', 'luxury-hotel') . '"  class="' . sanitize_html_class($class) . ' ' . sanitize_html_class($view_class) . '"></p>';
        $html .= '<p>';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label class="icon-envlp">' . __('Enter Your Email Address', 'luxury-hotel') . '</label>';
        }
        $html .= '<input type="text" name="contact_email" placeholder="' . __('Email', 'luxury-hotel') . '"  class="' . sanitize_html_class($class) . ' ' . sanitize_html_class($view_class) . '" required></p>';
        $html .= '<p>';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label class="icon-globe">' . __('Enter Subject', 'luxury-hotel') . '</label>';
        }
        $html .= '<input type="text" name="subject" placeholder="' . __('Subject', 'luxury-hotel') . '"  class="' . sanitize_html_class($class) . ' ' . $view_class . '" required></p>';
        $html .= '<p class="comment-form-comment">';
        if (isset($cs_contactus_label) && $cs_contactus_label == 'on') {

            $html .= '<label>' . __('Message', 'luxury-hotel') . '</label>';
        }
        $html .= '<label class="icon-qute"><textarea placeholder="' . __('Message', 'luxury-hotel') . '"  id="comment_mes" name="contact_msg" class="commenttextarea ' . sanitize_html_class($class) . ' ' . $view_class . '" rows="4" cols="39"></textarea></label></p>';
        
        if ($cs_contactus_view == 'plain') {
            $html .= '<p class="form-submit">';
            $html .= '<input type="submit" name="submit" id="submit_btn' . $cs_email_counter . '" class="form-style" value="' . __('Submit Now', 'luxury-hotel') . '">'
                    . '</p>';
        } else {
            $html .= '<p class="form-submit"><input type="submit" name="submit" id="submit_btn' . absint($cs_email_counter) . '" class="form-style form-style-right" value="' . __('Submit Now', 'luxury-hotel') . '"></p>';
        }
        $html .= '</form>';
        $html .= '<div id="loading_div' . $cs_email_counter . '"></div>';
        $html .= '<div id="message' . $cs_email_counter . '"  style="display:none;"></div>';
        $html .= '</div>';
        $html .= '</div>';
        $cs_contact_class_id = '';
        if ($cs_contact_class <> '') {
            $cs_contact_class_id = ' id="' . $cs_contact_class . '"';
        }
        $fancy_view_class = '';
        if (trim($cs_contactus_view) != 'plain') {
            $fancy_view_class = 'cs-classic-form cs_form_styling blog_form';
        }
        return '<div class="' . $column_class . ' ' . $fancy_view_class . '"' . $cs_contact_class_id . '>' . $section_title . $html . '</div>';
    }
    if(function_exists('cs_shortcode_add')){
        cs_shortcode_add(CS_SC_CONTACTUS, 'cs_contactus_shortcode');

    }
}