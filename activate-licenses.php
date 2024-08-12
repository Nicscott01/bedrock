<?php

use Breakdance\Licensing\LicenseKeyManager;


/**
 * Activate EWWWIO
 * 
 */

 if ( defined( 'EWWWIO_KEY' ) ) {

    update_option( 'ewww_image_optimizer_cloud_key', EWWWIO_KEY );

 }



/**
 *  Activate Breakdance
 * 
 */

if ( defined( 'BREAKDANCE_KEY' ) ) {

    $license_key_manager = LicenseKeyManager::getInstance();

    $license_key_manager->refetchStoredLicenseKeyValidityInfo();

    $license_key_manager->changeLicenseKey( BREAKDANCE_KEY );

}


/**
 *    Activate Breakdance AI
 * 
 */

if ( defined( 'BREAKDANCE_AI_KEY') ) {

   Breakdance\AI\setLicenseKey(BREAKDANCE_AI_KEY );

}


/**
 *    Set BD Keys
 * 
 */

if ( defined( 'GOOGLE_MAPS_KEY' ) ) {

   Breakdance\APIKeys\setKey( BREAKDANCE_GOOGLE_MAPS_API_KEY_NAME, GOOGLE_MAPS_KEY );

}
if ( defined( 'OPEN_AI_KEY' ) ) {

   Breakdance\APIKeys\setKey( 'openai', OPEN_AI_KEY );

}


/**
 *  Default Settings for WPSES
 *  
 */

 $ses_settings = [
    'send-via-ses' => true,
    'default-email' => sanitize_email( get_bloginfo( 'name' ). 'website@crearewebsites.com' ),
    'default-email-name' => get_bloginfo( 'name' ) . ' Website',
    'completed-setup' => true
 ];

 update_option( 'wposes_settings', $ses_settings );



echo "\nCompleted activations\n";