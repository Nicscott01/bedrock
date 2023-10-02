<?php

$userini = file_get_contents( __DIR__ . '/web/.user.ini' );

if ( empty( $userini) ) {

	echo "Could not find .user.ini file.";

	return;

}


//Do a search-replace for the url string

$needle = '/wp/wordfence-waf.php';
$replace = '/wordfence-waf.php';

$new_userini = str_replace( $needle, $replace,  $userini );


if ( file_put_contents( __DIR__ . '/web/.user.ini', $new_userini ) ) {

	echo 'New .user.ini written.';
	echo $new_userini;

} else {
	
	echo 'The file was not written.';
}