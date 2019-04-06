<?php

return [
	'routes' => [
		'captcha' => [
			'path' => '/captcha/{captcha_token?}',
			'resource' => 'captcha/captcha',
			'walled' => false,
		],
	],
];
