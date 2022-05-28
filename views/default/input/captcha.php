<?php
/**
 * Elgg captcha plugin captcha hook view override.
 *
 * @package ElggCaptcha
 */

// Generate a token which is then passed into the captcha algorithm for verification
$token = \CaptchaFunctions::captcha_generate_token();

echo '<div class="captcha">';
echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'captcha_token',
	'value' => $token,
]);

echo '<div class="captcha-right">';
echo elgg_view('output/img', [
	'src' => "captcha/$token",
	'alt' => "captcha_image",
	'class' => 'captcha-input-image',
]);
echo '</div>';

echo '<div class="captcha-left">';
echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('captcha:entercaptcha'),
	'name' => 'captcha_input',
	'class' => 'captcha-input-text',
	'required' => true,
]);
echo '</div>';
echo '</div>';
