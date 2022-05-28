<?php
/**
 * Elgg captcha plugin
 *
 * @package ElggCaptcha
 */

class CaptchaFunctions {
	/**
	* Generate a token to act as a seed value for the captcha algorithm.
	*/
	public static function captcha_generate_token() {
		return md5(elgg()->csrf->generateActionToken(time()) . rand()); // Use action token plus some random for uniqueness
	}

	/**
	* Generate a captcha based on the given seed value and length.
	*
	* @param string $seed_token
	* @return string
	*/
	public static function captcha_generate_captcha($seed_token) {
		/**
		* We generate a token out of the random seed value + some session data,
		* this means that solving via pr0n site or indian cube farm becomes
		* significantly more tricky (we hope).
		*
		* We also add the site secret, which is unavailable to the client and so should
		* make it very very hard to guess values before hand.
		*
		*/
		return strtolower(substr(md5(elgg()->csrf->generateActionToken(0) . $seed_token), 0, CAPTCHA_LENGTH));
	}

	/**
	* Verify a captcha based on the input value entered by the user and the seed token passed.
	*
	* @param string $input_value
	* @param string $seed_token
	* @return bool
	*/
	public static function captcha_verify_captcha($input_value, $seed_token) {
		if (strcasecmp($input_value, self::captcha_generate_captcha($seed_token)) == 0) {
			return true;
		}

		return false;
	}
}