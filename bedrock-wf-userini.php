<?php




//Check if the wordfence file exists in the wo directory
//echo __DIR__ . 'web/wp/wordfence-waf.php';

if ( file_exists( __DIR__ . '/web/wp/wordfence-waf.php') ) {

	$wordfence_waf = file_get_contents( __DIR__ . '/web/wp/wordfence-waf.php');

	$wordfence_waf = str_replace( '../app', 'app', $wordfence_waf );

	if( file_put_contents( __DIR__ . '/web/wordfence-waf.php', $wordfence_waf ) ){
		echo $wordfence_waf;
		echo "The Wordfence WAF was written.\n";
	} else {

		echo "The Wordfence WAF was not written.\n";
	
	}
} else {
 
echo "There was an error.\n";

}




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


