<?php

require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/hooks.php');

return [
	'bootstrap' => \CaptchaBootstrap::class,
	'routes' => [
		'captcha' => [
			'path' => '/captcha/{captcha_token?}',
			'resource' => 'captcha/captcha',
			'walled' => false,
		],
	],
	'view_extensions' => [
		'css/elgg' => [
			'captcha/captcha.css' => [],
		],
	],
];
