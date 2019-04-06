<?php
/**
 * Elgg captcha plugin
 *
 * @package ElggCaptcha
 */

// Number of background images
define('CAPTCHA_NUM_BG', 5);
// Default length
define('CAPTCHA_LENGTH', 5);

elgg_register_event_handler('init', 'system', 'captcha_init');

function captcha_init() {
	// Extend CSS
	elgg_extend_view('elgg.css', 'captcha/captcha.css');

	// Register a function that provides some default override actions
	elgg_register_plugin_hook_handler('actionlist', 'captcha', 'captcha_actionlist_hook');

	// Register actions to intercept
	$actions = [];
	$actions = elgg_trigger_plugin_hook('actionlist', 'captcha', null, $actions);

	if (($actions) && (is_array($actions))) {
		foreach ($actions as $action) {
			elgg_register_plugin_hook_handler('action', $action, 'captcha_verify_action_hook');
		}
	}
}

/**
 * Generate a token to act as a seed value for the captcha algorithm.
 */
function captcha_generate_token() {
	return md5(generate_action_token(time()) . rand()); // Use action token plus some random for uniqueness
}

/**
 * Generate a captcha based on the given seed value and length.
 *
 * @param string $seed_token
 * @return string
 */
function captcha_generate_captcha($seed_token) {
	/**
	 * We generate a token out of the random seed value + some session data,
	 * this means that solving via pr0n site or indian cube farm becomes
	 * significantly more tricky (we hope).
	 *
	 * We also add the site secret, which is unavailable to the client and so should
	 * make it very very hard to guess values before hand.
	 *
	 */
	return strtolower(substr(md5(generate_action_token(0) . $seed_token), 0, CAPTCHA_LENGTH));
}

/**
 * Verify a captcha based on the input value entered by the user and the seed token passed.
 *
 * @param string $input_value
 * @param string $seed_token
 * @return bool
 */
function captcha_verify_captcha($input_value, $seed_token) {
	if (strcasecmp($input_value, captcha_generate_captcha($seed_token)) == 0) {
		return true;
	}

	return false;
}

/**
 * Listen to the action plugin hook and check the captcha.
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function captcha_verify_action_hook(\Elgg\Hook $hook) {
	elgg_make_sticky_form($hook->getType());

	$token = get_input('captcha_token');
	$input = get_input('captcha_input');

	if (($token) && (captcha_verify_captcha($input, $token))) {
		return true;
	}

	register_error(elgg_echo('captcha:captchafail'));

	// forward to referrer or else action code sends to front page
	forward(REFERER);

	return false;
}

/**
 * This function returns an array of actions the captcha will expect a captcha for, other plugins may
 * add their own to this list thereby extending the use.
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function captcha_actionlist_hook(\Elgg\Hook $hook) {
	$returnvalue = $hook->getValue();

	if (!is_array($returnvalue)) {
		$returnvalue = [];
	}

	$returnvalue[] = 'register';
	$returnvalue[] = 'user/requestnewpassword';

	return $returnvalue;
}
