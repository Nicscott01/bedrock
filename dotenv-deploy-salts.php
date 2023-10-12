<?php
/**
 *  Write the salts to .env file
 *  Overwrite if they already exist.
 *  Must have https://github.com/RobDWaller/wordpress-salts-generator/ installed
 * 
 * 
 */

include_once( __DIR__ . '/vendor/autoload.php' );

$salts = new WPSalts\Salts;

$salts_string = $salts->dotEnv();

$dotenv = '';
//Look for existing .env

$dotenv_path = __DIR__ . '/.env';

if ( file_exists( $dotenv_path ) ) {

    $dotenv = file_get_contents( $dotenv_path );

}

//Look for existing salts in the file
$salts_regex_search = [
    'AUTH_KEY',
    'SECURE_AUTH_KEY',
    'LOGGED_IN_KEY',
    'NONCE_KEY',
    'AUTH_SALT',
    'SECURE_AUTH_SALT',
    'LOGGED_IN_SALT',
    'NONCE_SALT',
];

$c = 0;

foreach( $salts_regex_search as $salt ) {

    $re = sprintf( "/^%s=.+\n/m", $salt );

    $sub = '';

    //Leave a placeholder to replace after we clear out the rest of the salts
    if ( $c == 0 ) {

        $sub = "#{salts}\n";
    }


    $dotenv = preg_replace( $re, $sub, $dotenv );

    $c++;
}

$dotenv = str_replace( '#{salts}', $salts_string, $dotenv );


if ( file_put_contents( $dotenv_path, $dotenv ) ) {

    echo "\nThe new salts have been written.\n";

} else {

    echo "\nThere was an error writing the salts.\n";
}