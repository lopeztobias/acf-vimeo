<?php

/**
 * Registration logic for the new ACF field type.
 */

if (!defined('ABSPATH')) {
	exit;
}

add_action('init', 'mco_include_acf_field_vimeo_uploader___minimalart');

function mco_include_acf_field_vimeo_uploader___minimalart()
{
	if (!function_exists('acf_register_field_type')) {
		return;
	}

	require_once __DIR__ . '/class-mco-acf-field-vimeo-uploader---minimalart.php';

	acf_register_field_type('mco_acf_field_vimeo_uploader___minimalart');
}
