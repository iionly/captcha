<?php

// Number of background images
define('CAPTCHA_NUM_BG', 5);
// Default length
define('CAPTCHA_LENGTH', 5);

return [
	'plugin' => [
		'name' => 'Captcha',
		'version' => '4.2.0',
	],
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
			'captcha/css' => [],
		],
	],
];
