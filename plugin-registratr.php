<?php
//version final
/**
 * Plugin Name:       Invitation code
 * Plugin URI:        https://bibiai.es/
 * Description:       Permite a los usuarios tener un código de afiliado con el que los visitantes pueden registrarse, con un sistema de validación por parte del afiliado que ha compartido su código.
 * Version:           1.2.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Bibiai
 * Author URI:        mailto:support@bibiai.es
 * Update URI:        https://github.com/jirohn/RegistraTR.git
 * License:           GPL v2 or later
 */
defined( 'ABSPATH') or die('Hey no tienes acceso a esto');

/**
 * definiendo rutas // setting paths
 */
define('EXTRACTR_PATH',__FILE__);
define ('EXTRACTR_PLUGIN_PATH',plugin_dir_path( 'EXTRACTR_PATH' ));
define ('EXTRACTR_PLUGIN_URL',plugin_dir_url( 'EXTRACTR_PATH' ));
define ('EXTRACTR_PLUGIN_NAME','ExtracTr');
/**
 * definiendo idioma / setting lang // VERSION DE VENTA
 */


/**
 * definiendo dependencias // setting dependencies
 */
include("includes/rttr_filters.php");
include("includes/rttr_functions.php");
include("includes/rttr_email_functions.php");
include("public/rttr_user_functions.php");
include("admin/rttr_admin.php");




class RegistraTrPlugin{
    function activated(){
        registratr_add_codes();
        registratr_add_activation_id();
        registratr_add_config_meta();


    }
    function deactivated(){
        


    }
    function uninstall(){
        $users = get_users( ['fields' => ['ID'] ] );
        foreach ( $users as $user ) {
            $user_update = delete_user_meta($user->ID, $key);
        }

    }
    
}

if(class_exists('RegistraTrPlugin')){
    $registratrPlugin = new RegistraTrPlugin();
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');
function salcode_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'admin.php?page=invitation-code' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}

wp_register_style('invitation_code_dashicons', plugins_url('RegistraTR-Invitation-code/css/invitation-code-icon.css'));
wp_enqueue_style('invitation_code_dashicons');

function registratr_menu(){
    add_menu_page( 'Registratr config', 'Invitation code', 'manage_options', 'invitation-code', 'registratr_config_form', 'dashicons-ic-icon');
}
add_action('admin_menu', 'registratr_menu');

/**
 * Activate the plugin.
 */
register_activation_hook( __FILE__, array($registratrPlugin, 'activated') );

/**
 * Deactivation hook.
 */
register_deactivation_hook( __FILE__, array($registratrPlugin, 'deactivated') );



/**
 * @param array   $meta   Default meta values and keys for the user.
 * @param WP_User $user   User object.
 * @param bool    $update Whether the user is being updated rather than created.
 */
function new_user_meta( $meta, $user, $update ) 
{
    if ( $update )
        return $meta;
    $meta['_codigo_para_invitar'] = registratr_code_generate();
    $meta['_id_activacion'] = registratr_code_generate();
    $meta['_activado'] = '0';   
    return $meta;
}

function getinvitationid( $meta_key, $meta_value ) {

	// Query for users based on the meta data
	$user_query = new WP_User_Query(
		array(
			'meta_key'	  =>	$meta_key,
			'meta_value'	=>	$meta_value
		)
	);

	// Get the results from the query, returning the first user
	$users = $user_query->get_results();

	return $users[0]->ID;

} // end get_user_by_meta_data

function registratr_code_exists($code){
    $users = get_users(array(
        'meta_key' => '_codigo_para_invitar',
        'meta_value' => $code
    ));
    $exists = false;
    if(!empty($users))
            $exists=true;
    
    return $exists;
}



?>