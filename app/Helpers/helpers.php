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

/**
 * Determins the amount of momentum that should be added.
 *
 * @param  	integer
 * @return 	float
 */
function calculateMomentum($difference)
{
	switch ($difference) {
		case ($difference < 60):
			$momentumAdd = 1;
			break;

		case ($difference > 59 && $difference < 120):
			$momentumAdd = 0.9;
			break;

		case ($difference > 119 && $difference < 180):
			$momentumAdd = 0.75;
			break;

		case ($difference > 179 && $difference < 300):
			$momentumAdd = 0.6;
			break;

		case ($difference > 299 && $difference < 600):
			$momentumAdd = 0.5;
			break;

		case ($difference > 599 && $difference < 900):
			$momentumAdd = 0.35;
			break;

		case ($difference > 899 && $difference < 1800):
			$momentumAdd = 0.25;
			break;

		case ($difference > 1799 && $difference < 3600):
			$momentumAdd = 0.1;
			break;

		default:
			$momentumAdd = 0.05;
			break;
	}

	return $momentumAdd * 0.46;
}