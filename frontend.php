<?php

function registration_form( $username, $password, $email, $code ) {

    ?>
    
<div id="login">
    <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
    <p>
        <!--label for="username">Nombre de usuario <strong>*</strong></label><br-->
        <input class="input" placeholder="Nombre de usuario" value size="20" autocapitalize="off" type="text" name="username" value="<?php ( isset( $_POST['username'] ) ? $username : null ) ?>">
    </p>
     
    <p>
        <!--label for="password">Contraseña <strong>*</strong></label><br-->
        <input class="input" placeholder="Contraseña" value size="20" autocapitalize="off" type="password" name="password" value="<?php ( isset( $_POST['password'] ) ? $password : null ) ?>">
    </p>
     
    <p>
    <!--label for="email">Email <strong>*</strong></label><br-->
    <input class="input" placeholder="E-mail" value size="20" type="text" name="email" value="<?php ( isset( $_POST['email']) ? $email : null ) ?>">
    </p>
     
    <p>
    <!--label for="code">Código de invitación</label></br-->
    <input class="input" placeholder="Código" value size="10" autocapitalize="off" type="text" name="code"  value="<?php ( isset( $_POST['code']) ? $code : null ) ?>">
    <input  type="submit" name="submit" id="submit" class="button button-primary button-large" value="Register"/>
    </p>


    </form>
</div>




    <?php
    
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
        registratr_add_invited_by_id($user, $code);
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
        );
            
        // sanitize user form input
        global $username, $password, $email, $code;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $code    =   esc_attr( $_POST['code'] );
    
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