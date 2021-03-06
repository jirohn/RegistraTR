<?php
//version final
function registration_form( $username, $password, $email, $code ) {

    ?>
    
<div id="singup">
    <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
    <p>
        <label for="username" style="display:none">Nombre de usuario <strong>*</strong></label>
        <input class="input" placeholder="Nombre de usuario" value size="20" autocapitalize="off" type="text" name="username" value="<?php ( isset( $_POST['username'] ) ? $username : null ) ?>">
    </p>
     
    <p>
        <label for="password" style="display:none">Contraseña <strong>*</strong></label>
        <input class="input" placeholder="Contraseña" value size="20" autocapitalize="off" type="password" name="password" value="<?php ( isset( $_POST['password'] ) ? $password : null ) ?>">
    </p>
     
    <p>
    <label for="email" style="display:none">Email <strong>*</strong></label>
    <input class="input" placeholder="E-mail" value size="20" type="text" name="email" value="<?php ( isset( $_POST['email']) ? $email : null ) ?>">
    </p>
    <p>
        <label for="dni" style="display:none">DNI<strong>*</strong></label>
        <input class="input" placeholder="DNI" value size="20" autocapitalize="off" type="text" name="dni" value="<?php ( isset( $_POST['dni'] ) ? $dni : null ) ?>">
    </p>
     
    <p>
    <label for="code" style="display:none">Código de invitación</label>
    <input class="input" placeholder="Código" value size="10" autocapitalize="off" type="text" name="code"  value="<?php ( isset( $_POST['code']) ? $code : null ) ?>">
    <input  type="submit" name="submit" id="submit" class="button button-primary button-large" value="Continuar"/>
    </p>


    </form>
</div>




    <?php
    
}




function registration_validation( $username, $password, $email, $code, $dni )  {
    global $reg_errors;
    $reg_errors = new WP_Error;
    if ( empty( $username ) || empty( $password ) || empty( $email ) || empty($code) || empty($dni)) {
        $reg_errors->add('field', 'Falta uno de los campos');
    }
    if ( 4 > strlen( $username ) ) {
        $reg_errors->add( 'username_length', 'El nombre de usuario es muy corto, minimo 4 caracteres' );
    }
    if ( username_exists( $username ) )
    $reg_errors->add('user_name', 'El nombre de usuario ya existe!');
    if ( ! validate_username( $username ) ) {
        $reg_errors->add( 'username_invalid', 'El nombre de usuario es invalido' );
    }
    if ( 8 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'La contraseña debe ser mayor de 8 caracteres' );
    }
    if ( !is_email( $email ) ) {
        $reg_errors->add( 'email_invalid', 'El correo es invalido' );
    }
    if ( email_exists( $email ) ) {
        $reg_errors->add( 'email', 'El correo ya esta en uso' );
    }
    if ( ! empty( $code ) ) {
        if ( ! registratr_code_exists( $code) ) {
            $reg_errors->add( 'code', 'Este codigo es invalido');
        }
    }
    if ( ! empty( $dni ) ) {
        if ( rttr_dni_compare($dni) ) {
            $reg_errors->add( 'DNI', 'Este DNI esta en uso!');
        }
    }
    if ( 9 < strlen( $dni ) || 9 > strlen( $dni ) ) {
        $reg_errors->add( 'DNI', 'El DNI no es correcto' );
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
function rttr_dni_compare($dni){
    $users = get_users(array(
        'meta_key' => '_rttr_user_document_identification',
        'meta_value' => $dni
    ));
    $exists = false;
    if(!empty($users))
            $exists=true;
    
    return $exists;
}
function complete_registration() {
    global $reg_errors, $username, $password, $email, $code, $dni;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'codetoregister'=>   $code,
        'dni'           => $dni,
        );
        $user = wp_insert_user( $userdata );
        registratr_add_invited_by_id($user, $code, $dni);

        ?> <div id="info-registro">Registro completado</div> <?php
        send_confirmation_email_to_old_user(getinvitationid('_codigo_para_invitar',$code), $user);
    }
}
function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['code'],
        $_POST['dni'],
        );
            
        // sanitize user form input
        global $username, $password, $email, $code, $dni;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $code    =   esc_attr( $_POST['code'] );
        $dni     =      sanitize_text_field( $_POST['dni'] );
    
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
        $username,
        $password,
        $email,
        $code,
        $dni
        );
    }
    
    registration_form(
        '','','',''
        );
}

// Register a new shortcode: [registratr_register]
add_shortcode( 'registratr_register', 'custom_registration_shortcode' );
 

function custom_registration_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}

function registratr_invited_list($code){
    $thereis=false;
    $key = '_invitado_por_ID';
    $user = get_current_user_id();
    $invitedusers = get_users(array(
		'meta_key' => $key, 
		'meta_value' => $user, 
	));

    foreach($invitedusers as $iuser){
        $active = get_user_meta($iuser->ID,'_activado',true);
        $thereis=false;
        if($active =='0' && $active!=null){
            $key = '_id_activacion';
            $code = get_user_meta($iuser->ID, $key, true);
            ?>
            <div id="textwidget ">
                <table>
                    <thead>
                        <tr>

                            <td><?php echo $iuser->nickname?></td>
                            <td>
                            <form action="<?php $_SERVER['REQUEST_URI'] ;?>" method="post">
                            <input class="input" name="code"  value="<?php echo $code;?>" style="display:none"/>
                            <input  type="submit" name="submit" id="submit" class="button button-primary button-large" value="Confirmar Usuario"/>
                            </form>  
                            </td>
                        </tr>
                    </thead>
                </table>

            </div>
            <?php
                $thereis=true;
        }        
    }
    if($thereis==false){
        ?>
        <div id="user_list">
            Sin usuarios pendientes de registro
        </div>
        <?php
    }

}

add_shortcode( 'registratr_users', 'custom_user_list_shortcode' );
function registratr_activation_function(){
    if ( isset($_POST['submit'] ) ) {
        registratr_invited_list(
        $_POST['code'],
        );
        // sanitize user form input
        global $code;

        $code    =   esc_attr( $_POST['code'] );
    
        // call @function complete_registration to create the user
        activate_user($code);
        // only when no WP_error is found

    }
    registratr_invited_list('');

    
}
function activate_user($code){
    $user = getactivationid('_id_activacion', $code);
    $user_update = update_user_meta($user, '_activado', '1');
    send_acepted_email_to_new_user($user);
    header("Location: http://localhost/wordpress/index.php/tus-usuarios-pendientes-2/");
}

function custom_user_list_shortcode() {
    ob_start();
    registratr_activation_function();
    return ob_get_clean();
}
function getactivationid( $meta_key, $meta_value ) {

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



?>