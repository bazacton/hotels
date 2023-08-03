<?php
/**
 *
 *@Search page Template
 *
 */
	global $post,$cs_notification,$cs_plugin_options;
	get_header();
	
	$post_id	= $_GET['invoice'];
	$currency_sign	= isset($cs_plugin_options['currency_sign']) && $cs_plugin_options['currency_sign'] !='' ? $cs_plugin_options['currency_sign'] : '$';
	$session_id	= '';
	if( isset( $_SESSION['cs_session_booked_id'] )  && $_SESSION['cs_session_booked_id'] !='' ) {
		$session_id	= $_SESSION['cs_session_booked_id'];
	}
	$search_link    = add_query_arg( array('action'=>'booking' ),  esc_url( get_permalink( $cs_page_id ) ) );
	

?>

<div class="container">
  <div class="row">
    <div class="section-fullwidth reservation-editor">
      <?php if( $session_id == $post_id ) {?>
      <div class="page-sidebar">
        <div class="reservation-search">
          <div class="reservation-box">
            <div class="search-inner">
              <div class="rooms-list">
                <div class="cs-bank-transfer">
                  <h2><?php _e('Order detail','booking');?></h2>
                  <ul class="list-group">
                    <li class="list-group-item"><span class="badge">#<?php echo get_post_meta( $post_id , 'cs_booking_id', true );?></span><?php _e('Booking Id','booking');?></li>
                    <?php
						$cs_booking  			= get_post_meta($post_id, 'cs_booked_room_data', false);
						$cs_booking_extras  	= get_post_meta($post_id, 'cs_booking_extras', false);
						$cs_bkng_gross_total		= get_post_meta((int)$post_id,'cs_bkng_gross_total',true);
						$vat_percentage		= get_post_meta((int)$post_id,'cs_bkng_vat_percentage',true);
						$cs_bkng_tax			= get_post_meta((int)$post_id,'cs_bkng_tax',true);
						$grand_total	= get_post_meta( $post_id , 'cs_bkng_grand_total', true );
						$cs_bkng_advance	= get_post_meta( $post_id , 'cs_bkng_advance', true );
						$cs_booking				= isset($cs_booking[0]) ? $cs_booking[0] : '';
						$cs_booking_extras		= isset($cs_booking_extras[0]) ? $cs_booking_extras[0] : '';
						if( isset( $cs_booking ) && is_array( $cs_booking ) && !empty( $cs_booking ) ) {
							$counter	= 0;
							foreach( $cs_booking as $key => $data ){
								$counter++;
						?>
						<li class="room-1">
						  <div class="text">
							<h6><?php echo get_the_title( $data['capacity'] ).' #'.$data['room_id'];?></h6>
							<p><?php _e('Guests: ','booking');?> <?php echo $data['adults'];?> <?php _e(' Adult(s), ','booking');?><?php echo $data['adults'];?> <?php _e(' Child(s)','booking');?></p>
						  </div>
						  <small><?php echo absint( $counter );?></small>
						</li>
                      <?php }}?>
                    <li class="list-group-item"><span class="badge"><?php echo $currency_sign.number_format( $cs_bkng_gross_total,2 );?></span><?php _e('Gross Total','booking');?></li>
                    <li class="list-group-item"><span class="badge"><?php echo $currency_sign.number_format( $cs_bkng_tax,2 );?></span><?php _e('VAT','booking');?>(<?php echo $vat_percentage;?>%)</li>
                    <li class="list-group-item"><span class="badge"><?php echo $currency_sign.number_format( $grand_total,2 );?></span><?php _e('Grand Total','booking');?></li>
                    <li class="list-group-item"><span class="badge"><?php echo $currency_sign.( number_format( ( $grand_total - $cs_bkng_advance ), 2 ) );?></span><?php _e('Balance','booking');?></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="page-content">
        <section class="page-section">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="cs-reservation-tabs">
                  <ul class="tabs-nav">
                 	  <li>
                       <i class="icon-plus8"></i>
                       <a href="javascript:;" data-id="tab1"><span><?php _e('Step 1','booking');?></span><?php _e('Add Essentials','booking');?></a>
                      </li>
                      <li>
                       <i class="icon-spotify3"></i>
                      <a href="javascript:;" data-id="tab2"><span><?php _e('Step 2','booking');?></span><?php _e('Reservation and Payments','booking');?></a>
                      </li>
                      <li  class="active">
                       <i class="icon-checkmark6"></i>
                       <a href="javascript:;" data-id="tab3"><span><?php _e('Step 3','booking');?></span><?php _e('Confirmation','booking');?></a>
                      </li>
                  </ul>
               </div>
               <div class="tabs-content">
                    <div class="tabs" id="tab4">
                      <div class="cs-confirmation">
                       <figure><i class="icon-check"></i></figure>
                       <div class="text">
                        <h5><?php _e('Thank you!!!','booking');?></h5>
                        <?php 
							if( $cs_plugin_options['cs_thank_msg'] && $cs_plugin_options['cs_thank_msg'] !='' ) {
                            	echo '<p>'. __($cs_plugin_options['cs_thank_msg'],'booking').'</p>';
							}
						?>
                        <p>
							<?php 
							  if( $cs_plugin_options['cs_thank_title'] && $cs_plugin_options['cs_thank_title'] !='' ) {
								echo '<span>'. __($cs_plugin_options['cs_thank_title'],'booking').'</span>';
							  }
							  ?>
                          </p>
                        <ul>
                        <?php
							if( $cs_plugin_options['cs_confir_phone'] && $cs_plugin_options['cs_confir_phone'] !='' ) {
								echo '<li><span>'. __('Phone:','booking').'</span>'. __($cs_plugin_options['cs_confir_phone'],'booking').'</li>';
							}
							
							if( $cs_plugin_options['cs_confir_fax'] && $cs_plugin_options['cs_confir_fax'] !='' ) {
							   echo '<li><span>'. __('Fax:','booking').'</span>'. __($cs_plugin_options['cs_confir_fax'],'booking').'</li>';
							}
							
							if( $cs_plugin_options['cs_confir_email'] && $cs_plugin_options['cs_confir_email'] !='' ) {
								echo '<li><span>'. __('Email:','booking').'</span><a href="mailto:'.$cs_plugin_options['cs_confir_email'].'&subject=hello">'. __($cs_plugin_options['cs_confir_email'],'booking').'</a></li>';
							}
						?>
                        </ul>
                        <a class="go-back" href="<?php echo esc_url(home_url());?>"><?php _e('Back to Home page','booking');?></a> 
                       </div>
                      </div>
                    </div>
                  </div> 
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php } else{
			$cs_notification->error(__('Oops! direct access is not allowed.','booking'));
	  }?>
    </div>
  </div>
</div>
<?php get_footer();?>
