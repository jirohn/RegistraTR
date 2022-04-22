<?php
/**
 * Plugin Name:       RegistraTr
 * Description:       Plugin de registro de usuario por clave de invitación
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pablo Reyes (Jirohn)
 * Author URI:        mailto:canariasraptv@gmail.com
 * Update URI:        https://github.com/jirohn/RegistraTR/archive/refs/heads/main.zip
 * License:           GPL v2 or later
 */
defined( 'ABSPATH') or die('Hey no tienes acceso a esto');

//definiendo ruta del plugin//
define('EXTRACTR_PATH',__FILE__);
define ('EXTRACTR_PLUGIN_PATH',plugin_dir_path( 'EXTRACTR_PATH' ));
define ('EXTRACTR_PLUGIN_URL',plugin_dir_url( 'EXTRACTR_PATH' ));
define ('EXTRACTR_PLUGIN_NAME','ExtracTr');

include("filters.php");
include("functions.php");
include("frontend.php");
include("frontendadmin.php");

class RegistraTrPlugin{
    function activated(){
        registratr_add_codes();
        echo'plugin was activated';

    }
    function deactivated(){
        echo'plugin was deactivated';


    }
    function uninstall(){
        $users = get_users( ['fields' => ['ID'] ] );
        foreach ( $users as $user ) {
            $user_update = delete_user_meta($user->ID, $key, registratr_code_generate());
        }

    }
    
}

if(class_exists('RegistraTrPlugin')){
    $registratrPlugin = new RegistraTrPlugin();
}



 
function registratr_menu(){
    add_menu_page( 'Registratr config', 'RegistraTr', 'manage_options', 'registratr-plugin', 'registratr_config_page' );
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
    return $meta;
}


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


function theme_add_user_code_column( $columns ) {
      $columns['_codigo_para_invitar'] = __( 'codigo de invitacion', 'theme' );
      return $columns;
    } // end theme_add_user_code_column
    add_filter( 'manage_users_columns', 'theme_add_user_code_column' );

function theme_show_user_code_data( $value, $column_name, $user_id ) {

    if( '_codigo_para_invitar' == $column_name ) {
        return get_user_meta( $user_id, '_codigo_para_invitar', true );
    } // end if
    
    } // end theme_show_user_code_data
    add_action( 'manage_users_custom_column', 'theme_show_user_code_data', 10, 3 );
?>