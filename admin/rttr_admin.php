<?php
//version final



function theme_add_user_code_column( $columns ) {
    $columns['_dni'] = __('DNI', 'theme');
    $columns['_codigo_para_invitar'] = __( 'Código', 'theme' );
    $columns['_activado'] = __( 'Estado de activación', 'theme' );
    $columns['_activar'] = __( 'Activar', 'theme' );

    return $columns;
  } // end theme_add_user_code_column
add_filter( 'manage_users_columns', 'theme_add_user_code_column' );

function theme_show_user_code_data( $value, $column_name, $user_id ) {
  if( '_dni' == $column_name ) {
    return get_user_meta( $user_id, '_rttr_user_document_identification', true );
  }
  if( '_codigo_para_invitar' == $column_name ) {
      return get_user_meta( $user_id, '_codigo_para_invitar', true );
  } // end if
  if( '_activado' == $column_name ) {
    if(get_user_meta( $user_id, '_activado', true )=='1')
    return 'Activado';
    else return 'Desactivado';
  }
  if( '_activar' == $column_name ) {
    if(get_user_meta( $user_id, '_activado', true )=='0')
    return rttr_activate_button($user_id);
  else
    return rttr_disabled_button();  
  }

  } // end theme_show_user_code_data
  add_action( 'manage_users_custom_column', 'theme_show_user_code_data', 10, 3 );
  
  
  function rttr_activate_user($user_id){
  update_user_meta( $user_id, '_activado', "1");
  
}
 function rttr_activate_button($user_id){
    return $html ="<button class='button button-primary activate-user-button' value='". $user_id ."' 'type='button' ondblclick = 'myFunction();'>Activar</button>
    <script>
    jQuery(document).ready(function($) {
    
      $('.activate-user-button').click(function(){

        // This does the ajax request (The Call).

      $.ajax({
          url: ajaxurl, // Since WP 2.8 ajaxurl is always defined and points to admin-ajax.php
          data: {
              'action':'rttr_activate_request', // This is our PHP function below
              'user' : $('.activate-user-button').val() // This is the variable we are sending via AJAX
          },
          success:function(data) {
      // This outputs the result of the ajax request (The Callback)
              window.alert(data);
          },  
          error: function(errorThrown){
              window.alert(errorThrown);
          }
      });   
  });
  });
    </script>";
    }

 function rttr_disabled_button(){
  return  $html = '<button class="button button-primary" disabled="true" type="button" role="button">Activar</button>
  ';
 }


 function rttr_activate_request() {

  // The $_REQUEST contains all the data sent via AJAX from the Javascript call
  if ( isset($_REQUEST) ) {

      $user = $_REQUEST['user'];
      // Now let's return the result to the Javascript function (The Callback) 
      rttr_activate_user($user);
      echo 'El usuario ha sido activado satisfactoriamente' ;        
  }

  // Always die in functions echoing AJAX content
 die();
}

// This bit is a special action hook that works with the WordPress AJAX functionality. 
add_action( 'wp_ajax_rttr_activate_request', 'rttr_activate_request' ); 


