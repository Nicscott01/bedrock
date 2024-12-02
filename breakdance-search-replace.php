<?php

function breakdance_search_replace( $from, $to ) {

    printf( "Running Breakdance Search-Replace from: %s to: %s...\n\r", $from, $to );

    $affectedValues = \Breakdance\Setup\replaceUrls($from, $to);

    if (is_wp_error($affectedValues)) {
        /** @psalm-suppress PossiblyInvalidMethodCall */
       echo $affectedValues->get_error_message();
	echo "\r\n";
        return;
    }

    /** @var array{postMeta: string, preferences: boolean} $affectedValues */
    $affectedValues = $affectedValues;

    /** @psalm-suppress PossiblyInvalidArgument */
    echo (sprintf("%s rows affected.\n\r", $affectedValues['postMeta']));

    //  always regenerate fonts, even if no replace was done
    // a user may have used a tool like "Search And Replace" to update all their URLs
    // and then run this tool for fonts, or just to verify everything was replaced
    /** @psalm-suppress UndefinedFunction
     * @var array{error?: string} $fontFilesRegenerated
     */
    $fontFilesRegenerated = \Breakdance\CustomFonts\regenerateFontFiles();

    if (isset($fontFilesRegenerated['error'])) {
        echo "Error regenerating font files: " . $fontFilesRegenerated['error'] . "\n\r";
    }


}


if ( empty( $args ) ) {
    echo "Usage: wp eval-file breakdance-search-replace.php <from> <to>\n";
    exit(1);
}

$from = $args[1];
$to = $args[2];

breakdance_search_replace( $from, $to );