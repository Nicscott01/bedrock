<?php
/**
 *  Load global dot file with keys on your server
 *  
 * 
 * 
 */

// require_once( __DIR__ . '/vendor/autoload.php' );
// require_once( __DIR__ . '/config/application.php' );
 




//Get the .env 
if ( file_exists( '.env' ) ) {
    
    $dotenv = file_get_contents( '.env' );

} else {

    $dotenv = '';

}




$global_keys_file_path =  '/etc/creare/keys.json';


if ( file_exists( $global_keys_file_path ) ) {
    
    $global_keys_file = trim( file_get_contents( $global_keys_file_path ) );

} else {

    echo 'Cannot find global keys file.';
    return;
}



if ( !empty( $global_keys_file ) ) {

    $global_keys = json_decode( $global_keys_file );

    $dotenv .= "\n# Global keys from server. Autodeployed by script.\n";

    $key_strings = [];

    foreach( $global_keys as $key => $val ) {


        $key_strings[] = sprintf( '%s=\'%s\'', strtoupper($key), $val );

    }
    
    $dotenv .= implode( "\n", $key_strings );
    

    if ( file_put_contents( '.env', $dotenv ) ) {

        echo "\nNew .env file written.\n";

    } else {

        echo "\nThere was an error writing the following contents:";
        echo $dotenv . "\n";
    }


} else {

    echo "\nNo global keys file found at " . $global_keys_file_path . "\n";
}