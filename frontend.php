<?php

function registration_form( $username, $password, $email, $code ) {

    ?>
    
<div id="login"
    <form name="registerform" id="registerform" action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
    <p>
        <label for="username">Username <strong>*</strong></label><br>
        <input class="input" value size="20" autocapitalize="off" type="text" name="username" value="<?php ( isset( $_POST['username'] ) ? $username : null ) ?>">
    </p>
     
    <p>
        <label for="password">Password <strong>*</strong></label><br>
        <input class="input" value size="20" autocapitalize="off" type="password" name="password" value="<?php ( isset( $_POST['password'] ) ? $password : null ) ?>">
    </p>
     
    <p>
    <label for="email">Email <strong>*</strong></label><br>
    <input class="input" value size="20" type="text" name="email" value="<?php ( isset( $_POST['email']) ? $email : null ) ?>">
    </p>
     
    <p>
    <label for="code">code</label></br>
    <input class="input" value size="10" autocapitalize="off" type="text" name="code"  value="">
    <input  type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Registro"/>
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
?>