function registratr_config_page($page){
  $key = '_rttr_correo_para_invitados';
  $key1 = '_rttr_correo_para_confirmados';
  $key2 = '_rttr_correo_para_anfitriones';
  $key3 = '_rttr_pagina_de_lista_de_pendientes';
  $key4 = '_rttr_pagina_de_no_confirmado';
  $key6 = '_rttr_correo_noresponder';
  $emailtxt1 = get_option($key2);
  $emailtxt1 = utf8_decode($emailtxt1);
  $emailtxt3 = get_option($key1);  
  $emailtxt2 = get_option($key); 
  $page1val = get_option($key3);
  $page2val = get_option($key4);
  $correo = get_option($key6);
    ?>
    <meta charset="UTF-8">
    <script src="https://unpkg.com/boxicons@2.1.2/dist/boxicons.js"></script>

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>

    <style>
      body {

      }

      .fa {
        padding: 10px;
      }
      #infotext,
      #infotext2,
      #infotext3 {
        display: none;
      }
      
      .info:hover ~ #infotext {
        display: block;
      }
      .info2:hover ~ #infotext2 {
        display: block;
      }
      .info3:hover ~ #infotext3 {
        display: block;
      }
    </style>
   <div class="wrap">
   <h1>RegistraTr Plugin</h1>
   <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post" id="registro">
   
    <table class="form-table">
    <tbody>
        <tr>
        <th scope="row"><label for="email1">email para usuarios registrados</label></th>
        <td><textarea placeholder="Email para anfitrion" rows = "5" cols = "50" type="textarea" form="registro" name="email1" class="regular-text"><?php echo $emailtxt1 ?></textarea>
        <i class='bx bx-info-circle info'></i>
          <div id="infotext">{nombre sitio} = nombre de la página
           <br>{enlace sitio} = enlace de la página
           <br>{lista usuarios} = enlace a la lista de usuarios
          <br>{nombre usuario} = nombre del usuario al que se dirige el mail
          <br>{nombre invitado} = nombre del usuario invitado</div>
      </td>
        </tr>
        <tr>
        <th scope="row"><label for="email2">email para nuevos usuarios</label></th>
        <td><textarea placeholder="Email para invitado" rows = "5" cols = "50" type="textarea" name="email2"  class="regular-text"><?php echo $emailtxt2 ?></textarea>
        <i class='bx bx-info-circle info2'></i>
        <div id="infotext2">{nombre sitio} = nombre de la página
           <br>{enlace sitio} = enlace de la página
          <br>{nombre usuario} = nombre del usuario al que se dirige el mail
      </td>       
        </tr>
        <tr>
        <th scope="row"><label for="email3">email para usuarios confirmados</label></th>
        <td><textarea placeholder="Email de aceptacion" rows = "5" cols = "50" type="textarea" name="email3"  class="regular-text"><?php echo $emailtxt3 ?></textarea>
        <i class='bx bx-info-circle info3'></i>
        <div id="infotext3">{nombre sitio} = nombre de la página
           <br>{enlace sitio} = enlace de la página
          <br>{nombre usuario} = nombre del usuario al que se dirige el mail
      </td>       
        </tr>
        <tr>
        <th scope="row"><label for="page1">Pagina de usuarios pendientes</label></th>
        <td><input type="text" placeholder="url de lista de usuarios pendientes" name="page1"  class="regular-text" value="<?php echo $page1val ?>">
         </td>       
        </tr>
        <tr>
        <th scope="row"><label for="page2">Pagina de redireccion a no confirmados</label></th>
        <td><input type="text" placeholder="Redirecion a usuarios pendientes" name="page2"  class="regular-text" value="<?php echo $page2val ?>">
         </td>       
        </tr>
        <tr>
        <th scope="row"><label for="correo">direccion Correo para envio de emails</label></th>
        <td><input type="text" placeholder="Correo de envios de notificaciones" name="correo"  class="regular-text" value="<?php echo $correo ?>">
         </td>       
        </tr>
        
    </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios"></p>
    
    </form></div>  
    </html>
<?php
}

function save_options_from_setup($opt2, $opt, $opt3, $pg1, $pg2, $correo ){

  $key = '_rttr_correo_para_invitados';
  $key1 = '_rttr_correo_para_confirmados';
  $key2 = '_rttr_correo_para_anfitriones';
  $key3 = '_rttr_pagina_de_lista_de_pendientes';
  $key4 = '_rttr_pagina_de_no_confirmado';
  $key6 = '_rttr_correo_noresponder';

  if(get_option($key)){

    $user_update = update_option($key, $opt);
  }
  if(get_option($key)){
    $user_update = update_option($key1, $opt3);
  }
  if(get_option($key2)){

      $user_update = update_option($key2, $opt2);
  }
  if(get_option($key3)){
  $user_update = update_option($key3, $pg1);
  }
  if(get_option($key4)){
  $user_update = update_option($key4, $pg2);
  }
  if(get_option($key5)){
    $user_update = update_option($key6, $correo);
    }

}
function registratr_config_form() {
  if ( isset($_POST['submit'] ) ) {
   
      // sanitize user form input
      global $mail1, $mail2, $mail3, $page1, $page2, $correo;
      $mail1   =   sanitize_html($_POST['email1']);
      
      $mail2   =   sanitize_html($_POST['email2']);
      $mail3   =   sanitize_html($_POST['email3']);
      $page1  =   sanitize_text_field($_POST['page1']);
      $page2   =   sanitize_text_field($_POST['page2']);
      $correo = sanitize_email($_POST['correo']);

 
      save_options_from_setup(
        $mail1,
        $mail2,
        $mail3,
        $page1,
        $page2,
        $correo
      );
  }
  registratr_config_page('');
  }
function sanitize_html($str){
  $symbols = array(
    '\"',
    "\'"
  );
  $final = array (
    '"',
    "'"
  );
  $output =str_replace($symbols, $final, $str);
  return $output;
}
?>