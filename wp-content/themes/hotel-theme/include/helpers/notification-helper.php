<?php/*** @Start notification helper class* @return*/if( !class_exists( 'CS_Notification_Helper' ) ) {	class CS_Notification_Helper{				public $message;				public function __construct() {			// Do Something here..		}				public function success( $message = 'No recored found' ) {			global $post;						$output	 = '';			$output	.= '<div class="col-md-12 cs-succ_mess"><p>';			$output	.= $message;			$output	.= '</p></div>';						echo force_balance_tags (cs_data_validation($output ));		}				public function error( $message='No recored found' ) {			global $post;		   		   $output	 = '';		   $output	.= '<div class="col-md-12 cs-error"><p>';		   $output	.= $message;		   $output	.= '</p></div>';		  		   echo force_balance_tags ( cs_data_validation($output ));				}	  		public function warning( $message='No recored found' ) {		  global $post;						  $output	 = '';		  $output	.= '<div class="col-md-12 cs-warning"><p>';		  $output	.= $message;		  $output	.= '</p></div>';		 		 echo force_balance_tags (cs_data_validation( $output ));		}				public function informations($message='No recored found') { 		  global $post;		 		  $output	 = '';		  $output	.= '<div class="col-md-12 cs-informations"><p>';		  $output	.= $message;		  $output	.= '</p></div>';		 		 echo force_balance_tags ( cs_data_validation($output ));				}	}}$cs_notification = new CS_Notification_Helper();global $cs_notification;?>