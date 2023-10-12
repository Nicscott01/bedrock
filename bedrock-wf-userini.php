<?php
/**
 * 	create/update user.ini file for wordfence
 *  MUST RUN THIS SCRIPT FROM THE ROOT OF THE PROJECT (one up from /web)
 * 
 * 	; Wordfence WAF
 *	auto_prepend_file = '~/files/web/wordfence-waf.php'
 * 	: END Wordfence WAF
 * 
 */


$user_ini_path = __DIR__ . '/web/.user.ini';
$waf_path = __DIR__ . '/web/wordfence-waf.php';


//Write the Waf file
/**
 * // Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.
 * 
 * if (file_exists(__DIR__.'/app/plugins/wordfence/waf/bootstrap.php')) {
 *	define("WFWAF_LOG_PATH", __DIR__.'/app/wflogs/');
 *	include_once __DIR__.'/app/plugins/wordfence/waf/bootstrap.php';
 *  }
 */

 ob_start();
?>
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists(__DIR__.'/app/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", __DIR__.'/app/wflogs/');
	include_once __DIR__.'/app/plugins/wordfence/waf/bootstrap.php';
}
<?php

$waf_file_contents = ob_get_clean();

if ( file_put_contents( $waf_path, $waf_file_contents ) ) {

	echo 'New wordfence-waf.php written.';

} else {
	
	echo 'The waf file was not written.';
}


//Write the  user INI
$new_userini = "
; Wordfence WAF
auto_prepend_file = '$waf_path'
: END Wordfence WAF
";

echo $new_userini;

if ( file_put_contents( $user_ini_path, $new_userini ) ) {

	echo 'New .user.ini written.';

} else {
	
	echo 'The file was not written.';
}