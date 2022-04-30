<?php


function send_welcome_email_to_new_user($user_id) {
  $firstkey = '_rttr_correo_noresponder';
  $correotosend = get_option($firstkey);
    $key = '_rttr_correo_para_invitados';
    $user = get_userdata($user_id);
    $user_email = $user->user_email;
    // for simplicity, lets assume that user has typed their first and last name when they sign up
    $user_full_name = $user->user_nicename;

    // Now we are ready to build our welcome email
    $to = $user_email;
    
    $subject = 'Tu Registro en '. get_bloginfo( 'name' ) . ' esta en proceso';
    //$msg = "<p>Querido,</p>" . $user_full_name . "<p> gracias por iniciar su proceso de registro en " . get_bloginfo( 'name') ."</p><p> Su proceso de registro se ha iniciado correctamente. Se le ha enviado un mail de confirmacion a tu anfitrion.</p><p>Una vez el anfitrion confirme su invitacion le llegara un correo a usted indiciandole los pasos a seguir";
    $site_name = get_bloginfo( 'name');
    $msg = get_option($key);
    $msg = str_replace('{nombre sitio}', $site_name, $msg );
    $msg = str_replace('{nombre usuario}', $user_full_name, $msg);
    $msg = str_replace('{enlace sitio}', '<a href='.get_site_url().'>Lista de Usuarios Pendientes</a>', $msg );
    $msg = str_replace('{p}', '<br>', $msg );
    $message  = '<html dir="ltr" lang="es">' . PHP_EOL;
    $message .= '<head>' . PHP_EOL;
    $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL;
    $message .= '<title>Gracias por iniciar el registro en ' . get_bloginfo( 'name' ) . '</title>' . PHP_EOL;
    $message .= '</head>' . PHP_EOL;
    $message .= '<body style="padding:0;margin:0;">' . $msg . '</body>' . PHP_EOL;
    $message .= '</html>' . PHP_EOL;
    
    $message = str_replace(array(chr(3)), '', $message);
    $body = '
              <h1>Dear ' . $user_full_name . ',</h1></br>
              <p>Thank you for joining our site. Your account is now active.</p>
              <p>Please go ahead and navigate around your account.</p>
              <p>Let me know if you have further questions, I am here to help.</p>
              <p>Enjoy the rest of your day!</p>
              <p>Kind Regards,</p>
              <p>poanchen</p>
    ';
    $from = $correotosend;
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From:" . $from;
    
    // Sending email
    if(mail($to, $subject, $message, $headers)){

    } else{
        echo 'Unable to send email. Please try again.';
    }
    
  }

  //adding welcome mail at register//
  add_action('user_register', 'send_welcome_email_to_new_user');



