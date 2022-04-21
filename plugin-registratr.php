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


function registratr_code_generate($length = 8) {
    do{
        $characters = '0123456789abcdefghijklmnopqrs092u3tuvwxyzaskdhfhf9882323ABCDEFGHIJKLMNksadf9044OPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

    }while(registratr_code_exists($randomString));
    return $randomString;
}
function registratr_add_codes() {
    $allusers = get_users();
    $key = '_codigo_para_invitar';
    $users = get_users( ['fields' => ['ID'] ] );
    foreach ( $users as $user ) {
        $user_update = update_user_meta($user->ID, $key, registratr_code_generate());
    }
    echo('user');
}
 
function registratr_menu(){
    add_menu_page( 'Registratr config', 'RegistraTr', 'manage_options', 'registratr-plugin', 'registratr_config_page' );
}
add_action('admin_menu', 'registratr_menu');


function registratr_config_page(){
    echo '
   <div class="wrap">
   <h1>RegistraTr Plugin</h1>
    <table class="form-table">
    <tbody><tr>
        <th scope="row"><label for="recreate">Recreate codes in all users</label></th>
        <td><input name="recreate" type="button" id="recreate" value="Recreate code" class="regular-text"></td>
        </tr>
        <tr>
        <th scope="row"><label for="sendmailcheck">Send email</label></th>
        <td><input name="sendmailcheck" type="checkbox" id="sendmailcheck" class="regular-text"></td>
        </tr>
        <tr>
        <th scope="row"><label for="emailforreg">email to registered user</label></th>
        <td><textarea rows = "5" cols = "50" name="emailforreg" type="textarea" id="emailforreg" value="Write here your message" class="regular-text">
        </textarea>
        </td>
        </tr>
        <tr>
        <th scope="row"><label for="emailfornew">email to new user</label></th>
        <td><textarea rows = "5" cols = "50" name="emailfornew" type="textarea" id="emailfornew" value="Write here your message" class="regular-text">
        </tr>
        </textarea>
        </td>       
        </tr>
        
    </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
        <form action="" method="post">
        <p><button type="submit" value="Recrear"/></p>  
    </div>  
    </html>
';
}






function registratr_del_codes() {
    
} 

 
/**
 * Activate the plugin.
 */
register_activation_hook( __FILE__, array($registratrPlugin, 'activated') );



/**
 * Deactivation hook.
 */
register_deactivation_hook( __FILE__, array($registratrPlugin, 'deactivated') );



function registration_form( $username, $password, $email, $code ) {

    echo '
    <style>
    div {
      margin-bottom:2px;
    }
     
    input{
        margin-bottom:4px;
    }
    </style>
    ';
 
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    <div>
    <label for="username">Username <strong>*</strong></label>
    <input type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
    </div>
     
    <div>
    <label for="password">Password <strong>*</strong></label>
    <input type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    </div>
     
    <div>
    <label for="email">Email <strong>*</strong></label>
    <input type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>
     
    <div>
    <label for="code">code</label>
    <input type="text" name="code"  value="">
    </div>
     
    <input type="submit" name="submit" value="Register"/>
    </form>
    ';
}
function registration_validation( $username, $password, $email, $code )  {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) || empty($code)) {
        $reg_errors->add('field', 'Required form field is missing');
    }
    if ( 4 > strlen( $username ) ) {
        $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
    }
    if ( username_exists( $username ) )
    $reg_errors->add('user_name', 'Sorry, that username already exists!');
    if ( ! validate_username( $username ) ) {
        $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
    }
    if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Password length must be greater than 5' );
    }
    if ( !is_email( $email ) ) {
        $reg_errors->add( 'email_invalid', 'Email is not valid' );
    }
    if ( email_exists( $email ) ) {
        $reg_errors->add( 'email', 'Email Already in use' );
    }
    if ( ! empty( $code ) ) {
        if ( ! registratr_code_exists( $code) ) {
            $reg_errors->add( 'code', 'This code is not valid');
        }
    }
    if ( is_wp_error( $reg_errors ) ) {
 
        foreach ( $reg_errors->get_error_messages() as $error ) {
         
            echo '<div>';
            echo '<strong>ERROR</strong>:';
            echo $error . '<br/>';
            echo '</div>';
             
        }
     
    }
}

add_filter( 'insert_user_meta', 'new_user_meta', 20, 3);

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

function complete_registration() {
    global $reg_errors, $username, $password, $email, $code;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'codetoregister'      =>   $code,

        );
        $user = wp_insert_user( $userdata );
        echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
    }
}
function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['code'],
        );
            
        // sanitize user form input
        global $username, $password, $email, $code;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        //$code    =   esc_url( $_POST['code'] );

    
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
        $username,
        $password,
        $email,
        $code
        );
    }
    
    registration_form(
        $username,
        $password,
        $email,
        $code
        );
}

// Register a new shortcode: [registratr_register]
add_shortcode( 'registratr_register', 'custom_registration_shortcode' );
 
// The callback function that will replace [book]
function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
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