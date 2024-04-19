<?php
class McoACFVimeo
{
    public $scriptVersion = "1.0.0";
    public $page_settings_slug = "mco-acf-vimeo-settings";
    private $file;


    function __construct($file)
    {
        $this->file = $file;
        register_activation_hook($this->file, [$this, "activation_plugin"]);
        register_deactivation_hook($this->file, [$this, "deactivation_plugin"]);
        $this->load_hooks();
    }

    function activation_plugin()
    {
    }

    function deactivation_plugin()
    {
    }

    function load_hooks()
    {

        add_shortcode('render_vimeo_acf', [$this , 'render_vimeo_acf']);

        if (is_admin()) {
            add_action('save_post', [$this, 'actualizar_valor_campo_personalizado_al_guardar_post']);
        }
    }

    function actualizar_valor_campo_personalizado_al_guardar_post($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['video_selected_vimeo'])) {
            update_field('video_selected_vimeo', $_POST['video_selected_vimeo'], $post_id);
        }

        if (isset($_POST['video_selected_vimeo_url'])) {
            update_field('video_selected_vimeo_url', $_POST['video_selected_vimeo_url'], $post_id);
        }

        if (isset($_POST['video_selected_vimeo_embed'])) {
            update_field('video_selected_vimeo_embed', $_POST['video_selected_vimeo_embed'], $post_id);
        }

        if (isset($_POST['acf_key'])) {
            update_field('video_selected_vimeo_acf_key', $_POST['acf_key'], $post_id);
        }
    }

    
function render_vimeo_acf()
{

   $post_id = get_the_ID();
   $post_id_custom = uniqid("mco-");
   $video_selected_vimeo_embed = get_field('video_selected_vimeo_embed', $post_id);
   $acf_key_field = get_field('video_selected_vimeo_acf_key', $post_id);
   $oject_acf_field = get_field_object($acf_key_field, $post_id);


   if (!empty($video_selected_vimeo_embed)) {

      if (!wp_script_is('vimeo-lightbox', 'enqueued') && !wp_script_is('vimeo-lightbox', 'registered')) {
         wp_register_script('vimeo-lightbox', MCO_ACF_VIMEO_URI . 'public/js/vimeo-ligthbox.js', array('jquery'), $this -> scriptVersion, true);
         wp_register_style('vimeo-lightbox', MCO_ACF_VIMEO_URI . 'public/css/vimeo-ligthbox.css', array(), $this -> scriptVersion);

         wp_enqueue_style('vimeo-lightbox');
         wp_enqueue_script('vimeo-lightbox');
         
      }


      if(isset($oject_acf_field["thumbnail_trigger"]) && !empty($oject_acf_field["thumbnail_trigger"])){
         $image = get_field($oject_acf_field["thumbnail_trigger"] , $post_id);

         if(isset($image) && !empty($image) && is_array($image)){
            $output = '<div class="mco_vimeo_lightbox_portada" data-linked=' . $post_id_custom . '><image class="mco_vimeo_lightbox_portada__icon" src="'.MCO_ACF_VIMEO_URI . 'public/images/icon.png' .'"/>  <image class="mco_vimeo_lightbox_portada__portada"  src="'.$image["sizes"]['medium'].'" /></div>';
         }

      }

      if(!isset($output)) {
            $output = '<a class="mco_vimeo_lightbox" data-linked=' . $post_id_custom . '>Ver Video</a>';
      }

      $output .= '<div class="mco_lightbox_container_vimeo" style="display:none;"  id=' . $post_id_custom . '>';
      $output .= $video_selected_vimeo_embed;
      $output .= '</div>';

      return $output;
   } 
   
   
}

}
