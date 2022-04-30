<?php

/**
*Genera el codigo de registro devuelve un valor string // Generate code, returning  string value
*/
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

/** 
 * Añade codigos a todos los usuarios // add codes for all users
 */
function registratr_add_codes() {
    $allusers = get_users();
    $key = '_codigo_para_invitar';
    $users = get_users( ['fields' => ['ID'] ] );
    foreach ( $users as $user ) {
        if(!get_user_meta($user->ID, $key, true)){
        $user_update = update_user_meta($user->ID, $key, registratr_code_generate());
        }
    }
    echo('user');
}
function registratr_add_activation_id() {
    $key = '_id_activacion';$key2 = '_activado';
    $users = get_users( ['fields' => ['ID'] ] );
    foreach ( $users as $user ) {
        if(!get_user_meta($user->ID, $key, true)){
        $user_update = update_user_meta($user->ID, $key, registratr_code_generate());
        }
        if(get_user_meta($user->ID, $key2)){
            $user_update = update_user_meta($user->ID, $key2, '1');
            }
    }
    echo('user');
}
function registratr_add_invited_by_id($user, $code) {
    $key='_invitado_por_id';
    $invitationid = getinvitationid('_codigo_para_invitar', $code);
    $user_update = update_user_meta($user, $key, $invitationid);

}
function registratr_regenerate_codes() {
    $allusers = get_users();
    $key = '_codigo_para_invitar';
    $users = get_users( ['fields' => ['ID'] ] );
    foreach ( $users as $user ) {
        
        $user_update = update_user_meta($user->ID, $key, registratr_code_generate());

    }

}
function registratr_add_config_meta() {
    $key = '_rttr_correo_para_invitados';
    $key1 = '_rttr_correo_para_confirmados';
    $key2 = '_rttr_correo_para_anfitriones';
    $key3 = '_rttr_pagina_de_lista_de_pendientes';
    $key4 = '_rttr_pagina_de_no_confirmado';
    $key5 = '_rttr_pagina_de_redirecion_en_login';
    $key6 = '_rttr_correo_noresponder';

        if(!get_option($key)){
                $user_update = add_option($key, 'sin plantilla configurada','','yes');
            }
        if(!get_option($key1)){
            $user_update = add_option($key1, 'sin plantilla configurada','','yes');
        }
        if(!get_option($key2)){
                $user_update = add_option($key2, 'sin plantilla configurada','','yes');
            }
        if(!get_option($key3)){
            $user_update = add_option($key3, 'sin url','','yes');
        }
        if(!get_option($key4)){
            $user_update = add_option($key4, 'sin url','','yes');
        }
        if(!get_option($key5)){
            $user_update = add_option($key5, 'sin url','','yes');
        }
        if(!get_option($key6)){
            $user_update = add_option($key6, 'sin correo','','yes');
        }



}
add_shortcode( 'registratr_show_code', 'custom_show_code_shortcode' );

function custom_show_code_shortcode() {
    ob_start();
    registratr_show_code();
    return ob_get_clean();
}
function registratr_show_Code(){
    $user = get_current_user_id();
    $key = '_codigo_para_invitar';
    $code = get_user_meta($user, $key, true);
    echo $code;
}

add_shortcode( 'registratr_lock', 'custom_lock_shortcode' );

function custom_lock_shortcode() {
    ob_start();
    registratr_redirect_if_not_registered();
    return ob_get_clean();
}
function registratr_redirect_if_not_registered(){
    $userid = get_current_user_id();
    $key = '_activado';
    $usermeta = get_user_meta($userid, $key, true);
    if($userid == null){
        header("Location: ". get_site_url());
    }else{
        if($usermeta!='1'){
            header("Location: ". get_option('_rttr_pagina_de_no_confirmado'));        
        }
    }
}
function registratr_check_and_logout_user(){
    $userid = get_current_user_id();
    $key = '_activado';
    $usermeta = get_user_meta($userid, $key, true);
    if($usermeta != '1'&& $userid!=null){
        $redirect_url = get_option('_rttr_pagina_de_no_confirmado');
        wp_safe_redirect( $redirect_url );
        //exit;
        wp_logout();
    }
}
add_action('wp_login', 'registratr_check_and_logout_user', 10);

function registratr_redirect_on_logout(){
        $redirect_url = get_option('_rttr_pagina_de_no_confirmado');
        wp_safe_redirect( $redirect_url );
        exit;
    
    //header("Location: http://localhost/wordpress/index.php/espere/");
}
//add_action('wp_logout', 'registratr_redirect_on_logout', 10);
?>