<?php

/**
 * Defines the custom field type class.
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * mco_acf_field_vimeo_uploader___minimalart class.
 */
class mco_acf_field_vimeo_uploader___minimalart extends \acf_field
{
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;
	public $secret;
	public $token;
	public $client_identifier;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		/**
		 * Field type reference used in PHP and JS code.
		 *
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'vimeo_uploader___minimalart';

		/**
		 * Field type label.
		 *
		 * For public-facing UI. May contain spaces.
		 */
		$this->label = __('Vimeo Uploader - Minimalart', 'vimeo-acf-mco');

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 *
		 * For field descriptions. May contain spaces.
		 */
		$this->description = __('This field does amazing things.', 'vimeo-acf-mco');

		/**
		 * Field type Doc URL.
		 *
		 * For linking to a documentation page. Displayed in the field picker modal.
		 */
		$this->doc_url = '';

		/**
		 * Field type Tutorial URL.
		 *
		 * For linking to a tutorial resource. Displayed in the field picker modal.
		 */
		$this->tutorial_url = '';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array(
			'font_size'	=> 14,
		);

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("vimeo_uploader___minimalart", "error");
		 * ```
		 */
		$this->l10n = array(
			'error'	=> __('Error! Please enter a higher value', 'vimeo-acf-mco'),
		);

		$this->env = array(
			'url'     => site_url(str_replace(ABSPATH, '', __DIR__)), // URL to the acf-vimeo-uploader---minimalart directory.
			'version' => '1.0', // Replace this with your theme or plugin version constant.
		);

		/**
		 * Field type preview image.
		 *
		 * A preview image for the field type in the picker modal.
		 */
		$this->preview_image = $this->env['url'] . '/assets/images/field-preview-custom.png';

		// $this -> secret =  "";
		// $this -> token =  "";
		// $this -> client_identifier =  "";

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field_settings($field)
	{
		acf_render_field_setting(
			$field,
			array(
				'label'			=> __('Client identifier', 'vimeo-acf-mco'),
				'instructions'	=> __('Customise the input font size', 'vimeo-acf-mco'),
				'type'			=> 'text',
				'name'			=> 'client_identifier',
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'			=> __('Token', 'vimeo-acf-mco'),
				'instructions'	=> __('Customise the input font size', 'vimeo-acf-mco'),
				'type'			=> 'password',
				'name'			=> 'Token',
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'			=> __('Client secrets', 'vimeo-acf-mco'),
				'instructions'	=> __('Customise the input font size', 'vimeo-acf-mco'),
				'type'			=> 'text',
				'name'			=> 'client_secrets',
			)
		);

		acf_render_field_setting(
			$field,
			array(
				'label'			=> __('Utilizar miniatura', 'vimeo-acf-mco'),
				'instructions'	=> __('si no desea utilizar una miniatura como trigger para el ligthbox , deje este valor vacio ', 'vimeo-acf-mco'),
				'type'			=> 'text',
				'name'			=> 'thumbnail_trigger',
			)
		);
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field($field)
	{
		$video_selected_vimeo = get_field('video_selected_vimeo');
		$video_selected_vimeo_url = get_field('video_selected_vimeo_url');
		$video_selected_vimeo_embed = get_field('video_selected_vimeo_embed');

?>

		<!-- Campos ocultos -->
		<input type="hidden" name="video_selected_vimeo" value="<?php echo esc_attr($video_selected_vimeo); ?>">
		<input type="hidden" name="video_selected_vimeo_url" value="<?php echo esc_attr($video_selected_vimeo_url); ?>">
		<input type="hidden" name="video_selected_vimeo_embed" value="<?php echo esc_attr($video_selected_vimeo_embed); ?>">
		<input type="hidden" name="client_identifier" value="<?php echo $field["client_identifier"]; ?>">
		<input type="hidden" name="client_secrets" value="<?php echo $field["client_secrets"]; ?>">
		<input type="hidden" name="token" value="<?php echo $field["Token"]; ?>">
		<input type="hidden" name="acf_key" value="<?php echo $field["key"]; ?>">

		<!-- <input type="file" id="video-upload" accept="video/*">
<button id="upload-button" class="button">Subir a Vimeo</button> -->

		<br><br>

		<button id="custom-media-button" class="button button-primary ">Galería Multimedia Vimeo</button>
		<div id="vimeo-embed"> <?php echo $video_selected_vimeo_embed ?> </div>

<?php
	}


	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts()
	{
		$url     = MCO_ACF_VIMEO_URI;
		$version = $this->env['version'] . time();
		wp_register_script(
			'mco-vimeo-uploader---minimalart',
			"{$url}acf-vimeo-uploader---minimalart/assets/js/field.js",
			array('acf-input'),
			$version
		);


		wp_register_style(
			'mco-vimeo-uploader---minimalart',
			"{$url}acf-vimeo-uploader---minimalart/assets/css/field.css",
			array('acf-input'),
			$version
		);

		wp_enqueue_media();

		wp_enqueue_script('mco-vimeo-uploader---minimalart');
		wp_enqueue_style('mco-vimeo-uploader---minimalart');
	}
}
