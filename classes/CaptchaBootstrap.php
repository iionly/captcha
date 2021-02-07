<?php

use Elgg\DefaultPluginBootstrap;

class CaptchaBootstrap extends DefaultPluginBootstrap {

	public function init() {
		// Register a function that provides some default override actions
		elgg_register_plugin_hook_handler('actionlist', 'captcha', 'captcha_actionlist_hook');

		// Register actions to intercept
		$actions = [];
		$actions = elgg_trigger_plugin_hook('actionlist', 'captcha', null, $actions);

		if (($actions) && (is_array($actions))) {
			foreach ($actions as $action) {
				elgg_register_plugin_hook_handler('action:validate', $action, 'captcha_verify_action_hook');
			}
		}
	}
}
