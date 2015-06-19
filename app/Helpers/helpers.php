<?php

/**
 * Check if a string ends with a specific character.
 *
 * @param  	string $haystack 	The string to check
 * @param  	string $needle   	The character that $haystack should end with
 * @return 	boolean
 */
function stringEndsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}