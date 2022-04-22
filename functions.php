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
        $user_update = update_user_meta($user->ID, $key, registratr_code_generate());
    }
    echo('user');
}

?>