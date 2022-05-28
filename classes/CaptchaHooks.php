<?php
/**
 * Elgg captcha plugin
 *
 * @package ElggCaptcha
 */

class CaptchaHooks {
	/**
	* Listen to the action plugin hook and check the captcha.
	*
	* @param \Elgg\Hook $hook Hook
	* @return array
	*/
	public static function captcha_verify_action_hook(\Elgg\Hook $hook) {
		elgg_make_sticky_form($hook->getType());

		$token = get_input('captcha_token');
		$input = get_input('captcha_input');

		if (($token) && (\CaptchaFunctions::captcha_verify_captcha($input, $token))) {
			return true;
		}

		elgg_register_error_message(elgg_echo('captcha:captchafail'));

		// forward to referrer or else action code sends to front page
		elgg_redirect_response(REFERER);

		return false;
	}

	/**
	* This function returns an array of actions the captcha will expect a captcha for, other plugins may
	* add their own to this list thereby extending the use.
	*
	* @param \Elgg\Hook $hook Hook
	* @return array
	*/
	public static function captcha_actionlist_hook(\Elgg\Hook $hook) {
		$returnvalue = $hook->getValue();

		if (!is_array($returnvalue)) {
			$returnvalue = [];
		}

		$returnvalue[] = 'register';
		$returnvalue[] = 'user/requestnewpassword';

		return $returnvalue;
	}
}