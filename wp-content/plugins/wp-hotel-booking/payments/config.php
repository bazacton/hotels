<?php
/**
 *  File Type: Payment Configuration
 *
 */
 
 $dir = wp_hotel_booking::plugin_dir().'/payments/gateways/';
 $dh = opendir($dir);
 if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			if( $ext == 'php' ) {
				include(wp_hotel_booking::plugin_dir().'/payments/gateways/'.$file);
			}
		}
		closedir($dh);
	}
 }
 
?>