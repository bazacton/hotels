<?php
/**
 * The template for displaying Comment form
 */ 
    global $cs_theme_options;
    if ( comments_open() ) {
        if ( post_password_required() ) return;
    }   
    if ( have_comments() ) : 
	?>
    <div class="col-md-12">
        <div id="cs-comments">		
            <div class="cs-section-title"><h2><?php echo comments_number( '0 Comments', '1 Comment', '% Comments' ); ?></h2></div>
            <ul>
                <?php wp_list_comments( array( 'callback' => 'cs_comment' ) );    ?>
            </ul>
            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
                <div class="navigation">
                    <div class="nav-previous"><span class="meta-nav">&larr;</span><?php previous_comments_link( __( 'Older Comments', 'luxury-hotel') ); ?></div>
                    <div class="nav-next"><span class="meta-nav">&rarr;</span><?php next_comments_link( __( 'Newer Comments', 'luxury-hotel') ); ?></div>
                </div> <!-- .navigation -->
            <?php endif; // check for comment navigation ?>        
            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
                <div class="navigation">
                    <div class="nav-previous"><span class="meta-nav">&larr;</span><?php previous_comments_link( __( 'Older Comments', 'luxury-hotel') ); ?></div>
                    <div class="nav-next"><span class="meta-nav">&rarr;</span><?php next_comments_link( __( 'Newer Comments', 'luxury-hotel') ); ?></div>
                </div><!-- .navigation -->
            <?php endif; ?>
        </div>
    </div>
	<?php
    endif;
	if ( comments_open() ) {
	?>
	<div class="col-md-12">
        <div id="respond-comment" class="cs-classic-form cs_form_styling blog_form">
            <?php 
            global $post_id;
            $you_may_use = __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'luxury-hotel');
            $must_login ='<a href="%s">logged in</a>'.__( 'You must be  to post a comment.', 'luxury-hotel');
            $logged_in_as =__('Logged in as ', 'luxury-hotel').'<a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account"></a>'.__(' Log out', 'luxury-hotel');
            $required_fields_mark = ' ' .__('Required fields are marked %s', 'luxury-hotel');
            $required_text = sprintf($required_fields_mark , '<span class="required">*</span>' );
            $defaults = array( 'fields' => apply_filters( 'comment_form_default_fields', 
                array(
                    'notes' => '',                
                    'author' => '<p class="comment-form-author">
                    <input placeholder="Enter Name" id="author"  name="author" class="nameinput" type="text" value=""' .
                    esc_attr( $commenter['comment_author'] ) . ' tabindex="1">' .
                    '</p><!-- #form-section-author .form-section -->',                
                    'email'  => '<p class="comment-form-email">' .
                    '<input id="email" name="email" placeholder="Email Address" class="emailinput" type="text"  value=""' . 
                    esc_attr(  $commenter['comment_author_email'] ) . ' size="30" tabindex="2">' .
                    '</p><!-- #form-section-email .form-section -->',                
                    'url'    => '<p class="comment-form-website">' .
                    '<input id="url" name="url" type="text" placeholder="Website" class="websiteinput"  value="" size="30" tabindex="3">' .
                    '</p>' ) ),                
                    'comment_field' => '<p class="comment-form-comment">
                        <textarea id="comment_mes" placeholder="Enter Message" name="comment"  class="commenttextarea" rows="55" cols="15"></textarea>' .
                    '</p>',                
                    'must_log_in' => '<span>' .  sprintf( $must_login,    wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</span>',
                    'logged_in_as' => '<span>' . sprintf( $logged_in_as, admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ).'</span>',
                    'comment_notes_before' => '',
                    'comment_notes_after' =>  '',
                    'class_form' => 'comment-form contact-form',
                    'id_form' => 'form-style',
                    'class_submit' => 'submit-btn cs-bgcolor',
                    'id_submit' => 'cs-bg-color',
                    'title_reply' => __( 'Leave us a comment', 'luxury-hotel' ),
                    'title_reply_to' =>'<h2 class="cs-section-title">'.__( 'Leave us a comment', 'luxury-hotel' ).'</h2>',
                    'cancel_reply_link' => __( 'Cancel reply', 'luxury-hotel' ),
                    'label_submit' => __( 'Submit', 'luxury-hotel' ),);
                    comment_form($defaults, $post_id); 
                ?>
		</div>
	</div> 
    <?php
	}