function send_confirmation_email_to_old_user($user_id, $user2_id) {
  $firstkey = '_rttr_correo_noresponder';
$correotosend = get_option($firstkey);
  $key = '_rttr_correo_para_anfitriones';
  $key2 = '_rttr_pagina_de_lista_de_pendientes';
  $amigo = get_userdata($user2_id);
  $amigo_nombre = $amigo->user_nicename;
  $user = get_userdata($user_id);
  $user_email = $user->user_email;
  // for simplicity, lets assume that user has typed their first and last name when they sign up
  $user_full_name = $user->user_nicename;

  // Now we are ready to build our welcome email
  $to = $user_email;
  
  $subject = 'El usuario '. $amigo_nombre . ' se ha registrado con su codigo de invitación en ' . get_bloginfo( 'name' );
  //$msg = "<p>Querido,</p>" . $user_full_name . "<p> Gracias por invitar al usuario ". $amigo_nombre . "a formar parte de " . get_bloginfo( 'name') ."</p><p> Su proceso de registro se ha iniciado correctamente. Haga click en el siguiente enlace</p><p><a href='http://localhost/wordpress/index.php/tus-usuarios-pendientes-2/'>Lista de usuarios pendientes de confirmación</a></p><p>Confirme la invitación para que su invitado pueda disfrutar las ventajas de" . get_bloginfo( 'name') . "</p><p>Gracias</p>";
    $site_name = get_bloginfo( 'name');
    $url_users = get_option( $key2);
    $msg = get_option($key);
    $msg = str_replace('{nombre sitio}', $site_name, $msg );
    $msg = str_replace('{nombre usuario}', $user_full_name, $msg);
    $msg = str_replace('{nombre invitado}', $amigo_nombre, $msg);
    $msg = str_replace('{enlace sitio}', '<a href='.get_site_url().'>Lista de Usuarios Pendientes</a>', $msg );
    $msg = str_replace('{lista usuarios}', '<a href='.$url_users.'>Lista de Usuarios Pendientes</a>', $msg );
    $msg = str_replace('{p}', '<br>', $msg );
  $message  = '<html dir="ltr" lang="es">' . PHP_EOL;
  $message .= '<head>' . PHP_EOL;
  $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL;
  $message .= '<title>Gracias por iniciar el registro en ' . get_bloginfo( 'name' ) . '</title>' . PHP_EOL;
  $message .= '</head>' . PHP_EOL;
  $message .= '<body style="padding:0;margin:0;">' . $msg . '</body>' . PHP_EOL;
  $message .= '</html>' . PHP_EOL;
  
  $message = str_replace(array(chr(3)), '', $message);
  
  $from = $correotosend;
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= "From:" . $from;


  
  // Sending email
  if(mail($to, $subject, $message, $headers)){

  } else{
      echo 'Unable to send email. Please try again.';
  }
  
}

//NO SE AÑADE ACCION YA QUE SE LLAMA EN LA FUNCION DE REGISTRO INCLUYE DATOS
function send_acepted_email_to_new_user($user_id) {
  $firstkey = '_rttr_correo_noresponder';
  $correotosend = get_option($firstkey);
  $key = '_rttr_correo_para_aceptados';

  $user = get_userdata($user_id);
  $user_email = $user->user_email;
  // for simplicity, lets assume that user has typed their first and last name when they sign up
  $user_full_name = $user->user_nicename;

  // Now we are ready to build our welcome email
  $to = $user_email;
  
  $subject = 'Has sido aceptado en '. get_bloginfo( 'name' ) . ' disfruta!';
  $site_name = get_bloginfo( 'name');
  $msg = get_option($key);
  $msg = str_replace('{nombre sitio}', $site_name, $msg );
  $msg = str_replace('{nombre usuario}', $user_full_name, $msg);
  $msg = str_replace('{enlace sitio}', '<a href='.get_site_url().'>Lista de Usuarios Pendientes</a>', $msg );
  $msg = str_replace('{p}', '<br>', $msg );
  $message  = '<html dir="ltr" lang="es">' . PHP_EOL;
  $message .= '<head>' . PHP_EOL;
  $message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL;
  $message .= '<title>Gracias por iniciar el registro en ' . get_bloginfo( 'name' ) . '</title>' . PHP_EOL;
  $message .= '</head>' . PHP_EOL;
  $message .= '<body style="padding:0;margin:0;">' . $msg . '</body>' . PHP_EOL;
  $message .= '</html>' . PHP_EOL;
  
  $message = str_replace(array(chr(3)), '', $message);
  $body = '
            <h1>Dear ' . $user_full_name . ',</h1></br>
            <p>Thank you for joining our site. Your account is now active.</p>
            <p>Please go ahead and navigate around your account.</p>
            <p>Let me know if you have further questions, I am here to help.</p>
            <p>Enjoy the rest of your day!</p>
            <p>Kind Regards,</p>
            <p>poanchen</p>
  ';
  $from = $correotosend;
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $headers .= "From:" . $from;
  
  // Sending email
  if(mail($to, $subject, $message, $headers)){

  } else{
      echo 'Unable to send email. Please try again.';
  }
  
}
  ?>